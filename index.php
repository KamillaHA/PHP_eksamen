<?php

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

    case '/signup':
        require_once __DIR__ . '/app/controllers/AuthController.php';
        AuthController::signup();
        break;

    case '/login':
        require_once __DIR__ . '/app/controllers/AuthController.php';
        AuthController::login();
        break;

    case '/logout':
        require_once __DIR__ . '/app/controllers/AuthController.php';
        AuthController::logout();
        break;

    case '/profile':
        require_once __DIR__ . '/app/controllers/UserController.php';
        UserController::get();
        break;

    case '/profile/cover':
        require_once __DIR__ . '/app/controllers/UserController.php';
        UserController::updateCover();
        break;

    case '/profile/update':
        require_once __DIR__ . '/app/controllers/UserController.php';
        UserController::update();
        break;

    case '/profile/delete':
        require_once __DIR__ . '/app/controllers/UserController.php';
        UserController::delete();
        break;

    case preg_match('#^/([^/]+)/status/([a-f0-9]+)$#', $uri, $matches) ? true : false:
        require_once __DIR__ . '/app/controllers/PostController.php';
        PostController::singleByUrl($matches[1], $matches[2]); // brugernavn og post ID
        break;

    case '/home':
        require_once __DIR__ . '/app/controllers/PostController.php';
        PostController::index();
        break;

    case '/post/create':
        require_once __DIR__ . '/app/controllers/PostController.php';
        PostController::create();
        break;

    case '/post/update':
        require_once __DIR__ . '/app/controllers/PostController.php';
        PostController::update();
        break;

    case '/post/delete':
        require_once __DIR__ . '/app/controllers/PostController.php';
        PostController::delete();
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

    case '/comment':
        require_once __DIR__ . '/app/controllers/CommentController.php';
        CommentController::create();
        break;

    case '/comment/update':
        require_once __DIR__ . '/app/controllers/CommentController.php';
        CommentController::update();
        break;

    case '/comment/delete':
        require_once __DIR__ . '/app/controllers/CommentController.php';
        CommentController::delete();
        break;

    default:
        http_response_code(404);
        echo '404 - Not Found';
}
