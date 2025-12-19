<?php

if (!isset($_SESSION["user"])) {
    return;
}
$user = $_SESSION["user"];
?>

<!-- Edit Post Popup Modal -->
<div id="editPostModal" class="x-dialog">
    <div class="x-dialog__overlay"></div>
    <div class="x-dialog__content">
        <div class="modal-header">
            <button class="modal-close x-dialog__close">&times;</button>
        </div>
        <div class="modal-content">

            <form class="edit-post-form" action="/api/api-update-post.php" method="POST" enctype="multipart/form-data">
                <!-- Hidden input til post ID -->
                <input type="hidden" name="post_pk" id="edit_post_pk">
            <input 
                type="file" 
                name="post_image_path"
                id="postImageInput"
                accept="image/*"
                hidden
            >
                
                <!-- Bruger info -->
                <div class="user-info">
                    <div class="avatar-circle">
                        <?php echo strtoupper(substr($user['user_username'], 0, 1)); ?>
                    </div>
                    <div>
                        <div class="name"><?php echo $user["user_full_name"]; ?></div>
                        <div class="handle"><?php echo "@".$user["user_username"]; ?></div>
                    </div>
                </div>
                
                <textarea 
                    name="post_message" 
                    id="editPostTextarea"
                    placeholder="Edit your post..." 
                    maxlength="300" 
                    required 
                    mix-check="^.{1,300}$"
                ></textarea>
                
                <div class="post-form-actions">
                    <div class="post-form-icons">
                        <button type="button" class="post-form-icon" title="Media" onclick="document.getElementById('postImageInput').click()">
                            <i class="fa-solid fa-image"></i>
                        </button>
                    </div>
                    <button 
                        type="submit" 
                        class="post-submit-btn" 
                        id="editPostSubmitBtn" 
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