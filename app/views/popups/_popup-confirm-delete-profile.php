<?php
if (!isset($_SESSION['user'])) {
    return;
}
?>

<div id="confirmDeleteProfileModal" class="dialog danger-modal">
    <div class="dialog-overlay"></div>

    <div class="dialog-content">
        <div class="modal-header">
            <h3>Delete profile</h3>
            <button class="modal-close dialog-close" aria-label="Close">&times;</button>
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
                    class="cancel-delete-profile dialog-close"
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
