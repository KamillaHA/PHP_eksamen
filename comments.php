<?php
session_start();
require_once __DIR__ . '/_/_header.php';
require_once __DIR__ . '/private/x.php';
$user = $_SESSION["user"];

$message = $_GET['message'] ?? '';

if (!$user) {
    header("Location: /login?message=User not found, please login first");
    exit;
}

?>

<?php if ($message): ?>
    <h1><?php _($message) ?></h1>
<?php endif; ?>

<h1>
    Comments Page
</h1>

<?php
try {
    require_once __DIR__ . '/private/db.php';
    $sql = "SELECT * FROM comments";
    $stmt = $_db->prepare($sql);
    $stmt->execute();
    $comments = $stmt->fetchall();

    foreach ($comments as $comment) {
        require __DIR__ . '/___/___comment.php';
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
<?php require_once __DIR__ . '/_/_footer.php'; ?>