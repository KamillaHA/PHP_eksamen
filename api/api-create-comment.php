<?php
session_start();

require_once __DIR__ . "/../private/x.php";

$user = $_SESSION["user"];

if (!$user) {
    echo '<mixhtml mix-redirect="/login?message=not logged in, please login first"></mixhtml>';
    exit;
}

try {
    $commentPk = bin2hex(random_bytes(25));
    $userPk = $user["user_pk"];
    $postPk = _validatePk("post_pk");
    $commentText = validateCommentText();

    require_once __DIR__ . "/../private/db.php";
    
    // Tjek om posten eksisterer
    $postCheck = $stmt = $_db->prepare("SELECT post_pk FROM posts WHERE post_pk = ?");
    $postCheck->execute([$postPk]);
    if (!$postCheck->fetch()) {
        throw new Exception("Post not found", 404);
    }
    
    // Indsæt kommentar - tilføj created_at hvis din tabel har det
    $sql = "INSERT INTO comments (comment_pk, user_fk, post_fk, comment_text, created_at) 
            VALUES (:commentPk, :userFk, :postFk, :commentText, NOW())";

    $stmt = $_db->prepare($sql);

    $stmt->bindValue(":commentPk", $commentPk);
    $stmt->bindValue(":userFk", $userPk);
    $stmt->bindValue(":postFk", $postPk);
    $stmt->bindValue(":commentText", $commentText);

    $stmt->execute();

    // Redirect tilbage til SAMME POST (single view)
    echo '<mixhtml mix-redirect="/home?post=' . htmlspecialchars($postPk) . '"></mixhtml>';
    exit;
    
} catch (Exception $e) {
    http_response_code($e->getCode() ?: 400);
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Error</title>
    </head>
    <body>
        <div id='toast'>".htmlspecialchars($e->getMessage())."</div>
    </body>
    </html>";
}
?>