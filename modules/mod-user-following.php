<?php
// Processes the ajax request to follow a user
function pwuf_process_new_follow() {
	if(isset($_POST['user_id']) && isset($_POST['follow_id'])) {
		if(pwuf_follow_user(absint($_POST['user_id']), absint($_POST['follow_id']))) {
			echo 'success';
		} else {
			echo 'failed';
		}
	}
	die();
}
add_action('wp_ajax_follow', 'pwuf_process_new_follow');

// Processes the ajax request to unfollow a user
function pwuf_process_unfollow() {
	if(isset($_POST['user_id']) && isset($_POST['follow_id'])) {
		if(pwuf_unfollow_user(absint($_POST['user_id']), absint($_POST['follow_id']))) {
			echo 'success';
		} else {
			echo 'failed';
		}
	}
	die();
}
add_action('wp_ajax_unfollow', 'pwuf_process_unfollow');

// Shows the links to follow/unfollow a user
function pwuf_follow_links_shortcode($atts) {
	extract( shortcode_atts( array(
			'follow_id' => get_the_author_meta( 'ID' )
		),
		$atts, 'follow_links' )
	);

	return pwuf_get_follow_unfollow_links( $follow_id );
}
add_shortcode( 'follow_links', 'pwuf_follow_links_shortcode' );

// Shows the posts from users that the current user follows
function pwuf_following_posts_shortcode($atts, $content = null) {
	$following = pwuf_get_following();

	if(empty($following))
		return;

	$items = new WP_Query(array(
		'post_type'      => 'any',
		'posts_per_page' => 16,
		'author__in'     => pwuf_get_following()
	));

	ob_start(); ?>
	<?php if($items->have_posts()) : ?>
		<?php while($items->have_posts()) : $items->the_post(); ?>
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="cinnamon-followed"><?php the_post_thumbnail('thumbnail'); ?>
		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>
	<?php endif; ?>
	<?php
	return ob_get_clean();
}
add_shortcode('following_posts', 'pwuf_following_posts_shortcode');

// Retrieves all users that the specified user follows
function pwuf_get_following( $user_id = 0 ) {
	if ( empty( $user_id ) ) {
		$user_id = get_current_user_id();
	}

	$following = get_user_meta( $user_id, '_pwuf_following', true );

	return apply_filters( 'pwuf_get_following', $following, $user_id );
}

/**
 * Retrieves users that follow a specified user
 *
 * Gets all users following $user_id
 */
function pwuf_get_followers( $user_id = 0 ) {
	if ( empty( $user_id ) ) {
		$user_id = get_current_user_id();
	}

	$followers = get_user_meta( $user_id, '_pwuf_followers', true );

	return apply_filters( 'pwuf_get_followers', $followers, $user_id );

}

/**
 * Follow a user
 *
 * Makes a user follow another user
 */
function pwuf_follow_user( $user_id = 0, $user_to_follow = 0 ) {
	// retrieve the IDs of all users who $user_id follows
	$following = pwuf_get_following( $user_id );

	if ( ! empty( $following ) && is_array( $following ) ) {
		$following[] = $user_to_follow;
	} else {
		$following = array();
		$following[] = $user_to_follow;
	}

	// retrieve the IDs of all users who are following $user_to_follow
	$followers = pwuf_get_followers( $user_to_follow );

	if ( ! empty( $followers ) && is_array( $followers ) ) {
		$followers[] = $user_id;
	} else {
		$followers = array();
		$followers[] = $user_id;
	}

	do_action( 'pwuf_pre_follow_user', $user_id, $user_to_follow );

	// update the IDs that this user is following
	$followed = update_user_meta( $user_id, '_pwuf_following', $following );

	// update the IDs that follow $user_to_follow
	$followers = update_user_meta( $user_to_follow, '_pwuf_followers', $followers );

	// increase the followers count
	$followed_count = pwuf_increase_followed_by_count($user_to_follow);

	if ( $followed ) {
        // notification
        global $wpdb;
        $act_time = current_time('mysql', true);
        $wpdb->query("INSERT INTO " . $wpdb->prefix . "notifications (ID, userID, postID, actionType, actionTime) VALUES (null, $user_id, $user_to_follow, 'followed', '$act_time')");
        //

		do_action( 'pwuf_post_follow_user', $user_id, $user_to_follow );

		return true;
	}
	return false;
}

/**
 * Unfollow a user
 *
 * Makes a user unfollow another user
 */
function pwuf_unfollow_user( $user_id = 0, $unfollow_user = 0 ) {
	do_action( 'pwuf_pre_unfollow_user', $user_id, $unfollow_user );

	// get all IDs that $user_id follows
	$following = pwuf_get_following( $user_id );

	if ( is_array( $following ) && in_array( $unfollow_user, $following ) ) {

		$modified = false;

		foreach ( $following as $key => $follow ) {
			if ( $follow == $unfollow_user ) {
				unset( $following[$key] );
				$modified = true;
			}
		}

		if ( $modified ) {
			if ( update_user_meta( $user_id, '_pwuf_following', $following ) ) {
				pwuf_decrease_followed_by_count( $unfollow_user );
			}
		}

	}

	// get all IDs that follow the user we have just unfollowed so that we can remove $user_id
	$followers = pwuf_get_followers( $unfollow_user );

	if ( is_array( $followers ) && in_array( $user_id, $followers ) ) {

		$modified = false;

		foreach ( $followers as $key => $follower ) {
			if ( $follower == $user_id ) {
				unset( $followers[$key] );
				$modified = true;
			}
		}

		if ( $modified ) {
			update_user_meta( $unfollow_user, '_pwuf_followers', $followers );
		}

	}

	if ( $modified ) {
		do_action( 'pwuf_post_unfollow_user', $user_id, $unfollow_user );
		return true;
	}

	return false;
}

