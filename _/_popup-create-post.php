<?php
// Enten start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["user"])) {
    return;
}
$user = $_SESSION["user"];
?>

<div id="createPostModal" class="x-dialog">
    <div class="x-dialog__overlay"></div>
    <div class="x-dialog__content">
        <div class="modal-header">
            <button class="modal-close x-dialog__close">&times;</button>
        </div>
        <div class="modal-content">
            <form class="post-form" action="api/api-create-post.php" method="POST" mix-post mix-after="main">
                <div class="user-info">
                    <img src="https://avatar.iran.liara.run/public/73" alt="Profile">
                    <div>
                        <div class="name"><?php echo $user["user_full_name"]; ?></div>
                        <div class="handle"><?php echo "@".$user["user_username"]; ?></div>
                    </div>
                </div>
                <textarea name="post_message" placeholder="What's happening?!" maxlength="300" required mix-check="^.{1,300}$"></textarea>
                <div class="post-form-actions">
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
                    <button type="submit" class="post-submit-btn" id="postSubmitBtn" mix-await="Posting..." mix-default="Post">Post</button>
                </div>
            </form>
        </div>
    </div>
</div>