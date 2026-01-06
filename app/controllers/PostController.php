<?php

// Loader de nødvendige models som PostController afhænger af
require_once __DIR__ . "/../models/PostModel.php";
require_once __DIR__ . "/../models/CommentModel.php";
require_once __DIR__ . "/../models/LikeModel.php";
require_once __DIR__ . "/../models/FollowModel.php";

class PostController
{
    public static function index(): void
    {
        // Sørger for at sessionen er startet
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Kun loggede brugere må se feedet
        if (!isset($_SESSION['user'])) {
            header("Location: /");
            exit;
        }

        // Gem den loggede brugers PK
        $current_user_id = $_SESSION['user']['user_pk'];

        // Hvis der er et post parameter, redirect til ny URL struktur
        if (isset($_GET['post']) && !empty($_GET['post'])) {
            require __DIR__ . '/../../private/db.php';

            // Find postens ejer for at bygge korrekt URL
            $stmt = $_db->prepare("
                SELECT users.user_username 
                FROM posts 
                JOIN users ON posts.post_user_fk = users.user_pk 
                WHERE posts.post_pk = ?
            ");
            $stmt->execute([$_GET['post']]);
            $post = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Redirect til /username/status/postId
            if ($post) {
                header("Location: /" . $post['user_username'] . "/status/" . $_GET['post']);
                exit;
            }
        }

        // Hent alle posts til feedet (grunddata)
        $posts = PostModel::getAll();

        // Berig hvert post med kommentarer og likes
        foreach ($posts as &$post) {
            $postPk = $post['post_pk'];

            // Kommentarer
            $post['comments'] = CommentModel::findByPost($postPk);
            $post['commentCount'] = CommentModel::countByPost($postPk);

            // Likes
            $post['likeCount'] = LikeModel::countByPost($postPk);
            $post['userLiked'] = LikeModel::exists($current_user_id, $postPk);
        }
        unset($post); // VIGTIGT: bryder reference fra foreach

        // Forslag til brugere man kan følge
        $followSuggestions = FollowModel::suggestions($current_user_id);

        // Marker hvilke forslag brugeren allerede følger
        foreach ($followSuggestions as &$user) {
            $user['isFollowing'] = FollowModel::isFollowing(
                $current_user_id,
                $user['user_pk']
            );
        }
        unset($user);

        // Send data videre til view
        require __DIR__ . '/../views/home.php';
        exit;
    }

    public static function singleByUrl(string $username, string $postId): void
    {
        // Sørger for aktiv session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Kun loggede brugere må se posts
        if (!isset($_SESSION['user'])) {
            header("Location: /");
            exit;
        }

        $current_user_id = $_SESSION['user']['user_pk'];

        // Gem hvor brugeren kom fra (bruges til "Tilbage"-knap)
        $_SESSION['back_to_feed'] = $_SERVER['HTTP_REFERER'] ?? '/home';

        // Tjek om posten eksisterer og ikke er slettet
        require __DIR__ . '/../../private/db.php';
        
        $stmt = $_db->prepare("
            SELECT posts.*, users.user_username, users.user_full_name
            FROM posts
            JOIN users ON posts.post_user_fk = users.user_pk
            WHERE posts.post_pk = :post_pk
            AND posts.deleted_at IS NULL
        ");
        
        $stmt->execute([':post_pk' => $postId]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Hvis posten ikke findes → 404
        if (!$post) {
            http_response_code(404);
            echo 'Post not found';
            exit;
        }

        // Hvis URL-brugernavn ikke matcher postens ejer → redirect til korrekt URL
        if ($post['user_username'] !== $username) {
            header("Location: /" . $post['user_username'] . "/status/" . $postId);
            exit;
        }

        // Hent ALLE posts igen (bruges af feedet i home.php)
        $posts = PostModel::getAll();
        
        // Berig ALLE posts med kommentarer og likes
        foreach ($posts as &$p) {
            $postPk = $p['post_pk'];
            $p['comments'] = CommentModel::findByPost($postPk);
            $p['commentCount'] = CommentModel::countByPost($postPk);
            $p['likeCount'] = LikeModel::countByPost($postPk);
            $p['userLiked'] = LikeModel::exists($current_user_id, $postPk);
        }
        unset($p);

        // Bruges af viewet til at vise single post
        $_GET['post'] = $postId;
        
        // Follow-suggestions
        $followSuggestions = FollowModel::suggestions($current_user_id);
        foreach ($followSuggestions as &$user) {
            $user['isFollowing'] = FollowModel::isFollowing($current_user_id, $user['user_pk']);
        }
        unset($user);

        // Genbrug home.php som single-post view
        require __DIR__ . '/../views/home.php';
        exit;
    }

    public static function create(): void
    {
        // Sørger for aktiv session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Kun loggede brugere må oprette posts
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo 'You have to be logged in to create a post';
            exit;
        }

        // Indlæser valideringsfunktioner
        require_once __DIR__ . "/../../private/x.php";

        // Default: ingen billede
        $imagePath = null;

        // Hvis der er uploadet et billede
        if (!empty($_FILES['post_image_path']['tmp_name'])) {

            // Valider filstørrelse (max 5MB)
            $fileSizeKB = $_FILES['post_image_path']['size'] / 1024;
            if ($fileSizeKB > 5120) { // 5MB
                http_response_code(400);
                exit('The file is too big. 5MB max allowed');
            }

            // Tjek at filen faktisk er uploadet via HTTP POST
            if (!is_uploaded_file($_FILES['post_image_path']['tmp_name'])) {
                http_response_code(400);
                exit('The file wasn´t uploadet correct');
            }

            // Tilladte billedtyper
            $allowedTypes = [
                'image/jpeg' => 'jpg',
                'image/jpg' => 'jpg',
                'image/png' => 'png',
                'image/webp' => 'webp',
                'image/gif' => 'gif'
            ];
            
            $fileType = mime_content_type($_FILES['post_image_path']['tmp_name']);

            // Afvis ikke-tilladte filtyper
            if (!isset($allowedTypes[$fileType])) {
                http_response_code(400);
                exit('Only JPEG, PNG, GIF og WebP images are allowed');
            }

            // Generér sikkert filnavn
            $fileExtension = $allowedTypes[$fileType];
            $filename = bin2hex(random_bytes(16)) . '.' . $fileExtension;
            $uploadDir = __DIR__ . '/../../uploads/';
            $targetPath = $uploadDir . $filename;

            // Flyt filen til uploads-mappen
            if (!move_uploaded_file($_FILES['post_image_path']['tmp_name'], $targetPath)) {
                http_response_code(500);
                exit('Could not save the file');
            }

            // Gem relativ sti i databasen
            $imagePath = '/uploads/' . $filename;
        }

        // Opret post i databasen
        PostModel::create([
            ':pk'      => bin2hex(random_bytes(25)),
            ':message' => _validatePost(),
            ':image'   => $imagePath,
            ':user'    => $_SESSION['user']['user_pk']
        ]);

        // Redirect tilbage til hvor brugeren kom fra
        $redirect = $_SERVER['HTTP_REFERER'] ?? '/home';
        header("Location: " . $redirect);
        exit;
    }

    public static function update(): void
    {
        // Sørger for aktiv session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Kun loggede brugere må redigere posts
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo 'You have to be logged in to update a post';
            exit;
        }

        require_once __DIR__ . "/../../private/x.php";

        // Valider input
        $postPk  = _validatePk("post_pk");
        $message = _validatePost();
        $imagePath = null;

        // Håndtér evt. nyt billede
        if (!empty($_FILES['post_image_path']['tmp_name'])) {

            // Valider filstørrelse (max 5MB)
            $fileSizeKB = $_FILES['post_image_path']['size'] / 1024;
            if ($fileSizeKB > 5120) {
                header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '/home'));
                exit;
            }

            // Tjek om filen blev uploadet korrekt
            if (!is_uploaded_file($_FILES['post_image_path']['tmp_name'])) {
                header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '/home'));
                exit;
            }

            $allowedTypes = [
                'image/jpeg' => 'jpg',
                'image/jpg' => 'jpg',
                'image/png' => 'png',
                'image/webp' => 'webp',
                'image/gif' => 'gif'
            ];
            
            $fileType = mime_content_type($_FILES['post_image_path']['tmp_name']);

            if (!isset($allowedTypes[$fileType])) {
                header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '/home'));
                exit;
            }

            // Brug korrekt filtype-endelse
            $fileExtension = $allowedTypes[$fileType];
            $filename = bin2hex(random_bytes(16)) . '.' . $fileExtension;
            $uploadDir = __DIR__ . '/../../uploads/';
            $targetPath = $uploadDir . $filename;

            // Flyt filen med korrekt navn
            if (!move_uploaded_file($_FILES['post_image_path']['tmp_name'], $targetPath)) {
                header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '/home'));
                exit;
            }

            $imagePath = '/uploads/' . $filename;
        }

        // Opdater post i databasen
        PostModel::update($postPk, $message, $imagePath);

        // Redirect til korrekt single post URL
        require __DIR__ . '/../../private/db.php';
        $stmt = $_db->prepare("
            SELECT users.user_username 
            FROM posts 
            JOIN users ON posts.post_user_fk = users.user_pk 
            WHERE posts.post_pk = ?
        ");
        $stmt->execute([$postPk]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Hvis vi fandt posten, redirect til dens pæne URL, ellers fallback
        if ($post) {
            header("Location: /" . $post['user_username'] . "/status/" . $postPk);
        } else {
            header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '/home'));
        }
        exit;
    }

    public static function delete(): void
    {
        // Sørger for aktiv session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Kun loggede brugere må slette posts
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo 'You have to be logged in to delete a post';
            exit;
        }

        require_once __DIR__ . "/../../private/x.php";

        // Valider postens PK
        $postPk = _validatePk("post_pk");
        
        // Soft delete af posten
        PostModel::delete($postPk);

        // Bestem hvor brugeren skal sendes hen bagefter
        $redirect = $_POST['redirect_to'] ?? '/home';

        // Hvis redirect peger på en single post URL, redirect til home i stedet
        if (str_contains($redirect, '/status/') || str_contains($redirect, '?post=')) {
            $redirect = '/home';
        }

        // Undgå redirect tilbage til slettet single-post side
        header("Location: " . $redirect);
        exit;
    }
}