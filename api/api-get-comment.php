<?php

require_once __DIR__ . "/../app/controllers/CommentController.php";
CommentController::get();

// session_start();
// header('Content-Type: application/json');

// try {
//     require_once __DIR__ . "/../private/db.php";
    
//     if (isset($_GET['comment_pk'])) {
//         $comment_pk = $_GET['comment_pk'];
        
//         $sql = "SELECT 
//                 c.comment_pk,
//                 c.comment_text,
//                 u.user_username,
//                 u.user_full_name
//                 FROM comments c
//                 JOIN users u ON c.user_fk = u.user_pk
//                 WHERE c.comment_pk = :comment_pk";
                
//         $stmt = $_db->prepare($sql);
//         $stmt->bindValue(":comment_pk", $comment_pk);
//         $stmt->execute();
        
//         $comment = $stmt->fetch(PDO::FETCH_ASSOC);
        
//         if (!$comment) {
//             http_response_code(404);
//             echo json_encode(["error" => "Comment not found"]);
//             exit;
//         }
        
//         echo json_encode([
//             "success" => true,
//             "comment" => $comment
//         ]);
        
//     } else if (isset($_GET['post_pk'])) {
//         $post_pk = $_GET['post_pk'];
        
//         $sql = "SELECT 
//                 c.comment_pk,
//                 c.comment_text,
//                 u.user_username,
//                 u.user_full_name
//                 FROM comments c
//                 JOIN users u ON c.user_fk = u.user_pk
//                 WHERE c.post_fk = :post_pk
//                 ORDER BY c.comment_pk DESC";
                
//         $stmt = $_db->prepare($sql);
//         $stmt->bindValue(":post_pk", $post_pk);
//         $stmt->execute();
        
//         $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
//         echo json_encode([
//             "success" => true,
//             "comments" => $comments,
//             "count" => count($comments)
//         ]);
        
//     } else {
//         http_response_code(400);
//         echo json_encode(["error" => "Comment ID or Post ID required"]);
//     }
    
// } catch (Exception $e) {
//     http_response_code(500);
//     echo json_encode(["error" => "Server error: " . $e->getMessage()]);
// }