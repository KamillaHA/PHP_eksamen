<?php
// Default values hvis variabler ikke er sat
$posts = $posts ?? [];
$current_user_id = $current_user_id ?? null;

// Inkluder comment komponent
require_once __DIR__ . '/___comment.php';
?>

<div class="posts-container">
    <?php if (empty($posts)): ?>
        <p>No posts found.</p>
    <?php else: ?>
        <?php foreach ($posts as $post): 
            // Hent kommentarer for denne post
            try {
                $sql = "SELECT comments.*, users.user_username 
                        FROM comments 
                        INNER JOIN users ON comments.user_fk = users.user_pk 
                        WHERE comments.post_fk = :post_pk"; 
                $stmt = $_db->prepare($sql);
                $stmt->execute([':post_pk' => $post['post_pk']]);
                $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $commentCount = count($comments);
            } catch (Exception $e) {
                $comments = [];
                $commentCount = 0;
            }
        ?>
        
        <article class="post" id="post-<?php echo $post['post_pk']; ?>">
        <!-- Post header med brugernavn og tre-prikker -->
        <div class="post-header">
            <div class="post-header-left">
                <strong><?php echo _($post['user_username']); ?></strong>
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
        
        <!-- Billedet på sin egen linje -->
        <?php if (!empty($post['post_image_path'])): ?>
            <div class="post-image">
                <img src="<?php echo _($post['post_image_path']); ?>" 
                    alt="Image for <?php echo _($post['post_message']); ?>">
            </div>
        <?php endif; ?>
        
        <!-- Post tekst -->
        <p class="post-message"><?php echo _($post['post_message']); ?></p>

        <!-- Knapper til interaktioner - kommentar knap -->
        <div class="post-actions">
            <!-- Kommentar knap -->
            <button 
                type="button" 
                class="comment-btn"
                data-open="createCommentModal"
                data-post-id="<?php echo $post['post_pk']; ?>"
                data-post-author="<?php echo _($post['user_username']); ?>"
                data-post-content="<?php echo htmlspecialchars(_($post['post_message']) ?? '', ENT_QUOTES, 'UTF-8'); ?>"
            >
                <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18">
                    <path d="M1.751 10c0-4.42 3.584-8 8.005-8h4.366c4.49 0 8.129 3.64 8.129 8.13 0 2.96-1.607 5.68-4.196 7.11l-8.054 4.46v-3.69h-.067c-4.49.1-8.183-3.51-8.183-8.01zm8.005-6c-3.317 0-6.005 2.69-6.005 6 0 3.37 2.77 6.08 6.138 6.01l.351-.01h1.761v2.3l5.087-2.81c1.951-1.08 3.163-3.13 3.163-5.36 0-3.39-2.744-6.13-6.129-6.13H9.756z"/>
                </svg>
                <?php if ($commentCount > 0): ?>
                    <span class="comment-count"><?php echo $commentCount; ?></span>
                <?php endif; ?>
            </button>
        </div>

        <!-- Vis kommentarer hvis der er nogen -->
        <?php if ($commentCount > 0): ?>
            <div class="comments-section">
                <h4 class="comments-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M1.751 10c0-4.42 3.584-8 8.005-8h4.366c4.49 0 8.129 3.64 8.129 8.13 0 2.96-1.607 5.68-4.196 7.11l-8.054 4.46v-3.69h-.067c-4.49.1-8.183-3.51-8.183-8.01zm8.005-6c-3.317 0-6.005 2.69-6.005 6 0 3.37 2.77 6.08 6.138 6.01l.351-.01h1.761v2.3l5.087-2.81c1.951-1.08 3.163-3.13 3.163-5.36 0-3.39-2.744-6.13-6.129-6.13H9.756z"/>
                    </svg>
                    Comments (<?php echo $commentCount; ?>)
                </h4>
                
                <div class="comments-list">
                    <?php foreach ($comments as $comment): 
                        // Send både comment og current_user_id til funktionen
                        renderComment($comment, $current_user_id);
                    endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <hr> 
        </article>

        <?php endforeach; ?>
    <?php endif; ?>
</div>