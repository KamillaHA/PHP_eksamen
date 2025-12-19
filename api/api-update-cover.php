<?php
session_start();
require_once __DIR__ . '/../private/db.php';

if (!isset($_SESSION['user'])) {
    exit;
}

if (empty($_FILES['cover_image']['tmp_name'])) {
    exit;
}

$filename = uniqid() . '_' . basename($_FILES['cover_image']['name']);
$uploadDir = __DIR__ . '/../uploads/covers/';
$targetPath = $uploadDir . $filename;

move_uploaded_file($_FILES['cover_image']['tmp_name'], $targetPath);

// VIGTIGT: web-path i DB
$coverPath = '/uploads/covers/' . $filename;

$stmt = $_db->prepare(
    "UPDATE users SET user_cover_image = :cover WHERE user_pk = :pk"
);
$stmt->execute([
    ':cover' => $coverPath,
    ':pk' => $_SESSION['user']['user_pk']
]);

header('Location: /profile');
exit;
