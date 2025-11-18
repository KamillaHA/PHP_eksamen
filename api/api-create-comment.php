<?php
session_start();

require_once __DIR__ . "/../private/x.php";

$user = $_SESSION["user"];

if (!$user) {
    header("Location: /login?message=not logged in, please login first");
    exit;
}

try {

    $commentPk = bin2hex(random_bytes(25));
    $userPk = $user["user_pk"];
    $postPk = _validatePk("post_pk");
    $commentText = validateCommentText();

    require_once __DIR__ . "/../private/db.php";
    $sql = "INSERT INTO comments (comment_pk, user_fk, post_fk, comment_text) Values (:commentPk, :userFk, :postFk, :commentText)";

    $stmt = $_db->prepare($sql);

    $stmt->bindValue(":commentPk", $commentPk);
    $stmt->bindValue(":userFk", $userPk);
    $stmt->bindValue(":postFk", $postPk);
    $stmt->bindValue(":commentText", $commentText);

    $stmt->execute();

    header("Location: /comments?message=" . urlencode("Comment created!"));
    exit();
} catch (Exception $e) {
    http_response_code($e->getCode());
    echo $e->getMessage();
}
