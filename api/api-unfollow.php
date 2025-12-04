<?php
session_start();
require_once __DIR__ . '/../private/db.php';

$follower_pk = $_SESSION['user']['user_pk'] ?? null; 
$user_pk = $_GET['user_pk'] ?? '';

if (!$follower_pk || !$user_pk) exit('Invalid request');

try {
    $stmt = $_db->prepare("DELETE FROM follows WHERE follower_fk = ? AND following_fk = ?");
    $stmt->execute([$follower_pk, $user_pk]);
} catch (PDOException $e) {
    exit('Error: ' . $e->getMessage());
}

echo "<mix-html mix-replace='.button-$user_pk'>";
require_once __DIR__ . '/../micro_components/___button-follow.php';
echo "</mix-html>";
?>