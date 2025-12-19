<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/public/assets/css/style.css">
    <script defer src="/public/assets/js/popup.js"></script>
    <title>Document</title>
</head>
<body>
    
    <?php if (isset($_SESSION['user'])): ?>
    <button class="burger" aria-label="Menu">
            <i class="fa-solid fa-bars"></i>
            <i class="fa-solid fa-xmark"></i>
    </button>
        <nav>
            <ul>
                <li><a href="/home"><i class="fab fa-twitter">Logo</i></a></li>
                <li><a href="/home"><i class="fa-solid fa-house"></i><span>Forside</span></a></li>
                <li><a href="#"><i class="fa-solid fa-magnifying-glass"></i><span>Explore</span></a></li>
                <li><a href="#"><i class="fa-regular fa-bell"></i><span>Notifications</span></a></li>
                <li><a href="#"><i class="fa-regular fa-envelope"></i><span>Messages</span></a></li>
                <li><a href="#"><i class="fa-solid fa-atom"></i><span>Grok</span></a></li>
                <li><a href="#"><i class="fa-regular fa-bookmark"></i><span>Bookmarks</span></a></li>
                <li><a href="#"><i class="fa-solid fa-briefcase"></i><span>Jobs</span></a></li>
                <li><a href="#"><i class="fa-solid fa-users"></i><span>Communities</span></a></li>
                <li><a href="#"><i class="fa-solid fa-star"></i><span>Premium</span></a></li>
                <li><a href="#"><i class="fa-solid fa-bolt"></i><span>Verified Orgs</span></a></li>
                <li><a href="/profile"><i class="fa-regular fa-user"></i><span>Profile</span></a></li>
                <li><a href="#"><i class="fa-solid fa-ellipsis"></i><span>More</span></a></li>
                <li><a href="/logout"><i class="fa-solid fa-right-from-bracket"></i><span>Logout</span></a></li>
            </ul>
    <button type="button" class="create-post-btn" id="createPostBtn" data-open="createPostModal">Post</button>

                <div id="profile_tag">
                    <img src="https://picsum.photos/400/250" alt="Profile">
                    <div>
                        <div class="name">
                            <?php echo $_SESSION["user"]["user_full_name"]; ?>
                        </div>
                        <div class="handle">
                            <?php echo "@".$_SESSION["user"]["user_username"]; ?>
                        </div>
                    </div>
                    <i class="fa-solid fa-ellipsis option"></i>
                </div>
        </nav>
<?php endif; ?>

<?php if (isset($_SESSION['user'])): ?>
    <?php require_once __DIR__."/../popups/_popup-create-post.php"; ?>
<?php endif; ?>

<div id="toast"></div>