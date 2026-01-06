<!-- Wrapper bruges til mix-html replace -->
<div class="button-<?= $user['user_pk'] ?>">
    
    <!-- Follow-form (POST via mix-html) -->
    <form
        action="/follow"
        method="POST"
        mix-post
    >
        <!-- ID på den bruger der skal følges -->
        <input type="hidden" name="following_fk" value="<?= $user['user_pk'] ?>">

        <!-- Follow-knap -->
        <button type="submit" class="follow-btn">Follow</button>
    </form>
</div>
