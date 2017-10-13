<?php
// Notifications module 0.4.4

add_shortcode('notifications', 'imagepress_notifications');

function notification_count($count = 50) {
    global $wpdb;
    $user_ID = get_current_user_id();
    $n = 0;

    $sql = "SELECT * FROM " . $wpdb->prefix . "notifications WHERE status = 0 ORDER BY actionTime DESC LIMIT " . $count . "";
    $res = $wpdb->get_results($sql);
    foreach($res as $line) {
        $postdata = get_post($line->postID, ARRAY_A);
        $authorID = $postdata['post_author'];
        $action = $line->actionType;

        $following = array(pwuf_get_following($user_ID));

        if(
            ($action == 'loved' && $user_ID == $authorID) || 
            ($action == 'collected' && $user_ID == $authorID) || 
            ($action == 'added' && pwuf_is_following($user_ID, $authorID)) ||
            ($action == 'followed' && $user_ID == $line->postID) || 
            ($action == 'commented on' && $user_ID == $authorID && $user_ID != $line->userID) || 
            ($action == 'replied to a comment on' && $user_ID == get_comment($line->postID)->user_id) || 
            ($action == 'featured' && $user_ID == $authorID) || 
            (0 == $line->postID || '-1' == $line->postID || $user_ID == $line->postID)
        ) {
            ++$n;
        }
    }

    return $n;
}

function notification_reset() {
    global $wpdb;
    $user_ID = get_current_user_id();

    $sql = "SELECT * FROM " . $wpdb->prefix . "notifications WHERE status = 0";
    $res = $wpdb->get_results($sql);
    foreach($res as $line) {
        $postdata = get_post($line->postID, ARRAY_A);
        $authorID = $postdata['post_author'];
        $action = $line->actionType;

        $following = array(pwuf_get_following($user_ID));

        if(
            ($action == 'loved' && $user_ID == $authorID) || 
            ($action == 'collected' && $user_ID == $authorID) || 
            ($action == 'added' && pwuf_is_following($user_ID, $authorID)) ||
            ($action == 'followed' && $user_ID == $line->postID) || 
            ($action == 'commented on' && $user_ID == $authorID && $user_ID != $line->userID) || 
            ($action == 'replied to a comment on' && $user_ID == get_comment($line->postID)->user_id) || 
            ($action == 'featured' && $user_ID == $authorID) || 
            (0 == $line->postID || '-1' == $line->postID || $user_ID == $line->postID)
        ) {
            $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "notifications SET status = 1 WHERE ID = %d", $line->ID));
        }
    }

	echo 'success';
	die();
}

