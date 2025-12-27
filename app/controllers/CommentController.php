<?php
require_once __DIR__ . "/../models/CommentModel.php";

class CommentController
{
    public static function create(): void
    {
        require_once __DIR__ . "/../../private/x.php";

        CommentModel::create([
            ":pk"   => bin2hex(random_bytes(25)),
            ":text" => validateCommentText(),
            ":post" => _validatePk("post_pk"),
            ":user" => $_SESSION["user"]["user_pk"]
        ]);

        header("Location: /home?post=" . _validatePk("post_pk"));
        exit;
    }

    public static function update(): void
    {
        require_once __DIR__ . "/../../private/x.php";

        $commentPk = _validatePk("comment_pk");
        $text      = validateCommentText();
        $postPk = CommentModel::getPostPkByComment($commentPk);

        CommentModel::update($commentPk, $text);

        header("Location: /home?post=" . $postPk);
        exit;
    }

    public static function delete(): void
    {
        require_once __DIR__ . "/../../private/x.php";

        $commentPk = _validatePk("comment_pk");
        $postPk    = _validatePk("post_pk");

        CommentModel::softDelete($commentPk);

        header("Location: /home?post=" . $postPk);
        exit;
    }
}