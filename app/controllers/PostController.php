<?php
require_once __DIR__ . "/../models/PostModel.php";

class PostController
{
    public static function create(): void
    {
        session_start();
        require_once __DIR__ . "/../../private/x.php";

        PostModel::create([
            ":pk"      => bin2hex(random_bytes(25)),
            ":message" => _validatePost(),
            ":image"   => "https://picsum.photos/400/250",
            ":user"    => $_SESSION["user"]["user_pk"]
        ]);

        echo '<mixhtml mix-redirect="/home"></mixhtml>';
        exit;
    }

    public static function update(): void
    {
        require_once __DIR__ . "/../../private/x.php";
        PostModel::update(_validatePk("post_pk"), _validatePost());
        echo '<mixhtml mix-redirect="/home"></mixhtml>';
        exit;
    }

    public static function delete(): void
    {
        require_once __DIR__ . "/../../private/x.php";
        PostModel::delete(_validatePk("post_pk"));
        echo '<mixhtml mix-redirect="/home"></mixhtml>';
        exit;
    }
}
