<?php

// TODO: VAlidate data
// TODO: if error back to signup
// TODO: if ok redirect to profile
require_once __DIR__ . "/../private/x.php";

try {
    $userFullName = _validateFullName(); 
    $username = _validateUsername();
    $userEmail = _validateEmail();
    $userPassword = _validatePassword();
    $hashedPassword = password_hash($userPassword, PASSWORD_DEFAULT);

    $userPk = bin2hex(random_bytes(25));

    require_once __DIR__ . "/../private/db.php";
    $sql = "INSERT INTO users (user_pk, user_username, user_full_name, user_email, user_password) VALUES (:user_pk, :user_username, :user_full_name, :email, :password)";
    $stmt = $_db->prepare($sql);

    $stmt->bindValue(":user_pk", $userPk);
    $stmt->bindValue(":user_username", $username);
    $stmt->bindValue(":user_full_name", $userFullName);
    $stmt->bindValue(":email", $userEmail);
    $stmt->bindValue(":password", $hashedPassword);

    $stmt->execute();

    header("Location: /login?message=" . urlencode("Account created successfully! Please login."));
    exit();
} catch (Exception $e) {
    http_response_code($e->getCode());
    echo $e->getMessage();
}
