<?php
// landing.php
// Dette er forsiden for ikke-loggede brugere
// session_start håndteres centralt i index.php
?>

<!-- Landing page wrapper -->
<div class="landing">

  <!-- Venstre sektion med logo -->
  <div class="landing-left">

    <!-- Logo (kun visuelt, ingen interaktion) -->
    <div class="landing-logo" aria-hidden="true">
      <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" role="img" aria-labelledby="xLogoTitle">

        <!-- Titel for tilgængelighed -->
        <title id="xLogoTitle">X (Twitter) logo</title>

        <!-- SVG path for logo -->
        <path fill="currentColor" d="M 21.742 21.75 l -7.563 -11.179 l 7.056 -8.321 h -2.456 l -5.691 6.714 l -4.54 -6.714 H 2.359 l 7.29 10.776 L 2.25 21.75 h 2.456 l 6.035 -7.118 l 4.818 7.118 h 6.191 h -0.008 Z M 7.739 3.818 L 18.81 20.182 h -2.447 L 5.29 3.818 h 2.447 Z"/>
      </svg>
    </div>
  </div>

  <!-- Højre sektion med tekst og handlinger -->
  <div class="landing-right">

    <!-- Hovedoverskrift -->
    <h1 class="landing-title">Happening now</h1>

    <!-- Underoverskrift -->
    <h2 class="landing-subtitle">Join today</h2>

    <!-- Knapper til handlinger -->
    <div class="landing-buttons">

      <!-- Knap til oprettelse af konto (Signup)-->
      <!-- data-open bruges af JavaScript til at åbne signup-modal -->
      <button 
        class="landing-btn landing-btn-signup" 
        data-open="signupModal"
      >
        Create an account
      </button>
      
      <!-- Login-sektion -->
      <div class="landing-login">

        <!-- Knap til login -->
        <!-- data-open bruges af JavaScript til at åbne login-modal -->
        <button 
          class="landing-btn landing-btn-login" 
          data-open="loginModal"
        >
          Log in
        </button>
      </div>

    </div>
  </div>
</div>

<?php
// Inkluderer login- og signup-popups
// require_once sikrer, at de kun indlæses én gang
require_once __DIR__ . "/popups/_popup-login.php";
require_once __DIR__ . "/popups/_popup-signup.php";
?>
