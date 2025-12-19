<?php

class CommentModel
{
    public static function create(array $data): void
    {
        require __DIR__ . "/../../private/db.php";

        $sql = "INSERT INTO comments
                (comment_pk, comment_text, post_fk, user_fk)
                VALUES (:pk, :text, :post, :user)";
        $_db->prepare($sql)->execute($data);
    }

    public static function findByPost(string $postPk): array
    {
        require __DIR__ . "/../../private/db.php";

        $sql = "SELECT comments.*, users.user_username
                FROM comments
                JOIN users ON comments.user_fk = users.user_pk
                WHERE post_fk = :post
                AND deleted_at IS NULL
                ORDER BY created_at DESC";

        $stmt = $_db->prepare($sql);
        $stmt->bindValue(":post", $postPk);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function softDelete(string $commentPk): void
    {
        require __DIR__ . "/../../private/db.php";

        $_db->prepare(
            "UPDATE comments SET deleted_at = CURRENT_TIMESTAMP WHERE comment_pk = :pk"
        )->execute([":pk" => $commentPk]);
    }
}
