<?php

class FollowModel
{
    public static function countFollowers(string $userPk): int
    {
        require __DIR__ . '/../../private/db.php';

        $stmt = $_db->prepare("
            SELECT COUNT(*)
            FROM follows
            JOIN users ON follows.follower_fk = users.user_pk
            WHERE follows.following_fk = ?
            AND users.deleted_at IS NULL
        ");
        $stmt->execute([$userPk]);

        return (int) $stmt->fetchColumn();
    }

    public static function countFollowing(string $userPk): int
    {
        require __DIR__ . '/../../private/db.php';

        $stmt = $_db->prepare("
            SELECT COUNT(*)
            FROM follows
            JOIN users ON follows.following_fk = users.user_pk
            WHERE follows.follower_fk = ?
            AND users.deleted_at IS NULL
        ");
        $stmt->execute([$userPk]);

        return (int) $stmt->fetchColumn();
    }

    // forslag til "Who to follow"
    public static function suggestions(string $currentUserPk): array
    {
        require __DIR__ . '/../../private/db.php';

        $stmt = $_db->prepare("
            SELECT user_pk, user_username, user_full_name
            FROM users
            WHERE user_pk != :current_user
            AND deleted_at IS NULL
            ORDER BY created_at ASC
            LIMIT 3
        ");

        $stmt->execute([
            ':current_user' => $currentUserPk
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔹 tjek om man allerede følger
    public static function isFollowing(string $currentUserPk, string $suggestedUserPk): bool
    {
        require __DIR__ . '/../../private/db.php';

        $stmt = $_db->prepare("
            SELECT 1
            FROM follows
            WHERE follower_fk = :current
            AND following_fk = :suggested
            LIMIT 1
        ");

        $stmt->execute([
            ':current'   => $currentUserPk,
            ':suggested' => $suggestedUserPk
        ]);

        return (bool) $stmt->fetchColumn();
    }

    // 🔹 OPRET FOLLOW
    public static function create(string $followerPk, string $followingPk): void
    {
        require __DIR__ . '/../../private/db.php';

        $stmt = $_db->prepare("
            INSERT INTO follows (follower_fk, following_fk)
            VALUES (:follower, :following)
        ");

        $stmt->execute([
            ':follower'  => $followerPk,
            ':following' => $followingPk
        ]);
    }

    // 🔹 SLET FOLLOW
    public static function delete(string $followerPk, string $followingPk): void
    {
        require __DIR__ . '/../../private/db.php';

        $stmt = $_db->prepare("
            DELETE FROM follows
            WHERE follower_fk = :follower
              AND following_fk = :following
        ");

        $stmt->execute([
            ':follower'  => $followerPk,
            ':following' => $followingPk
        ]);
    }
}
