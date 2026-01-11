<?php

// Tjekker om brugeren er logget ind
// Hvis der ikke findes en bruger i sessionen,
// stopper vi eksekveringen, så comment-modal’en ikke vises
if (!isset($_SESSION["user"])) {
    return;
}

// Gemmer den loggede bruger i en variabel
// for nemmere adgang i HTML’en nedenfor
$user = $_SESSION["user"];
?>

<!-- Modal til oprettelse af en kommentar -->
<div id="createCommentModal" class="dialog">
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

            <!-- Formular til oprettelse af kommentar -->
            <!-- Sender en POST-request til API endpoint -->
            <!-- mix-post og mix-after bruges til AJAX-opdatering -->
            <form class="comment-form" action="/api/api-create-comment" method="POST" mix-post mix-after="main">
                
                <!-- Skjult input der indeholder ID på det opslag, som kommentaren hører til -->
                <input type="hidden" name="post_pk" id="comment_post_pk">

                <!-- CSRF-token til beskyttelse mod Cross-Site Request Forgery -->
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                
                <!-- Visning af det opslag, der svares på (preview) -->
                <div class="commenting-on">

                    <!-- Label der viser at der svares på et opslag -->
                    <div class="reply-label">
                        <svg viewBox="0 0 24 24" width="16" height="16" style="fill: #64748b;">
                            <path d="M1.751 10c0-4.42 3.584-8 8.005-8h4.366c4.49 0 8.129 3.64 8.129 8.13 0 2.96-1.607 5.68-4.196 7.11l-8.054 4.46v-3.69h-.067c-4.49.1-8.183-3.51-8.183-8.01zm8.005-6c-3.317 0-6.005 2.69-6.005 6 0 3.37 2.77 6.08 6.138 6.01l.351-.01h1.761v2.3l5.087-2.81c1.951-1.08 3.163-3.13 3.163-5.36 0-3.39-2.744-6.13-6.129-6.13H9.756z"/>
                        </svg>
                        <span>Replying to</span>
                    </div>

                    <!-- Her indsættes opslagets indhold dynamisk via JavaScript -->
                    <div class="original-post" id="originalPostPreview">
                        <!-- Post preview indsættes her -->
                    </div>
                </div>

                <!-- Brugerinformation (avatar, navn og brugernavn) -->
                <div class="user-info">

                    <!-- Avatar med første bogstav fra brugernavnet -->
                    <div class="avatar-circle">
                        <?php 
                        // Viser første bogstav i brugernavnet med stort
                        echo strtoupper(substr($user['user_username'], 0, 1)); ?>
                    </div>

                    <!-- Brugerens navn og handle -->
                    <div>
                        <div class="name"><?php echo $user["user_full_name"]; ?></div>
                        <div class="handle"><?php echo "@".$user["user_username"]; ?></div>
                    </div>
                </div>
                
                <!-- Tekstfelt til kommentar -->
                <textarea 
                    name="comment_text" 
                    id="commentTextarea"
                    placeholder="Post your reply" 
                    maxlength="280" 
                    required 
                    mix-check="^.{1,280}$"
                ></textarea>
                
                <!-- Handlinger og submit-knap -->
                <div class="post-form-actions">

                    <!-- Ikoner (UI – ingen funktionalitet endnu) -->
                    <div class="post-form-icons">
                        <button type="button" class="post-form-icon" title="Media">
                            <i class="fa-solid fa-image"></i>
                        </button>
                        <button type="button" class="post-form-icon" title="GIF">
                            <i class="fa-solid fa-film"></i>
                        </button>
                        <button type="button" class="post-form-icon" title="Poll">
                            <i class="fa-solid fa-chart-bar"></i>
                        </button>
                        <button type="button" class="post-form-icon" title="Emoji">
                            <i class="fa-regular fa-face-smile"></i>
                        </button>
                    </div>

                    <!-- Submit-knap til at oprette kommentaren -->
                    <!-- mix-await og mix-default håndterer loading-tekst -->
                    <button 
                        type="submit" 
                        class="post-submit-btn" 
                        id="commentSubmitBtn" 
                        mix-await="Posting..." 
                        mix-default="Reply"
                        disabled
                    >
                        Reply
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

