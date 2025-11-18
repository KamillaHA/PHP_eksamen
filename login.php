<?php
require_once __DIR__."/_/_header.php";

$message = $_GET['message'] ?? '';
?>

<?php if($message): ?>
    <h1><?php echo htmlspecialchars($message) ?></h1>
<?php endif; ?>

<form action="api/api-login" method="POST">

    <h1>Login</h1>

    <input name="user_email" type="email" value="" placeholder="email" value="a@a.com">
    <input name="user_password" type="password" value="" placeholder="password" value="password">

    <button>
        Login
    </button>

</form>

<?php
require_once __DIR__."/_/_footer.php";
?>