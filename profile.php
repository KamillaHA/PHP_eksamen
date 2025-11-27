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

<?php if($message): ?>
    <h1><?php echo htmlspecialchars($message) ?></h1>
<?php endif; ?>

<section>
    <h2>
        Your profile details:
    </h2>
    <p>
        Email: <?php _($user['user_email']) ?>
    </p>
    <p>
        Username: <?php _($user['user_username']) ?>
    </p>
    <p>
        Full Name: <?php _($user['user_full_name']) ?>
    </p>
</section>

<form action="api/api-update-profile.php" method="POST">
    <h3>
        Update Profile
    </h3>
    <input type="email" name="user_email" value="<?php _($user['user_email']) ?>" required>
    <input type="text" name="user_username" value="<?php _($user['user_username']) ?>" required>
    <input type="text" name="user_full_name" value="<?php _($user['user_full_name']) ?>" required>
    <button type="submit">
        Update Profile
    </button>
</form>

<a href="/logout">
    logout
</a>

<?php require_once __DIR__ . '/_/_footer.php'; ?>