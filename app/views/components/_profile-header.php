<?php
if (!isset($user)) return;
$followers = $followers ?? 0;
$following = $following ?? 0;
?>

<section class="profile-header">
    <div
      class="profile-cover"
      style="<?php if (!empty($user['user_cover_image'])): ?>
        background-image: url('<?= htmlspecialchars($user['user_cover_image']) ?>');
      <?php endif; ?>"
    >
    <form action="/profile/cover" method="POST" enctype="multipart/form-data" class="cover-upload-form">
      <input type="file" name="cover_image" accept="image/*" onchange="this.form.submit()" hidden>
      <button type="button" class="cover-upload-btn" onclick="this.previousElementSibling.click()">Change cover</button>
    </form>
  </div>

  <div class="profile-header-content">
    <div class="profile-avatar avatar-circle">
      <?php echo strtoupper(substr($user['user_username'], 0, 1)); ?>
    </div>

    <div class="profile-actions">
      <button class="profile-edit-btn" data-open="editProfileModal">Edit profile</button>
    </div>

    <div class="profile">
      <h2 class="profile-name"><?php echo htmlspecialchars($user['user_full_name']); ?></h2>
      <p class="profile-handle">@<?php echo htmlspecialchars($user['user_username']); ?></p>

      <p class="profile-joined">
        Joined <?php echo date('F Y', strtotime($user['created_at'])); ?>
      </p>

      <div class="profile-stats">
        <span class="profile-following-count">
          <strong><?php echo $following; ?></strong> Following
        </span>
        <span>
          <strong><?php echo $followers; ?></strong> Followers
        </span>
      </div>
    </div>
  </div>
</section>
