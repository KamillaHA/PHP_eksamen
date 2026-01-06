<?php

class UserModel
{
    public static function findByEmail(string $email): ?array
    {
        require __DIR__ . "/../../private/db.php";

        $sql = "
            SELECT *
            FROM users
            WHERE user_email = :email
            AND deleted_at IS NULL
            LIMIT 1
        ";        
        $stmt = $_db->prepare($sql);
        $stmt->bindValue(":email", $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function findByPk(string $pk): ?array
    {
        require __DIR__ . "/../../private/db.php";

        $sql = "
            SELECT user_pk, user_username, user_full_name,
            user_email, user_cover_image, created_at
            FROM users
            WHERE user_pk = :pk
            AND deleted_at IS NULL
        ";
        $stmt = $_db->prepare($sql);
        $stmt->bindValue(":pk", $pk);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function create(array $user): void
    {
        require __DIR__ . "/../../private/db.php";

        try {
            $sql = "INSERT INTO users
                    (user_pk, user_username, user_full_name, user_email, user_password)
                    VALUES (:pk, :username, :fullname, :email, :password)";
            $_db->prepare($sql)->execute($user);
            
        } catch (PDOException $e) {
            // Håndter UNIQUE constraint violation
            if (strpos($e->getMessage(), 'unique_user_email') !== false) {
                throw new Exception("Email already in use");
            }
            if (strpos($e->getMessage(), 'unique_user_username') !== false) {
                throw new Exception("Username already in use");
            }
            throw $e; // Kast andre exceptions videre
        }
    }

    public static function update(string $pk, array $data): void
    {
        require __DIR__ . "/../../private/db.php";

        $sql = "UPDATE users
                SET user_username = :username,
                    user_full_name = :fullname,
                    user_email = :email,
                    updated_at = CURRENT_TIMESTAMP
                WHERE user_pk = :pk";

        $_db->prepare($sql)->execute([
            ":pk" => $pk,
            ":username" => $data["username"],
            ":fullname" => $data["fullname"],
            ":email" => $data["email"]
        ]);
    }

    public static function updateCover(string $pk, string $path): void
    {
        require __DIR__ . "/../../private/db.php";

        $_db->prepare("
            UPDATE users
            SET user_cover_image = :cover
            WHERE user_pk = :pk
        ")->execute([
            ':cover' => $path,
            ':pk' => $pk
        ]);
    }

    public static function delete(string $pk): void
    {
        require __DIR__ . "/../../private/db.php";

        $_db->prepare("
            UPDATE users
            SET deleted_at = CURRENT_TIMESTAMP
            WHERE user_pk = :pk
            AND deleted_at IS NULL
        ")->execute([
            ":pk" => $pk
        ]);
    }
}