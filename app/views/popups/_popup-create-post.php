<?php

// Tjekker om brugeren er logget ind
// Hvis der ikke findes en bruger i sessionen,
// stopper vi eksekveringen, så post-modal’en ikke vises
if (!isset($_SESSION["user"])) {
    return;
}
// Gemmer den loggede bruger i en variabel
// for nemmere adgang i HTML’en
$user = $_SESSION["user"];
?>

<!-- Modal til oprettelse af et nyt opslag -->
<div id="createPostModal" class="dialog">

    <!-- Overlay der mørklægger baggrunden -->
    <div class="dialog-overlay"></div>

    <!-- Selve modal-indholdet -->
    <div class="dialog-content">

        <!-- Header med luk-knap -->
        <div class="modal-header">
            <button class="modal-close dialog-close">&times;</button>
        </div>

        <!-- Modalens indhold -->
        <div class="modal-content">

            <!-- Formular til oprettelse af opslag -->
            <!-- enctype="multipart/form-data" kræves for at kunne uploade filer -->
            <form class="post-form" action="/post/create" method="POST" enctype="multipart/form-data">
            
            <!-- Skjult fil-input til billede upload -->
            <!-- Accepterer kun billedfiler -->
            <input 
                type="file" 
                name="post_image_path"
                id="createPostImageInput"
                accept="image/*"
                hidden
            >
                <!-- Brugerinformation -->
                <div class="user-info">

                    <!-- Avatar med første bogstav fra brugernavnet -->
                    <div class="avatar-circle">
                        <?php 
                        // Viser første bogstav i brugernavnet med stort
                        echo strtoupper(substr($user['user_username'], 0, 1)); ?>
                    </div>

                    <!-- Brugerens navn og brugernavn -->
                    <div>
                        <div class="name"><?php echo $user["user_full_name"]; ?></div>
                        <div class="handle"><?php echo "@".$user["user_username"]; ?></div>
                    </div>
                </div>

                <!-- Tekstfelt til opslagets indhold -->
                <!-- maxlength og mix-check bruges til klient-side validering -->
                <textarea name="post_message" placeholder="What's happening?!" maxlength="300" required mix-check="^.{1,300}$"></textarea>
                
                <!-- Handlinger og submit-knap -->
                <div class="post-form-actions">

                    <!-- Ikon til at vælge billede -->
                    <!-- Klik åbner det skjulte file input -->
                    <div class="post-form-icons">
                        <button type="button" class="post-form-icon" title="Media" onclick="document.getElementById('createPostImageInput').click()">
                            <i class="fa-solid fa-image"></i>
                        </button>

                    </div>

                    <!-- Submit-knap til at oprette opslag -->
                    <!-- mix-await og mix-default håndterer loading-tekst -->
                    <button type="submit" class="post-submit-btn" id="postSubmitBtn" mix-await="Posting..." mix-default="Post">Post</button>
                </div>
            </form>
        </div>
    </div>
</div>