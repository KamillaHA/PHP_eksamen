<?php
// Default values hvis variabler ikke er sat
$posts = $posts ?? [];
$current_user_id = $current_user_id ?? null;

// NY: Tjek om vi er i "single view" eller "feed view"
$view_mode = isset($_GET['post']) && !empty($_GET['post']) ? 'single' : 'feed';
$current_post_id = $_GET['post'] ?? null;

// Inkluder comment komponent
require_once __DIR__ . '/___comment.php';
?>

<div class="posts-container">
    <?php if ($view_mode === 'single'): ?>
        <!-- Tilbage knap kun i single view -->
        <div class="single-view-header">
            <a href="/home" class="back-to-feed-btn">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                </svg>
                Back to feed
            </a>
        </div>
    <?php endif; ?>

    <?php if (empty($posts)): ?>
        <p>No posts found.</p>
    <?php else: ?>
<?php 
// I single view: Find det rigtige post baseret på ID
if ($view_mode === 'single') {
    $found_post = null;
    foreach ($posts as $post_item) {
        if ($post_item['post_pk'] == $single_post_id) {
            $found_post = $post_item;
            break;
        }
    }
    $posts_to_display = $found_post ? [$found_post] : [];
} else {
    $posts_to_display = $posts; // Feed view: alle posts
}
?>
<?php
        foreach ($posts_to_display as $post): 
            // Hent kommentar count eller hele kommentarlisten afhængig af view
            try {
                if ($view_mode === 'single') {
                    // Single view: Hent hele kommentarlisten
                    $sql = "SELECT comments.*, users.user_username 
                            FROM comments 
                            INNER JOIN users ON comments.user_fk = users.user_pk 
                            WHERE comments.post_fk = :post_pk
                            ORDER BY comments.created_at DESC"; 
                    $stmt = $_db->prepare($sql);
                    $stmt->execute([':post_pk' => $post['post_pk']]);
                    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $commentCount = count($comments);
                } else {
                    // Feed view: Hent kun count for bedre performance
                    $sql = "SELECT COUNT(*) as comment_count FROM comments WHERE post_fk = :post_pk"; 
                    $stmt = $_db->prepare($sql);
                    $stmt->execute([':post_pk' => $post['post_pk']]);
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    $commentCount = $result['comment_count'] ?? 0;
                    $comments = []; // Tom array i feed view
                }
            } catch (Exception $e) {
                $comments = [];
                $commentCount = 0;
            }
        ?>
        
        <article class="post-container <?php echo $view_mode === 'single' ? 'single-post-view' : 'feed-post-view'; ?>" 
                 id="post-<?php echo $post['post_pk']; ?>">
            <!-- Container til profilbillede og brugerinfo -->
            <div class="post-profile-section">
                
                <!-- Brugerinfo og indhold -->
                <div class="post-content-section">
                    <!-- Brugerinfo og tre-prikker menu -->
                    <div class="post-user-header">
                        <div class="post-user-info">
                            <!-- Profilbillede -->
                            <div class="post-user-avatar">
                                <div class="avatar-circle">
                                    <?php echo strtoupper(substr($post['user_username'], 0, 1)); ?>
                                </div>
                            </div>
                            <div class="post-user-text">
                                <p class="post-username"><?php echo _($post['user_username']); ?></p>
                                <p class="post-user-handle">@<?php echo _($post['user_username']); ?></p>
                            </div>
                        </div>
                        
                        <?php if ($current_user_id == $post['post_user_fk']): ?>
                        <div class="post-menu">
                            <button type="button" class="post-menu-btn" 
                                    data-post-id="<?php echo $post['post_pk']; ?>" 
                                    aria-label="Post options">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M3 12c0-1.1.9-2 2-2s2 0.9 2 2-0.9 2-2 2-2-0.9-2-2zm9 2c1.1 0 2-0.9 2-2s-0.9-2-2-2-2 0.9-2 2 0.9 2 2 2zm7 0c1.1 0 2-0.9 2-2s-0.9-2-2-2-2 0.9-2 2 0.9 2 2 2z"/>
                                </svg>
                            </button>
                            <div class="post-dropdown" id="post-dropdown-<?php echo $post['post_pk']; ?>">
                                <button type="button" class="dropdown-item" 
                                        data-action="edit-post" 
                                        data-post-id="<?php echo $post['post_pk']; ?>" 
                                        data-post-text="<?php echo htmlspecialchars($post['post_message'], ENT_QUOTES); ?>">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="margin-right: 8px;">
                                        <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                                    </svg>
                                    Edit Post
                                </button>
                                <form action="/api/api-delete-post.php" method="POST" class="dropdown-item-form" mix-post>
                                    <input type="hidden" name="post_pk" value="<?php echo $post['post_pk']; ?>">
                                    <button type="submit" class="dropdown-item delete-btn">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="margin-right: 8px;">
                                            <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                                        </svg>
                                        Delete Post
                                    </button>
                                </form>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Post tekst -->
                    <p class="post-content"><?php echo _($post['post_message']); ?></p>
                    
                    <!-- Billede -->
                    <?php if (!empty($post['post_image_path'])): ?>
                        <div class="post-image">
                            <img src="<?php echo _($post['post_image_path']); ?>" 
                                alt="Image for <?php echo _($post['post_message']); ?>">
                        </div>
                    <?php endif; ?>
                    
                    <!-- Kommentar sektion - forskellig i single vs feed view -->
                    <div class="post-actions">
                        <?php if ($view_mode === 'single'): ?>
                            <!-- Single view: Vis count som tekst -->
                            <span class="comment-count-display">
                                <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18">
                                    <path d="M1.751 10c0-4.42 3.584-8 8.005-8h4.366c4.49 0 8.129 3.64 8.129 8.13 0 2.96-1.607 5.68-4.196 7.11l-8.054 4.46v-3.69h-.067c-4.49.1-8.183-3.51-8.183-8.01zm8.005-6c-3.317 0-6.005 2.69-6.005 6 0 3.37 2.77 6.08 6.138 6.01l.351-.01h1.761v2.3l5.087-2.81c1.951-1.08 3.163-3.13 3.163-5.36 0-3.39-2.744-6.13-6.129-6.13H9.756z"/>
                                </svg>
                                <span class="comment-count">
                                    <?php echo $commentCount > 0 ? $commentCount . ' comments' : 'No comments yet'; ?>
                                </span>
                            </span>
                        <?php else: ?>
                            <!-- Feed view: Vis som link til single view -->
                            <a href="/home?post=<?php echo $post['post_pk']; ?>" 
                               class="comment-btn"
                               title="View post and comments">
                                <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18">
                                    <path d="M1.751 10c0-4.42 3.584-8 8.005-8h4.366c4.49 0 8.129 3.64 8.129 8.13 0 2.96-1.607 5.68-4.196 7.11l-8.054 4.46v-3.69h-.067c-4.49.1-8.183-3.51-8.183-8.01zm8.005-6c-3.317 0-6.005 2.69-6.005 6 0 3.37 2.77 6.08 6.138 6.01l.351-.01h1.761v2.3l5.087-2.81c1.951-1.08 3.163-3.13 3.163-5.36 0-3.39-2.744-6.13-6.129-6.13H9.756z"/>
                                </svg>
                                <?php if ($commentCount > 0): ?>
                                    <span class="comment-count"><?php echo $commentCount; ?></span>
                                <?php endif; ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Kommentarer - KUN vis i single view -->
