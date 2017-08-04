<?php
function imagepress_quota($atts, $content = null) {
    extract(shortcode_atts(array(
    ), $atts));

    global $current_user;

    $user_id = $current_user->ID;

    $quota = get_the_author_meta('ip_upload_limit', $user_id);
    if (isset($quota) && !empty($quota)) {
        $limit = $quota;
    } else if (!empty(get_imagepress_option('ip_global_upload_limit'))) {
        $limit = get_imagepress_option('ip_global_upload_limit');
    } else {
        $limit = __('No quota', 'imagepress');
    }

    // get current user uploads
    $userUploads = cinnamon_count_user_posts_by_type($user_id, get_imagepress_option('ip_slug'));

    return '<span class="imagepress-quota">' . $userUploads . '/<span>' . $limit . '</span></span>';
}

add_shortcode('imagepress-quota', 'imagepress_quota');
