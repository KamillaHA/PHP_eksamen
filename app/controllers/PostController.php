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

        // 🔥 REDIRECT: Hvis der er et post parameter, redirect til ny URL struktur
        if (isset($_GET['post']) && !empty($_GET['post'])) {
            require __DIR__ . '/../../private/db.php';
            $stmt = $_db->prepare("
                SELECT users.user_username 
                FROM posts 
                JOIN users ON posts.post_user_fk = users.user_pk 
                WHERE posts.post_pk = ?
            ");
            $stmt->execute([$_GET['post']]);
            $post = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($post) {
                header("Location: /" . $post['user_username'] . "/status/" . $_GET['post']);
                exit;
            }
        }

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

    public static function singleByUrl(string $username, string $postId): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header("Location: /");
            exit;
        }

        $current_user_id = $_SESSION['user']['user_pk'];

        // Tjek om post findes
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
        
        if (!$post) {
            http_response_code(404);
            echo 'Post not found';
            exit;
        }

        // Hvis brugernavn i URL ikke matcher, redirect til korrekt URL
        if ($post['user_username'] !== $username) {
            header("Location: /" . $post['user_username'] . "/status/" . $postId);
            exit;
        }

        // Hent ALLE posts (til feed)
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

        // Sæt $_GET['post'] så ___post.php kan vise single view
        $_GET['post'] = $postId;
        
        $followSuggestions = FollowModel::suggestions($current_user_id);
        foreach ($followSuggestions as &$user) {
            $user['isFollowing'] = FollowModel::isFollowing($current_user_id, $user['user_pk']);
        }
        unset($user);

        // Brug det EKSISTERENDE home.php view
        require __DIR__ . '/../views/home.php';
        exit;
    }

    public static function create(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo 'Du skal være logget ind for at oprette et indlæg';
            exit;
        }

        require_once __DIR__ . "/../../private/x.php";

        $imagePath = null;

        if (!empty($_FILES['post_image_path']['tmp_name'])) {
            // Valider filstørrelse (max 5MB)
            $fileSizeKB = $_FILES['post_image_path']['size'] / 1024;
            if ($fileSizeKB > 5120) { // 5MB
                http_response_code(400);
                exit('Filen er for stor. Maksimum 5MB tilladt.');
            }

            // Tjek om filen blev uploadet korrekt
            if (!is_uploaded_file($_FILES['post_image_path']['tmp_name'])) {
                http_response_code(400);
                exit('Filen blev ikke uploadet korrekt.');
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
                http_response_code(400);
                exit('Kun JPEG, PNG, GIF og WebP billeder er tilladt');
            }

            // Brug korrekt filtype-endelse
            $fileExtension = $allowedTypes[$fileType];
            $filename = bin2hex(random_bytes(16)) . '.' . $fileExtension;
            $uploadDir = __DIR__ . '/../../uploads/';
            $targetPath = $uploadDir . $filename;

            // Flyt filen med korrekt navn
            if (!move_uploaded_file($_FILES['post_image_path']['tmp_name'], $targetPath)) {
                http_response_code(500);
                exit('Kunne ikke gemme filen');
            }

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
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo 'Du skal være logget ind for at redigere et indlæg';
            exit;
        }

        require_once __DIR__ . "/../../private/x.php";

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

        PostModel::update($postPk, $message, $imagePath);

        // Redirect tilbage til den korrekte single post URL
        require __DIR__ . '/../../private/db.php';
        $stmt = $_db->prepare("
            SELECT users.user_username 
            FROM posts 
            JOIN users ON posts.post_user_fk = users.user_pk 
            WHERE posts.post_pk = ?
        ");
        $stmt->execute([$postPk]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($post) {
            header("Location: /" . $post['user_username'] . "/status/" . $postPk);
        } else {
            header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '/home'));
        }
        exit;
    }

    public static function delete(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo 'Du skal være logget ind for at slette et indlæg';
            exit;
        }

        require_once __DIR__ . "/../../private/x.php";

        $postPk = _validatePk("post_pk");
        
        PostModel::delete($postPk);

        $redirect = $_POST['redirect_to'] ?? '/home';

        // Hvis redirect peger på en single post URL, redirect til home i stedet
        if (str_contains($redirect, '/status/') || str_contains($redirect, '?post=')) {
            $redirect = '/home';
        }

        header("Location: " . $redirect);
        exit;
    }
}