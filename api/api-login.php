<?php

try{
    require_once __DIR__."/../private/x.php";
    $userEmail = _validateEmail();
    $userPassword = _validatePassword();
    require_once __DIR__."/../private/db.php";
    $sql = "SELECT * FROM users WHERE user_email = :email";
    $stmt = $_db->prepare( $sql );

    $stmt->bindValue(":email", $userEmail);
    $stmt->execute();
    $user = $stmt->fetch();

    if(!$user){
        header("Location: /login?message=User not found");
        exit();
    }

    if( !password_verify($userPassword, $user["user_password"])){
        header("Location: /login?message=Invalid credentials");
        exit();
    };

    unset($user["user_password"]);
    session_start();
    $_SESSION["user"] = $user;
    header("Location: /home");
}

catch(Exception $e){
    http_response_code($e->getCode());
    echo ($e->getMessage());
}