function imagepress_notifications($atts, $content = null) {
    extract(shortcode_atts(array(
        'count' => 50
    ), $atts));

    global $wpdb;

    $user_ID = get_current_user_id();
    $following = array(pwuf_get_followers($user_ID));

    $ip_slug = get_imagepress_option('ip_slug');
    $display = '';

    $display .= '<div class="notifications-title">
		Notifications
		<a href="#" class="ip_notification_mark" data-userid="' . $user_ID . '">' . get_imagepress_option('ip_notifications_mark') . '</a>
	</div>';
    $display .= '<div class="notifications-inner" id="c">';

    $sql = "SELECT * FROM " . $wpdb->prefix . "notifications ORDER BY actionTime DESC LIMIT " . $count . "";

    $res = $wpdb->get_results($sql);

    foreach($res as $line) {
        $action = $line->actionType;
        $nickname = get_the_author_meta('nickname', $line->userID);
        $time = human_time_diff(strtotime($line->actionTime), current_time('timestamp')) . ' ago';

        $postdata = get_post($line->postID, ARRAY_A);
        $authorID = $postdata['post_author'];

        if($line->status == 0)
            $class = 'unread';
        if($line->status == 1)
            $class = 'read';

        if($action == 'loved' && $user_ID == $authorID)
            $display .= '<div class="notification-item n' . $line->ID . ' ' . $class . '" data-id="' . $line->ID . '"><div class="navatar">' . get_avatar($line->userID, 48) . '</div><i class="fa fa-fw fa-heart"></i> <a href="' . get_author_posts_url($line->userID) . '">' . $nickname . '</a> ' . $action . ' your ' . $ip_slug . ' <a href="' . get_permalink($line->postID) . '">' . get_the_title($line->postID) . '</a><time>' . $time . '</time></div>';

        if($action == 'collected' && $user_ID == $authorID)
            $display .= '<div class="notification-item n' . $line->ID . ' ' . $class . '" data-id="' . $line->ID . '"><div class="navatar">' . get_avatar($line->userID, 48) . '</div><i class="fa fa-fw fa-folder"></i> <a href="' . get_author_posts_url($line->userID) . '">' . $nickname . '</a> ' . $action . ' your ' . $ip_slug . ' <a href="' . get_permalink($line->postID) . '">' . get_the_title($line->postID) . '</a><time>' . $time . '</time></div>';

		if($action == 'added' && pwuf_is_following($user_ID, $authorID))
            $display .= '<div class="notification-item n' . $line->ID . ' ' . $class . '" data-id="' . $line->ID . '"><div class="navatar">' . get_avatar($line->userID, 48) . '</div><i class="fa fa-fw fa-arrow-circle-up"></i> <a href="' . get_author_posts_url($line->userID) . '">' . $nickname . '</a> ' . $action . ' <a href="' . get_permalink($line->postID) . '">' . get_the_title($line->postID) . '</a><time>' . $time . '</time></div>';

        if($action == 'followed' && $user_ID == $line->postID)
            $display .= '<div class="notification-item n' . $line->ID . ' ' . $class . '" data-id="' . $line->ID . '"><div class="navatar">' . get_avatar($line->userID, 48) . '</div><i class="fa fa-fw fa-plus-circle"></i> <a href="' . get_author_posts_url($line->userID) . '">' . $nickname . '</a> ' . $line->actionType . ' you<time>' . $time . '</time></div>';

        if($action == 'commented on' && $user_ID == $authorID && $user_ID != $line->userID)
            $display .= '<div class="notification-item n' . $line->ID . ' ' . $class . '" data-id="' . $line->ID . '"><div class="navatar">' . get_avatar($line->userID, 48) . '</div><i class="fa fa-fw fa-comment"></i> <a href="' . get_author_posts_url($line->userID) . '">' . $nickname . '</a> ' . $action . ' your ' . $ip_slug . ' <a href="' . get_permalink($line->postID) . '">' . get_the_title($line->postID) . '</a><time>' . $time . '</time></div>';

        if($action == 'replied to a comment on') {
            $comment_id = get_comment($line->postID);
            $comment_post_ID = $comment_id->comment_post_ID;
            $b = $comment_id->user_id;

            if($user_ID == $b)
                $display .= '<div class="notification-item n' . $line->ID . ' ' . $class . '" data-id="' . $line->ID . '"><div class="navatar">' . get_avatar($line->userID, 48) . '</div><i class="fa fa-fw fa-comment"></i> <a href="' . get_author_posts_url($line->userID) . '">' . $nickname . '</a> replied to your comment on <a href="' . get_permalink($comment_post_ID) . '">' . get_the_title($comment_post_ID) . '</a><time>' . $time . '</time></div>';
        }

        if($action == 'featured' && $user_ID == $authorID && $user_ID != $line->userID)
            $display .= '<div class="notification-item n' . $line->ID . ' ' . $class . '" data-id="' . $line->ID . '"><div class="navatar">' . get_the_post_thumbnail($line->postID, array(48,48)) . '</div><i class="fa fa-fw fa-star"></i> Your <a href="' . get_permalink($line->postID) . '">' . get_the_title($line->postID) . '</a> ' . $ip_slug . ' was ' . $action . '<time>' . $time . '</time></div>';

        // custom
        if(0 == $line->postID || '-1' == $line->postID) {
            $display .= '<div class="notification-item n' . $line->ID . ' ' . $class . '" data-id="' . $line->ID . '"><i class="fa fa-fw ' . $line->actionIcon . '"></i> ' . $line->actionType . '<time>' . $time . '</time></div>';
        }
    }

	$display .= '</div>';
	$display .= '<div class="nall"><a href="' . home_url() . '/notifications/"><i class="fa fa-th-list"></i> ' . get_imagepress_option('ip_notifications_all') . '</a></div>';
    return $display;
}

