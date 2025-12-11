<?php
session_start();

try {
    require_once __DIR__."/../private/x.php";
    $userEmail = _validateEmail();
    $userPassword = _validatePassword();
    require_once __DIR__."/../private/db.php";
    
    $sql = "SELECT * FROM users WHERE user_email = :email";
    $stmt = $_db->prepare($sql);
    $stmt->bindValue(":email", $userEmail);
    $stmt->execute();
    $user = $stmt->fetch();

    if(!$user) {
        // Returner HTML som mix.js kan håndtere
        echo '<mixhtml mix-redirect="/?message=User not found"></mixhtml>';
        exit();
    }

    if(!password_verify($userPassword, $user["user_password"])) {
        echo '<mixhtml mix-redirect="/?message=Invalid credentials"></mixhtml>';
        exit();
    }

    // Login successful
    unset($user["user_password"]);
    $_SESSION["user"] = $user;

    // Returner mix.js kompatibelt svar
    echo '<!DOCTYPE html>
    <html>
    <head>
        <title>Login Success</title>
    </head>
    <body>
        <mixhtml mix-redirect="/home"></mixhtml>
        <script>
            // Sikkerhedsmæssigt: Luk popup vinduet hvis det er åbent i et separat vindue
            try {
                if (window.opener && !window.opener.closed) {
                    window.close();
                }
            } catch(e) {
                console.log("Could not close popup window:", e);
            }
        </script>
    </body>
    </html>';
    exit();
    
} catch(Exception $e) {
    http_response_code($e->getCode() ?: 400);
    echo '<mixhtml mix-redirect="/?message=' . urlencode($e->getMessage()) . '"></mixhtml>';
    exit();
}