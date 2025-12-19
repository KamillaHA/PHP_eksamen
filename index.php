<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($uri) {

    case '/':
        if (isset($_SESSION['user'])) {
            header('Location: /home');
            exit;
        }

        $title = "Welcome";
        $body_class = "page-landing";

        require __DIR__ . '/app/views/components/_header.php';
        require __DIR__ . '/app/views/landing.php';
        require __DIR__ . '/app/views/components/_footer.php';
        exit;

    case '/profile':
        require_once __DIR__ . '/app/controllers/ProfileController.php';
        ProfileController::get();
        break;

    case '/home':
        require_once __DIR__ . '/app/controllers/PostController.php';
        PostController::index();
        break;

    case '/follow':
        require_once __DIR__ . '/app/controllers/FollowController.php';
        FollowController::follow();
        break;

    case '/unfollow':
        require_once __DIR__ . '/app/controllers/FollowController.php';
        FollowController::unfollow();
        break;

    case '/like':
        require_once __DIR__ . '/app/controllers/LikeController.php';
        LikeController::like();
        break;

    case '/unlike':
        require_once __DIR__ . '/app/controllers/LikeController.php';
        LikeController::unlike();
        break;

    default:
        http_response_code(404);
        echo '404 - Not Found';
}
