<?php

// Loader LikeModel, som håndterer likes i databasen
require_once __DIR__ . "/../models/LikeModel.php";

class LikeController
{
    public static function like(): void
    {
        // Sørger for at sessionen er startet
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Brugeren skal være logget ind for at kunne like
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo 'You have to be logged in to like a post';
            exit;
        }

        // Indlæser fælles validerings- og hjælpefunktioner
        require_once __DIR__ . "/../../private/x.php";

        // Validerer CSRF-token for at beskytte mod Cross-Site Request Forgery.
        // if (!csrf_verify()) {
        //     http_response_code(403);
        //     exit('CSRF token mismatch');
        // }

        // Den loggede ind brugers primære nøgle
        $userPk = $_SESSION["user"]["user_pk"];

        // Validerer postens primære nøgle (beskytter mod manipulation)
        $postPk = _validatePk("post_fk");

        // Opret kun like hvis den ikke allerede findes
        // Forhindrer dubletter i databasen
        if (!LikeModel::exists($userPk, $postPk)) {
            LikeModel::create($userPk, $postPk);
        }

        // 204 No Content:
        // Bruges fordi kaldet lykkes, men der returneres ikke noget HTML eller JSON
        // Typisk anvendt til AJAX / fetch / mix-html handlinger
        http_response_code(204);
        exit;
    }

    public static function unlike(): void
    {
        // Sørger for at sessionen er startet
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Brugeren skal være logget ind for at kunne unlike
        // if (!isset($_SESSION['user'])) {
        //     http_response_code(401);
        //     echo 'You have to be logged in to unlike a post';
        //     exit;
        // }

        // Indlæser hjælpefunktioner
        require_once __DIR__ . "/../../private/x.php";

        // Validerer CSRF-token for at beskytte mod Cross-Site Request Forgery.
        if (!csrf_verify()) {
            http_response_code(403);
            exit('CSRF token mismatch');
        }

        // Den loggede ind brugers primære nøgle
        $userPk = $_SESSION["user"]["user_pk"];

        // Validerer postens primære nøgle
        $postPk = _validatePk("post_fk");

        // Sletter like-relationen i databasen
        LikeModel::delete($userPk, $postPk);

        // Returnerer igen 204 No Content ved succes
        http_response_code(204);
        exit;
    }

};
