<?php

require_once __DIR__ . "/../app/controllers/CommentController.php";
CommentController::delete();

// session_start();

// require_once __DIR__ . "/../private/x.php";

// $user = $_SESSION["user"];

// if (!$user) {
//     echo '<mixhtml mix-redirect="/login?message=not logged in, please login first"></mixhtml>';
//     exit;
// }

// try {
//     $commentPk = _validatePk("comment_pk");
//     $userFk = $user["user_pk"];

//     require_once __DIR__ . "/../private/db.php";
    
//     // Tjek at kommentaren eksisterer og at brugeren ejer den
//     $checkSql = "SELECT post_fk FROM comments WHERE comment_pk = :commentPk AND user_fk = :userFk";
//     $checkStmt = $_db->prepare($checkSql);
//     $checkStmt->execute([':commentPk' => $commentPk, ':userFk' => $userFk]);
//     $commentData = $checkStmt->fetch(PDO::FETCH_ASSOC);
    
//     if (!$commentData) {
//         throw new Exception("Comment not found or you don't have permission to delete it", 403);
//     }
    
//     // Hent post_pk fra resultatet
//     $postPk = $commentData['post_fk'];

//     // Slet kommentaren
//     $sql = "DELETE FROM comments WHERE comment_pk = :commentPk";
//     $stmt = $_db->prepare($sql);

//     $stmt->bindValue(":commentPk", $commentPk);
//     $stmt->execute();

//     // Brug mix.js redirect til homepage
//     echo '<mixhtml mix-redirect="/home?post=' . htmlspecialchars($postPk) . '"></mixhtml>';
//     exit();
    
// } catch (Exception $e) {
//     http_response_code($e->getCode() ?: 400);
//     echo $e->getMessage();
// }
// ?>