<?php
require_once __DIR__ . "/../models/PostModel.php";
require_once __DIR__ . "/../models/CommentModel.php";
require_once __DIR__ . "/../models/LikeModel.php";
require_once __DIR__ . "/../models/FollowModel.php";


class PostController
{
    public static function index(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header("Location: /");
            exit;
        }

        $current_user_id = $_SESSION['user']['user_pk'];

        // 1️⃣ Hent alle posts (med user info)
        $posts = PostModel::getAll();

        // 2️⃣ Berig hvert post med comments + likes
        foreach ($posts as &$post) {
            $postPk = $post['post_pk'];

            // Comments
            $post['comments'] = CommentModel::findByPost($postPk);
            $post['commentCount'] = CommentModel::countByPost($postPk);

            // Likes
            $post['likeCount'] = LikeModel::countByPost($postPk);
            $post['userLiked'] = LikeModel::exists($current_user_id, $postPk);
        }
        unset($post); // vigtigt ved reference-loop

        $followSuggestions = FollowModel::suggestions($current_user_id);

            foreach ($followSuggestions as &$user) {
            $user['isFollowing'] = FollowModel::isFollowing(
            $current_user_id,
            $user['user_pk']
        );
        }
        unset($user);

        // 3️⃣ Send data til view
        require __DIR__ . '/../views/home.php';
        exit;
    }

    public static function create(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        require_once __DIR__ . "/../../private/x.php";

        PostModel::create([
            ":pk"      => bin2hex(random_bytes(25)),
            ":message" => _validatePost(),
            ":image"   => "https://picsum.photos/400/250",
            ":user"    => $_SESSION["user"]["user_pk"]
        ]);

        echo '<mixhtml mix-redirect="/home"></mixhtml>';
        exit;
    }

    public static function update(): void
    {
        require_once __DIR__ . "/../../private/x.php";

        PostModel::update(
            _validatePk("post_pk"),
            _validatePost()
        );

        echo '<mixhtml mix-redirect="/home"></mixhtml>';
        exit;
    }

    public static function delete(): void
    {
        require_once __DIR__ . "/../../private/x.php";

        PostModel::delete(_validatePk("post_pk"));

        echo '<mixhtml mix-redirect="/home"></mixhtml>';
        exit;
    }
}
