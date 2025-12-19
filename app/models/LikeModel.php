<?php

class LikeModel
{
    public static function exists(string $userPk, string $postPk): bool
    {
        require __DIR__ . "/../../private/db.php";

        $stmt = $_db->prepare(
            "SELECT 1 FROM likes WHERE user_fk = :u AND post_fk = :p"
        );
        $stmt->execute([":u" => $userPk, ":p" => $postPk]);

        return (bool) $stmt->fetch();
    }

    public static function create(string $userPk, string $postPk): void
    {
        require __DIR__ . "/../../private/db.php";

        $_db->prepare(
            "INSERT INTO likes (user_fk, post_fk) VALUES (:u, :p)"
        )->execute([":u" => $userPk, ":p" => $postPk]);
    }

    public static function delete(string $userPk, string $postPk): void
    {
        require __DIR__ . "/../../private/db.php";

        $_db->prepare(
            "DELETE FROM likes WHERE user_fk = :u AND post_fk = :p"
        )->execute([":u" => $userPk, ":p" => $postPk]);
    }
}
