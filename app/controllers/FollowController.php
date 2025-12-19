<?php
require_once __DIR__ . "/../models/FollowModel.php";

class FollowController
{
    public static function follow(): void
    {
        session_start();
        require_once __DIR__ . "/../../private/x.php";

        $follower  = $_SESSION["user"]["user_pk"];
        $following = _validatePk("following_fk");

        if (!FollowModel::exists($follower, $following)) {
            FollowModel::create($follower, $following);
        }

        echo '<mixhtml mix-redirect="/home"></mixhtml>';
        exit;
    }

    public static function unfollow(): void
    {
        session_start();
        require_once __DIR__ . "/../../private/x.php";

        FollowModel::delete($_SESSION["user"]["user_pk"], _validatePk("following_fk"));
        echo '<mixhtml mix-redirect="/home"></mixhtml>';
        exit;
    }
}
