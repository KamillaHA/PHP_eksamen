<?php
if (!isset($_SESSION['user'])) {
    return;
}
?>

<div id="confirmDeleteProfileModal" class="x-dialog danger-modal">
    <div class="x-dialog__overlay"></div>

    <div class="x-dialog__content">
        <div class="modal-header">
            <h3>Delete profile</h3>
            <button class="modal-close x-dialog__close" aria-label="Close">&times;</button>
        </div>

        <div class="modal-content">
            <p class="danger-text">
                Are you sure you want to delete your profile?
                <br><br>
                <strong>This action cannot be undone.</strong>
            </p>

            <div class="confirm-actions">
                <!-- Cancel -->
                <button
                    type="button"
                    class="cancel-delete-profile x-dialog__close"
                >
                    Cancel
                </button>

                <!-- Confirm delete -->
                <form action="/profile/delete" method="POST">
                    <button
                        type="submit"
                        class="confirm-delete-btn"
                    >
                        Yes, delete my profile
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
