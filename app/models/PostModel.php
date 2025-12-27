<?php

class PostModel
{
    public static function getAllWithUser(string $userPk): array
    {
        require __DIR__ . '/../../private/db.php';

        $stmt = $_db->prepare("
            SELECT posts.*, users.user_username, users.user_full_name
            FROM posts
            JOIN users ON posts.post_user_fk = users.user_pk
            WHERE posts.post_user_fk = ?
              AND posts.deleted_at IS NULL
            ORDER BY posts.created_at DESC
        ");

        $stmt->execute([$userPk]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔥 DEN MANGLENDE METODE (til /home)
    public static function getAll(): array
    {
        require __DIR__ . '/../../private/db.php';

        $stmt = $_db->prepare("
            SELECT posts.*, users.user_username, users.user_full_name
            FROM posts
            JOIN users ON posts.post_user_fk = users.user_pk
            WHERE posts.deleted_at IS NULL
            ORDER BY posts.created_at DESC
        ");

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create(array $data): void
    {
        require __DIR__ . '/../../private/db.php';

        $stmt = $_db->prepare("
            INSERT INTO posts (post_pk, post_message, post_image_path, post_user_fk)
            VALUES (:pk, :message, :image, :user)
        ");

        $stmt->execute($data);
    }

    public static function update(string $postPk, string $message, ?string $imagePath = null): void
    {
        require __DIR__ . '/../../private/db.php';

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
}