<?php
// landing.php
// Ingen session_start her – det håndteres i index.php
?>

<div class="x-landing">
  <div class="x-landing__left">
      <div class="x-landing__logo" aria-hidden="true">
        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" role="img" aria-labelledby="xLogoTitle">
          <title id="xLogoTitle">X (Twitter) logo</title>
          <path fill="currentColor" d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
        </svg>
      </div>
  </div>

  <div class="x-landing__right">
    <h1 class="x-landing__title">Happening now</h1>
    <h2 class="x-landing__subtitle">Join today</h2>

    <div class="x-landing__buttons">

      <!-- Signup -->
      <button 
        class="x-landing__btn x-landing__btn--signup" 
        data-open="signupModal"
      >
        Create an account
      </button>
      
      <!-- Login -->
      <div class="x-landing__login">
        <!-- <p class="x-landing__login-text">Already have an account?</p> -->
        <button 
          class="x-landing__btn x-landing__btn--login" 
          data-open="loginModal"
        >
          Log in
        </button>
      </div>

    </div>
  </div>
</div>

<?php
// Popups til landing page
require_once __DIR__ . "/popups/_popup-login.php";
require_once __DIR__ . "/popups/_popup-signup.php";
?>
