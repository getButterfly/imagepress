<?php
// remove these:
// http://wpengineer.com/968/wordpress-working-with-options/
// https://www.billerickson.net/code/wp_query-arguments/

// Check if option key exists.
// Cleanup old versions.
if ((int) get_option('imagepress_option_array') !== 1) {
    if (get_option('imagepress')) {
        update_option('imagepress_option_array', 1);

        /**
        delete_option('ip_presstrends');
        delete_option('ip_default_category');
        delete_option('ip_default_category_show');
        delete_option('ip_author_filter');
        delete_option('ip_box_styling');
        delete_option('ip_box_hover');
        delete_option('gs_title_colour');
        delete_option('gs_text_colour');
        delete_option('gs_cpt');
        delete_option('ip_module_masonry');
        delete_option('ip_width');
        delete_option('ip_content_optional');
        delete_option('ip_url_optional');
        delete_option('ip_module_flip');
        delete_option('cinnamon_post_type');
        delete_option('cinnamon_colour');
        delete_option('cinnamon_colour_step');
        delete_option('cinnamon_hover_colour');
        delete_option('act_settings');
        delete_option('cinnamon_awards_more');
        delete_option('cinnamon_mod_activity');
        delete_option('cinnamon_style_pure');
        delete_option('ip_timebeforerevote');
        delete_option('ip_module_slider');
        delete_option('ip_lightbox');
        delete_option('gs_category');
        delete_option('gs_slides');
        delete_option('gs_width');
        delete_option('gs_autoplay');
        delete_option('gs_secondary_background');
        delete_option('gs_secondary_border');
        delete_option('gs_secondary_border_type');
        delete_option('gs_easing_style');
        delete_option('gs_additional_levels');
        delete_option('cinnamon_text_colour');
        delete_option('cinnamon_background_colour');
        delete_option('ip_box_background');
        delete_option('ip_text_colour');
        delete_option('ip_cookie_expiration');
        delete_option('cinnamon_show_progress');
        delete_option('cinnamon_profile_title');
        delete_option('ip_createusers');
        delete_option('cms_featured_tooltip');
        delete_option('cms_title');
        delete_option('ip_collections_read_more');
        delete_option('ip_collections_read_more_link');
        delete_option('ip_disqus');
        delete_option('ip_mod_async');
        delete_option('ip_cards_image_size');
        delete_option('ip_override_email_notification');
        delete_option('ip_ipr');
        delete_option('ip_behance_label');
        delete_option('ip_purchase_label');
        delete_option('ip_print_label');
        delete_option('cms_available_for_print');
        delete_option('ip_lazy_loading');
        delete_option('ip_show_single_image');
        delete_option('imagepress_location');
        delete_option('ip_location_label');
        delete_option('noir_ui_logo_url');
        delete_option('noir_ui_boilerplate_primary');
        delete_option('noir_ui_bg_color');
        delete_option('noir_ui_dark_color');
        delete_option('noir_ui_accent_color');
        delete_option('ip_fix_avada');
        delete_option('ip_keywords_label');
        delete_option('cinnamon_show_posts');
        delete_option('ip_padding');
        delete_option('cinnamon_show_online');
        delete_option('noir_ui_upload_url');
        delete_option('ip_vote_nobody');
        delete_option('ip_vote_who_singular');
        delete_option('ip_vote_who_link');
        delete_option('ip_vote_who_plural');
        delete_option('ip_vote_who');
        delete_option('ip_thin_ui');
        delete_option('cinnamon_show_activity');
        delete_option('cinnamon_show_comments');

        // 7.4
        delete_option('ip_vote_meta'); // replaced it with hardcoded _like_count
        delete_option('ip_vote_login'); // replaced by blank space
        delete_option('ip_likes'); // unused
        delete_option('ip_login_flat_mode');
        delete_option('imagepress_settings');
        delete_option('ip_upload_tos_text');
        delete_option('hook_upload_success');
        delete_option('hook_share_single');

        delete_option('ip_box_ui');
        delete_option('ip_grid_ui');
        delete_option('ip_ipw');
        delete_option('ip_ipp');
        delete_option('ip_app');
        delete_option('ip_order');
        delete_option('ip_orderby');
        delete_option('ip_slug');
        delete_option('ip_image_size');
        delete_option('ip_title_optional');
        delete_option('ip_meta_optional');
        delete_option('ip_views_optional');
        delete_option('ip_comments');
        delete_option('ip_likes_optional');
        delete_option('ip_author_optional');
        delete_option('ip_rel_tag');
        delete_option('ip_collections_page');
        delete_option('ip_login_image');
        delete_option('ip_login_bg');
        delete_option('ip_login_box_bg');
        delete_option('ip_login_box_text');
        delete_option('ip_login_page_text');
        delete_option('ip_login_button_bg');
        delete_option('ip_login_button_text');
        delete_option('ip_login_copyright');
        delete_option('ip_name_label');
        delete_option('ip_email_label');
        delete_option('ip_caption_label');
        delete_option('ip_category_label');
        delete_option('ip_tag_label');
        delete_option('ip_description_label');
        delete_option('ip_ezdz_label');
        delete_option('ip_upload_label');
        delete_option('ip_image_label');
        delete_option('ip_video_label');
        delete_option('ip_sticky_label');
        delete_option('ip_author_find_title');
        delete_option('ip_author_find_placeholder');
        delete_option('ip_image_find_title');
        delete_option('ip_image_find_placeholder');
        delete_option('ip_notifications_mark');
        delete_option('ip_notifications_all');
        delete_option('cms_verified_profile');
        delete_option('ip_upload_success_title');
        delete_option('ip_upload_success');
        delete_option('ip_vote_like');
        delete_option('ip_vote_unlike');
        delete_option('cinnamon_edit_label');
        delete_option('cinnamon_pt_account');
        delete_option('cinnamon_pt_social');
        delete_option('cinnamon_pt_author');
        delete_option('cinnamon_pt_profile');
        delete_option('cinnamon_pt_portfolio');
        delete_option('cinnamon_pt_collections');
        delete_option('cinnamon_pt_images');
        delete_option('ip_moderate');
        delete_option('ip_registration');
        delete_option('ip_click_behaviour');
        delete_option('ip_cat_moderation_include');
        delete_option('cinnamon_mod_login');
        delete_option('cinnamon_mod_hub');
        delete_option('ip_mod_login');
        delete_option('ip_mod_collections');
        delete_option('ip_upload_redirection');
        delete_option('ip_delete_redirection');
        delete_option('ip_notification_email');
        delete_option('ip_fix_reporting');
        delete_option('ip_fix_upload');
        delete_option('ip_profile_page');
        delete_option('cinnamon_author_slug');
        delete_option('cinnamon_label_index');
        delete_option('cinnamon_label_portfolio');
        delete_option('cinnamon_label_about');
        delete_option('cinnamon_label_hub');
        delete_option('cinnamon_hide');
        delete_option('cinnamon_image_size');
        delete_option('ip_cards_per_author');
        delete_option('ip_et_login');
        delete_option('cinnamon_show_uploads');
        delete_option('cinnamon_show_awards');
        delete_option('cinnamon_show_about');
        delete_option('cinnamon_show_map');
        delete_option('cinnamon_show_followers');
        delete_option('cinnamon_show_following');
        delete_option('cinnamon_hide_admin');
        delete_option('cinnamon_account_page');
        delete_option('cinnamon_edit_page');
        delete_option('cinnamon_show_likes');
        delete_option('cinnamon_show_collections');
        delete_option('cinnamon_fancy_header');
        delete_option('approvednotification');
        delete_option('declinednotification');
        delete_option('ip_ezdz');
        delete_option('ip_request_user_details');
        delete_option('ip_upload_secondary');
        delete_option('ip_allow_tags');
        delete_option('ip_require_description');
        delete_option('ip_upload_tos');
        delete_option('ip_upload_tos_url');
        delete_option('ip_upload_tos_error');
        delete_option('ip_upload_tos_content');
        delete_option('ip_upload_size');
        delete_option('ip_global_upload_limit');
        delete_option('ip_global_upload_limit_message');
        delete_option('ip_cat_exclude');
        delete_option('ip_resize');
        delete_option('ip_max_width');
        delete_option('ip_max_quality');
        delete_option('ip_dropbox_enable');
        delete_option('ip_dropbox_key');
        delete_option('notification_limit');
        delete_option('notification_thumbnail_custom');

        delete_metadata('user', 0, 'hub_gender', '', true);
        delete_metadata('user', 0, 'hub_software', '', true);

        wp_clear_scheduled_hook('act_cron_daily');

        global $wp_taxonomies;
        $taxonomy = 'imagepress_image_property';
        if (taxonomy_exists($taxonomy)) {
            unset($wp_taxonomies[$taxonomy]);
        }
        /**/
    } else {
        // Option key does not exist and this is an upgrade.
        // Let's migrate all options.
        if (get_option('ip_slug')) {
            $newImagePressOptions = array(
                // Configurator tab
                'ip_box_ui' => get_option('ip_box_ui'),
                'ip_grid_ui' => get_option('ip_grid_ui'),
                'ip_ipw' => get_option('ip_ipw'),
                'ip_ipp' => get_option('ip_ipp'),
                'ip_app' => get_option('ip_app'),
                'ip_order' => get_option('ip_order'),
                'ip_orderby' => get_option('ip_orderby'),
                'ip_slug' => get_option('ip_slug'),
                'ip_image_size' => get_option('ip_image_size'),
                'ip_title_optional' => get_option('ip_title_optional'),
                'ip_meta_optional' => get_option('ip_meta_optional'),
                'ip_views_optional' => get_option('ip_views_optional'),
                'ip_comments' => get_option('ip_comments'),
                'ip_likes_optional' => get_option('ip_likes_optional'),
                'ip_author_optional' => get_option('ip_author_optional'),
                'ip_rel_tag' => get_option('ip_rel_tag'),

                // Collections tab
                'ip_collections_page' => get_option('ip_collections_page'),

                // Login tab
                'ip_login_image' => get_option('ip_login_image'),
                'ip_login_bg' => get_option('ip_login_bg'),
                'ip_login_box_bg' => get_option('ip_login_box_bg'),
                'ip_login_box_text' => get_option('ip_login_box_text'),
                'ip_login_page_text' => get_option('ip_login_page_text'),
                'ip_login_button_bg' => get_option('ip_login_button_bg'),
                'ip_login_button_text' => get_option('ip_login_button_text'),
                'ip_login_copyright' => get_option('ip_login_copyright'),

                // Labels tab
                'ip_name_label' => get_option('ip_name_label'),
                'ip_email_label' => get_option('ip_email_label'),
                'ip_caption_label' => get_option('ip_caption_label'),
                'ip_category_label' => get_option('ip_category_label'),
                'ip_tag_label' => get_option('ip_tag_label'),
                'ip_description_label' => get_option('ip_description_label'),
                'ip_ezdz_label' => get_option('ip_ezdz_label'),
                'ip_upload_label' => get_option('ip_upload_label'),
                'ip_image_label' => get_option('ip_image_label'),
                'ip_video_label' => get_option('ip_video_label'),
                'ip_sticky_label' => get_option('ip_sticky_label'),
                'ip_notifications_mark' => get_option('ip_notifications_mark'),
                'ip_notifications_all' => get_option('ip_notifications_all'),
                'cms_verified_profile' => get_option('cms_verified_profile'),
                'ip_upload_success_title' => get_option('ip_upload_success_title'),
                'ip_upload_success' => get_option('ip_upload_success'),
                'ip_vote_like' => get_option('ip_vote_like'),
                'ip_vote_unlike' => get_option('ip_vote_unlike'),
                'cinnamon_edit_label' => get_option('cinnamon_edit_label'),
                'cinnamon_pt_account' => get_option('cinnamon_pt_account'),
                'cinnamon_pt_social' => get_option('cinnamon_pt_social'),
                'cinnamon_pt_author' => get_option('cinnamon_pt_author'),
                'cinnamon_pt_profile' => get_option('cinnamon_pt_profile'),
                'cinnamon_pt_portfolio' => get_option('cinnamon_pt_portfolio'),
                'cinnamon_pt_collections' => get_option('cinnamon_pt_collections'),
                'cinnamon_pt_images' => get_option('cinnamon_pt_images'),

                // Settings tab
                'ip_moderate' => get_option('ip_moderate'),
                'ip_registration' => get_option('ip_registration'),
                'ip_click_behaviour' => get_option('ip_click_behaviour'),
                'ip_cat_moderation_include' => get_option('ip_cat_moderation_include'),
                'cinnamon_mod_login' => get_option('cinnamon_mod_login'),
                'cinnamon_mod_hub' => get_option('cinnamon_mod_hub'),
                'ip_mod_login' => get_option('ip_mod_login'),
                'ip_mod_collections' => get_option('ip_mod_collections'),
                'ip_upload_redirection' => get_option('ip_upload_redirection'),
                'ip_delete_redirection' => get_option('ip_delete_redirection'),
                'ip_notification_email' => get_option('ip_notification_email'),

                // Authors tab
                'ip_profile_page' => get_option('ip_profile_page'),
                'cinnamon_author_slug' => get_option('cinnamon_author_slug'),
                'cinnamon_label_index' => get_option('cinnamon_label_index'),
                'cinnamon_label_portfolio' => get_option('cinnamon_label_portfolio'),
                'cinnamon_label_about' => get_option('cinnamon_label_about'),
                'cinnamon_label_hub' => get_option('cinnamon_label_hub'),
                'cinnamon_hide' => get_option('cinnamon_hide'),
                'cinnamon_image_size' => get_option('cinnamon_image_size'),
                'ip_cards_per_author' => get_option('ip_cards_per_author'),
                'ip_et_login' => get_option('ip_et_login'),
                'cinnamon_show_uploads' => get_option('cinnamon_show_uploads'),
                'cinnamon_show_awards' => get_option('cinnamon_show_awards'),
                'cinnamon_show_about' => get_option('cinnamon_show_about'),
                'cinnamon_show_map' => get_option('cinnamon_show_map'),
                'cinnamon_show_followers' => get_option('cinnamon_show_followers'),
                'cinnamon_show_following' => get_option('cinnamon_show_following'),
                'cinnamon_hide_admin' => get_option('cinnamon_hide_admin'),
                'cinnamon_account_page' => get_option('cinnamon_account_page'),
                'cinnamon_edit_page' => get_option('cinnamon_edit_page'),
                'cinnamon_show_likes' => get_option('cinnamon_show_likes'),
                'cinnamon_show_collections' => get_option('cinnamon_show_collections'),
                'cinnamon_fancy_header' => get_option('cinnamon_fancy_header'),
                'approvednotification' => get_option('approvednotification'),
                'declinednotification' => get_option('declinednotification'),

                // Upload tab
                'ip_ezdz' => get_option('ip_ezdz'),
                'ip_request_user_details' => get_option('ip_request_user_details'),
                'ip_upload_secondary' => get_option('ip_upload_secondary'),
                'ip_allow_tags' => get_option('ip_allow_tags'),
                'ip_require_description' => get_option('ip_require_description'),
                'ip_upload_tos' => get_option('ip_upload_tos'),
                'ip_upload_tos_url' => get_option('ip_upload_tos_url'),
                'ip_upload_tos_error' => get_option('ip_upload_tos_error'),
                'ip_upload_tos_content' => get_option('ip_upload_tos_content'),
                'ip_upload_size' => get_option('ip_upload_size'),
                'ip_global_upload_limit' => get_option('ip_global_upload_limit'),
                'ip_global_upload_limit_message' => get_option('ip_global_upload_limit_message'),
                'ip_cat_exclude' => get_option('ip_cat_exclude'),
                'ip_resize' => get_option('ip_resize'),
                'ip_max_width' => get_option('ip_max_width'),
                'ip_max_quality' => get_option('ip_max_quality'),
                'ip_dropbox_enable' => get_option('ip_dropbox_enable'),
                'ip_dropbox_key' => get_option('ip_dropbox_key'),

                // Notifications tab
                'notification_limit' => get_option('notification_limit'),
                'notification_thumbnail_custom' => get_option('notification_thumbnail_custom'),
            );
        } else {
            // Option key does not exist and this is a fresh installation.
            // Let's create all options.
            $newImagePressOptions = array(
                // Configurator tab
                'ip_box_ui' => 'default',
                'ip_grid_ui' => 'default',
                'ip_ipw' => 5,
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
                'ip_rel_tag' => 'lightbox',

                // Collections tab
                'ip_collections_page' => '',

                // Login tab
                'ip_login_image' => '',
                'ip_login_bg' => '#fefefe',
                'ip_login_box_bg' => '#ffffff',
                'ip_login_box_text' => '#000000',
                'ip_login_page_text' => '#000000',
                'ip_login_button_bg' => '#00a0d2',
                'ip_login_button_text' => '#ffffff',
                'ip_login_copyright' => '',

                // Labels tab
                'ip_name_label' => 'Name',
                'ip_email_label' => 'Email Address',
                'ip_caption_label' => 'Image Caption',
                'ip_category_label' => 'Image Category',
                'ip_tag_label' => 'Image Tag',
                'ip_description_label' => 'Image Description',
                'ip_ezdz_label' => 'Drag an image here or click to select.',
                'ip_upload_label' => 'Upload',
                'ip_image_label' => 'Select Image',
                'ip_video_label' => 'Youtube URL',
                'ip_sticky_label' => 'Sticky (display this image with higher priority)',
                'ip_notifications_mark' => 'Mark all as read',
                'ip_notifications_all' => 'View all notifications',
                'cms_verified_profile' => 'Verified Profile',
                'ip_upload_success_title' => 'Image uploaded!',
                'ip_upload_success' => 'Click here to view your image.',
                'ip_vote_like' => 'Like',
                'ip_vote_unlike' => 'Unlike',
                'cinnamon_edit_label' => 'Edit profile',
                'cinnamon_pt_account' => 'Account details',
                'cinnamon_pt_social' => 'Social details',
                'cinnamon_pt_author' => 'Author details',
                'cinnamon_pt_profile' => 'Profile details',
                'cinnamon_pt_portfolio' => 'Portfolio editor',
                'cinnamon_pt_collections' => 'Collections',
                'cinnamon_pt_images' => 'Image editor',

                // Settings tab
                'ip_moderate' => 1,
                'ip_registration' => 1,
                'ip_click_behaviour' => 'media',
                'ip_cat_moderation_include' => '',
                'cinnamon_mod_login' => 0,
                'cinnamon_mod_hub' => 0,
                'ip_mod_login' => 0,
                'ip_mod_collections' => 1,
                'ip_upload_redirection' => '',
                'ip_delete_redirection' => '',
                'ip_notification_email' => '',

                // Authors tab
                'ip_profile_page' => '',
                'cinnamon_author_slug' => 'profile',
                'cinnamon_label_index' => '',
                'cinnamon_label_portfolio' => '',
                'cinnamon_label_about' => '',
                'cinnamon_label_hub' => '',
                'cinnamon_hide' => '',
                'cinnamon_image_size' => '',
                'ip_cards_per_author' => 5,
                'ip_et_login' => '',
                'cinnamon_show_uploads' => 1,
                'cinnamon_show_awards' => 0,
                'cinnamon_show_about' => 1,
                'cinnamon_show_map' => 0,
                'cinnamon_show_followers' => 1,
                'cinnamon_show_following' => 1,
                'cinnamon_hide_admin' => 1,
                'cinnamon_account_page' => '',
                'cinnamon_edit_page' => '',
                'cinnamon_show_likes' => 0,
                'cinnamon_show_collections' => 0,
                'cinnamon_fancy_header' => 1,
                'approvednotification' => 1,
                'declinednotification' => 1,

                // Upload tab
                'ip_ezdz' => 0,
                'ip_request_user_details' => 1,
                'ip_upload_secondary' => 0,
                'ip_allow_tags' => 0,
                'ip_require_description' => 0,
                'ip_upload_tos' => 0,
                'ip_upload_tos_url' => '',
                'ip_upload_tos_error' => 'You have to agree! You definitely have to!',
                'ip_upload_tos_content' => 'I have read and agree to the terms & conditions of use.',
                'ip_upload_size' => 4096,
                'ip_global_upload_limit' => 1000,
                'ip_global_upload_limit_message' => 'You have reached the maximum number of images allowed.',
                'ip_cat_exclude' => '',
                'ip_resize' => 0,
                'ip_max_width' => 1920,
                'ip_max_quality' => 100,
                'ip_dropbox_enable' => 0,
                'ip_dropbox_key' => '',

                // Notifications tab
                'notification_limit' => 50,
                'notification_thumbnail_custom' => '',
            );
        }

        update_option('imagepress', $newImagePressOptions);
    }
}
