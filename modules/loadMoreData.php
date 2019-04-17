<?php
require '../../../../wp-load.php';

$user_ID = get_current_user_id();
$last_id = (int) trim($_GET['last_id']);

$myFollowing = [pwuf_get_following($user_ID)];

$followers = '';
if (!empty($myFollowing[0])) {
    $followers = implode(',', $myFollowing[0]);
    $followers = "AND userID IN (" . $followers . ")";
}

$res = $wpdb->get_results($wpdb->prepare("SELECT
    ID,
    userID,
    postID,
    postKeyID,
    actionType,
    actionTime,
    status
FROM {$wpdb->prefix}notifications
WHERE actionType = 'added' %s
    AND ID < %d
    ORDER BY ID DESC LIMIT 10", $followers, $last_id));

$json = include 'feed-data.php';
