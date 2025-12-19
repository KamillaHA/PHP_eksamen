<?php
// session_start();
// require_once __DIR__ . '/components/_header.php';
// require_once __DIR__ . '/private/x.php';
// require_once __DIR__ . '/private/check-follow.php';
// $user = $_SESSION["user"];

// if (!$user) {
//     header("Location: /login?message=User not found, please login first");
//     exit;
// }
?>
<!-- <main>
<h1>Users</h1> -->

<?php
// try {
//     require_once __DIR__ . '/private/db.php';
//     $sql = "SELECT * FROM users";
//     $stmt = $_db->prepare($sql);
//     $stmt->execute();
//     $users = $stmt->fetchall();
    
//     foreach($users as $user){
//         if($_SESSION["user"]["user_pk"] != $user["user_pk"]){
//             $user_pk = $user["user_pk"];
//             $isFollowing = isFollowing($_SESSION["user"]["user_pk"], $user_pk); 
//             ?>
//             <div class="user" style="margin-top: 1rem;">
//                 <div><?= $user["user_pk"] ?></div>
//                 <div><?= $user["user_username"] ?></div>
//                 <div><?= $user["user_full_name"] ?></div>

//                 <div class="button-<?= $user_pk ?>">
//                     <?php if ($isFollowing): ?>
//                         <?php require __DIR__ . '/___/___button-unfollow.php'; ?>
//                     <?php else: ?>
//                         <?php require __DIR__ . '/___/___button-follow.php'; ?>
//                     <?php endif; ?>
//                 </div>
//             </div>
//             </main>
//             <?php
//         }
//     }
// } catch (Exception $e) {
//     echo "Error: " . $e->getMessage();
// }
?>

<?php 
// require_once __DIR__."/popups/_popup-create-post.php";
// require_once __DIR__ . '/components/_footer.php'; 
?>