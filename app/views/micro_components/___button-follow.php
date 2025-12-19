<div class="button-<?= $user['user_pk'] ?>">
    <form action="/api/api-follow.php" method="POST" mix-post>
        <input type="hidden" name="following_pk" value="<?= $user['user_pk'] ?>">
        <button type="submit" class="follow-btn">Follow</button>
    </form>
</div>
