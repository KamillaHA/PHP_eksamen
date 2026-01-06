<?php

// Inkluderer header-komponenten
// require_once sikrer, at headeren kun indlæses én gang
require_once __DIR__."/components/_header.php";

// Inkluderer sidebar-komponenten
// require stopper eksekveringen hvis filen ikke findes
require __DIR__ . '/components/_sidebar.php';

// Henter den loggede bruger fra sessionen
$user = $_SESSION["user"];

// Gemmer brugerens primære nøgle
// bruges til at identificere den aktuelle bruger
$current_user_id = $user['user_pk'];
?>

<!-- Hovedindholdet på profilsiden -->
<main id="main">

    <!-- Container til toast-beskeder (feedback til brugeren) -->
    <div id="toast"></div>

    <?php

        // Inkluderer profil-headeren
        // Viser brugeroplysninger, follow-knapper m.m.
        require __DIR__ . '/components/_profile-header.php';

        // Inkluderer post-komponenten
        // Viser brugerens opslag
        require __DIR__ . '/components/_post.php';
    ?>
</main>

<!-- Inkluderer footer-komponenten -->
<?php require __DIR__."/components/_footer.php"; ?>