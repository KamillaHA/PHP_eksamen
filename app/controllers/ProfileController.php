<?php

require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/PostModel.php';
require_once __DIR__ . '/../models/FollowModel.php';

class ProfileController
{
    public static function get(): void
    {
        // ⚠️ Session bør helst allerede være startet i entry point,
        // men vi sikrer os her uden at trigge warnings
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header("Location: /login");
            exit;
        }

        // Data til view
        $user = $_SESSION['user'];
        $userPk = $user['user_pk'];

        $posts     = PostModel::getAllWithUser($userPk);
        $followers = FollowModel::countFollowers($userPk);
        $following = FollowModel::countFollowing($userPk);

        // View renderer – variablerne ovenfor er nu tilgængelige i viewet
        require __DIR__ . '/../views/profile.php';
        exit;
    }

    public static function update(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header("Location: /login");
            exit;
        }

        require_once __DIR__ . '/../../private/x.php';

        $userPk = $_SESSION['user']['user_pk'];

        UserModel::update($userPk, [
            'user_username'   => _validateUsername(),
            'user_full_name'  => _validateFullName(),
            'user_email'      => _validateEmail()
        ]);

        // Opdater session med friske data
        $_SESSION['user'] = UserModel::findByPk($userPk);

        header("Location: /profile");
        exit;
    }

    public static function delete(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header("Location: /login");
            exit;
        }

        UserModel::delete($_SESSION['user']['user_pk']);

        session_destroy();

        header("Location: /login");
        exit;
    }
}
