<?php

class PostModel
{
    // Henter alle posts for en bestemt bruger (profil-visning)
    public static function getAllWithUser(string $userPk): array
    {
        // Indlæser databaseforbindelsen
        require __DIR__ . '/../../private/db.php';

        // Henter posts med tilhørende brugerinfo
        // Soft deleted brugere og posts filtreres fra
        $stmt = $_db->prepare("
            SELECT posts.*, users.user_username, users.user_full_name
            FROM posts
            JOIN users ON posts.post_user_fk = users.user_pk
            AND users.deleted_at IS NULL
            WHERE posts.post_user_fk = ?
            AND posts.deleted_at IS NULL
            ORDER BY posts.created_at DESC
        ");

        $stmt->execute([$userPk]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

     // Henter alle posts til feedet (/home)
    public static function getAll(): array
    {
        require __DIR__ . '/../../private/db.php';

        $stmt = $_db->prepare("
            SELECT posts.*, users.user_username, users.user_full_name
            FROM posts
            JOIN users ON posts.post_user_fk = users.user_pk
            AND users.deleted_at IS NULL
            WHERE posts.deleted_at IS NULL
            ORDER BY posts.created_at DESC
        ");

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Opretter et nyt post
    public static function create(array $data): void
    {
        require __DIR__ . '/../../private/db.php';

        // Prepared statement med placeholders
        $stmt = $_db->prepare("
            INSERT INTO posts (post_pk, post_message, post_image_path, post_user_fk)
            VALUES (:pk, :message, :image, :user)
        ");

        $stmt->execute($data);
    }

    // Opdaterer et post (med eller uden nyt billede)
    public static function update(string $postPk, string $message, ?string $imagePath = null): void
    {
        require __DIR__ . '/../../private/db.php';

        // Hvis der er uploadet et nyt billede
        if ($imagePath) {
            $stmt = $_db->prepare("
                UPDATE posts
                SET post_message = :message,
                    post_image_path = :image
                WHERE post_pk = :pk
            ");

            $stmt->execute([
                ':message' => $message,
                ':image'   => $imagePath,
                ':pk'      => $postPk
            ]);
        } else {

            // Opdater kun teksten hvis billedet ikke ændres
            $stmt = $_db->prepare("
                UPDATE posts
                SET post_message = :message
                WHERE post_pk = :pk
            ");

            $stmt->execute([
                ':message' => $message,
                ':pk'      => $postPk
            ]);
        }
    }

    // Soft delete af et post
    public static function delete(string $postPk): void
    {
        require __DIR__ . '/../../private/db.php';

        $stmt = $_db->prepare("
            UPDATE posts
            SET deleted_at = NOW()
            WHERE post_pk = :pk
        ");

        $stmt->execute([':pk' => $postPk]);
    }

    // Soft delete alle posts fra en bestemt bruger
    public static function softDeleteByUser(string $userPk): void
    {
    require __DIR__ . '/../../private/db.php';

    $_db->prepare("
        UPDATE posts
        SET deleted_at = CURRENT_TIMESTAMP
        WHERE post_user_fk = :user
        AND deleted_at IS NULL
    ")->execute([
        ':user' => $userPk
    ]);
}
}