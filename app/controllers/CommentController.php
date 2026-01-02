<?php
require_once __DIR__ . "/../models/CommentModel.php";

class CommentController
{
    public static function create(): void
    {
        require_once __DIR__ . "/../../private/x.php";

        $postPk = _validatePk("post_pk");
        
        CommentModel::create([
            ":pk"   => bin2hex(random_bytes(25)),
            ":text" => validateCommentText(),
            ":post" => $postPk,
            ":user" => $_SESSION["user"]["user_pk"]
        ]);

        // Hent brugernavn for redirect
        require __DIR__ . '/../../private/db.php';
        $stmt = $_db->prepare("
            SELECT users.user_username 
            FROM posts 
            JOIN users ON posts.post_user_fk = users.user_pk 
            WHERE posts.post_pk = ?
        ");
        $stmt->execute([$postPk]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($post) {
            header("Location: /" . $post['user_username'] . "/status/" . $postPk);
        } else {
            header("Location: /home");
        }
        exit;
    }

    public static function update(): void
    {
        require_once __DIR__ . "/../../private/x.php";

        $commentPk = _validatePk("comment_pk");
        $text      = validateCommentText();
        $postPk = CommentModel::getPostPkByComment($commentPk);

        CommentModel::update($commentPk, $text);

        require __DIR__ . '/../../private/db.php';
        $stmt = $_db->prepare("
            SELECT users.user_username 
            FROM posts 
            JOIN users ON posts.post_user_fk = users.user_pk 
            WHERE posts.post_pk = ?
        ");
        $stmt->execute([$postPk]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($post) {
            header("Location: /" . $post['user_username'] . "/status/" . $postPk);
        } else {
            header("Location: /home");
        }
        exit;
    }

    public static function delete(): void
    {
        require_once __DIR__ . "/../../private/x.php";

        $commentPk = _validatePk("comment_pk");
        $postPk    = _validatePk("post_pk");

        CommentModel::softDelete($commentPk);

        require __DIR__ . '/../../private/db.php';
        $stmt = $_db->prepare("
            SELECT users.user_username 
            FROM posts 
            JOIN users ON posts.post_user_fk = users.user_pk 
            WHERE posts.post_pk = ?
        ");
        $stmt->execute([$postPk]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($post) {
            header("Location: /" . $post['user_username'] . "/status/" . $postPk);
        } else {
            header("Location: /home");
        }
        exit;
    }
}