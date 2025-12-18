<?php

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

require_once __DIR__ . '/../private/db.php';
$current_user_id = $_SESSION['user']['user_pk'] ?? null;

$sql = "
    SELECT user_pk, user_username
    FROM users
    WHERE user_pk != :current_user
    ORDER BY RAND()
    LIMIT 3
";

$stmt = $_db->prepare($sql);
$stmt->execute([
    ':current_user' => $current_user_id
]);

$followSuggestions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$followCheckStmt = $_db->prepare("
    SELECT 1
    FROM follows
    WHERE follower_fk = :current_user
    AND following_fk = :suggested_user
    LIMIT 1
");

?>
<aside class="sidebar">
    <!-- Trends sektion -->
    <div class="trends-section">
        <h3 class="trends-title">What's trending</h3>
        
        <div class="trends-list">
            <?php foreach ($trends as $trend): ?>
                <div class="trend-item">
                    <div class="trend-category"><?php echo htmlspecialchars($trend['category']); ?></div>
                    <div class="trend-name"><?php echo htmlspecialchars($trend['name']); ?></div>
                    <div class="trend-stats"><?php echo htmlspecialchars($trend['stats']); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Follow sektion -->
    <div class="follow-section">
        <h3 class="follow-title">Who to follow</h3>
        
        <div class="follow-list">
            <?php foreach ($followSuggestions as $user): ?>

                <?php
                    $followCheckStmt->execute([
                        ':current_user' => $current_user_id,
                        ':suggested_user' => $user['user_pk']
                    ]);
                    $isFollowing = $followCheckStmt->fetchColumn();
                ?>
                
                <div class="follow-item">

                <!-- Avatar circle -->
                <div class="follow-avatar">
                    <div class="avatar-circle">
                        <?php echo strtoupper(substr($user['user_username'], 0, 1)); ?>
                    </div>
                </div>

                <!-- Bruger info -->
                <div class="follow-user-info">
                    <div class="follow-name">
                        <?php echo htmlspecialchars($user['user_username']); ?>
                    </div>
                    <div class="follow-handle">
                        @<?php echo htmlspecialchars($user['user_username']); ?>
                    </div>
                </div>

                <!-- Follow-knap -->
                <?php if ($isFollowing): ?>
                    <?php require __DIR__ . '/../micro_components/___button-unfollow.php'; ?>
                <?php else: ?>
                    <?php require __DIR__ . '/../micro_components/___button-follow.php'; ?>
                <?php endif; ?>
                
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</aside>