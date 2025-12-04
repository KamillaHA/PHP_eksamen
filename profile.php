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

<main>
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

<!-- Ændret: Knap til at åbne popup i stedet for direkte form -->
<button 
    type="button" 
    class="edit-profile-btn"
    data-open="editProfileModal"
>
    Update Profile
</button>

<a href="/logout">
    logout
</a>
</main>

<?php 
// Tilføj profil-popup (vi skal oprette denne fil)
require_once __DIR__."/popup/_popup-update-profile.php";
require_once __DIR__."/popup/_popup-create-post.php";
require_once __DIR__ . '/_/_footer.php'; 
?>