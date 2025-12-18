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

<!-- Comment Popup Modal -->
<div id="createCommentModal" class="x-dialog">
    <div class="x-dialog__overlay"></div>
    <div class="x-dialog__content">
        <div class="modal-header">
            <button class="modal-close x-dialog__close">&times;</button>
        </div>
        <div class="modal-content">
            <form class="comment-form" action="api/api-create-comment.php" method="POST" mix-post mix-after="main">
                <!-- Hidden input til post ID -->
                <input type="hidden" name="post_pk" id="comment_post_pk">
                
                <!-- Post vi kommenterer på (preview) -->
                <div class="commenting-on">
                    <div class="reply-label">
                        <svg viewBox="0 0 24 24" width="16" height="16" style="fill: #64748b;">
                            <path d="M1.751 10c0-4.42 3.584-8 8.005-8h4.366c4.49 0 8.129 3.64 8.129 8.13 0 2.96-1.607 5.68-4.196 7.11l-8.054 4.46v-3.69h-.067c-4.49.1-8.183-3.51-8.183-8.01zm8.005-6c-3.317 0-6.005 2.69-6.005 6 0 3.37 2.77 6.08 6.138 6.01l.351-.01h1.761v2.3l5.087-2.81c1.951-1.08 3.163-3.13 3.163-5.36 0-3.39-2.744-6.13-6.129-6.13H9.756z"/>
                        </svg>
                        <span>Replying to</span>
                    </div>
                    <div class="original-post" id="originalPostPreview">
                        <!-- Her vil post preview blive indsat via JavaScript -->
                    </div>
                </div>

                <!-- Bruger info og comment input -->
                <div class="user-info">
                    <img src="https://avatar.iran.liara.run/public/73" alt="Profile">
                    <div>
                        <div class="name"><?php echo $user["user_full_name"]; ?></div>
                        <div class="handle"><?php echo "@".$user["user_username"]; ?></div>
                    </div>
                </div>
                
                <textarea 
                    name="comment_text" 
                    id="commentTextarea"
                    placeholder="Post your reply" 
                    maxlength="280" 
                    required 
                    mix-check="^.{1,280}$"
                ></textarea>
                
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

<style>
/* Tilføj styling til comment popup */
.commenting-on {
    padding: 12px 16px;
    border-bottom: 1px solid #e5e7eb;
    margin-bottom: 12px;
    background-color: #f8fafc;
    border-radius: 8px;
}

.reply-label {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #64748b;
    font-size: 14px;
    margin-bottom: 8px;
}

.original-post {
    font-size: 15px;
    line-height: 1.4;
    color: #374151;
}

.original-post .post-author {
    font-weight: 600;
    color: #111827;
    margin-bottom: 4px;
}

.original-post .post-content {
    color: #4b5563;
}
</style>