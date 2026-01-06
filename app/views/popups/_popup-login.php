<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION["user"])) {
    return;
}
?>

<div id="loginModal" class="dialog login-popup">
    <div class="dialog-overlay"></div>
    <div class="dialog-content">
        <div class="modal-header">
            <button class="modal-close dialog-close">&times;</button>
        </div>
        
        <div class="modal-content">
            <div class="popup-logo">
                <svg viewBox="0 0 24 24" width="40" height="40" fill="currentColor">
                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                </svg>
            </div>
            
            <h2 class="popup-header">Log in to X</h2>
            
            <form class="login-form" action="/login" method="POST">
                <?php if (isset($_GET['message'])): ?>
                <div class="error-message">
                    <?= htmlspecialchars($_GET['message']) ?>
                </div>
                <?php endif; ?>
                
                <input name="user_email" type="email" placeholder="Email" required>
                <input name="user_password" type="password" placeholder="Password" required>
                
                <button type="submit" class="login-submit-btn" 
                        mix-await="Logger ind..." 
                        mix-default="Log ind">
                    Log in
                </button>
            </form>
            
            <div class="login-footer">
                <p>Don´t have an account? 
                    <a href="/signup" style="color: #1DA1F2; text-decoration: none; font-weight: bold;">Join us</a>
                </p>
            </div>
        </div>
    </div>
</div>