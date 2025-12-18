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

<!-- Edit Comment Popup Modal -->
<div id="editCommentModal" class="x-dialog">
    <div class="x-dialog__overlay"></div>
    <div class="x-dialog__content">
        <div class="modal-header">
            <button class="modal-close x-dialog__close">&times;</button>
        </div>
        <div class="modal-content">
            <form class="edit-comment-form" action="api/api-update-comment.php" method="POST" mix-post>
                <!-- Hidden input til comment ID -->
                <input type="hidden" name="comment_pk" id="edit_comment_pk">
                
                <!-- Bruger info -->
                <div class="user-info">
                    <img src="https://avatar.iran.liara.run/public/73" alt="Profile">
                    <div>
                        <div class="name"><?php echo $user["user_full_name"]; ?></div>
                        <div class="handle"><?php echo "@".$user["user_username"]; ?></div>
                    </div>
                </div>
                
                <textarea 
                    name="comment_text" 
                    id="editCommentTextarea"
                    placeholder="Edit your comment..." 
                    maxlength="255" 
                    required 
                    mix-check="^.{1,255}$"
                ></textarea>
                
                <div class="post-form-actions">
                    <button 
                        type="submit" 
                        class="post-submit-btn" 
                        id="editCommentSubmitBtn" 
                        mix-await="Updating..." 
                        mix-default="Update"
                        disabled
                    >
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>