<?php
if (!isset($_SESSION["user"])) {
    return;
}
$user = $_SESSION["user"];
?>

<div id="createCommentModal" class="x-dialog">
    <div class="x-dialog__overlay"></div>
    <div class="x-dialog__content">
        <div class="modal-header">
            <h3>Post your reply</h3>
            <button class="modal-close x-dialog__close">&times;</button>
        </div>
        <div class="modal-content">
            <form class="comment-form" action="api/api-create-comment.php" method="POST" mix-post mix-after="main">
                <input type="hidden" name="post_pk" id="commentPostPk">
                <div class="user-info">
                    <img src="https://avatar.iran.liara.run/public/73" alt="Profile">
                    <div>
                        <div class="name"><?php echo $user["user_full_name"]; ?></div>
                        <div class="handle"><?php echo "@".$user["user_username"]; ?></div>
                    </div>
                </div>
                <textarea name="comment_text" placeholder="Post your reply" maxlength="300" required mix-check="^.{1,300}$"></textarea>
                <div class="comment-form-actions">
                    <button type="submit" class="comment-submit-btn" id="commentSubmitBtn" mix-await="Posting..." mix-default="Reply">Reply</button>
                </div>
            </form>
        </div>
    </div>
</div>