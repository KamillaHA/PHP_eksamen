<?php

// Renderer én kommentar (bruges i views)
// $current_user_id bruges til at afgøre om menuen (edit/delete) skal vises
function renderComment($comment, $current_user_id = null) {
?>
    
    <!-- Wrapper for én kommentar -->
    <div class="comment" id="comment-<?= $comment['comment_pk'] ?>">

        <div class="comment-header">
            <div class="comment-header-left">

                <!-- Kommentarens forfatter (escapes via _()) -->
                <strong class="comment-author">
                    <?= _($comment['user_full_name']) ?>
                </strong>

                <!-- Relativ tidsvisning (just now / min ago / hour ago / dato) -->
                <small class="comment-time">

                    <?php
                    // Konverter tidspunkt fra databasen til timestamp
                    $time = strtotime($comment['created_at']);
                    $diff = time() - $time;

                    // Vælg format baseret på hvor gammel kommentaren er
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

                    <!-- 3-prik knap til at åbne dropdown -->
                    <button
                        type="button"
                        class="comment-menu-btn"
                        onclick="window.toggleCommentDropdown('<?= $comment['comment_pk'] ?>')"
                        aria-label="Comment options"
                    >
                        <!-- Ikon -->
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M3 12c0-1.1.9-2 2-2s2 0.9 2 2-0.9 2-2 2-2-0.9-2-2zm9 2c1.1 0 2-0.9 2-2s-0.9-2-2-2-2 0.9-2 2 0.9 2 2 2zm7 0c1.1 0 2-0.9 2-2s-0.9-2-2-2-2 0.9-2 2 0.9 2 2 2z"/>
                        </svg>
                    </button>

                    <!-- Dropdown-menu med handlinger -->
                    <div class="comment-dropdown" id="dropdown-<?= $comment['comment_pk'] ?>">

                        <!-- Edit: åbner popup og sender nødvendige data -->
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

                        <!-- Delete: sender POST request til MVC-endpoint -->
                        <form
                            action="/comment/delete"
                            method="POST"
                            class="dropdown-item-form"
                        >
                            <input type="hidden" name="comment_pk" value="<?= $comment['comment_pk'] ?>">
                            <input type="hidden" name="post_pk" value="<?= $comment['post_pk'] ?>">
                            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                            <button type="submit" class="dropdown-item delete-btn">
                                Delete
                            </button>
                        </form>

                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Kommentarens tekst (escapes via _()) -->
        <p class="comment-text"><?= _($comment['comment_text']) ?></p>
    </div>
<?php
}
