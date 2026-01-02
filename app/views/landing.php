<?php
// landing.php
// Ingen session_start her – det håndteres i index.php
?>

<div class="x-landing">
  <div class="x-landing__left">
      <div class="x-landing__logo" aria-hidden="true">
        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" role="img" aria-labelledby="xLogoTitle">
          <title id="xLogoTitle">X (Twitter) logo</title>
          <path fill="currentColor" d="M 21.742 21.75 l -7.563 -11.179 l 7.056 -8.321 h -2.456 l -5.691 6.714 l -4.54 -6.714 H 2.359 l 7.29 10.776 L 2.25 21.75 h 2.456 l 6.035 -7.118 l 4.818 7.118 h 6.191 h -0.008 Z M 7.739 3.818 L 18.81 20.182 h -2.447 L 5.29 3.818 h 2.447 Z"/>
        </svg>
      </div>
  </div>

  <div class="x-landing__right">
    <h1 class="x-landing__title">Happening now</h1>
    <h2 class="x-landing__subtitle">Join today.</h2>

    <div class="x-landing__buttons">

      <!-- Signup -->
      <button 
        class="x-landing__btn x-landing__btn--signup" 
        data-open="signupModal"
      >
        Create an account
      </button>
      
      <p class="x-landing__terms">
        By signing up, you agree to the 
        <a href="/terms">Terms of Service</a> and 
        <a href="/privacy">Privacy Policy</a>, including 
        <a href="/cookies">Cookie Use</a>.
      </p>
      
      <!-- Login -->
      <div class="x-landing__login">
        <p class="x-landing__login-text">Already have an account?</p>
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
