<?php

// Tjekker om brugeren er logget ind
// Hvis der ikke findes en bruger i sessionen,
// stopper vi eksekveringen, så modal’en ikke vises
if (!isset($_SESSION['user'])) {
    return;
}
?>

<!-- Modal til bekræftelse af sletning af profil -->
<div id="confirmDeleteProfileModal" class="dialog danger-modal">

    <!-- Overlay som lægger en mørk baggrund bag modal’en -->
    <div class="dialog-overlay"></div>

    <!-- Selve modal-indholdet -->
    <div class="dialog-content">

        <!-- Header-sektion med titel og luk-knap -->
        <div class="modal-header">
            <h3>Delete profile</h3>

            <!-- Luk-knap til modal (bruges af JavaScript) -->
            <button class="modal-close dialog-close" aria-label="Close">&times;</button>
        </div>

        <!-- Modalens indhold -->
        <div class="modal-content">

            <!-- Advarselstekst til brugeren -->
            <p class="danger-text">
                Are you sure you want to delete your profile?
                <br><br>

                <!-- Fremhæver at handlingen ikke kan fortrydes -->
                <strong>This action cannot be undone.</strong>
            </p>

            <!-- Knapper til bekræftelse eller annullering -->
            <div class="confirm-actions">
                
                <!-- Annuller-knap: lukker modal’en uden handling -->
                <button
                    type="button"
                    class="cancel-delete-profile dialog-close"
                >
                    Cancel
                </button>

                <!-- Formular til at slette profilen -->
                <!-- Sender en POST-request til serveren -->
                <form action="/profile/delete" method="POST">

                    <!-- CSRF-token til beskyttelse mod Cross-Site Request Forgery -->
                    <?php csrf_input(); ?>
                    
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
