<?php

class LikeModel
{
    // Tjekker om en bruger allerede har liket et post
    public static function exists(string $userPk, string $postPk): bool
    {
        // Indlæser databaseforbindelsen
        require __DIR__ . "/../../private/db.php";

        // Returnerer 1 hvis relationen findes
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

    // Opretter et like på et post
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

    // Fjerner et like (unlike)
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

    // Tæller antal likes på et post
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

    // Sletter alle likes fra en bestemt bruger
    // Bruges fx når en bruger soft deletes
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
