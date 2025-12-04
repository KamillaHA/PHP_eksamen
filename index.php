<?php 
session_start();
$title = "Welcome";

// Hvis bruger allerede er logget ind, redirect til home
if (isset($_SESSION['user_id'])) {
  header('Location: /home');
  exit();
}

require_once __DIR__."/components/_header.php";
?>


<div class="x-landing">
  <div class="x-landing__left">
      <div class="x-landing__logo" aria-hidden="true">
        <svg viewBox="0 0 300 300" xmlns="http://www.w3.org/2000/svg" role="img" aria-labelledby="xLogoTitle">
          <title id="xLogoTitle">X logo</title>
          <g fill="none" stroke="#0b0f11" stroke-linecap="butt" stroke-linejoin="miter" stroke-width="44">
            <line x1="40"  y1="40"  x2="260" y2="260"></line>
            <line x1="260" y1="40"  x2="40"  y2="260"></line>
          </g>
        </svg>
      </div>
  </div>

  <div class="x-landing__right">
    <h1 class="x-landing__title">Happening now</h1>
    <h2 class="x-landing__subtitle">Join today.</h2>

    <a href="signup" class="x-landing__btn x-landing__btn--signup">Sign up</a>
    <a href="login" class="x-landing__btn x-landing__btn--login">Log in</a>
  </div>


<?php 
require_once __DIR__."/private/db.php";
?>
</div>

<?php 
require_once __DIR__."/components/_footer.php"; 
?>