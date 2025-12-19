<?php
require_once __DIR__ . "/../models/LikeModel.php";

class LikeController
{
    public static function like(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        require_once __DIR__ . "/../../private/x.php";

        $userPk = $_SESSION["user"]["user_pk"];
        $postPk = _validatePk("post_fk");

        if (!LikeModel::exists($userPk, $postPk)) {
            LikeModel::create($userPk, $postPk);
        }

        // Beregn ny state
        $userLiked = true;
        $likeCount = LikeModel::countByPost($postPk);

        require __DIR__ . '/../views/micro_components/___like-button.php';
        exit;
    }


    public static function unlike(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        require_once __DIR__ . "/../../private/x.php";

        $userPk = $_SESSION["user"]["user_pk"];
        $postPk = _validatePk("post_fk");

        LikeModel::delete($userPk, $postPk);

        // Beregn ny state
        $userLiked = false;
        $likeCount = LikeModel::countByPost($postPk);

        require __DIR__ . '/../views/micro_components/___like-button.php';
        exit;
    }
}
