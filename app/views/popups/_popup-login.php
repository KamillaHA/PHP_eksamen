<?php

// Sørger for at PHP-sessionen er startet
// session_start() må kun kaldes, hvis der ikke allerede findes en session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Tjekker om brugeren allerede er logget ind
// Hvis der findes en bruger i sessionen,
// vises login-modal’en ikke
if (isset($_SESSION["user"])) {
    return;
}
?>

<!-- Modal til login -->
<div id="loginModal" class="dialog login-popup">

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

            <!-- Logo / ikon i toppen af popup -->
            <div class="popup-logo">
                <svg viewBox="0 0 24 24" width="40" height="40" fill="currentColor">
                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                </svg>
            </div>
            
            <!-- Overskrift -->
            <h2 class="popup-header">Log in to X</h2>
            
            <!-- Login-formular -->
            <!-- Sender en POST-request til /login -->
            <form class="login-form" action="/login" method="POST">
                <?php if (isset($_GET['message'])): ?>
                <div class="error-message">
                    <?= htmlspecialchars($_GET['message']) ?>
                </div>
                <?php endif; ?>
                
                <!-- Inputfelt til email -->
                <input name="user_email" type="email" placeholder="Email" required>

                <!-- Inputfelt til adgangskode -->
                <input name="user_password" type="password" placeholder="Password" required>

                <!-- CSRF-token til beskyttelse mod Cross-Site Request Forgery -->
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                
                <!-- Submit-knap til login -->
                <!-- mix-await og mix-default håndterer loading-tekst -->
                <button type="submit" class="login-submit-btn" 
                        mix-await="Logger ind..." 
                        mix-default="Log ind">
                    Log in
                </button>
            </form>
            
            <!-- Footer med link til oprettelse af konto -->
            <div class="login-footer">
                <p>Don´t have an account? 
                    <a href="/signup" style="color: #1DA1F2; text-decoration: none; font-weight: bold;">Join us</a>
                </p>
            </div>
        </div>
    </div>
</div>