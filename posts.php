<?php
session_start();
require_once __DIR__."/_/_header.php";
require_once __DIR__."/private/x.php";

require_once __DIR__ . "/private/db.php";

$current_user_id = $_SESSION['user']['user_pk'] ?? null;

try {

    $sql = "SELECT posts.*, users.user_username FROM posts INNER JOIN users ON posts.post_user_fk = users.user_pk";
    $stmt = $_db->prepare($sql);
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    echo "<div>Error: Could not fetch posts.</div>";
    $posts = [];
}

?>

<main>
    <h1>All Posts</h1>

    
    <div class="posts-container">
        
        <?php
        if (empty($posts)):
        ?>
            <p>No posts found.</p>
        <?php
        else:
            foreach ($posts as $post):
            ?>
            <article class="post">
                
                <div>
                <?php 
                if (!empty($post['post_image_path'])): 
                ?>
                    <div class="post-image">
                        <img src="<?php echo _($post['post_image_path']); ?>" 
                            alt="Image for <?php echo _($post['post_message']); ?>">
                    </div>
                <?php 
                endif; 
                ?>
                    <strong><?php echo _($post['user_username']); ?></strong>
                </div>
                <p><?php echo _($post['post_message']); ?></p>

                <?php if ($current_user_id == $post['post_user_fk']): ?>

                    <form action="/api/api-delete-post.php" method="POST">
                        <input type="hidden" name="post_pk" value="<?php echo $post['post_pk']; ?>">
                        <button type="submit">Delete</button>
                    </form>

                <?php endif; ?>
                <hr> 
                
            </article>

        <?php
            endforeach;
        endif;
        ?>

    </div>
</main>

<?php
require_once __DIR__."/_/_footer.php";
?>