<?php
require_once __DIR__ . "/../models/CommentModel.php";

class CommentController
{
    public static function create(): void
    {
        session_start();
        require_once __DIR__ . "/../../private/x.php";

        CommentModel::create([
            ":pk"   => bin2hex(random_bytes(25)),
            ":text" => validateCommentText(),
            ":post" => _validatePk("post_fk"),
            ":user" => $_SESSION["user"]["user_pk"]
        ]);

        echo '<mixhtml mix-redirect="/home"></mixhtml>';
        exit;
    }

    public static function delete(): void
    {
        require_once __DIR__ . "/../../private/x.php";
        CommentModel::softDelete(_validatePk("comment_pk"));
        echo '<mixhtml mix-redirect="/home"></mixhtml>';
        exit;
    }
}
