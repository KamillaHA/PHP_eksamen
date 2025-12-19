<?php
session_start();
require_once __DIR__ . '/components/_header.php';
require_once __DIR__ . '/../../private/x.php';
require_once __DIR__ . '/../../private/db.php';

$user = $_SESSION["user"];

$message = $_GET['message'] ?? '';

if (!$user) {
    header("Location: /login?message=User not found, please login first");
    exit;
}

// Hent brugerens egne posts fra databasen
try {
    $sql = "SELECT * FROM posts WHERE post_user_fk = :user_id ORDER BY post_pk DESC";
    $stmt = $_db->prepare($sql);
    $stmt->bindParam(':user_id', $user['user_pk']);
    $stmt->execute();
    $userPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $userPosts = [];
    $error = "Kunne ikke hente posts: " . $e->getMessage();
}

?>

<main>
    <?php if($message): ?>
        <div class="message">
            <?php echo htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>


 <div class="profile-container">
        <!-- Profile Information Section -->
        <section class="profile-info">
            <h2>Your Profile Details</h2>
            <div class="profile-details">
                <p><strong>Email:</strong> <?php _($user['user_email']) ?></p>
                <p><strong>Username:</strong> <?php _($user['user_username']) ?></p>
                <p><strong>Full Name:</strong> <?php _($user['user_full_name']) ?></p>
            </div>
        </section>

        <!-- Update Profile Form -->
        <section class="update-profile">
            <h3>Update Profile</h3>
            <form action="/api/api-update-profile.php" method="POST" class="profile-form">
                <div class="form-group">
                    <label for="user_email">Email:</label>
                    <input type="email" id="user_email" name="user_email" value="<?php _($user['user_email']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="user_username">Username:</label>
                    <input type="text" id="user_username" name="user_username" value="<?php _($user['user_username']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="user_full_name">Full Name:</label>
                    <input type="text" id="user_full_name" name="user_full_name" value="<?php _($user['user_full_name']) ?>" required>
                </div>
                
                <button type="button" class="edit-profile-btn" data-open="editProfileModal">Update Profile</button>
            </form>
        </section>

        <!-- User's Posts Section -->
        <section class="user-posts">
            <h2>Your Posts</h2>
            
            <?php if (!empty($userPosts)): ?>
                <div class="posts-list">
                    <?php foreach ($userPosts as $post): ?>
                        <div class="post-card">
                            <div class="post-header">
                                <div class="post-user">
                                    <strong><?php echo htmlspecialchars($post['username'] ?? $user['user_username']); ?></strong>
                                    <span class="post-time">
                                        <?php 
                                        $date = new DateTime($post['created_at']);
                                        echo $date->format('d/m/Y H:i');
                                        ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="post-content">
                                <p><?php echo htmlspecialchars($post['post_message']); ?></p>
                            </div>
                            
                            <?php if (!empty($post['post_image_path'])): ?>
                                <div class="post-image">
                                    <img src="<?php echo htmlspecialchars($post['post_image_path']); ?>" alt="Post image">
                                </div>
                            <?php endif; ?>
                            
                            <div class="post-stats">
                                <span class="likes">❤️ <?php echo $post['likes_count'] ?? 0; ?></span>
                                <span class="comments">💬 <?php echo $post['comments_count'] ?? 0; ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-posts">
                    <p>You haven't posted anything yet.</p>
                </div>
            <?php endif; ?>
        </section>
    </div>
    <?php
    require_once __DIR__ . '/components/_sidebar.php';
    ?>

</main>

<?php 
// Tilføj profil-popup (vi skal oprette denne fil)
require_once __DIR__."/popups/_popup-update-profile.php";
require_once __DIR__."/popups/_popup-create-post.php";
require_once __DIR__ . '/components/_footer.php'; 
?>