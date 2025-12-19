<div class="button-<?= $user['user_pk'] ?>">
    <form
        action="/unfollow"
        method="POST"
        mix-post
        mix-target=".button-<?= $user['user_pk'] ?>"
        mix-swap="outerHTML"
    >
        <input type="hidden" name="following_fk" value="<?= $user['user_pk'] ?>">
        <button type="submit" class="unfollow-btn">Unfollow</button>
    </form>
</div>
