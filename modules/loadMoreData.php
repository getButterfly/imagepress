<?php
require '../../../../wp-load.php';

$user_ID = get_current_user_id();
$last_id = (int) trim($_GET['last_id']);

$myFollowing = array(pwuf_get_following($user_ID));

$followers = '';
if (!empty($myFollowing[0])) {
    $followers = implode(',', $myFollowing[0]);
    $followers = "AND userID IN (" . $followers . ")";
}

$sql = "SELECT ID, userID, postID, postKeyID, actionType, actionTime, actionIcon, status FROM {$wpdb->prefix}notifications WHERE actionType = 'added' %s AND ID < %d ORDER BY ID DESC LIMIT 10";

$res = $wpdb->get_results($wpdb->prepare($sql, $followers, $_GET['last_id']));

$json = include 'feed-data.php';
