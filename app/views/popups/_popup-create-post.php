<?php

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
            <form class="post-form" action="/api/api-create-post.php" method="POST" enctype="multipart/form-data">
            <input 
                type="file" 
                name="post_image_path"
                id="postImageInput"
                accept="image/*"
                hidden
            >
                <div class="user-info">
                    <div class="avatar-circle">
                        <?php echo strtoupper(substr($user['user_username'], 0, 1)); ?>
                    </div>
                    <div>
                        <div class="name"><?php echo $user["user_full_name"]; ?></div>
                        <div class="handle"><?php echo "@".$user["user_username"]; ?></div>
                    </div>
                </div>
                <textarea name="post_message" placeholder="What's happening?!" maxlength="300" required mix-check="^.{1,300}$"></textarea>
                <div class="post-form-actions">
                    <div class="post-form-icons">
                        <button type="button" class="post-form-icon" title="Media" onclick="document.getElementById('postImageInput').click()">
                            <i class="fa-solid fa-image"></i>
                        </button>

                    </div>
                    <button type="submit" class="post-submit-btn" id="postSubmitBtn" mix-await="Posting..." mix-default="Post">Post</button>
                </div>
            </form>
        </div>
    </div>
</div>