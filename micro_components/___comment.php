<?php

function renderComment($comment, $current_user_id = null) {
    ?>
    <div class="comment" id="comment-<?php echo $comment['comment_pk']; ?>">
        <div class="comment-header">
            <div class="comment-header-left">
                <strong class="comment-author"><?php echo _($comment['user_username']); ?></strong>
                <small class="comment-time">
                    <?php 
                    $time = strtotime($comment['created_at']);
                    $now = time();
                    $diff = $now - $time;
                    
                    if ($diff < 60) {
                        echo 'just now';
                    } elseif ($diff < 3600) {
                        echo floor($diff / 60) . ' min ago';
                    } elseif ($diff < 86400) {
                        echo floor($diff / 3600) . ' hour' . (floor($diff / 3600) > 1 ? 's' : '') . ' ago';
                    } else {
                        echo date('M j, Y', $time);
                    }
                    ?>
                </small>
            </div>
            
            <?php if ($current_user_id && $current_user_id == $comment['user_fk']): ?>
            <div class="comment-menu">
                <button type="button" class="comment-menu-btn" 
                        onclick="window.toggleCommentDropdown('<?php echo $comment['comment_pk']; ?>')"
                        data-comment-id="<?php echo $comment['comment_pk']; ?>" 
                        aria-label="Comment options">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3 12c0-1.1.9-2 2-2s2 0.9 2 2-0.9 2-2 2-2-0.9-2-2zm9 2c1.1 0 2-0.9 2-2s-0.9-2-2-2-2 0.9-2 2 0.9 2 2 2zm7 0c1.1 0 2-0.9 2-2s-0.9-2-2-2-2 0.9-2 2 0.9 2 2 2z"/>
                    </svg>
                </button>
                <div class="comment-dropdown" id="dropdown-<?php echo $comment['comment_pk']; ?>">
                    <button type="button" class="dropdown-item" 
                            onclick="window.handleEditClick(event, '<?php echo $comment['comment_pk']; ?>', '<?php echo htmlspecialchars($comment['comment_text'], ENT_QUOTES); ?>')"
                            data-action="edit" 
                            data-comment-id="<?php echo $comment['comment_pk']; ?>" 
                            data-comment-text="<?php echo htmlspecialchars($comment['comment_text'], ENT_QUOTES); ?>">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="margin-right: 8px;">
                            <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                        </svg>
                        Edit
                    </button>
                    <form action="/api/api-delete-comment.php" method="POST" class="dropdown-item-form" mix-post>
                        <input type="hidden" name="comment_pk" value="<?php echo $comment['comment_pk']; ?>">
                        <button type="submit" class="dropdown-item delete-btn">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="margin-right: 8px;">
                                <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                            </svg>
                            Delete
                        </button>
                    </form>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <p class="comment-text"><?php echo _($comment['comment_text']); ?></p>
    </div>
    <?php
}
?>