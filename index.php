<?php
session_start();

// Henter kun path-delen af URL’en (uden query string)
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Simpel router baseret på URL path
switch ($uri) {

    case '/':

        // Hvis brugeren er logget ind, sendes de til /home
        if (isset($_SESSION['user'])) {
            header('Location: /home');
            exit;
        }

        // Variabler bruges i header-viewet
        $title = "Welcome";
        $body_class = "page-landing";

        // Loader landing page for ikke-loggede brugere
        require __DIR__ . '/app/views/components/_header.php';
        require __DIR__ . '/app/views/landing.php';
        require __DIR__ . '/app/views/components/_footer.php';
        exit;

    case '/signup':
        // Opretter ny bruger
        require_once __DIR__ . '/app/controllers/AuthController.php';
        AuthController::signup();
        break;

    case '/login':
        // Logger bruger ind
        require_once __DIR__ . '/app/controllers/AuthController.php';
        AuthController::login();
        break;

    case '/logout':
        // Logger bruger ud og rydder session
        require_once __DIR__ . '/app/controllers/AuthController.php';
        AuthController::logout();
        break;

    case '/profile':
        // Viser brugerens profil
        require_once __DIR__ . '/app/controllers/UserController.php';
        UserController::get();
        break;

    case '/profile/cover':
        // Opdaterer profil-cover
        require_once __DIR__ . '/app/controllers/UserController.php';
        UserController::updateCover();
        break;

    case '/profile/update':
        // Opdaterer brugerens profiloplysninger
        require_once __DIR__ . '/app/controllers/UserController.php';
        UserController::update();
        break;

    case '/profile/delete':
        // Sletter brugerens profil
        require_once __DIR__ . '/app/controllers/UserController.php';
        UserController::delete();
        break;

    // Matcher URL’er som: /username/status/postid
    case preg_match('#^/([^/]+)/status/([a-f0-9]+)$#', $uri, $matches) ? true : false:
        require_once __DIR__ . '/app/controllers/PostController.php';

        // $matches[1] = brugernavn, $matches[2] = post ID
        PostController::singleByUrl($matches[1], $matches[2]);
        break;

    case '/home':
        // Viser feed (forside for loggede brugere)
        require_once __DIR__ . '/app/controllers/PostController.php';
        PostController::index();
        break;

    case '/post/create':
        // Opretter nyt opslag
        require_once __DIR__ . '/app/controllers/PostController.php';
        PostController::create();
        break;

    case '/post/update':
        // Opdaterer eksisterende opslag
        require_once __DIR__ . '/app/controllers/PostController.php';
        PostController::update();
        break;

    case '/post/delete':
        // Sletter opslag
        require_once __DIR__ . '/app/controllers/PostController.php';
        PostController::delete();
        break;

    case '/follow':
        // Følger en bruger
        require_once __DIR__ . '/app/controllers/FollowController.php';
        FollowController::follow();
        break;

    case '/unfollow':
        // Stopper med at følge en bruger
        require_once __DIR__ . '/app/controllers/FollowController.php';
        FollowController::unfollow();
        break;

    case '/like':
        // Liker et opslag
        require_once __DIR__ . '/app/controllers/LikeController.php';
        LikeController::like();
        break;

    case '/unlike':
        // Fjerner like fra opslag
        require_once __DIR__ . '/app/controllers/LikeController.php';
        LikeController::unlike();
        break;

    case '/comment':
        // Opretter kommentar
        require_once __DIR__ . '/app/controllers/CommentController.php';
        CommentController::create();
        break;

    case '/comment/update':
        // Opdaterer kommentar
        require_once __DIR__ . '/app/controllers/CommentController.php';
        CommentController::update();
        break;

    case '/comment/delete':
        // Sletter kommentar
        require_once __DIR__ . '/app/controllers/CommentController.php';
        CommentController::delete();
        break;

    default:
        // Fanges hvis ingen routes matcher
        http_response_code(404);
        echo '404 - Not Found';
}
