<?php

class SearchModel
{
    public static function posts(string $q): array
    {
        require __DIR__ . "/../../private/db.php";

        $stmt = $_db->prepare(
            "SELECT posts.post_pk, posts.post_message, users.user_username
            FROM posts
            JOIN users ON posts.post_user_fk = users.user_pk
            WHERE post_message LIKE :q
            ORDER BY posts.created_at DESC
            LIMIT 20"
        );
        $stmt->execute([":q" => "%{$q}%"]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
