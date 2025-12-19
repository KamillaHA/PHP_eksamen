<?php

require_once __DIR__ . "/../app/controllers/ProfileController.php";
ProfileController::delete();
try {
    
    require_once __DIR__ . "/../private/db.php";
        
    if (!isset($_SESSION["user"])) {
        http_response_code(401);
        header("Location: /login?message=error");
        exit;
    }
    
    $user_id = $_SESSION["user"]["user_pk"];
    session_destroy();
}
// try {
    
//     require_once __DIR__ . "/../private/db.php";
    
//     session_start();
    
//     if (!isset($_SESSION["user"])) {
//         http_response_code(401);
//         header("Location: /login?message=error");
//         exit;
//     }
    
//     session_destroy();
//     $user_id = $_SESSION["user"]["user_pk"];

//     $sql = "DELETE FROM users WHERE user_pk = :user_pk";
//     $stmt = $_db->prepare($sql);
//     $stmt->bindValue(":user_pk", $user_id);
//     $stmt->execute();

//     header("Location: /login?message=profile deleted");

// } catch (Exception $e) {
//     http_response_code(500);
//     echo "error";
// }