$ip_slug = get_imagepress_option('ip_slug');

//add_action('post_updated', 'imagepress_post_update');
//add_action('publish_' . $ip_slug, 'imagepress_post_add');
add_action('new_to_publish', 'imagepress_post_add');
//add_action('draft_to_publish', 'imagepress_post_add');
add_action('comment_post', 'imagepress_comment_add');

function imagepress_post_add($act_post) {
    global $wpdb, $user_ID;

	// there's also this: if ($post->post_status != "publish") return;
	// http://wordpress.stackexchange.com/questions/63976/do-new-to-publish-hooks-work-for-custom-post-types
	if(!wp_is_post_revision($act_post)) {
		$ip_slug = get_imagepress_option('ip_slug');

		if(get_query_var('post_type') == $ip_slug && is_numeric($act_post)) {
			$act_time = current_time('mysql', true);
			$wpdb->query("INSERT INTO " . $wpdb->prefix . "notifications (ID, userID, postID, actionType, actionTime) VALUES (null, $user_ID, " . $act_post . ", 'added', '" . $act_time . "')");
		}
	}
}
// function for image add
function imagepress_post_add_custom($post, $author) {
    global $wpdb;

    $act_time = current_time('mysql', true);
    $wpdb->query("INSERT INTO " . $wpdb->prefix . "notifications (ID, userID, postID, actionType, actionTime) VALUES (null, $author, " . $post . ", 'added', '" . $act_time . "')");
}
function imagepress_comment_add($act_comment) {
    global $wpdb, $user_ID;

    $comment_id = get_comment($act_comment);
    $comment_post_ID = $comment_id->comment_post_ID;
    $comment_parent = $comment_id->comment_parent;

    $act_time = current_time('mysql', true);

    if(empty($comment_parent))
        $wpdb->query("INSERT INTO " . $wpdb->prefix . "notifications (ID, userID, postID, actionType, actionTime) VALUES (null, $user_ID, " . $comment_post_ID . ", 'commented on', '" . $act_time . "')");
    else
        $wpdb->query("INSERT INTO " . $wpdb->prefix . "notifications (ID, userID, postID, actionType, actionTime) VALUES (null, $user_ID, " . $comment_parent . ", 'replied to a comment on', '" . $act_time . "')");
}



add_action('wp_ajax_notification_read', 'notification_read');
function notification_read() {
    global $wpdb;
    $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "notifications SET status = 1 WHERE ID = %d LIMIT 1", $_REQUEST['id']));
        echo 'success';
    die();
}
add_action('wp_ajax_notification_read_all', 'notification_reset');

function ajax_trash_action_callback() {
    global $wpdb;
    $odvm_post = $_POST['odvm_post'];

    $wpdb->query($wpdb->prepare("DELETE FROM " . $wpdb->prefix . "notifications WHERE ID = %d LIMIT 1", $odvm_post));

    echo 'Notification deleted successfully!';

    exit();
}
add_action('wp_ajax_ajax_trash_action', 'ajax_trash_action_callback');



/*
 * Helper functions
 *
 * Use these functions inside themes to display various notification-related information
 */
function ip_notifications_menu_item($count = 50) {
	if (notification_count($count) > 0)
		$item = '<a href="#" class="notifications-bell"><i class="fa fa-bell"></i><sup class="ui-accent-background">' . notification_count($count) . '</sup></a><div class="notifications-container ui">' . do_shortcode('[notifications]') . '</div>';
	else
		$item = '<a href="#" class="notifications-bell"><i class="fa fa-bell-o"></i></a><div class="notifications-container ui">' . do_shortcode('[notifications count="' . $count . '"]') . '</div>';

	return $item;
}
