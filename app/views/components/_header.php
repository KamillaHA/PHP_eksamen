<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../../private/x.php';

// Kald _noCache() på ALLE sider der har med auth at gøre
$current_page = basename($_SERVER['PHP_SELF']);
$auth_pages = ['home.php', 'profile.php', 'logout.php']; // Tilføj dine auth-relaterede sider

if (isset($_SESSION['user_id']) || in_array($current_page, $auth_pages)) {
    if (function_exists('_noCache')) {
        _noCache();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="/public/assets/css/style.css">
    <script defer src="/public/assets/js/popup.js"></script>
    <title>Document</title>
</head>
<body class="<?= $body_class ?? '' ?>">
    
    
    <?php if (isset($_SESSION['user'])): ?>
    <button class="burger" aria-label="Menu">
            <i class="fa-solid fa-bars"></i>
            <i class="fa-solid fa-xmark"></i>
    </button>
        <nav>
            <ul>
                <li><a href="/home">
                    <svg viewBox="0 0 24 24" width="24" height="24" style="fill: currentColor;">
                        <path d="M 21.742 21.75 l -7.563 -11.179 l 7.056 -8.321 h -2.456 l -5.691 6.714 l -4.54 -6.714 H 2.359 l 7.29 10.776 L 2.25 21.75 h 2.456 l 6.035 -7.118 l 4.818 7.118 h 6.191 h -0.008 Z M 7.739 3.818 L 18.81 20.182 h -2.447 L 5.29 3.818 h 2.447 Z"/>
                    </svg>
                </a></li>
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
        </nav>

    <!-- MOBILE TOP BAR -->
    <header class="mobile-topbar">
        <button class="burger avatar-burger" aria-label="Menu">
            <div class="avatar-circle">
                <?= strtoupper(substr($_SESSION['user']['user_username'], 0, 1)); ?>
            </div>
        </button>
    </header>

    <!-- OVERLAY -->
    <div class="nav-overlay"></div>

    <!-- SIDEBAR NAV -->
    <nav>
        <ul>
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

        <button
            type="button"
            class="create-post-btn"
            id="createPostBtn"
            data-open="createPostModal"
        >
            Post
        </button>

        <div id="profile_tag">
            <div class="avatar-circle">
                <?= strtoupper(substr($_SESSION['user']['user_username'], 0, 1)); ?>
            </div>
            <div class="profile_handle">
                <div class="name">
                    <?= $_SESSION["user"]["user_full_name"]; ?>
                </div>
                <div class="handle">
                    @<?= $_SESSION["user"]["user_username"]; ?>
                </div>
            </div>
            
            <i class="fa-solid fa-ellipsis option"></i>
        </div>
    </nav>

<?php endif; ?>

<div id="toast"></div>