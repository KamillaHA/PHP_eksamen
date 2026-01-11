<!-- Scroll-to-top knap -->
<a href="#" class="scroll-top" id="scrollTop">↑</a>

<?php if (isset($_SESSION['user'])): ?>

    <!-- Popup til oprettelse af post -->
    <?php require __DIR__ . "/../popups/_popup-create-post.php"; ?>

    <!-- Popup til redigering af post -->
    <?php require __DIR__ . "/../popups/_popup-update-post.php"; ?>

    <!-- Popup til oprettelse af kommentar -->
    <?php require __DIR__ . "/../popups/_popup-create-comment.php"; ?>

    <!-- Popup til redigering af kommentar -->
    <?php require __DIR__ . "/../popups/_popup-update-comment.php"; ?>

    <!-- Popup til opdatering af profil -->
    <?php require __DIR__ . "/../popups/_popup-update-profile.php"; ?>

    <!-- Popup til bekræftelse af sletning af profil -->
    <?php require __DIR__ . "/../popups/_popup-confirm-delete-profile.php"; ?>

<?php endif; ?>

<!-- Hoved-JavaScript (interaktion, events, popups) -->
<script src="/public/assets/js/app.js"></script>

<!-- MixHTML til delvise DOM-opdateringer -->
<script src="/public/assets/js/mixhtml.js"></script>


<script>
    const csrfToken = "<?= $_SESSION['csrf_token'] ?>";
</script>

</body>
</html>
