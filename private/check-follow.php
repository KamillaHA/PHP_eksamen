<?php
function isFollowing($follower_pk, $followee_pk) {
    global $_db;
    $stmt = $_db->prepare("SELECT 1 FROM follows WHERE follower_fk = ? AND following_fk = ?");
    $stmt->execute([$follower_pk, $followee_pk]);
    return $stmt->fetch() !== false;
}
?>