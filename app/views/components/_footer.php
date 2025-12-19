
<a href="#" class="scroll-top" id="scrollTop">↑</a>

<?php if (isset($_SESSION['user'])): ?>
    <?php require __DIR__ . "/../popups/_popup-create-post.php"; ?>
    <?php require __DIR__ . "/../popups/_popup-update-post.php"; ?>
    <?php require __DIR__ . "/../popups/_popup-create-comment.php"; ?>
    <?php require __DIR__ . "/../popups/_popup-update-comment.php"; ?>
    <?php require __DIR__ . "/../popups/_popup-update-profile.php"; ?>
    <?php require __DIR__ . "/../popups/_popup-confirm-delete-profile.php"; ?>
<?php endif; ?>

<script src="/public/assets/js/app.js"></script>
<script src="/public/assets/js/mixhtml.js"></script>

</body>
</html>
