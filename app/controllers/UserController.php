<?php

// Loader alle models som UserController har ansvar for
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/PostModel.php';
require_once __DIR__ . '/../models/FollowModel.php';
require_once __DIR__ . '/../models/CommentModel.php';
require_once __DIR__ . '/../models/LikeModel.php';

class UserController
{
    public static function get(): void
    {
        // Sørger for at sessionen er startet
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Kun loggede brugere må se deres profil
        if (!isset($_SESSION['user'])) {
            header("Location: /login");
            exit;
        }

        // Hent brugerdata fra session
        $user = $_SESSION['user'];
        $userPk = $user['user_pk'];
        $current_user_id = $userPk;

        // Hent posts til profilen
        // Henter alle posts der tilhører brugeren (med user-info)
        $posts = PostModel::getAllWithUser($userPk);

        // Berig hvert post med kommentarer og likes
        foreach ($posts as &$post) {
            $postPk = $post['post_pk'];

            // Kommentarer
            $post['comments']     = CommentModel::findByPost($postPk);
            $post['commentCount'] = CommentModel::countByPost($postPk);

            // Likes
            $post['likeCount'] = LikeModel::countByPost($postPk);
            $post['userLiked'] = LikeModel::exists($current_user_id, $postPk);
        }
        unset($post); // Vigtigt: bryder reference fra foreach

        // Antal følgere og antal brugeren følger
        $followers = FollowModel::countFollowers($userPk);
        $following = FollowModel::countFollowing($userPk);

         // Forslag til brugere den loggede ind bruger kan følge
        $followSuggestions = FollowModel::suggestions($current_user_id);

        // Marker hvilke forslag brugeren allerede følger
        foreach ($followSuggestions as &$suggestedUser) {
            $suggestedUser['isFollowing'] = FollowModel::isFollowing(
                $current_user_id,
                $suggestedUser['user_pk']
            );
        }
        unset($suggestedUser);

        // Send data videre til profil-viewet
        require __DIR__ . '/../views/profile.php';
        exit;
    }


    public static function update(): void
    {
        // Sørger for at sessionen er startet
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Kun loggede brugere må opdatere deres profil
        if (!isset($_SESSION['user'])) {
            header("Location: /login");
            exit;
        }

        // Indlæser valideringsfunktioner
        require_once __DIR__ . '/../../private/x.php';

        // Hent brugerens PK
        $userPk = $_SESSION['user']['user_pk'];

        // Opdater brugerens basisoplysninger
        UserModel::update($userPk, [
            'username'   => _validateUsername(),
            'fullname'  => _validateFullName(),
            'email'      => _validateEmail()
        ]);

        // Opdater session med friske data fra databasen
        $_SESSION['user'] = UserModel::findByPk($userPk);

        // Redirect tilbage til profilen
        header("Location: /profile");
        exit;
    }


    public static function updateCover(): void
    {
        // Sørger for aktiv session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Kun loggede brugere må ændre cover-billede
        if (!isset($_SESSION['user'])) {
            header("Location: /");
            exit;
        }

         // Hvis der ikke er uploadet en fil, gør ingenting
        if (empty($_FILES['cover_image']['tmp_name'])) {
            header("Location: /profile");
            exit;
        }

        // Valider filstørrelse (max 10MB for cover-billede)
        $fileSizeKB = $_FILES['cover_image']['size'] / 1024;
        if ($fileSizeKB > 10240) { // 10MB
            header("Location: /profile");
            exit;
        }

        // Tjek at filen er uploadet korrekt via HTTP POST
        if (!is_uploaded_file($_FILES['cover_image']['tmp_name'])) {
            header("Location: /profile");
            exit;
        }

        // Tilladte billedtyper
        $allowedTypes = [
            'image/jpeg' => 'jpg',
            'image/jpg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif'
        ];
        
        $type = mime_content_type($_FILES['cover_image']['tmp_name']);

        // Afvis filer med forkert mime-type
        if (!isset($allowedTypes[$type])) {
            header("Location: /profile");
            exit;
        }

        // Generér sikkert filnavn
        $fileExtension = $allowedTypes[$type];
        $filename = bin2hex(random_bytes(16)) . '.' . $fileExtension;
        $uploadDir = __DIR__ . '/../../uploads/covers/';
        $target = $uploadDir . $filename;

        // Opret mappen hvis den ikke findes
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Flyt filen til uploads-mappen
        if (!move_uploaded_file($_FILES['cover_image']['tmp_name'], $target)) {
            header("Location: /profile");
            exit;
        }

        // Gem relativ sti til billedet
        $coverPath = '/uploads/covers/' . $filename;

        // Opdater cover-billedet i databasen
        UserModel::updateCover($_SESSION['user']['user_pk'], $coverPath);

        // Opdatér session med ny cover-sti
        $_SESSION['user']['user_cover_image'] = $coverPath;

        // Redirect tilbage til profilen
        header("Location: /profile");
        exit;
    }

    public static function delete(): void
    {
        // Sørger for aktiv session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Kun loggede brugere må slette deres konto
        if (!isset($_SESSION['user'])) {
            header("Location: /login");
            exit;
        }
        $userPk = $_SESSION['user']['user_pk'];

        // Soft delete brugeren (bevarer historik)
        UserModel::delete($userPk);

        // Soft delete alle brugerens posts
        PostModel::softDeleteByUser($userPk);

        // Soft delete alle brugerens kommentarer
        CommentModel::softDeleteByUser($userPk);

        // Hard delete likes (relationer uden historikværdi)
        LikeModel::deleteByUser($userPk);

        // Log brugeren helt ud
        session_destroy();

        // Redirect til forsiden
        header("Location: /");
        exit;
    }
}