/**
 * Retrieve following count
 *
 * Gets the total number of users that the specified user is following
 */
function pwuf_get_following_count( $user_id = 0 ) {
	if ( empty( $user_id ) ) {
		$user_id = get_current_user_id();
	}

	$following = pwuf_get_following( $user_id );

	$count = 0;

	if ( $following ) {
		$count = count( $following );
	}

	return apply_filters( 'pwuf_get_following_count', $count, $user_id );
}

/**
 * Retrieve follower count
 *
 * Gets the total number of users that are following the specified user
 */
function pwuf_get_follower_count( $user_id = 0 ) {
	if ( empty( $user_id ) ) {
		$user_id = get_current_user_id();
	}

	$followed_count = get_user_meta( $user_id, '_pwuf_followed_by_count', true );

	$count = 0;

	if ( $followed_count ) {
		$count = $followed_count;
	}

	return apply_filters( 'pwuf_get_follower_count', $count, $user_id );
}

/**
 * Increase follower count
 *
 * Increments the total count for how many users a specified user is followed by
 */
function pwuf_increase_followed_by_count( $user_id = 0 ) {
	do_action( 'pwuf_pre_increase_followed_count', $user_id );

	$followed_count = pwuf_get_follower_count( $user_id );

	if ( $followed_count !== false ) {

		$new_followed_count = update_user_meta( $user_id, '_pwuf_followed_by_count', $followed_count + 1 );

	} else {

		$new_followed_count = update_user_meta( $user_id, '_pwuf_followed_by_count', 1 );

	}

	do_action( 'pwuf_post_increase_followed_count', $user_id );

	return $new_followed_count;
}

/**
 * Decrease follower count
 *
 * Decrements the total count for how many users a specified user is followed by
 */
function pwuf_decrease_followed_by_count( $user_id = 0 ) {
	do_action( 'pwuf_pre_decrease_followed_count', $user_id );

	$followed_count = pwuf_get_follower_count( $user_id );

	if ( $followed_count ) {

		$count = update_user_meta( $user_id, '_pwuf_followed_by_count', ( $followed_count - 1 ) );

		do_action( 'pwuf_post_increase_followed_count', $user_id );

	}
	return $count;
}

/**
 * Check if a user is following another
 *
 * Increments the total count for how many users a specified user is followed by
 */
function pwuf_is_following( $user_id = 0, $followed_user = 0 ) {
	$following = pwuf_get_following( $user_id );
	$ret = false; // is not following by default
	if ( is_array( $following ) && in_array( $followed_user, $following ) ) {
		$ret = true; // is following
	}
	return (bool) apply_filters( 'pwuf_is_following', $ret, $user_id, $followed_user );

}



/**
 * Outputs the follow / unfollow links
 */
function pwuf_follow_unfollow_links( $follow_id = null ) {
	echo pwuf_get_follow_unfollow_links( $follow_id );
}

/**
 * Retrieves the follow / unfollow links
 */
function pwuf_get_follow_unfollow_links($follow_id = null) {
    global $user_ID;

    if (empty($follow_id))
        return;

    if (!is_user_logged_in())
        return;

    if ((int) $follow_id === (int) $user_ID)
        return;

    ob_start(); ?>
    <?php if (pwuf_is_following($user_ID, $follow_id)) { ?>
        <a href="#" class="unfollow followed thin-ui-button" data-user-id="<?php echo $user_ID; ?>" data-follow-id="<?php echo $follow_id; ?>"><i class="fa fa-minus"></i> <?php _e('Unfollow', 'imagepress'); ?></a>
		<a href="#" class="follow thin-ui-button" style="display: none;" data-user-id="<?php echo $user_ID; ?>" data-follow-id="<?php echo $follow_id; ?>"><i class="fa fa-plus"></i> <?php _e('Follow', 'imagepress'); ?></a>
	<?php } else { ?>
		<a href="#" class="follow thin-ui-button" data-user-id="<?php echo $user_ID; ?>" data-follow-id="<?php echo $follow_id; ?>"><i class="fa fa-plus"></i> <?php _e('Follow', 'imagepress'); ?></a>
		<a href="#" class="followed unfollow thin-ui-button" style="display: none;" data-user-id="<?php echo $user_ID; ?>" data-follow-id="<?php echo $follow_id; ?>"><i class="fa fa-minus"></i> <?php _e('Unfollow', 'imagepress'); ?></a>
	<?php } ?>
	<img src="<?php echo IP_PLUGIN_URL; ?>/img/loading.gif" class="pwuf-ajax" style="display: none;">
	<?php

    return ob_get_clean();
}
