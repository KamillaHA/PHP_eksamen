<?php
require_once __DIR__ . "/../models/LikeModel.php";

class LikeController
{
    public static function like(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo 'Du skal være logget ind for at like';
            exit;
        }

        require_once __DIR__ . "/../../private/x.php";

        $userPk = $_SESSION["user"]["user_pk"];
        $postPk = _validatePk("post_fk");

        if (!LikeModel::exists($userPk, $postPk)) {
            LikeModel::create($userPk, $postPk);
        }

        http_response_code(204);
        exit;
    }

    public static function unlike(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo 'Du skal være logget ind for at unlike';
            exit;
        }

        require_once __DIR__ . "/../../private/x.php";

        $userPk = $_SESSION["user"]["user_pk"];
        $postPk = _validatePk("post_fk");

        LikeModel::delete($userPk, $postPk);

        http_response_code(204);
        exit;
    }

};
