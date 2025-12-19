<?php
require_once __DIR__ . "/../models/LikeModel.php";

class LikeController
{
    public static function like(): void
    {
        session_start();
        require_once __DIR__ . "/../../private/x.php";

        $userPk = $_SESSION["user"]["user_pk"];
        $postPk = _validatePk("post_fk");

        if (!LikeModel::exists($userPk, $postPk)) {
            LikeModel::create($userPk, $postPk);
        }

        echo '<mixhtml mix-redirect="/home"></mixhtml>';
        exit;
    }

    public static function unlike(): void
    {
        session_start();
        require_once __DIR__ . "/../../private/x.php";

        LikeModel::delete($_SESSION["user"]["user_pk"], _validatePk("post_fk"));
        echo '<mixhtml mix-redirect="/home"></mixhtml>';
        exit;
    }
}
