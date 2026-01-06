<?php

class CommentModel
{
    // Opretter en ny kommentar i databasen
    public static function create(array $data): void
    {
        // Indlæser databaseforbindelsen
        require __DIR__ . "/../../private/db.php";

        // Prepared statement beskytter mod SQL injection
        $sql = "
            INSERT INTO comments
            (comment_pk, comment_text, post_fk, user_fk)
            VALUES (:pk, :text, :post, :user)
        ";

        $_db->prepare($sql)->execute($data);
    }

    // Henter alle kommentarer til et specifikt post
    public static function findByPost(string $postPk): array
    {
        require __DIR__ . "/../../private/db.php";

        // Join med users for at få brugernavn
        // Soft deleted brugere og kommentarer filtreres fra
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
                AND users.deleted_at IS NULL
                WHERE comments.post_fk = :post
                AND comments.deleted_at IS NULL
                ORDER BY comments.created_at DESC
        ";

        $stmt = $_db->prepare($sql);
        $stmt->execute([":post" => $postPk]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tæller antal aktive kommentarer på et post
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

        // fetchColumn returnerer string → castes til int
        return (int) $stmt->fetchColumn();
    }

    // Opdaterer teksten på en eksisterende kommentar
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

    // Soft delete af en kommentar (bevarer data i databasen)
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

    // Soft delete alle kommentarer fra en bestemt bruger
    public static function softDeleteByUser(string $userPk): void
    {
        require __DIR__ . '/../../private/db.php';

        $_db->prepare("
            UPDATE comments
            SET deleted_at = CURRENT_TIMESTAMP
            WHERE user_fk = :user
            AND deleted_at IS NULL
        ")->execute([
            ':user' => $userPk
        ]);
    }

    // Finder hvilket post en kommentar hører til
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
    
        // Hvis kommentaren ikke findes, kastes en exception
        if (!$postPk) {
            throw new Exception("Post not found for comment");
        }
    
        return $postPk;
    }
}

