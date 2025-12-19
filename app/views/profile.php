<?php
session_start();
require_once __DIR__ . '/components/_header.php';
require_once __DIR__ . '/../../private/x.php';
require_once __DIR__ . '/../../private/db.php';

$user = $_SESSION["user"];
$current_user_id = $_SESSION['user']['user_pk'] ?? null;

// $message = $_GET['message'] ?? '';

if (!$user) {
    header("Location: /login?message=User not found, please login first");
    exit;
}

// Hent post_id fra URL
$single_post_id = $_GET['post'] ?? null;

try {
    $sql = "SELECT posts.*, users.user_username, users.user_full_name FROM posts INNER JOIN users ON posts.post_user_fk = users.user_pk WHERE posts.post_user_fk = :user_pk ORDER BY posts.created_at DESC";
    $stmt = $_db->prepare($sql);
    $stmt->execute([':user_pk' => $current_user_id]);    
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} 

catch (Exception $e) {
    echo "<div>Error: Could not fetch posts.</div>";
    $posts = [];
}
?>

<main id="main">

    <!-- <h1 class="h1">All Posts</h1> -->

    <div id="toast"></div>
    
    <?php if (empty($posts)): ?>
    <?php endif; ?>

    <?php
    // Inkluder posts komponentet
    require_once __DIR__."/components/_profile-header.php";
    require_once __DIR__."/components/___post.php";
    require_once __DIR__."/components/_sidebar.php";
    ?>
</main>

<?php
// Inkluder post modal komponent
require_once __DIR__."/popups/_popup-create-post.php";
require_once __DIR__."/popups/_popup-update-post.php";
require_once __DIR__."/popups/_popup-create-comment.php";
require_once __DIR__."/popups/_popup-update-comment.php";
require_once __DIR__."/popups/_popup-update-profile.php";
require_once __DIR__."/popups/_popup-confirm-delete-profile.php";
require_once __DIR__."/components/_footer.php";
?>