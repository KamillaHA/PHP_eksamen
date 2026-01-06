<?php

class LikeModel
{
    public static function exists(string $userPk, string $postPk): bool
    {
        require __DIR__ . "/../../private/db.php";

        $stmt = $_db->prepare(
            "SELECT 1 
            FROM likes 
            WHERE like_user_fk = :u 
            AND like_post_fk = :p"
        );
        $stmt->execute([
            ":u" => $userPk,
            ":p" => $postPk
        ]);

        return (bool) $stmt->fetchColumn();
    }

    public static function create(string $userPk, string $postPk): void
    {
        require __DIR__ . "/../../private/db.php";

        $_db->prepare(
            "INSERT INTO likes (like_user_fk, like_post_fk)
            VALUES (:u, :p)"
        )->execute([
            ":u" => $userPk,
            ":p" => $postPk
        ]);
    }

    public static function delete(string $userPk, string $postPk): void
    {
        require __DIR__ . "/../../private/db.php";

        $_db->prepare(
            "DELETE FROM likes 
            WHERE like_user_fk = :u 
            AND like_post_fk = :p"
        )->execute([
            ":u" => $userPk,
            ":p" => $postPk
        ]);
    }

    /* Antal likes */
    public static function countByPost(string $postPk): int
    {
        require __DIR__ . "/../../private/db.php";

        $stmt = $_db->prepare(
            "SELECT COUNT(*) 
            FROM likes 
            WHERE like_post_fk = :p"
        );
        $stmt->execute([":p" => $postPk]);

        return (int) $stmt->fetchColumn();
    }

// Delete likes by soft deleted user
    public static function deleteByUser(string $userPk): void
{
    require __DIR__ . "/../../private/db.php";

    $_db->prepare("
        DELETE FROM likes
        WHERE like_user_fk = :user
    ")->execute([
        ':user' => $userPk
    ]);
}
}
