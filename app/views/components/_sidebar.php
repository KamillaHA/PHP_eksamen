<?php
// Sidebar må kun vises for loggede brugere
if (!isset($_SESSION['user'])) return;

// Data leveres af controller:
// - $followSuggestions
// - $current_user_id

// Midlertidige hardcodede trends (UI-demo)
$trends = [
    [
        'category' => 'Trending in Technology',
        'name' => 'WebDevelopment',
        'stats' => '121 K posts'
    ],
    [
        'category' => 'Trending in Technology',
        'name' => 'PHP',
        'stats' => '8.7 K posts'
    ],
    [
        'category' => 'Trending in Denmark',
        'name' => 'AI',
        'stats' => '2.3 K posts'
    ],
    [
        'category' => 'Trending in Copenhagen',
        'name' => 'Erhvervsakademi København',
        'stats' => '1.2 K posts'
    ],
    [
        'category' => 'Trending in Copenhagen',
        'name' => 'Eksamensprojekt',
        'stats' => '4.9 K posts'
    ]
];
?>

<aside class="sidebar">

    <!-- Trending sektion -->
    <div class="trends-section">
        <h3 class="trends-title">What's trending</h3>

        <div class="trends-list">
            <?php foreach ($trends as $trend): ?>
                <div class="trend-item">

                    <!-- Trend kategori -->
                    <div class="trend-category">
                        <?= htmlspecialchars($trend['category']) ?>
                    </div>

                    <!-- Trend navn -->
                    <div class="trend-name">
                        <?= htmlspecialchars($trend['name']) ?>
                    </div>

                    <!-- Trend statistik -->
                    <div class="trend-stats">
                        <?= htmlspecialchars($trend['stats']) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <<!-- Who to follow sektion -->
    <div class="follow-section">
        <h3 class="follow-title">Who to follow</h3>

        <div  iv class="follow-list">

            <?php foreach ($followSuggestions as $user): ?>

            <div class="follow-item">

                <!-- Avatar med første bogstav -->
                <div class="follow-avatar">
                    <div class="avatar-circle">
                        <?= strtoupper(substr($user['user_username'], 0, 1)) ?>
                    </div>
                </div>

                <!-- Brugerinfo -->
                <div class="follow-user-info">
                    <div class="follow-name">
                        <?= htmlspecialchars($user['user_full_name']) ?>
                    </div>
                    <div class="follow-handle">
                        @<?= htmlspecialchars($user['user_username']) ?>
                    </div>
                </div>

                <!-- Follow / Unfollow knap -->
                <div class="follow-action">
                        <?php $followUserPk = $user['user_pk']; ?>
                    <?php if (!empty($user['isFollowing'])): ?>
                        <?php require __DIR__ . '/../micro_components/___button-unfollow.php'; ?>
                    <?php else: ?>
                        <?php require __DIR__ . '/../micro_components/___button-follow.php'; ?>
                    <?php endif; ?>
                </div>
            </div>

            <?php endforeach; ?>

        </div>
    </div>
</aside>
