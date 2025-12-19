<div class="button-<?= $user['user_pk'] ?>">
    <form action="/api/api-unfollow.php" method="POST" mix-post>
        <input type="hidden" name="following_pk" value="<?= $user['user_pk'] ?>">
        <button type="submit" class="unfollow-btn">Unfollow</button>
    </form>
</div>
