<?php

class FollowModel
{
    public static function exists(string $follower, string $following): bool
    {
        require __DIR__ . "/../../private/db.php";

        $stmt = $_db->prepare(
            "SELECT 1 FROM follows WHERE follower_fk = :f AND following_fk = :g"
        );
        $stmt->execute([":f" => $follower, ":g" => $following]);

        return (bool) $stmt->fetch();
    }

    public static function create(string $follower, string $following): void
    {
        require __DIR__ . "/../../private/db.php";

        $_db->prepare(
            "INSERT INTO follows (follower_fk, following_fk)
            VALUES (:f, :g)"
        )->execute([":f" => $follower, ":g" => $following]);
    }

    public static function delete(string $follower, string $following): void
    {
        require __DIR__ . "/../../private/db.php";

        $_db->prepare(
            "DELETE FROM follows WHERE follower_fk = :f AND following_fk = :g"
        )->execute([":f" => $follower, ":g" => $following]);
    }
}
