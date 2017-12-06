<?php
function imagepress_quota($atts) {
    extract(shortcode_atts(array(
    ), $atts));

    global $current_user;

    $user_id = $current_user->ID;
    $quota = get_the_author_meta('ip_upload_limit', $user_id);
    $limit = esc_attr__('No quota', 'imagepress');

    if (isset($quota) && !empty($quota)) {
        $limit = $quota;
    } else if (!empty(get_imagepress_option('ip_global_upload_limit'))) {
        $limit = get_imagepress_option('ip_global_upload_limit');
    }

    // get current user uploads
    $userUploads = cinnamon_count_user_posts_by_type($user_id);

    return '<span class="imagepress-quota">' . $userUploads . '/<span>' . $limit . '</span></span>';
}

add_shortcode('imagepress-quota', 'imagepress_quota');
