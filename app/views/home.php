<?php

// Inkluderer header-komponenten
// require_once bruges for at sikre, at headeren kun indlæses én gang
require __DIR__."/components/_header.php";

// Inkluderer sidebar-komponenten
// require stopper eksekvering hvis filen ikke findes
require __DIR__."/components/_sidebar.php";

// Henter den loggede bruger fra sessionen
$user = $_SESSION["user"];

// Gemmer brugerens primære nøgle i en variabel
// bruges typisk til rettighedstjek og handlinger
$current_user_id = $user['user_pk'];
?>

<!-- Hovedindholdet på siden -->
<main>

    <!-- Inkluderer post-komponenten -->
    <!-- Viser opslag (feed) -->
    <?php require __DIR__."/components/_post.php"; ?>
</main>

<!-- Inkluderer footer-komponenten -->
<?php require __DIR__."/components/_footer.php"; ?>