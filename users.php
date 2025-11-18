<?php
session_start();
require_once __DIR__ . '/_/_header.php';
require_once __DIR__ . '/private/x.php';
$user = $_SESSION["user"];

if (!$user) {
    header("Location: /login?message=User not found, please login first");
    exit;
}

?>

<h1>
    Users
</h1>

<?php
try {
    require_once __DIR__ . '/private/db.php';
    $sql = "SELECT * FROM users";
    $stmt = $_db->prepare($sql);
    $stmt->execute();
    $users = $stmt->fetchall();
    foreach($users as $user){
        if($_SESSION["user"]["user_pk"] != $user["user_pk"]){
            $followee_pk = $user["user_pk"];
        ?>
            <div class="user" style="margin-top: 1rem;">
                <div><?php echo $user["user_pk"] ?></div>
                <div><?php echo $user["user_username"] ?></div>
                <div><?php echo $user["user_full_name"] ?></div>


            <div class="button-<?php echo $followee_pk; ?>">
                <button class="follow-btn" 
                    mix-get="api/api-follow?followee_pk=<?php echo $followee_pk; ?>">
                    Follow
                </button>
            </div>

            
            </div>
        <?php
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>



<?php require_once __DIR__ . '/_/_footer.php'; ?>