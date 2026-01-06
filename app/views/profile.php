<?php
require_once __DIR__."/components/_header.php";
require __DIR__ . '/components/_sidebar.php';

$user = $_SESSION["user"];
$current_user_id = $user['user_pk'];
?>


<main id="main">

    <div id="toast"></div>

    <?php
        require __DIR__ . '/components/_profile-header.php';
        require __DIR__ . '/components/_post.php';
    ?>

</main>

<?php require __DIR__."/components/_footer.php"; ?>