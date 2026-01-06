<?php

class FollowModel
{
    // Tæller hvor mange brugere der følger en given bruger
    public static function countFollowers(string $userPk): int
    {
        // Indlæser databaseforbindelsen
        require __DIR__ . '/../../private/db.php';

        // Tæller kun følgere der ikke er soft deleted
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

    // Tæller hvor mange brugere en given bruger følger
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

    // Finder forslag til "Who to follow"
    public static function suggestions(string $currentUserPk): array
    {
        require __DIR__ . '/../../private/db.php';

        // Henter aktive brugere, undtagen den loggede ind bruger
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

    // Tjekker om en bruger allerede følger en anden bruger
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

    // Opretter en follow-relation
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

    // Sletter en follow-relation (unfollow)
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
