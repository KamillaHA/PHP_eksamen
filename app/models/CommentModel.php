<?php

class CommentModel
{
    /* =========================
    CREATE
    ========================= */
    public static function create(array $data): void
    {
        require __DIR__ . "/../../private/db.php";

        $sql = "
            INSERT INTO comments
            (comment_pk, comment_text, post_fk, user_fk)
            VALUES (:pk, :text, :post, :user)
        ";

        $_db->prepare($sql)->execute($data);
    }

    /* =========================
    READ – comments for post
    ========================= */
    public static function findByPost(string $postPk): array
    {
        require __DIR__ . "/../../private/db.php";

        $sql = "
                SELECT
                    comments.comment_pk,
                    comments.comment_text,
                    comments.post_fk AS post_pk,
                    comments.user_fk,
                    comments.created_at,
                    users.user_username
                FROM comments
                JOIN users ON comments.user_fk = users.user_pk
                WHERE comments.post_fk = :post
                AND comments.deleted_at IS NULL
                ORDER BY comments.created_at DESC
        ";

        $stmt = $_db->prepare($sql);
        $stmt->execute([":post" => $postPk]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =========================
    COUNT comments on post
    ========================= */
    public static function countByPost(string $postPk): int
    {
        require __DIR__ . "/../../private/db.php";

        $stmt = $_db->prepare("
            SELECT COUNT(*)
            FROM comments
            WHERE post_fk = :post
            AND deleted_at IS NULL
        ");

        $stmt->execute([":post" => $postPk]);

        return (int) $stmt->fetchColumn();
    }

    /* =========================
    UPDATE (edit comment)
    ========================= */
    public static function update(string $commentPk, string $text): void
    {
        require __DIR__ . "/../../private/db.php";

        $_db->prepare("
            UPDATE comments
            SET comment_text = :text
            WHERE comment_pk = :pk
            AND deleted_at IS NULL
        ")->execute([
            ":text" => $text,
            ":pk"   => $commentPk
        ]);
    }

    /* =========================
    SOFT DELETE
    ========================= */
    public static function softDelete(string $commentPk): void
    {
        require __DIR__ . "/../../private/db.php";

        $_db->prepare("
            UPDATE comments
            SET deleted_at = CURRENT_TIMESTAMP
            WHERE comment_pk = :pk
        ")->execute([
            ":pk" => $commentPk
        ]);
    }

    /* =========================
    READ – post for comment
    ========================= */
    public static function getPostPkByComment(string $commentPk): string
    {
        require __DIR__ . "/../../private/db.php";
    
        $stmt = $_db->prepare("
            SELECT post_fk
            FROM comments
            WHERE comment_pk = :pk
            LIMIT 1
        ");
    
        $stmt->execute([":pk" => $commentPk]);
    
        $postPk = $stmt->fetchColumn();
    
        if (!$postPk) {
            throw new Exception("Post not found for comment");
        }
    
        return $postPk;
    }
}

