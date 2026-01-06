<?php

if (!isset($_SESSION["user"])) {
    return;
}
$user = $_SESSION["user"];
?>

<!-- Update Profile Popup Modal -->
<div id="editProfileModal" class="dialog profile-edit-modal">
    <div class="dialog-overlay"></div>
    <div class="dialog-content">
        <div class="modal-header">
            <h3 class="update-header">Update Profile</h3>
            <button class="modal-close dialog-close">&times;</button>
        </div>
        <div class="modal-content">
            <form class="edit-profile-form" action="/profile/update" method="POST">
                <!-- Email Field -->
                <div class="form-group">
                    <label for="user_email">Email</label>
                    <input 
                        type="email" 
                        name="user_email" 
                        id="user_email"
                        value="<?php echo htmlspecialchars($user['user_email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" 
                        required
                        mix-check="^[^\s@]+@[^\s@]+\.[^\s@]+$"
                        mix-feedback="Please enter a valid email"
                    >
                </div>
                
                <!-- Username Field -->
                <div class="form-group">
                    <label for="user_username">Username</label>
                    <input 
                        type="text" 
                        name="user_username" 
                        id="user_username"
                        value="<?php echo htmlspecialchars($user['user_username'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" 
                        required
                        mix-check="^[a-zA-Z0-9_]{3,20}$"
                        mix-feedback="Username must be 3-20 characters (letters, numbers, underscore)"
                    >
                </div>
                
                <!-- Full Name Field -->
                <div class="form-group">
                    <label for="user_full_name">Full Name</label>
                    <input 
                        type="text" 
                        name="user_full_name" 
                        id="user_full_name"
                        value="<?php echo htmlspecialchars($user['user_full_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" 
                        required
                        mix-check="^.{2,50}$"
                        mix-feedback="Full name must be 2-50 characters"
                    >
                </div>
                
                <div class="profile-btns">
                <!-- Opdatér profil knap -->
                <div class="form-actions">
                    <button 
                    type="submit" 
                    class="update-profile-btn" 
                    mix-await="Updating..." 
                    mix-default="Update Profile"
                    >
                    Update Profile
                </button>
                </div>
            
                <!-- Slet profil knap -->
                <div class="form-actions danger-zone">
                    <button 
                        type="button"
                        class="delete-profile-btn"
                        onclick="document.getElementById('confirmDeleteProfileModal').classList.add('active')"

                    >
                        Delete profile
                    </button>
                </div>
                </div>

            </form>
        </div>
    </div>
</div>