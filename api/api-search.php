<?php
session_start();
header('Content-Type: application/json');

try {
    require_once __DIR__ . "/../private/db.php";
    
    if (!isset($_GET['q']) || empty(trim($_GET['q']))) {
        http_response_code(400);
        echo json_encode(["error" => "Search query required"]);
        exit;
    }
    
    $searchTerm = '%' . $_GET['q'] . '%';
    
    // Søg i brugere
    $sqlUsers = "SELECT 
                user_pk, 
                user_username, 
                user_full_name 
                FROM users 
                WHERE user_username LIKE :search 
                OR user_full_name LIKE :search
                LIMIT 10";
                
    $stmtUsers = $_db->prepare($sqlUsers);
    $stmtUsers->bindValue(":search", $searchTerm);
    $stmtUsers->execute();
    $users = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);
    
    // Søg i posts
    $sqlPosts = "SELECT 
                p.post_pk,
                p.post_message,
                u.user_username,
                u.user_full_name
                FROM posts p
                JOIN users u ON p.post_user_fk = u.user_pk
                WHERE p.post_message LIKE :search
                LIMIT 10";
                
    $stmtPosts = $_db->prepare($sqlPosts);
    $stmtPosts->bindValue(":search", $searchTerm);
    $stmtPosts->execute();
    $posts = $stmtPosts->fetchAll(PDO::FETCH_ASSOC);
    
    // Søg i kommentarer
    $sqlComments = "SELECT 
                    c.comment_pk,
                    c.comment_text,
                    u.user_username,
                    u.user_full_name,
                    c.post_fk
                    FROM comments c
                    JOIN users u ON c.user_fk = u.user_pk
                    WHERE c.comment_text LIKE :search
                    LIMIT 10";
                    
    $stmtComments = $_db->prepare($sqlComments);
    $stmtComments->bindValue(":search", $searchTerm);
    $stmtComments->execute();
    $comments = $stmtComments->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        "success" => true,
        "search_term" => $_GET['q'],
        "results" => [
            "users" => $users,
            "posts" => $posts,
            "comments" => $comments
        ],
        "counts" => [
            "users" => count($users),
            "posts" => count($posts),
            "comments" => count($comments)
        ]
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Server error: " . $e->getMessage()]);
}