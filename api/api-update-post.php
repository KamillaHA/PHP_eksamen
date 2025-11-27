<?php 
session_start();

require_once __DIR__."/../private/x.php";

$user = $_SESSION["user"];

if (!$user) {
    header("Location: /login?message=not logged in, please login first");
    exit;
}

try {
    $post_pk = _validatePk('post_pk');
    $postMessage = _validatePost();

    require_once __DIR__."/../private/db.php";
    
    // Tjek at brugeren ejer posten
    $checkSql = "SELECT post_user_fk FROM posts WHERE post_pk = :post_pk";
    $checkStmt = $_db->prepare($checkSql);
    $checkStmt->bindValue(":post_pk", $post_pk);
    $checkStmt->execute();
    $post = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        throw new Exception("Post not found", 404);
    }

    if ($post['post_user_fk'] != $user["user_pk"]) {
        throw new Exception("You can only update your own posts", 403);
    }

    // Opdater posten
    $sql = "UPDATE posts SET post_message = :post_message WHERE post_pk = :post_pk";
    $stmt = $_db->prepare($sql);
    $stmt->bindValue(":post_pk", $post_pk);
    $stmt->bindValue(":post_message", $postMessage);
    $stmt->execute();

    // Brug toast component
    $message = "Post updated!";
    $toast_update = require_once __DIR__ . "/../___/___toast_update.php";
    
    // Send tilbage til toast container
    echo $toast_update;

}
catch(Exception $e){
    http_response_code($e->getCode() ?: 400);
    echo "<div id='toast'>".$e->getMessage()."</div>";
}
?>