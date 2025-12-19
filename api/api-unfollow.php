<?php
session_start();
require_once __DIR__ . '/../private/db.php';

$follower_pk = $_SESSION['user']['user_pk'] ?? null; 
$following_pk = $_POST['following_pk'] ?? null;

if (!$follower_pk || !$following_pk) exit('Invalid request');

try {
    $stmt = $_db->prepare("DELETE FROM follows WHERE follower_fk = ? AND following_fk = ?");
    $stmt->execute([$follower_pk, $following_pk]);
} catch (PDOException $e) {
    exit('Error: ' . $e->getMessage());
}

$user = ['user_pk' => $following_pk];

echo "<mix-html mix-replace='.button-$following_pk'>";
require_once __DIR__ . '/../micro_components/___button-follow.php';
echo "</mix-html>";
?>
