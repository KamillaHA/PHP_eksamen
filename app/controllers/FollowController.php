<?php

// Loader FollowModel, som håndterer follow/unfollow-relationer i databasen
require_once __DIR__ . "/../models/FollowModel.php";

class FollowController
{
    public static function follow(): void
    {
        // Sørger for at sessionen er startet
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Brugeren skal være logget ind for at kunne følge andre
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo 'You have to be logged in to follow another user';
            exit;
        }

        // Indlæser fælles validerings- og hjælpefunktioner
        require_once __DIR__ . "/../../private/x.php";

        // Den bruger der klikker på "Følg"-knappen (den loggede ind bruger)
        $followerPk = $_SESSION["user"]["user_pk"];

        // Den bruger der bliver fulgt (valideres fra POST)
        $followingPk = _validatePk("following_fk");

        // Opret kun follow-relationen hvis den ikke allerede findes
        // Dette forhindrer dubletter i databasen
        if (!FollowModel::isFollowing($followerPk, $followingPk)) {
            FollowModel::create($followerPk, $followingPk);
        }

        // Bruges af knap-komponenten (follow/unfollow)
        // Indeholder den bruger der bliver fulgt
        $user = [
            'user_pk' => $followingPk
        ];

        // Opdateret antal brugere som den loggede ind bruger følger
        // (bruges i profile header)
        $followingCount = FollowModel::countFollowing($followerPk);

        // Returnerer HTML der erstatter follow-knappen med "Unfollow"
        // mix-replace bruges til delvis DOM-opdatering (AJAX-lignende)
        echo '<mix-html mix-replace=".button-' . $followingPk . '">';
        require __DIR__ . '/../views/micro_components/___button-unfollow.php';
        echo '</mix-html>';

        // Returnerer HTML der opdaterer "following count" i profilen
        echo '<mix-html mix-replace=".profile-following-count">';
        require __DIR__ . '/../views/micro_components/___following-count.php';
        echo '</mix-html>';

        exit;
    }

    public static function unfollow(): void
    {
        // Sørger for at sessionen er startet
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Brugeren skal være logget ind for at kunne unfollowe
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo 'You have to be logged in to unfollow another user';
            exit;
        }

        // Indlæser fælles hjælpefunktioner
        require_once __DIR__ . "/../../private/x.php";

        // Den bruger der klikker (den loggede ind bruger)
        $followerPk = $_SESSION["user"]["user_pk"];

        // Den bruger der bliver unfollowed (valideret fra POST)
        $followingPk = _validatePk("following_fk");

        // Sletter follow-relationen i databasen
        FollowModel::delete($followerPk, $followingPk);

        // Bruges af knap-komponenten (follow/unfollow)
        $user = [
            'user_pk' => $followingPk
        ];

        // Opdateret following-tal efter unfollow
        $followingCount = FollowModel::countFollowing($followerPk);

        // Returnerer HTML der erstatter unfollow-knappen med "Follow"
        echo '<mix-html mix-replace=".button-' . $followingPk . '">';
        require __DIR__ . '/../views/micro_components/___button-follow.php';
        echo '</mix-html>';

        // Opdaterer "following count" i profile header
        echo '<mix-html mix-replace=".profile-following-count">';
        require __DIR__ . '/../views/micro_components/___following-count.php';
        echo '</mix-html>';

        exit;
    }
}
