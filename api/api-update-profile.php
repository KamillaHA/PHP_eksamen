<?php

session_start();
$user = $_SESSION["user"];

if (!$user) {
    header("Location: /login?message=Please login to update your profile");
    exit;
}

try {
    require_once __DIR__ . '/../private/x.php';

    $newEmail = _validateEmail();
    $newUsername = _validateUsername();
    $newFullName = _validateFullName();

    require_once __DIR__ . '/../private/db.php';
    $sql = "UPDATE users SET user_email = :email, user_username = :username, user_full_name = :full_name WHERE user_pk = :pk";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':email', $newEmail);
    $stmt->bindParam(':username', $newUsername);
    $stmt->bindParam(':full_name', $newFullName);
    $stmt->bindParam(':pk', $user['user_pk']);
    $stmt->execute();

    $user['user_email'] = $newEmail;
    $user['user_username'] = $newUsername;
    $user['user_full_name'] = $newFullName;
    $_SESSION["user"] = $user;

    header("Location: /home");
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
