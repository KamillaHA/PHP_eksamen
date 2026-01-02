<?php
require_once __DIR__ . "/../models/FollowModel.php";

class FollowController
{
    public static function follow(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo 'Du skal være logget ind for at følge nogen';
            exit;
        }

        require_once __DIR__ . "/../../private/x.php";

        // hvem klikker
        $followerPk = $_SESSION["user"]["user_pk"];

        // hvem bliver fulgt
        $followingPk = _validatePk("following_fk");

        // opret follow hvis den ikke findes
        if (!FollowModel::isFollowing($followerPk, $followingPk)) {
            FollowModel::create($followerPk, $followingPk);
        }

        // bruges af knap-komponenten
        $user = [
            'user_pk' => $followingPk
        ];

        // nyt following-tal (til profile header)
        $followingCount = FollowModel::countFollowing($followerPk);

        // opdater knappen
        echo '<mix-html mix-replace=".button-' . $followingPk . '">';
        require __DIR__ . '/../views/micro_components/___button-unfollow.php';
        echo '</mix-html>';

        // opdater following-count
        echo '<mix-html mix-replace=".profile-following-count">';
        require __DIR__ . '/../views/micro_components/___following-count.php';
        echo '</mix-html>';

        exit;
    }

    public static function unfollow(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo 'Du skal være logget ind for at unfølge nogen';
            exit;
        }

        require_once __DIR__ . "/../../private/x.php";

        // hvem klikker
        $followerPk = $_SESSION["user"]["user_pk"];

        // hvem bliver unfollowed
        $followingPk = _validatePk("following_fk");

        // slet follow
        FollowModel::delete($followerPk, $followingPk);

        // bruges af knap-komponenten
        $user = [
            'user_pk' => $followingPk
        ];

        // nyt following-tal (til profile header)
        $followingCount = FollowModel::countFollowing($followerPk);

        // opdater knappen
        echo '<mix-html mix-replace=".button-' . $followingPk . '">';
        require __DIR__ . '/../views/micro_components/___button-follow.php';
        echo '</mix-html>';

        // opdater following-count
        echo '<mix-html mix-replace=".profile-following-count">';
        require __DIR__ . '/../views/micro_components/___following-count.php';
        echo '</mix-html>';

        exit;
    }
}
