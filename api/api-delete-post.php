<?php
session_start();

require_once __DIR__ . "/../private/x.php";

$user = $_SESSION["user"];

if (!$user) {
    echo '<mixhtml mix-redirect="/login?message=not logged in, please login first"></mixhtml>';
    exit;
}

try {
    $postPk = _validatePk("post_pk");
    $userPk = $user["user_pk"];

    require_once __DIR__ . "/../private/db.php";
    
    // Tjek at posten eksisterer og at brugeren ejer den
    $checkSql = "SELECT * FROM posts WHERE post_pk = :postPk AND post_user_fk = :userPk";
    $checkStmt = $_db->prepare($checkSql);
    $checkStmt->execute([':postPk' => $postPk, ':userPk' => $userPk]);
    
    $post = $checkStmt->fetch();
    
    if (!$post) {
        echo '<mixhtml mix-redirect="/?message=Post not found or you dont have permission to delete it"></mixhtml>';
        exit;
    }
    
    // Slet posten (cascade vil også slette kommentarer)
    $sql = "DELETE FROM posts WHERE post_pk = :postPk";
    $stmt = $_db->prepare($sql);
    $stmt->execute([':postPk' => $postPk]);

    // Brug mix.js redirect
    echo '<mixhtml mix-redirect="/home"></mixhtml>';
    exit();
    
} catch (Exception $e) {
    echo '<mixhtml mix-redirect="/?message=' . urlencode("Error: " . $e->getMessage()) . '"></mixhtml>';
    exit();
}
?>