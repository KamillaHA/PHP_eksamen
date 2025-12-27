<?php

function renderComment($comment, $current_user_id = null) {
?>
    <div class="comment" id="comment-<?= $comment['comment_pk'] ?>">

        <div class="comment-header">
            <div class="comment-header-left">
                <strong class="comment-author">
                    <?= _($comment['user_username']) ?>
                </strong>

                <small class="comment-time">
                    <?php
                    $time = strtotime($comment['created_at']);
                    $diff = time() - $time;

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

            <?php if ($current_user_id && $current_user_id === $comment['user_fk']): ?>
                <div class="comment-menu">

                    <!-- 3-prik knap -->
                    <button
                        type="button"
                        class="comment-menu-btn"
                        onclick="window.toggleCommentDropdown('<?= $comment['comment_pk'] ?>')"
                        aria-label="Comment options"
                    >

                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M3 12c0-1.1.9-2 2-2s2 0.9 2 2-0.9 2-2 2-2-0.9-2-2zm9 2c1.1 0 2-0.9 2-2s-0.9-2-2-2-2 0.9-2 2 0.9 2 2 2zm7 0c1.1 0 2-0.9 2-2s-0.9-2-2-2-2 0.9-2 2 0.9 2 2 2z"/>
                        </svg>
                    </button>

                    <!-- Dropdown -->
                    <div class="comment-dropdown" id="dropdown-<?= $comment['comment_pk'] ?>">

                        <!-- EDIT: åbner popup + sender data -->
                        <button
                            type="button"
                            class="dropdown-item"
                            onclick="openEditCommentPopup(
                                '<?= $comment['comment_pk'] ?>',
                                '<?= htmlspecialchars($comment['comment_text'], ENT_QUOTES) ?>',
                                '<?= $comment['post_pk'] ?>'
                            )"
                        >
                            Edit
                        </button>

                        <!-- DELETE: MVC endpoint -->
                        <form
                            action="/comment/delete"
                            method="POST"
                            class="dropdown-item-form"
                        >
                            <input type="hidden" name="comment_pk" value="<?= $comment['comment_pk'] ?>">
                            <input type="hidden" name="post_pk" value="<?= $comment['post_pk'] ?>">
                            <button type="submit" class="dropdown-item delete-btn">
                                Delete
                            </button>
                        </form>

                    </div>
                </div>
            <?php endif; ?>
        </div>

        <p class="comment-text"><?= _($comment['comment_text']) ?></p>
    </div>
<?php
}