<!-- Kommentarer og tilføj kommentar form - KUN i single view -->
<?php if ($view_mode === 'single'): ?>
    <div class="comments-section single-view-comments">
        <?php if ($commentCount > 0): ?>
            <h4 class="comments-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M1.751 10c0-4.42 3.584-8 8.005-8h4.366c4.49 0 8.129 3.64 8.129 8.13 0 2.96-1.607 5.68-4.196 7.11l-8.054 4.46v-3.69h-.067c-4.49.1-8.183-3.51-8.183-8.01zm8.005-6c-3.317 0-6.005 2.69-6.005 6 0 3.37 2.77 6.08 6.138 6.01l.351-.01h1.761v2.3l5.087-2.81c1.951-1.08 3.163-3.13 3.163-5.36 0-3.39-2.744-6.13-6.129-6.13H9.756z"/>
                </svg>
                Comments (<?php echo $commentCount; ?>)
            </h4>
            
            <div class="comments-list">
                <?php foreach ($comments as $comment): 
                    renderComment($comment, $current_user_id);
                endforeach; ?>
            </div>
        <?php endif; ?>
        
<!-- NY: Add comment form -->
<div class="add-comment-form">
    <form action="/api/api-create-comment.php" method="POST" mix-post mix-target>
        <input type="hidden" name="post_pk" value="<?php echo $post['post_pk']; ?>">
        
        <div class="comment-input-group">
            <textarea name="comment_text"
                    id="write_comment"
                      placeholder="Write a comment..." 
                      rows="2"
                      maxlength="255"
                      required></textarea>
            <button type="submit" class="comment-submit-btn">Post</button>
        </div>
    </form>
</div>
        </div>
    </div>
<?php endif; ?>
                </div>
            </div>
        </article>
        <?php endforeach; ?>
    <?php endif; ?>
</div>