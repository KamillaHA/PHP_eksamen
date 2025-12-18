<?php
session_start();
require_once __DIR__ . '/../private/db.php';

$follower_pk = $_SESSION['user']['user_pk'] ?? null; 
$following_pk = $_POST['following_pk'] ?? null;

if (!$follower_pk || !$following_pk) exit('Invalid request');

try {
    $stmt = $_db->prepare("INSERT INTO follows (follower_fk, following_fk) VALUES (?, ?)");
    $stmt->execute([$follower_pk, $following_pk]);
} catch (PDOException $e) {
    exit('Error: ' . $e->getMessage());
}

echo "<mix-html mix-replace='.button-$following_pk'>";
require_once __DIR__ . '/../micro_components/___button-unfollow.php';
echo "</mix-html>";
?>