<?php

// Tjekker om brugeren er logget ind
// Hvis der ikke findes en bruger i sessionen,
// vises edit comment-modal’en ikke
if (!isset($_SESSION["user"])) {
    return;
}
// Gemmer den loggede bruger i en variabel
// så vi nemt kan bruge den i HTML’en
$user = $_SESSION["user"];
?>

<!-- Modal til redigering af kommentar -->
<div id="editCommentModal" class="dialog">

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

            <!-- Formular til opdatering af kommentar -->
            <!-- Sender en POST-request til /comment/update -->
            <form class="edit-comment-form" action="/comment/update" method="POST">

                <!-- Skjult input med kommentarens ID -->
                <input type="hidden" name="comment_pk" id="edit_comment_pk">

                <!-- Skjult input med ID på det opslag kommentaren hører til -->
                <input type="hidden" name="post_pk" id="edit_post_pk">

                <!-- CSRF-token til beskyttelse mod Cross-Site Request Forgery -->
                <?php csrf_input(); ?>
                
                <!-- Bruger info -->
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
                
                <!-- Tekstfelt til redigering af kommentar -->
                <!-- maxlength og mix-check bruges til klient-side validering -->
                <textarea 
                    name="comment_text" 
                    id="editCommentTextarea"
                    placeholder="Edit your comment..." 
                    maxlength="255" 
                    required 
                    mix-check="^.{1,255}$"
                ></textarea>
                
                <!-- Handlinger og submit-knap -->
                <div class="post-form-actions">

                    <!-- Submit-knap til at opdatere kommentaren -->
                    <!-- mix-await og mix-default håndterer loading-tekst -->
                    <button 
                        type="submit" 
                        class="post-submit-btn" 
                        id="editCommentSubmitBtn" 
                        mix-await="Updating..." 
                        mix-default="Update"
                    >
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>