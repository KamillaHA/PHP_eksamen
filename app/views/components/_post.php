<?php
// Fallback hvis variabler ikke er sat fra controller
$posts = $posts ?? [];
$current_user_id = $current_user_id ?? null;

// Afgør om vi er i single-post view eller feed view
$view_mode = isset($_GET['post']) && !empty($_GET['post']) ? 'single' : 'feed';
$single_post_id = $_GET['post'] ?? null;

// Inkluder renderComment helper
require_once __DIR__ . '/_comment.php';
?>

<div class="posts-container">
    <?php if ($view_mode === 'single'): ?>

        <!-- Tilbage til feed-knap (kun i single view) -->
        <div class="single-view-header">
            <a href="<?= $_SESSION['back_to_feed'] ?? '/home' ?>" class="back-to-feed-btn">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                </svg>
                Back to feed
            </a>
        </div>
    <?php endif; ?>

    <!-- Ingen posts fundet -->
    <?php if (empty($posts)): ?>
        <p>No posts found.</p>
    <?php else: ?>

    <?php 
    // I single view: find kun det post der matcher ID
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
        // Feed view: vis alle posts
        $posts_to_display = $posts; 
    }
    ?>

    <?php foreach ($posts_to_display as $post): 

        // Forbered data til viewet
        $comments     = $post['comments'] ?? [];
        $commentCount = $post['commentCount'] ?? 0;
        $likeCount    = $post['likeCount'] ?? 0;
        $userLiked    = $post['userLiked'] ?? false;
    ?>
        
    <article class="post-container <?php echo $view_mode === 'single' ? 'single-post-view' : 'feed-post-view'; ?>" 
        id="post-<?php echo $post['post_pk']; ?>">

        <!-- Profil- og indholdssektion -->
        <div class="post-profile-section">
            <div class="post-content-section">

                <!-- Brugerinfo og post-menu -->
                <div class="post-user-header">
                    <div class="post-user-info">

                        <!-- Avatar -->
                        <div class="post-user-avatar">
                            <div class="avatar-circle">
                                <?php echo strtoupper(substr($post['user_username'], 0, 1)); ?>
                            </div>
                        </div>

                        <!-- Navn og brugernavn -->
                        <div class="post-user-text">
                            <p class="post-username"><?php echo _($post['user_full_name']); ?></p>
                            <p class="post-user-handle">@<?php echo _($post['user_username']); ?></p>
                        </div>
                    </div>
                        
                    <?php if ($current_user_id == $post['post_user_fk']): ?>

                    <!-- Post menu (edit/delete) -->
                    <div class="post-menu">
                        <button type="button" class="post-menu-btn" 
                                onclick="togglePostDropdown('<?= $post['post_pk']; ?>')" 
                                aria-label="Post options">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M3 12c0-1.1.9-2 2-2s2 0.9 2 2-0.9 2-2 2-2-0.9-2-2zm9 2c1.1 0 2-0.9 2-2s-0.9-2-2-2-2 0.9-2 2 0.9 2 2 2zm7 0c1.1 0 2-0.9 2-2s-0.9-2-2-2-2 0.9-2 2 0.9 2 2 2z"/>
                            </svg>
                        </button>
                            
                        <div class="post-dropdown" id="post-dropdown-<?php echo $post['post_pk']; ?>">
                                
                            <!-- Edit post -->
                            <button type="button" class="dropdown-item" 
                                onclick="openEditPostPopup(
                                    '<?= $post['post_pk']; ?>',
                                    '<?= htmlspecialchars($post['post_message'], ENT_QUOTES); ?>'
                                )">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="margin-right: 8px;">
                                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                                </svg>
                                Edit Post
                            </button>

                            <!-- Delete post -->
                            <form action="/post/delete" method="POST">
                            <input type="hidden" name="post_pk" value="<?php echo $post['post_pk']; ?>">
                            <input type="hidden" name="redirect_to" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
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
                    
        <!-- Post Billede -->
        <?php if (!empty($post['post_image_path'])): ?>
            <div class="post-image">

                <?php if ($view_mode === 'feed'): ?>

                <!-- Feed view: link til single view -->
                <a href="/<?php echo $post['user_username']; ?>/status/<?php echo $post['post_pk']; ?>" class="post-image-link">
                    <img src="<?php echo _($post['post_image_path']); ?>" 
                        alt="Image for <?php echo _($post['post_message']); ?>">
                </a>

                <?php else: ?>

                    <!-- Single view: vis billede direkte -->
                    <img src="<?php echo _($post['post_image_path']); ?>" 
                        alt="Image for <?php echo _($post['post_message']); ?>">

                <?php endif; ?>

            </div>
                    
                <?php endif; ?>
                    
                    <!-- Post actions med både like og kommentar -->
                    <div class="post-actions">
                    
                    <!-- Like knap -->
                        <?php
                        $postPk    = $post['post_pk'];
                        $userLiked = $userLiked;
                        $likeCount = $likeCount;

                        require __DIR__ . '/../micro_components/___button-like.php';
                        ?>

                        <?php if ($view_mode === 'single'): ?>

                            <!-- Single view: vis kommentarantal som tekst -->
                            <span class="comment-count-display">
                                <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18">
                                    <path d="M1.751 10c0-4.42 3.584-8 8.005-8h4.366c4.49 0 8.129 3.64 8.129 8.13 0 2.96-1.607 5.68-4.196 7.11l-8.054 4.46v-3.69h-.067c-4.49.1-8.183-3.51-8.183-8.01zm8.005-6c-3.317 0-6.005 2.69-6.005 6 0 3.37 2.77 6.08 6.138 6.01l.351-.01h1.761v2.3l5.087-2.81c1.951-1.08 3.163-3.13 3.163-5.36 0-3.39-2.744-6.13-6.129-6.13H9.756z"/>
                                </svg>
                                <span class="comment-count">
                                    <?php echo $commentCount > 0 ? $commentCount . ' comments' : 'No comments yet'; ?>
                                </span>
                            </span>

                        <?php else: ?>
                            <!-- Feed view: Vis som link til single view med NY URL struktur -->
                            <a href="/<?php echo $post['user_username']; ?>/status/<?php echo $post['post_pk']; ?>" 
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
                            
                            <!-- Add comment form -->
                            <div class="add-comment-form">
                                <form action="/comment" method="POST">
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
                    <?php endif; ?>
            </div>
        </div>
    </article>

    <?php endforeach; ?>
    <?php endif; ?>
    
</div>