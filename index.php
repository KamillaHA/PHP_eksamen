<?php 
$title = "Welcome";
require_once __DIR__."/_/_header.php";
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

<form method="GET">
    <button class="x-landing__btn x-landing__btn--signup" formaction="signup">Sign up</button>
    <button class="x-landing__btn x-landing__btn--login" formaction="login">Log in</button>
</form>
  </div>


<?php 
require_once __DIR__."/private/db.php";
// require_once __DIR__."/login.php"; 
// require_once __DIR__."/signup.php"; 

?>

</div>

<?php require_once __DIR__."/_/_footer.php"; ?>