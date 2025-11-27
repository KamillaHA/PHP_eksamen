<?php
session_start();
header('Content-Type: application/json');

try {
    require_once __DIR__ . "/../private/db.php";
    
    if (isset($_GET['user_pk'])) {
        $user_pk = $_GET['user_pk'];
    } else if (isset($_SESSION["user"])) {
        $user_pk = $_SESSION["user"]["user_pk"];
    } else {
        http_response_code(401);
        echo json_encode(["error" => "Not logged in and no user specified"]);
        exit;
    }
    
    $sql = "SELECT 
            user_pk, 
            user_username, 
            user_full_name, 
            user_email
            FROM users 
            WHERE user_pk = :user_pk";
            
    $stmt = $_db->prepare($sql);
    $stmt->bindValue(":user_pk", $user_pk);
    $stmt->execute();
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        http_response_code(404);
        echo json_encode(["error" => "User not found"]);
        exit;
    }
    
    // Hent brugerens posts count
    $sqlPosts = "SELECT COUNT(*) as post_count FROM posts WHERE post_user_fk = :user_pk";
    $stmtPosts = $_db->prepare($sqlPosts);
    $stmtPosts->bindValue(":user_pk", $user_pk);
    $stmtPosts->execute();
    $postCount = $stmtPosts->fetch(PDO::FETCH_ASSOC);
    
    // Hent brugerens kommentarer count
    $sqlComments = "SELECT COUNT(*) as comment_count FROM comments WHERE user_fk = :user_pk";
    $stmtComments = $_db->prepare($sqlComments);
    $stmtComments->bindValue(":user_pk", $user_pk);
    $stmtComments->execute();
    $commentCount = $stmtComments->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode([
        "success" => true,
        "user" => $user,
        "stats" => [
            "post_count" => $postCount['post_count'] ?? 0,
            "comment_count" => $commentCount['comment_count'] ?? 0
        ]
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Server error: " . $e->getMessage()]);
}