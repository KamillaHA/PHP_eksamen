<?php

require_once __DIR__ . "/../app/controllers/ProfileController.php";
ProfileController::create();

// session_start();

// require_once __DIR__ . "/../private/x.php";

// try {
//     $userFullName = _validateFullName(); 
//     $username = _validateUsername();
//     $userEmail = _validateEmail();
//     $userPassword = _validatePassword();
//     $hashedPassword = password_hash($userPassword, PASSWORD_DEFAULT);

//     $userPk = bin2hex(random_bytes(25));

//     require_once __DIR__ . "/../private/db.php";
    
//     // Tjek om email eller brugernavn allerede findes
//     $checkSql = "SELECT * FROM users WHERE user_email = :email OR user_username = :username";
//     $checkStmt = $_db->prepare($checkSql);
//     $checkStmt->bindValue(":email", $userEmail);
//     $checkStmt->bindValue(":username", $username);
//     $checkStmt->execute();
    
//     if ($existingUser = $checkStmt->fetch()) {
//         // Returner fejl til popup
//         echo '<mixhtml mix-redirect="/?message=' . urlencode("Email or username already exists") . '"></mixhtml>';
//         exit();
//     }
    
//     // Indsæt ny bruger
//     $sql = "INSERT INTO users (user_pk, user_username, user_full_name, user_email, user_password) 
//             VALUES (:user_pk, :user_username, :user_full_name, :email, :password)";
//     $stmt = $_db->prepare($sql);

//     $stmt->bindValue(":user_pk", $userPk);
//     $stmt->bindValue(":user_username", $username);
//     $stmt->bindValue(":user_full_name", $userFullName);
//     $stmt->bindValue(":email", $userEmail);
//     $stmt->bindValue(":password", $hashedPassword);

//     $stmt->execute();

//     // Auto-login efter signup
//     $newUser = [
//         'user_pk' => $userPk,
//         'user_username' => $username,
//         'user_full_name' => $userFullName,
//         'user_email' => $userEmail
//     ];
    
//     $_SESSION["user"] = $newUser;
    
//     // VIGTIGT: Returner mix.js kompatibelt svar
//     echo '<!DOCTYPE html>
//     <html>
//     <head>
//         <title>Signup Success</title>
//     </head>
//     <body>
//         <mixhtml mix-redirect="/home"></mixhtml>
//         <script>
//             // Luk popup hvis det er åbent i et separat vindue
//             try {
//                 if (window.opener && !window.opener.closed) {
//                     window.close();
//                 }
//             } catch(e) {
//                 console.log("Could not close popup window");
//             }
//         </script>
//     </body>
//     </html>';
//     exit();
    
// } catch (Exception $e) {
//     http_response_code($e->getCode() ?: 400);
//     // Returner fejl med redirect til forsiden (hvor popup er)
//     echo '<mixhtml mix-redirect="/?message=' . urlencode($e->getMessage()) . '"></mixhtml>';
//     exit();
// }