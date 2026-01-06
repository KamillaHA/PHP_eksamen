<?php

// Afbryd hvis der ikke er en bruger
if (!isset($user)) return;

// Fallback-værdier til follow-stats
$followers = $followers ?? 0;
$following = $following ?? 0;
?>

<section class="profile-header">

  <!-- Cover-billede (vises kun hvis der findes et) -->
  <div
    class="profile-cover"
    style="<?php if (!empty($user['user_cover_image'])): ?>
      background-image: url('<?= htmlspecialchars($user['user_cover_image']) ?>');
    <?php endif; ?>"
  >

  <!-- Upload af nyt cover-billede -->
    <form action="/profile/cover" method="POST" enctype="multipart/form-data" class="cover-upload-form">

      <!-- Skjult file-input som trigges af knap -->
      <input type="file" name="cover_image" accept="image/*" onchange="this.form.submit()" hidden>

      <!-- Knap der åbner file-input -->
      <button type="button" class="cover-upload-btn" onclick="this.previousElementSibling.click()"></button>
    </form>
  </div>

  <div class="profile-header-content">

    <!-- Avatar med første bogstav i brugernavn -->
    <div class="profile-avatar avatar-circle">
      <?php echo strtoupper(substr($user['user_username'], 0, 1)); ?>
    </div>

    <!-- Profil-handlinger -->
    <div class="profile-actions">

      <!-- Åbner popup til redigering af profil -->
      <button class="profile-edit-btn" data-open="editProfileModal">Edit profile</button>
    </div>

    <div class="profile">

      <!-- Brugerens fulde navn -->
      <h2 class="profile-name"><?php echo htmlspecialchars($user['user_full_name']); ?></h2>

      <!-- Brugernavn -->
      <p class="profile-handle">@<?php echo htmlspecialchars($user['user_username']); ?></p>

      <!-- Oprettelsesdato -->
      <p class="profile-joined">
        Joined <?php echo date('F Y', strtotime($user['created_at'])); ?>
      </p>

      <!-- Følger-statistik -->
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
