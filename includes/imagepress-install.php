<?php
$optionArray = [
    // Configurator tab
    'ip_box_ui' => 'default',
    'ip_ipp' => 20,
    'ip_app' => 10,
    'ip_order' => 'DESC',
    'ip_orderby' => 'date',
    'ip_slug' => 'image',
    'ip_image_size' => 'imagepress_sq_std',
    'ip_title_optional' => 1,
    'ip_meta_optional' => 1,
    'ip_views_optional' => 1,
    'ip_comments' => 1,
    'ip_likes_optional' => 1,
    'ip_author_optional' => 0,

    // Collections tab
    'ip_collections_page' => '',

    // Labels tab
    'ip_caption_label' => 'Image Caption',
    'ip_category_label' => 'Image Category',
    'ip_description_label' => 'Image Description',
    'ip_upload_label' => 'Upload',
    'ip_image_label' => 'Select Image',
    'ip_notifications_mark' => 'Mark all as read',
    'ip_notifications_all' => 'View all notifications',
    'ip_upload_success_title' => 'Image uploaded!',
    'ip_upload_success' => 'Click here to view your image.',
    'ip_vote_like' => 'Like',
    'ip_vote_unlike' => 'Unlike',

    // Settings tab
    'ip_moderate' => 1,
    'ip_registration' => 1,
    'ip_click_behaviour' => 'media',
    'ip_cat_moderation_include' => '',
    'ip_upload_redirection' => '',
    'ip_notification_email' => '',

    // Authors tab
    'ip_profile_page' => '',
    'cinnamon_author_slug' => 'profile',
    'ip_cards_per_author' => 5,
    'ip_et_login' => '',
    'cinnamon_hide_admin' => 1,
    'cinnamon_account_page' => '',
    'cinnamon_edit_page' => '',
    'cinnamon_fancy_header' => 1,
    'approvednotification' => 1,
    'declinednotification' => 1,

    // Upload tab
    'ip_upload_secondary' => 0,
    'ip_upload_tos' => 0,
    'ip_upload_tos_url' => '',
    'ip_upload_tos_error' => 'You have to agree! You definitely have to!',
    'ip_upload_tos_content' => 'I have read and agree to the terms & conditions of use.',
    'ip_upload_size' => 4096,
    'ip_cat_exclude' => '',
    'ip_max_quality' => 100,
    'ip_dropbox_enable' => 0,
    'ip_dropbox_key' => '',

    'ip_enable_views' => 1,
];

add_option('imagepress', $optionArray);
