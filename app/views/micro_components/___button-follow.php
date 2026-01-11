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

        <!-- CSRF-token til beskyttelse mod Cross-Site Request Forgery -->
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        
        <!-- Follow-knap -->
        <button type="submit" class="follow-btn">Follow</button>
    </form>
</div>
