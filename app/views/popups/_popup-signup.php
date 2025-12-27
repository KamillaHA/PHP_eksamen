<?php
// Hvis bruger allerede er logget ind, vis ikke popup
if (isset($_SESSION["user"])) {
    return;
}
?>

<div id="signupModal" class="x-dialog signup-popup">
    <div class="x-dialog__overlay"></div>
    <div class="x-dialog__content">
        <div class="modal-header">
            <button class="modal-close x-dialog__close">&times;</button>
        </div>
        
        <div class="modal-content">
            <div class="popup-logo">
                <svg viewBox="0 0 24 24" width="40" height="40" fill="currentColor">
                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                </svg>
            </div>
            
            <h2>Opret konto på X</h2>
            
            <form class="signup-form" action="/signup" method="POST" mix-post mix-after="main">
                <?php if(isset($_GET['message'])): ?>
                    <div class="error-message">
                        <?php echo htmlspecialchars($_GET['message']); ?>
                    </div>
                <?php endif; ?>
                
                <input type="text" name="user_full_name" placeholder="Fulde navn" required>
                <input type="text" name="user_username" placeholder="Brugernavn" required>
                <input type="email" name="user_email" placeholder="Email" required>
                <input type="password" name="user_password" placeholder="Adgangskode" required>
                
                <div class="terms-notice" style="font-size: 13px; color: #536471; margin: 15px 0;">
                    <p>Ved at tilmelde dig accepterer du <a href="/terms" style="color: #1DA1F2;">Vilkår og Betingelser</a> og <a href="/privacy" style="color: #1DA1F2;">Privatlivspolitik</a>, inklusive <a href="/cookies" style="color: #1DA1F2;">Cookie-brug</a>.</p>
                </div>
                
                <button type="submit" class="signup-submit-btn" 
                        mix-await="Opretter konto..." 
                        mix-default="Tilmeld dig">
                    Tilmeld dig
                </button>
            </form>
            
            <div class="signup-footer">
                <p>Har du allerede en konto? 
                    <a href="#" class="switch-to-login-btn" style="color: #1DA1F2; text-decoration: none; font-weight: bold;">
                        Log ind
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
// Tilføj switching mellem login og signup popups
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

// Switch fra login til signup (tilføj også i popup-login.php)
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