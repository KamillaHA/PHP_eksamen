<!-- <?php
// session_start();
// require_once __DIR__ . '/private/x.php';


// if (!isset($_SESSION["user"])) {
//     header("Location: /login?message=not logged in, please login first");
//     exit;
// }
// $user = $_SESSION["user"];


// require_once __DIR__ . '/_/_header.php';
// ?>

<h1>
    Please enter your post <?php echo " - " . $user["user_username"] ?>
</h1>

<div id="create-post-form">
    <form action="api/api-create-post.php" mix-post>
        <textarea type="text" maxlength="300" name="post_message" placeholder="Your post message here"></textarea>
        <button>POST</button>
    </form>
    
    <div id="toast"></div>
    <div id="message"></div>
    
    <script src="js/mixhtml.js"></script>
</div> -->