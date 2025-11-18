<?php

try {
    
    require_once __DIR__ . "/../private/db.php";
    
    session_start();

    if (!isset($_SESSION["user"])) {
        http_response_code(401);
        header("Location: /login?message=error");
        exit;
    }

    if (!isset($_POST["post_pk"])) {
        http_response_code(400);
        header("Location: /home?message=Error: Missing post id");
        exit;
    }

    $user_id = $_SESSION["user"]["user_pk"];
    $post_id = $_POST["post_pk"];

    $sql = "DELETE FROM posts WHERE post_pk = :post_pk AND post_user_fk = :user_fk";
    
    $stmt = $_db->prepare($sql);
    $stmt->bindValue(":post_pk", $post_id);
    $stmt->bindValue(":user_fk", $user_id);
    $stmt->execute();

    if ($stmt->rowCount() == 0) {
        http_response_code(200);
        header("Location: /posts?message=Delete failed");
    } else {
        http_response_code(403);
        header("Location: /posts?message=post_deleted");
    }
    exit;

} catch (Exception $e) {
    http_response_code(500);
    echo "error";
}