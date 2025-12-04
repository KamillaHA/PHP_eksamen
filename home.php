<?php
session_start();
require_once __DIR__."/./components/_header.php";
require_once __DIR__."/./private/x.php";
require_once __DIR__ . "/./private/db.php";

$user = $_SESSION["user"];
$current_user_id = $_SESSION['user']['user_pk'] ?? null;
$message = $_GET['message'] ?? '';

if (!$user) {
    header("Location: /login?message=User not found, please login first");
    exit;
}

try {
    $sql = "SELECT posts.*, users.user_username FROM posts INNER JOIN users ON posts.post_user_fk = users.user_pk";
    $stmt = $_db->prepare($sql);
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} 

catch (Exception $e) {
    echo "<div>Error: Could not fetch posts.</div>";
    $posts = [];
}
?>

<main id="main">

    <h1>All Posts</h1>
    <div id="toast"></div>
    
    <?php
    // Inkluder posts komponentet
    require_once __DIR__."/micro_components/___post.php";
    require_once __DIR__."/components/_sidebar.php";
    ?>
</main>

<?php
// Inkluder post modal komponent
require_once __DIR__."/popup/_popup-create-post.php";
require_once __DIR__."/popup/_popup-update-post.php";
require_once __DIR__."/popup/_popup-create-comment.php";
require_once __DIR__."/popup/_popup-update-comment.php";
require_once __DIR__."/components/_footer.php";
?>

