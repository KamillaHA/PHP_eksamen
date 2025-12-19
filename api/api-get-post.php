<?php

require_once __DIR__ . "/../app/controllers/PostController.php";
PostController::get();

// session_start();
// require_once __DIR__."/../private/x.php";
// require_once __DIR__."/../private/db.php";

// try {
//     // Valider at vi får en post_pk parameter
//     $post_pk = _validatePk('post_pk');
    
//     // Hent post fra databasen
//     $sql = "SELECT posts.*, users.user_username 
//             FROM posts 
//             INNER JOIN users ON posts.post_user_fk = users.user_pk 
//             WHERE posts.post_pk = :post_pk";
    
//     $stmt = $_db->prepare($sql);
//     $stmt->bindValue(":post_pk", $post_pk);
//     $stmt->execute();
    
//     $post = $stmt->fetch(PDO::FETCH_ASSOC);
    
//     if (!$post) {
//         throw new Exception("Post not found", 404);
//     }
    
//     // Returner post som JSON
//     header('Content-Type: application/json');
//     echo json_encode($post);
    
// } catch(Exception $e) {
//     http_response_code($e->getCode() ?: 400);
//     echo json_encode(["error" => $e->getMessage()]);
// }