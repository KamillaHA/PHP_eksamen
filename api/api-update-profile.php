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

    // Check if username is already taken by another user
    require_once __DIR__ . '/../private/db.php';
    
    // Check if username is taken by another user
    $checkSql = "SELECT user_pk FROM users WHERE user_username = :username AND user_pk != :pk";
    $checkStmt = $_db->prepare($checkSql);
    $checkStmt->bindValue(':username', $newUsername);
    $checkStmt->bindValue(':pk', $user['user_pk']);
    $checkStmt->execute();
    
    if ($checkStmt->fetch()) {
        throw new Exception("Username already taken", 400);
    }
    
    // Check if email is taken by another user
    $checkSql = "SELECT user_pk FROM users WHERE user_email = :email AND user_pk != :pk";
    $checkStmt = $_db->prepare($checkSql);
    $checkStmt->bindValue(':email', $newEmail);
    $checkStmt->bindValue(':pk', $user['user_pk']);
    $checkStmt->execute();
    
    if ($checkStmt->fetch()) {
        throw new Exception("Email already in use", 400);
    }

    // Update user
    $sql = "UPDATE users 
            SET user_email = :email, 
                user_username = :username, 
                user_full_name = :full_name,
                updated_at = CURRENT_TIMESTAMP 
            WHERE user_pk = :pk";
    
    $stmt = $_db->prepare($sql);
    $stmt->bindValue(':email', $newEmail);
    $stmt->bindValue(':username', $newUsername);
    $stmt->bindValue(':full_name', $newFullName);
    $stmt->bindValue(':pk', $user['user_pk']);
    $stmt->execute();

    // Update session
    $user['user_email'] = $newEmail;
    $user['user_username'] = $newUsername;
    $user['user_full_name'] = $newFullName;
    $_SESSION["user"] = $user;

    // Redirect back to profile with success message
    header("Location: /profile?message=Profile updated successfully");
    exit;
    
} catch (Exception $e) {
    // Redirect back to profile with error message
    $errorMessage = urlencode($e->getMessage());
    header("Location: /profile?message=Error: " . $errorMessage);
    exit;
}
