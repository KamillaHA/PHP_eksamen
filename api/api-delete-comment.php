<?php
session_start();

require_once __DIR__ . "/../private/x.php";

$user = $_SESSION["user"];

if (!$user) {
    header("Location: /login?message=not logged in, please login first");
    exit;
}

try {

    $commentPk = _validatePk("comment_pk");
    $userFk = $user["user_pk"];

    require_once __DIR__ . "/../private/db.php";
    $sql = "DELETE FROM comments WHERE comment_pk = :commentPk AND user_fk = :userFk";

    $stmt = $_db->prepare($sql);

    $stmt->bindValue(":commentPk", $commentPk);
    $stmt->bindValue(":userFk", $userFk);

    $stmt->execute();

    header("Location: /comments?message=" . urlencode("Comment deleted!"));
    exit();
} catch (Exception $e) {
    http_response_code($e->getCode());
    echo $e->getMessage();
}
