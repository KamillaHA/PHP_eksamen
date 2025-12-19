<?php

class PostModel
{
    public static function create(array $post): void
    {
        require __DIR__ . "/../../private/db.php";

        $sql = "INSERT INTO posts
                (post_pk, post_message, post_image_path, post_user_fk)
                VALUES (:pk, :message, :image, :user)";
        $_db->prepare($sql)->execute($post);
    }

    public static function findByPk(string $pk): ?array
    {
        require __DIR__ . "/../../private/db.php";

        $sql = "SELECT posts.*, users.user_username
                FROM posts
                JOIN users ON posts.post_user_fk = users.user_pk
                WHERE post_pk = :pk";
        $stmt = $_db->prepare($sql);
        $stmt->bindValue(":pk", $pk);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function update(string $pk, string $message): void
    {
        require __DIR__ . "/../../private/db.php";

        $_db->prepare(
            "UPDATE posts SET post_message = :msg WHERE post_pk = :pk"
        )->execute([
            ":pk" => $pk,
            ":msg" => $message
        ]);
    }

    public static function delete(string $pk): void
    {
        require __DIR__ . "/../../private/db.php";

        $_db->prepare("DELETE FROM posts WHERE post_pk = :pk")
            ->execute([":pk" => $pk]);
    }
}
