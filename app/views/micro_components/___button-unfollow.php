<!-- Wrapper bruges til mix-html replace -->
<div class="button-<?= $user['user_pk'] ?>">

    <!-- Unfollow-form (POST via mix-html) -->
    <form
        action="/unfollow"
        method="POST"
        mix-post
    >
        <!-- ID på den bruger der skal unfollowes -->
        <input type="hidden" name="following_fk" value="<?= $user['user_pk'] ?>">

        <!-- Unfollow-knap -->
        <button type="submit" class="unfollow-btn">Unfollow</button>
    </form>
</div>
