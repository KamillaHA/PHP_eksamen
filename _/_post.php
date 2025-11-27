<?php
// Default values hvis variabler ikke er sat
$posts = $posts ?? [];
$current_user_id = $current_user_id ?? null;
?>

<div class="posts-container">
    <?php if (empty($posts)): ?>
        <p>No posts found.</p>
    <?php else: ?>
        <?php foreach ($posts as $post): ?>
        <article class="post" id="post-<?php echo $post['post_pk']; ?>">
            <div>
                <?php if (!empty($post['post_image_path'])): ?>
                    <div class="post-image">
                        <img src="<?php echo _($post['post_image_path']); ?>" 
                            alt="Image for <?php echo _($post['post_message']); ?>">
                    </div>
                <?php endif; ?>
                <strong><?php echo _($post['user_username']); ?></strong>
            </div>
            <p><?php echo _($post['post_message']); ?></p>

            <?php if ($current_user_id == $post['post_user_fk']): ?>
                <!-- Delete form -->
                <form action="/api/api-delete-post.php" method="POST">
                    <input type="hidden" name="post_pk" value="<?php echo $post['post_pk']; ?>">
                    <button type="submit">Delete</button>
                </form>

                <!-- Update form -->
                <form action="/api/api-update-post.php" method="POST">
                    <input type="hidden" name="post_pk" value="<?php echo $post['post_pk']; ?>">
                    <textarea name="post_message"><?php echo _($post['post_message']); ?></textarea>
                    <button type="submit">Update</button>
                </form>
            <?php endif; ?>
            <hr> 
        </article>
        <?php endforeach; ?>
    <?php endif; ?>
</div>