<?php
// Sikkerhed: sidebar kræver login
if (!isset($_SESSION['user'])) return;

// Data kommer fra controller:
// - $followSuggestions
// - $current_user_id

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

<aside class="sidebar" mix-root>

    <!-- Trends -->
    <div class="trends-section">
        <h3 class="trends-title">What's trending</h3>

        <div class="trends-list">
            <?php foreach ($trends as $trend): ?>
                <div class="trend-item">
                    <div class="trend-category">
                        <?= htmlspecialchars($trend['category']) ?>
                    </div>
                    <div class="trend-name">
                        <?= htmlspecialchars($trend['name']) ?>
                    </div>
                    <div class="trend-stats">
                        <?= htmlspecialchars($trend['stats']) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- 👥 Who to follow -->
    <div class="follow-section">
        <h3 class="follow-title">Who to follow</h3>

<div class="follow-list">
    <?php foreach ($followSuggestions as $user): ?>
        <div class="follow-item">

            <!-- Avatar -->
            <div class="follow-avatar">
                <div class="avatar-circle">
                    <?= strtoupper(substr($user['user_username'], 0, 1)) ?>
                </div>
            </div>

            <!-- User info -->
            <div class="follow-user-info">
                <div class="follow-name">
                    <?= htmlspecialchars($user['user_full_name']) ?>
                </div>
                <div class="follow-handle">
                    @<?= htmlspecialchars($user['user_username']) ?>
                </div>
            </div>

            <!-- Button -->
            <div class="follow-action">
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
    </div>

</aside>
