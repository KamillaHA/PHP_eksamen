<?php 
session_start();

require_once __DIR__."/../private/x.php";

$user = $_SESSION["user"];

if (!$user) {
    header("Location: /login?message=not logged in, please login first");
    exit;
}

try {
    $postMessage = _validatePost();
    $postImage = "https://picsum.photos/400/250";

    $postPk = bin2hex(random_bytes(25));

    require_once __DIR__."/../private/db.php";
    $sql = "INSERT INTO posts (post_pk, post_message, post_image_path, post_user_fk) Values (:post_pk, :post_message, :post_image_path, :post_user_fk)";

    $stmt = $_db->prepare( $sql );

    $stmt->bindValue(":post_pk", $postPk);
    $stmt->bindValue(":post_message", $postMessage);
    $stmt->bindValue(":post_image_path", $postImage);
    $stmt->bindValue("post_user_fk", $user["user_pk"]);

    $stmt->execute();

    $message = "Post created!";
    $toast_ok = "<div class='toast-ok' mix-ttl='3000'>$message</div>";
    
    $freshForm = "<form action='/api-create-post.php' method='POST' mix-post>".
                 "<textarea type='text' maxlength='300' name='post_message' placeholder='Your post message here'></textarea>".
                 "<button>POST</button>".
                 "</form>";
    
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Post Created</title>
    </head>
    <body>
        $toast_ok
        $freshForm
    </body>
    </html>";
}
catch(Exception $e){
    http_response_code($e->getCode() ?: 400);
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Error</title>
    </head>
    <body>
        <div id='toast'>".$e->getMessage()."</div>
    </body>
    </html>";
}