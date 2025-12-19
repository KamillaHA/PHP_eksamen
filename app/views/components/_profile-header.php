<?php
if (!isset($_SESSION['user'])) return;

$user = $_SESSION['user'];

require_once __DIR__ . '/../private/db.php';

// Followers count
$stmt = $_db->prepare("SELECT COUNT(*) FROM follows WHERE following_fk = ?");
$stmt->execute([$user['user_pk']]);
$followers = $stmt->fetchColumn();

// Following count
$stmt = $_db->prepare("SELECT COUNT(*) FROM follows WHERE follower_fk = ?");
$stmt->execute([$user['user_pk']]);
$following = $stmt->fetchColumn();
?>

<section class="profile-header">

    <!-- Cover -->
    <div class="profile-cover">
        <form 
            action="/api/api-update-cover.php"
            method="POST"
            enctype="multipart/form-data"
            class="cover-upload-form"
        >
            <input 
                type="file"
                name="cover_image"
                accept="image/*"
                onchange="this.form.submit()"
                hidden
            >

            <button 
                type="button" 
                class="cover-upload-btn"
                onclick="this.previousElementSibling.click()"
            >
                Change cover
            </button>
        </form>
    </div>





    <!-- Main info -->
    <div class="profile-header-content">

        <!-- Avatar -->
        <div class="profile-avatar avatar-circle">
            <?php echo strtoupper(substr($user['user_username'], 0, 1)); ?>
        </div>

        <!-- Edit button -->
        <div class="profile-actions">
            <button class="profile-edit-btn" data-open="editProfileModal">Edit profile</button>
        </div>

        <!-- User info -->
        <div class="profile">
            <h2 class="profile-name"><?php echo htmlspecialchars($user['user_full_name']); ?></h2>
            <p class="profile-handle">@<?php echo htmlspecialchars($user['user_username']); ?></p>

            <p class="profile-joined">
                Joined <?php echo date('F Y', strtotime($user['created_at'])); ?>
            </p>

            <div class="profile-stats">
                <span><strong><?php echo $following; ?></strong> Following</span>
                <span><strong><?php echo $followers; ?></strong> Followers</span>
            </div>
        </div>

    </div>
</section>