<?php

// Tjekker om brugeren er logget ind
// Hvis der ikke findes en bruger i sessionen,
// vises profil-redigerings-modal’en ikke
if (!isset($_SESSION["user"])) {
    return;
}
// Gemmer den loggede bruger i en variabel
// så vi nemt kan bruge brugerdata i formularen
$user = $_SESSION["user"];
?>

<!-- Modal til opdatering af brugerprofil -->
<div id="editProfileModal" class="dialog profile-edit-modal">

    <!-- Overlay der mørklægger baggrunden -->
    <div class="dialog-overlay"></div>

    <!-- Selve modal-indholdet -->
    <div class="dialog-content">

        <!-- Header med titel og luk-knap -->
        <div class="modal-header">
            <h3 class="update-header">Update Profile</h3>

            <!-- Lukker modal’en via JavaScript -->
            <button class="modal-close dialog-close">&times;</button>
        </div>

        <!-- Modalens indhold -->
        <div class="modal-content">

            <!-- Formular til opdatering af profil -->
            <!-- Sender en POST-request til /profile/update -->
            <form class="edit-profile-form" action="/profile/update" method="POST">

                <!-- Email-felt -->
                <div class="form-group">
                    <label for="user_email">Email</label>

                    <!-- Email udfyldes med nuværende værdi -->
                    <!-- htmlspecialchars beskytter mod XSS -->
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
                
                <!-- Brugernavn-felt -->
                <div class="form-group">
                    <label for="user_username">Username</label>

                    <!-- Validerer længde og tilladte tegn -->
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
                
                <!-- Fuldt navn-felt -->
                <div class="form-group">
                    <label for="user_full_name">Full Name</label>

                    <!-- Validerer længde på navn -->
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
                
                <!-- Knapper til profilhandlinger -->
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
                <!-- Åbner bekræftelses-modal -->
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