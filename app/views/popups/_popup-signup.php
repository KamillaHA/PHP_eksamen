<?php
// Hvis brugeren allerede er logget ind,
// skal signup-popup’en ikke vises
if (isset($_SESSION["user"])) {
    return;
}
?>

<!-- Modal til oprettelse af ny bruger -->
<div id="signupModal" class="dialog signup-popup">

    <!-- Overlay der mørklægger baggrunden -->
    <div class="dialog-overlay"></div>

    <!-- Selve modal-indholdet -->
    <div class="dialog-content">

        <!-- Header med luk-knap -->
        <div class="modal-header">

            <!-- Lukker modal’en via JavaScript -->
            <button class="modal-close dialog-close">&times;</button>
        </div>
        
        <!-- Modalens indhold -->
        <div class="modal-content">

            <!-- Logo / ikon i toppen -->
            <div class="popup-logo">
                <svg viewBox="0 0 24 24" width="40" height="40" fill="currentColor">
                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                </svg>
            </div>
            
            <!-- Overskrift -->
            <h2 class="popup-header">Join us on X</h2>
            
            <!-- Signup-formular -->
            <!-- Sender en POST-request til /signup -->
            <form class="signup-form" action="/signup" method="POST">

                <!-- Viser fejlbesked hvis der findes en message i URL’en -->
                <?php if(isset($_GET['message'])): ?>
                    <div class="error-message">
                        <?php 
                        // htmlspecialchars beskytter mod XSS
                        echo htmlspecialchars($_GET['message']); ?>
                    </div>
                <?php endif; ?>
                
                <!-- Inputfelter til brugeroprettelse -->
                <input type="text" name="user_full_name" placeholder="Full name" required>
                <input type="text" name="user_username" placeholder="Username" required>
                <input type="email" name="user_email" placeholder="Email" required>
                <input type="password" name="user_password" placeholder="Password" required>
                
                <!-- Information om vilkår -->
                <div class="terms-notice" style="font-size: 13px; color: #536471; margin: 15px 0;">
                    <p>By joining X you accept <a href="/terms" style="color: #1DA1F2;">Terms and Conditions</a> and <a href="/privacy" style="color: #1DA1F2;">Privacypolicy</a>, including <a href="/cookies" style="color: #1DA1F2;">Cookie-use</a>.</p>
                </div>
                
                <!-- Submit-knap til oprettelse af konto -->
                <!-- mix-await og mix-default håndterer loading-tekst -->
                <button type="submit" class="signup-submit-btn" 
                        mix-await="Opretter konto..." 
                        mix-default="Join us">
                    Join us
                </button>
            </form>
            
            <!-- Footer med link til login -->
            <div class="signup-footer">
                <p>Already have an account? 
                    <a href="#" class="switch-to-login-btn" style="color: #1DA1F2; text-decoration: none; font-weight: bold;">
                        Log in
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
// Skifter fra signup-popup til login-popup
document.querySelectorAll('.switch-to-login-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Luk signup popup
        const signupModal = document.getElementById('signupModal');
        if (signupModal) {
            signupModal.classList.remove('active');
        }
        
        // Åbn login popup
        const loginModal = document.getElementById('loginModal');
        if (loginModal) {
            loginModal.classList.add('active');
        }
    });
});

// Skifter fra login-popup til signup-popup
// Kører først når DOM er færdigindlæst
document.addEventListener('DOMContentLoaded', function() {
    const switchToSignupBtn = document.querySelector('.login-footer a[href="/signup"]');
    if (switchToSignupBtn) {
        switchToSignupBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Luk login popup
            const loginModal = document.getElementById('loginModal');
            if (loginModal) {
                loginModal.classList.remove('active');
            }
            
            // Åbn signup popup
            const signupModal = document.getElementById('signupModal');
            if (signupModal) {
                signupModal.classList.add('active');
            }
        });
    }
});
</script>