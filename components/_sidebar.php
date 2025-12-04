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

$followSuggestions = [
    [
        'img' => 'https://picsum.photos/400/250',
        'name' => 'Steve',
        'handle' => '@stevejobs'
    ],
    [
        'img' => 'https://picsum.photos/400/251',
        'name' => 'Grace', 
        'handle' => '@gracehopper'
    ],
    [
        'img' => 'https://picsum.photos/400/252',
        'name' => 'Rasmus',
        'handle' => '@rasmuslerdorf'
    ]
];
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
                <div class="follow-item">
                    <div class="follow-img">
                        <img src="<?php echo htmlspecialchars($user['img']); ?>" alt="Profile">
                    </div>
                    <div>
                        <div class="follow-name"><?php echo htmlspecialchars($user['name']); ?></div>
                        <div class="follow-handle"><?php echo htmlspecialchars($user['handle']); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</aside>