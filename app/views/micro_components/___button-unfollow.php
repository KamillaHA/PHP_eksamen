<div class="button-<?= $user['user_pk'] ?>">
    <form
        action="/unfollow"
        method="POST"
        mix-post
    >
        <input type="hidden" name="following_fk" value="<?= $user['user_pk'] ?>">
        <button type="submit" class="unfollow-btn">Unfollow</button>
    </form>
</div>
