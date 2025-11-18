<?php
session_start();
$follower_pk = $_SESSION['user']['user_pk'] ?? null; 
$followee_pk = $_GET['followee_pk'] ?? '';

if (!$follower_pk || !$followee_pk) exit('Invalid request');

// Normally: remove follow logic here (DB delete)

echo "<mix-html mix-replace='.button-$followee_pk'>";
require_once __DIR__ . '/../___/___button-follow.php'; // micro-component
echo "</mix-html>";
