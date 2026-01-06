<?php

require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/PostModel.php';
require_once __DIR__ . '/../models/FollowModel.php';
require_once __DIR__ . '/../models/CommentModel.php';
require_once __DIR__ . '/../models/LikeModel.php';

class UserController
{
    public static function get(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header("Location: /login");
            exit;
        }

        $user = $_SESSION['user'];
        $userPk = $user['user_pk'];
        $current_user_id = $userPk;

        // =========================
        // Hent posts til profilen
        // =========================
        $posts = PostModel::getAllWithUser($userPk);

        require_once __DIR__ . '/../models/CommentModel.php';
        require_once __DIR__ . '/../models/LikeModel.php';

        foreach ($posts as &$post) {
            $postPk = $post['post_pk'];

            // Comments
            $post['comments']     = CommentModel::findByPost($postPk);
            $post['commentCount'] = CommentModel::countByPost($postPk);

            // Likes
            $post['likeCount'] = LikeModel::countByPost($postPk);
            $post['userLiked'] = LikeModel::exists($current_user_id, $postPk);
        }
        unset($post);

        // =========================
        // Followers / following
        // =========================
        $followers = FollowModel::countFollowers($userPk);
        $following = FollowModel::countFollowing($userPk);

        // =========================
        // Follow suggestions
        // =========================
        $followSuggestions = FollowModel::suggestions($current_user_id);

        foreach ($followSuggestions as &$suggestedUser) {
            $suggestedUser['isFollowing'] = FollowModel::isFollowing(
                $current_user_id,
                $suggestedUser['user_pk']
            );
        }
        unset($suggestedUser);

        require __DIR__ . '/../views/profile.php';
        exit;
    }


    public static function update(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header("Location: /login");
            exit;
        }

        require_once __DIR__ . '/../../private/x.php';

        $userPk = $_SESSION['user']['user_pk'];

        UserModel::update($userPk, [
            'username'   => _validateUsername(),
            'fullname'  => _validateFullName(),
            'email'      => _validateEmail()
        ]);

        // Opdater session med friske data
        $_SESSION['user'] = UserModel::findByPk($userPk);

        header("Location: /profile");
        exit;
    }


    public static function updateCover(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header("Location: /");
            exit;
        }

        if (empty($_FILES['cover_image']['tmp_name'])) {
            header("Location: /profile");
            exit;
        }

        // Valider filstørrelse (max 10MB for cover)
        $fileSizeKB = $_FILES['cover_image']['size'] / 1024;
        if ($fileSizeKB > 10240) { // 10MB
            header("Location: /profile");
            exit;
        }

        // Tjek om filen blev uploadet korrekt
        if (!is_uploaded_file($_FILES['cover_image']['tmp_name'])) {
            header("Location: /profile");
            exit;
        }

        $allowedTypes = [
            'image/jpeg' => 'jpg',
            'image/jpg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif'
        ];
        
        $type = mime_content_type($_FILES['cover_image']['tmp_name']);

        if (!isset($allowedTypes[$type])) {
            header("Location: /profile");
            exit;
        }

        // Brug korrekt filtype-endelse
        $fileExtension = $allowedTypes[$type];
        $filename = bin2hex(random_bytes(16)) . '.' . $fileExtension;
        $uploadDir = __DIR__ . '/../../uploads/covers/';
        $target = $uploadDir . $filename;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Flyt filen med korrekt navn
        if (!move_uploaded_file($_FILES['cover_image']['tmp_name'], $target)) {
            header("Location: /profile");
            exit;
        }

        $coverPath = '/uploads/covers/' . $filename;

        require_once __DIR__ . '/../models/UserModel.php';
        UserModel::updateCover($_SESSION['user']['user_pk'], $coverPath);

        // opdatér session
        $_SESSION['user']['user_cover_image'] = $coverPath;

        header("Location: /profile");
        exit;
    }

    public static function delete(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header("Location: /login");
            exit;
        }
        $userPk = $_SESSION['user']['user_pk'];

        // Soft delete bruger
        UserModel::delete($userPk);

        // Soft delete alle posts
        PostModel::softDeleteByUser($userPk);

        // Soft delete kommentarer
        CommentModel::softDeleteByUser($userPk);

        // Hard delete likes
        LikeModel::deleteByUser($userPk);

        // Log ud
        session_destroy();

        header("Location: /");
        exit;
    }
}
