<?php
require_once __DIR__ . "/../models/PostModel.php";
require_once __DIR__ . "/../models/CommentModel.php";
require_once __DIR__ . "/../models/LikeModel.php";
require_once __DIR__ . "/../models/FollowModel.php";


class PostController
{
    public static function index(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header("Location: /");
            exit;
        }

        $current_user_id = $_SESSION['user']['user_pk'];

        // 1️⃣ Hent alle posts (med user info)
        $posts = PostModel::getAll();

        // 2️⃣ Berig hvert post med comments + likes
        foreach ($posts as &$post) {
            $postPk = $post['post_pk'];

            // Comments
            $post['comments'] = CommentModel::findByPost($postPk);
            $post['commentCount'] = CommentModel::countByPost($postPk);

            // Likes
            $post['likeCount'] = LikeModel::countByPost($postPk);
            $post['userLiked'] = LikeModel::exists($current_user_id, $postPk);
        }
        unset($post); // vigtigt ved reference-loop

        $followSuggestions = FollowModel::suggestions($current_user_id);

            foreach ($followSuggestions as &$user) {
            $user['isFollowing'] = FollowModel::isFollowing(
            $current_user_id,
            $user['user_pk']
        );
        }
        unset($user);

        // 3️⃣ Send data til view
        require __DIR__ . '/../views/home.php';
        exit;
    }

    public static function create(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        require_once __DIR__ . "/../../private/x.php";

        $imagePath = null;

        if (!empty($_FILES['post_image_path']['tmp_name'])) {

            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            $fileType = mime_content_type($_FILES['post_image_path']['tmp_name']);

            if (!in_array($fileType, $allowedTypes)) {
                http_response_code(400);
                exit('Invalid image type');
            }

            $filename = bin2hex(random_bytes(16)) . '.webp';
            $uploadDir = __DIR__ . '/../../uploads/';
            $targetPath = $uploadDir . $filename;

            move_uploaded_file(
                $_FILES['post_image_path']['tmp_name'],
                $targetPath
            );

            $imagePath = '/uploads/' . $filename;
        }

        PostModel::create([
            ':pk'      => bin2hex(random_bytes(25)),
            ':message' => _validatePost(),
            ':image'   => $imagePath,
            ':user'    => $_SESSION['user']['user_pk']
        ]);

        $redirect = $_SERVER['HTTP_REFERER'] ?? '/home';
        header("Location: " . $redirect);
        exit;
    }


    public static function update(): void
    {
        require_once __DIR__ . "/../../private/x.php";

        $postPk  = _validatePk("post_pk");
        $message = _validatePost();
        $imagePath = null;

        // Håndtér evt. nyt billede
        if (!empty($_FILES['post_image_path']['tmp_name'])) {

            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            $fileType = mime_content_type($_FILES['post_image_path']['tmp_name']);

            if (!in_array($fileType, $allowedTypes)) {
                header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '/home'));
                exit;
            }

            $filename = bin2hex(random_bytes(16)) . '.webp';
            $uploadDir = __DIR__ . '/../../uploads/';
            $targetPath = $uploadDir . $filename;

            move_uploaded_file($_FILES['post_image_path']['tmp_name'], $targetPath);

            $imagePath = '/uploads/' . $filename;
        }

        PostModel::update($postPk, $message, $imagePath);

        // Bliv på samme side
        header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '/home'));
        exit;
    }

    public static function delete(): void
    {
        require_once __DIR__ . "/../../private/x.php";

        PostModel::delete(_validatePk("post_pk"));

        $redirect = $_POST['redirect_to'] ?? '/home';

        // hvis redirect peger på single post → fallback
        if (str_contains($redirect, '?post=')) {
            $redirect = '/home';
        }

        // Bliv på samme side (feed eller single)
        header("Location: " . $redirect);
        exit;
    }
}
