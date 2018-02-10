<?php
/*
Plugin Name: ImagePress
Plugin URI: https://getbutterfly.com/wordpress-plugins/imagepress/
Description: Create a user-powered image gallery or an image upload site, using nothing but WordPress custom posts. Moderate image submissions and integrate the plugin into any theme.
Version: 7.6.5
License: GPLv3
Author: Ciprian Popescu
Author URI: https://getbutterfly.com
License: GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Text Domain: imagepress

ImagePress  Copyright (c) 2013-2018 Ciprian Popescu (email: getbutterfly@gmail.com)
Ezdz        Copyright (c) 2016 Jay Salvat (https://github.com/jaysalvat/ezdz) (MIT)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/
if (!function_exists('add_filter')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

define('IP_PLUGIN_URL', WP_PLUGIN_URL . '/' . dirname(plugin_basename(__FILE__)));
define('IP_PLUGIN_PATH', WP_PLUGIN_DIR . '/' . dirname(plugin_basename(__FILE__)));
define('IP_PLUGIN_FILE_PATH', WP_PLUGIN_DIR . '/' . plugin_basename(__FILE__));



// Plugin initialization
function imagepress_init() {
    load_plugin_textdomain('imagepress', false, dirname(plugin_basename(__FILE__)) . '/languages/');

    $ip_slug = get_imagepress_option('ip_slug');

    if (empty($ip_slug)) {
        $optionArray = array(
            'ip_slug' => 'image',
        );
        updateImagePressOption($optionArray);
    }
}
add_action('plugins_loaded', 'imagepress_init');



if (defined('ALLOW_IMAGEPRESS_UPDATE')) {
    require_once IP_PLUGIN_PATH . '/classes/Updater.php';

    if (is_admin()) {
        $config = array(
            'slug' => plugin_basename(__FILE__),
            'proper_folder_name' => 'imagepress',
            'github_url' => 'https://github.com/getButterfly/imagepress',
            'requires' => '4.6',
            'tested' => '4.9.4',
            'readme' => 'README.MD',
        );
        new WP_GitHub_Updater($config);
    }
}

include_once IP_PLUGIN_PATH . '/includes/imagepress-install.php';
include_once IP_PLUGIN_PATH . '/includes/functions.php';

include IP_PLUGIN_PATH . '/includes/alpha-functions.php';
include IP_PLUGIN_PATH . '/includes/page-settings.php';
include IP_PLUGIN_PATH . '/includes/cinnamon-users.php';

// user modules
include IP_PLUGIN_PATH . '/modules/mod-awards.php';
include IP_PLUGIN_PATH . '/modules/mod-user-following.php';
include IP_PLUGIN_PATH . '/modules/mod-likes.php';
include IP_PLUGIN_PATH . '/modules/mod-notifications.php';
include IP_PLUGIN_PATH . '/modules/mod-feed.php';

if (get_imagepress_option('ip_mod_collections') == 1) {
    include IP_PLUGIN_PATH . '/modules/mod-collections.php';
}

// user classes
if (get_imagepress_option('cinnamon_mod_login') == 1) {
    include IP_PLUGIN_PATH . '/classes/Frontend.php';
}
//

include IP_PLUGIN_PATH . '/includes/shortcodes.php';
include IP_PLUGIN_PATH . '/includes/extra.php';

add_action('init', 'imagepress_registration');

add_action('wp_ajax_nopriv_post-like', 'post_like');
add_action('wp_ajax_post-like', 'post_like');

add_action('admin_menu', 'imagepress_menu'); // settings menu

add_filter('transition_post_status', 'imagepress_notify_status', 10, 3); // email notifications
add_filter('widget_text', 'do_shortcode');

function imagepress_menu() {
    global $menu, $submenu;

    add_submenu_page('edit.php?post_type=' . get_imagepress_option('ip_slug'), 'ImagePress Settings', 'ImagePress Settings', 'manage_options', 'imagepress_admin_page', 'imagepress_admin_page');

    $url = 'https://getbutterfly.com/support/documentation/imagepress/';
    $submenu['edit.php?post_type=' . get_imagepress_option('ip_slug')][] = array('<span style="color: #F1C40F;">Documentation</span>', 'manage_options', $url);

    $args = array(
        'post_type' => get_imagepress_option('ip_slug'),
        'post_status' => 'pending',
        'showposts' => -1,
    );
    $draft_ip_links = count(get_posts($args));

    if ($draft_ip_links) {
        foreach ($menu as $key => $value) {
            if ($menu[$key][2] == 'edit.php?post_type=' . get_imagepress_option('ip_slug')) {
                $menu[$key][0] .= ' <span class="update-plugins count-' . $draft_ip_links . '"><span class="plugin-count">' . $draft_ip_links . '</span></span>';
                return;
            }
        }
    }
    if ($draft_ip_links) {
        foreach ($submenu as $key => $value) {
            if ($submenu[$key][2] == 'edit.php?post_type=' . get_imagepress_option('ip_slug')) {
                $submenu[$key][0] .= ' <span class="update-plugins count-' . $draft_ip_links . '"><span class="plugin-count">' . $draft_ip_links . '</span></span>';
                return;
            }
        }
    }
}

add_shortcode('imagepress-add', 'imagepress_add');
add_shortcode('imagepress-add-bulk', 'imagepress_add_bulk');
add_shortcode('imagepress-collection', 'imagepress_collection');
add_shortcode('imagepress-search', 'imagepress_search');
add_shortcode('imagepress-top', 'imagepress_top');

add_shortcode('ip-field', 'ip_get_field');

add_shortcode('imagepress', 'imagepress_widget');

add_shortcode('imagepress-collections', 'ip_collections_display_custom');

add_image_size('imagepress_sq_std', 250, 250, true);
add_image_size('imagepress_pt_std', 250, 375, true);
add_image_size('imagepress_ls_std', 375, 250, true);

// show admin bar only for admins
if (get_imagepress_option('cinnamon_hide_admin') == 1) {
    add_action('after_setup_theme', 'cinnamon_remove_admin_bar');
    function cinnamon_remove_admin_bar() {
        if (!current_user_can('administrator') && !is_admin()) {
            show_admin_bar(false);
        }
    }
}
//

/* CINNAMON ACTIONS */
add_action('init', 'cinnamon_author_base');

add_action('personal_options_update', 'save_cinnamon_profile_fields');
add_action('edit_user_profile_update', 'save_cinnamon_profile_fields');

/* CINNAMON SHORTCODES */
add_shortcode('cinnamon-card', 'cinnamon_card');
add_shortcode('cinnamon-profile', 'cinnamon_profile');
add_shortcode('cinnamon-profile-edit', 'cinnamon_profile_edit');
add_shortcode('cinnamon-awards', 'cinnamon_awards');

/* CINNAMON FILTERS */
add_filter('get_avatar', 'hub_gravatar_filter', 1, 5);
add_filter('user_contactmethods', 'cinnamon_extra_contact_info');







// custom thumbnail column
$ip_column_slug = get_imagepress_option('ip_slug');

add_filter('manage_edit-' . $ip_column_slug . '_columns', 'ip_columns_filter', 10, 1);
function ip_columns_filter($columns) {
    $column_thumbnail = array('thumbnail' => 'Thumbnail');
    $columns = array_slice($columns, 0, 1, true) + $column_thumbnail + array_slice($columns, 1, NULL, true);

    return $columns;
}
add_action('manage_posts_custom_column', 'ip_column_action', 10, 1);
function ip_column_action($column) {
    global $post;
    switch($column) {
        case 'thumbnail':
            echo get_the_post_thumbnail($post->ID, 'thumbnail');
        break;
    }
}
//

function ip_manage_users_custom_column($output = '', $column_name, $user_id) {
    if ($column_name === 'post_type_quota') {
        $quota = get_the_author_meta('ip_upload_limit', $user_id);
        $limit = __('No quota', 'imagepress');

        if (isset($quota) && !empty($quota)) {
            $limit = $quota;
        } else if (!empty(get_imagepress_option('ip_global_upload_limit'))) {
            $limit = get_imagepress_option('ip_global_upload_limit');
        }

        // get current user uploads
        $userUploads = cinnamon_count_user_posts_by_type($user_id);

        if ($userUploads > 0) {
            $userUploads = '<a href="' . admin_url('edit.php?post_type=' . get_imagepress_option('ip_slug') . '&author=' . $user_id) . '">' . $userUploads . '</a>';
        }

        return $userUploads . '/<small>' . $limit . '</small>';
    } else {
        return;
    }
}
add_filter('manage_users_custom_column', 'ip_manage_users_custom_column', 10, 3);

function ip_manage_users_columns($columns) {
    $columns['post_type_quota'] = __('Images/Quota', 'imagepress');

    return $columns;
}
add_filter('manage_users_columns', 'ip_manage_users_columns');

// Main upload function
function imagepress_add($atts) {
    extract(shortcode_atts(array(
        'category' => ''
    ), $atts));

    global $wpdb, $current_user;

    $out = '';
    $ipModerate = (int) get_imagepress_option('ip_moderate');

    if (isset($_POST['imagepress_upload_image_form_submitted']) && wp_verify_nonce($_POST['imagepress_upload_image_form_submitted'], 'imagepress_upload_image_form')) {
        $ip_status = ($ipModerate === 0) ? 'pending' : 'publish';

        $ip_image_author = $current_user->ID;
        $ipImageCaption = uniqid();

        if (!empty($_POST['imagepress_image_caption']))
            $ipImageCaption = sanitize_text_field($_POST['imagepress_image_caption']);

        $user_image_data = array(
            'post_title' => $ipImageCaption,
            'post_content' => sanitize_text_field($_POST['imagepress_image_description']),
            'post_status' => $ip_status,
            'post_author' => $ip_image_author,
            'post_type' => get_imagepress_option('ip_slug')
        );

        // send notification email to administrator
        $notificationEmail = get_imagepress_option('ip_notification_email');
        $notificationSubject = __('New image uploaded!', 'imagepress') . ' | ' . get_bloginfo('name');
        $notificationMessage = __('New image uploaded!', 'imagepress') . ' | ' . get_bloginfo('name');

        if (!empty($_FILES['imagepress_image_file'])) {
            $post_id = wp_insert_post($user_image_data);
            imagepress_process_image('imagepress_image_file', $post_id);

            // multiple images
            if (1 == get_imagepress_option('ip_upload_secondary')) {
                $files = $_FILES['imagepress_image_additional'];
                if ($files) {
                    foreach ($files['name'] as $key => $value) {
                        if ($files['name'][$key]) {
                            $file = array(
                                'name' => $files['name'][$key],
                                'type' => $files['type'][$key],
                                'tmp_name' => $files['tmp_name'][$key],
                                'error' => $files['error'][$key],
                                'size' => $files['size'][$key]
                            );
                        }
                        $_FILES = array("attachment" => $file);
                        foreach ($_FILES as $file => $array) {
                            $attach_id = media_handle_upload($file, $post_id);
                        }
                    }
                }
            }
            // end multiple images

            if (isset($_POST['imagepress_image_category']))
                wp_set_object_terms($post_id, (int) $_POST['imagepress_image_category'], 'imagepress_image_category');

            if (isset($_POST['imagepress_image_tag']))
                wp_set_object_terms($post_id, (int) $_POST['imagepress_image_tag'], 'imagepress_image_tag');

            // always moderate this category
            $moderatedCategory = get_imagepress_option('ip_cat_moderation_include');
            if (!empty($moderatedCategory)) {
                if ($_POST['imagepress_image_category'] == $moderatedCategory) {
                    $ip_post = array();
                    $ip_post['ID'] = $post_id;
                    $ip_post['post_status'] = 'pending';

                    wp_update_post($ip_post);
                }
            }
            //

            if (isset($_POST['imagepress_video']))
                add_post_meta($post_id, 'imagepress_video', $_POST['imagepress_video'], true);
            else
                add_post_meta($post_id, 'imagepress_video', '', true);

            if (isset($_POST['imagepress_author']))
                add_post_meta($post_id, 'imagepress_author', $_POST['imagepress_author'], true);
            if (isset($_POST['imagepress_email']))
                add_post_meta($post_id, 'imagepress_email', $_POST['imagepress_email'], true);

            // custom fields
            $result = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_fields ORDER BY field_order ASC", ARRAY_A);

            foreach ($result as $field) {
                add_post_meta($post_id, $field['field_slug'], $_POST[$field['field_slug']], true);
            }
            //

            // collections
            if ((int) get_imagepress_option('ip_mod_collections') === 1) {
                $ip_collections = (int) ($_POST['ip_collections']);

                if (!empty($_POST['ip_collections_new'])) {
                    $ip_collections_new = sanitize_text_field($_POST['ip_collections_new']);
                    $ip_collection_status = (int) ($_POST['collection_status']);

                    $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "ip_collections (collection_title, collection_status, collection_author_ID) VALUES (%s, %d, %d)", $ip_collections_new, $ip_collection_status, $ip_image_author));
                    $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "ip_collectionmeta (image_ID, image_collection_ID, image_collection_author_ID) VALUES (%d,  %d,  %d)", $post_id, $wpdb->insert_id, $ip_image_author));
                } else {
                    $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "ip_collectionmeta (image_ID, image_collection_ID, image_collection_author_ID) VALUES (%d,  %d,  %d)", $post_id, $ip_collections, $ip_image_author));
                }
            }
            //

            imagepress_post_add_custom($post_id, $ip_image_author);

            $headers[] = "MIME-Version: 1.0\r\n";
            $headers[] = "Content-Type: text/html; charset=\"" . get_option('blog_charset') . "\"\r\n";
            wp_mail($notificationEmail, $notificationSubject, $notificationMessage, $headers);

            $ip_upload_redirection = get_imagepress_option('ip_upload_redirection');
            if (!empty($ip_upload_redirection)) {
                wp_redirect(get_imagepress_option('ip_upload_redirection'));
                exit;
            }
        }

        $out .= '<p class="message noir-success">' . get_imagepress_option('ip_upload_success_title') . '</p>';
        $out .= '<p class="message"><a href="' . get_permalink($post_id) . '">' . get_imagepress_option('ip_upload_success') . '</a></p>';
    }

    if(get_imagepress_option('ip_registration') == 0 && !is_user_logged_in()) {
        $out .= '<p>' . __('You need to be logged in to upload an image.', 'imagepress') . '</p>';
    }
    if((get_imagepress_option('ip_registration') == 0 && is_user_logged_in()) || get_imagepress_option('ip_registration') == 1) {
        if(isset($_POST['imagepress_image_caption']) && isset($_POST['imagepress_image_category']))
            $out .= imagepress_get_upload_image_form($imagepress_image_caption = $_POST['imagepress_image_caption'], $imagepress_image_category = $_POST['imagepress_image_category'], $imagepress_image_description = $_POST['imagepress_image_description'], $category);
        else
            $out .= imagepress_get_upload_image_form($imagepress_image_caption = '', $imagepress_image_category = '', $imagepress_image_description = '', $category);
    }

    return $out;
}

function imagepress_add_bulk($atts) {
    extract(shortcode_atts(array(
        'category' => ''
    ), $atts));

    global $current_user;
    $out = '';

    if(isset($_POST['imagepress_upload_image_form_submitted_bulk']) && wp_verify_nonce($_POST['imagepress_upload_image_form_submitted_bulk'], 'imagepress_upload_image_form_bulk')) {
        if(get_imagepress_option('ip_moderate') == 0)
            $ip_status = 'pending';
        if(get_imagepress_option('ip_moderate') == 1)
            $ip_status = 'publish';

        $ip_image_author = $current_user->ID;

        // send notification email to administrator
        $notificationEmail = get_imagepress_option('ip_notification_email');
        $notificationSubject = __('New image uploaded!', 'imagepress') . ' | ' . get_bloginfo('name');
        $notificationMessage = __('New image uploaded!', 'imagepress') . ' | ' . get_bloginfo('name');

        // alpha
        $files = $_FILES['imagepress_image_file_bulk'];
        if(!empty($files)) {
            require_once ABSPATH . 'wp-admin' . '/includes/image.php';
            require_once ABSPATH . 'wp-admin' . '/includes/file.php';
            require_once ABSPATH . 'wp-admin' . '/includes/media.php';

            foreach($files['name'] as $key => $value) {
                if($files['name'][$key]) {
                    $file = array(
                        'name' => $files['name'][$key],
                        'type' => $files['type'][$key],
                        'tmp_name' => $files['tmp_name'][$key],
                        'error' => $files['error'][$key],
                        'size' => $files['size'][$key]
                    );
                }
                $_FILES = array("attachment" => $file);
                foreach($_FILES as $file => $array) {
                    $attach_id = media_handle_upload($file, 0);

                    $user_image_data = array(
                        'post_title' => $_POST['imagepress_image_caption'][$key],
                        'post_content' => $_POST['imagepress_image_description'][$key],
                        'post_status' => $ip_status,
                        'post_author' => $ip_image_author,
                        'post_type' => get_imagepress_option('ip_slug')
                    );
                    if ($post_id == wp_insert_post($user_image_data)) {
                        update_post_meta($post_id, '_thumbnail_id', $attach_id);
                    }

                    wp_set_object_terms($post_id, (int) $_POST['imagepress_image_category'][$key], 'imagepress_image_category');
                }
            }
        }

        $headers[] = "MIME-Version: 1.0\r\n";
        $headers[] = "Content-Type: text/html; charset=\"" . get_option('blog_charset') . "\"\r\n";
        wp_mail($notificationEmail, $notificationSubject, $notificationMessage, $headers);

        $out .= '<p class="message noir-success">' . get_imagepress_option('ip_upload_success_title') . '</p>';
        $out .= '<p class="message"><a href="' . get_permalink($post_id) . '">' . get_imagepress_option('ip_upload_success') . '</a></p>';
    }

    if(get_imagepress_option('ip_registration') == 0 && !is_user_logged_in()) {
        $out .= '<p>' . __('You need to be logged in to upload an image.', 'imagepress') . '</p>';
    }
    if((get_imagepress_option('ip_registration') == 0 && is_user_logged_in()) || get_imagepress_option('ip_registration') == 1) {
        if(isset($_POST['imagepress_image_caption']) && isset($_POST['imagepress_image_category']))
            $out .= imagepress_get_upload_image_form_bulk($imagepress_image_category = $_POST['imagepress_image_category'], $category);
        else
            $out .= imagepress_get_upload_image_form_bulk($imagepress_image_category = '', $category);
    }

    return $out;
}

function imagepress_jpeg_quality($quality, $context) {
    $ip_quality = (int) get_imagepress_option('ip_max_quality');

	return $ip_quality;
}
add_filter('jpeg_quality', 'imagepress_jpeg_quality', 10, 2);



function imagepress_process_image($file, $post_id, $feature = 1) {
    require_once ABSPATH . 'wp-admin' . '/includes/image.php';
    require_once ABSPATH . 'wp-admin' . '/includes/file.php';
    require_once ABSPATH . 'wp-admin' . '/includes/media.php';

    $attachment_id = media_handle_upload($file, $post_id);

    if ($feature == 1) {
        set_post_thumbnail($post_id, $attachment_id);
    }

    return $attachment_id;
}

function imagepress_get_upload_image_form($imagepress_image_caption = '', $imagepress_image_category = 0, $imagepress_image_description = '', $imagepress_hardcoded_category) {
    $current_user = wp_get_current_user();

    // upload form // customize

    // labels
    $ip_slug = get_imagepress_option('ip_slug');

    $ip_caption_label = get_imagepress_option('ip_caption_label');
    $ip_description_label = get_imagepress_option('ip_description_label');
    $ip_video_label = get_imagepress_option('ip_video_label');
    $ip_upload_label = get_imagepress_option('ip_upload_label');

    $ip_upload_tos = get_imagepress_option('ip_upload_tos');
    $ip_upload_tos_url = get_imagepress_option('ip_upload_tos_url');
    $ip_upload_tos_content = get_imagepress_option('ip_upload_tos_content');

    $ip_request_user_details = get_imagepress_option('ip_request_user_details');
    $ip_allow_tags = get_imagepress_option('ip_allow_tags');
    $ip_upload_size = get_imagepress_option('ip_upload_size');
    $ip_dropbox_enable = get_imagepress_option('ip_dropbox_enable');
    $ip_dropbox_key = get_imagepress_option('ip_dropbox_key');
    $ip_upload_secondary = get_imagepress_option('ip_upload_secondary');

    // get global upload limit
    $ip_global_upload_limit = get_imagepress_option('ip_global_upload_limit');
    if(empty($ip_global_upload_limit)) {
        $ip_global_upload_limit = 999999;
    }
    $ip_global_upload_limit_message = get_imagepress_option('ip_global_upload_limit_message');

    // get current user uploads
    $user_uploads = cinnamon_count_user_posts_by_type($current_user->ID);

    // get upload limit for current user
    $ip_user_upload_limit = get_the_author_meta('ip_upload_limit', $current_user->ID);
    if (!empty($ip_user_upload_limit)) {
        $ip_upload_limit = $ip_user_upload_limit;
    }
    if (empty($ip_upload_limit)) {
        $ip_upload_limit = 999999;
    }

    $out = '';

    $out .= '<div class="ip-uploader" id="fileuploads">';
        if(is_numeric($ip_upload_limit) && $user_uploads >= $ip_upload_limit) {
            $out .= '<p>' . $ip_global_upload_limit_message . ' (' . $user_uploads . '/' . $ip_upload_limit . ')</p>';
        } else {
            $out .= '<form id="imagepress_upload_image_form" method="post" action="" enctype="multipart/form-data" class="imagepress-form imagepress-upload-form">';
                $out .= wp_nonce_field('imagepress_upload_image_form', 'imagepress_upload_image_form_submitted');
                // name and email
                if($ip_request_user_details == 1) {
                    $out .= '<p>
                        <label>' . get_imagepress_option('ip_name_label') . '</label>
                        <input type="text" name="imagepress_author" value="' . $current_user->display_name . '" placeholder="' . get_imagepress_option('ip_name_label') . '" required>
                    </p>
                    <p>
                        <label>' . get_imagepress_option('ip_email_label') . '</label>
                        <input type="email" name="imagepress_email" value="' . $current_user->user_email . '" placeholder="' . get_imagepress_option('ip_email_label') . '" required>
                    </p>';
                } else {
                    $out .= '<input type="hidden" name="imagepress_author" value="' . $current_user->display_name . '">
                    <input type="hidden" name="imagepress_email" value="' . $current_user->user_email . '">';
                }

                if(!empty($ip_caption_label))
                    $out .= '<p>
                        <label>' . $ip_caption_label . '</label>
                        <input type="text" id="imagepress_image_caption" name="imagepress_image_caption" placeholder="' . $ip_caption_label . '" required>
                    </p>';

                if(!empty($ip_description_label)) {
                    $out .= '<p>
                        <label>' . get_imagepress_option('ip_description_label') . '</label>
                        <textarea id="imagepress_image_description" name="imagepress_image_description" placeholder="' . get_imagepress_option('ip_description_label') . '" rows="6"></textarea>
                    </p>';
                }

                $out .= '<p>';
                    if('' != $imagepress_hardcoded_category) {
                        $iphcc = get_term_by('slug', $imagepress_hardcoded_category, 'imagepress_image_category'); // ImagePress hard-coded category
                        $out .= '<input type="hidden" id="imagepress_image_category" name="imagepress_image_category" value="' . $iphcc->term_id . '">';
                    }
                    else {
                        $out .= imagepress_get_image_categories_dropdown('imagepress_image_category', '') . '';
                    }

                    if($ip_allow_tags == 1)
                        $out .= imagepress_get_image_tags_dropdown('imagepress_image_tag', '') . '';
                $out .= '</p>';

                // Add to collection on upload
                if ((int) get_imagepress_option('ip_mod_collections') === 1) {
                    global $wpdb;

                    $result = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_collections WHERE collection_author_ID = '" . get_current_user_id() . "'", ARRAY_A);

                    $out .= '<hr>
                    <p>
                        <select name="ip_collections" id="ip-collections">
                            <option value="">' . __('Choose a collection...', 'imagepress') . '</option>';
                            foreach ($result as $collection) {
                                $out .= '<option value="' . $collection['collection_ID'] . '">' . $collection['collection_title'] . '</option>';
                            }
                        $out .= '</select> <span class="ip-collection-create-new">' . __('or', 'imagepress') . ' <input type="text" name="ip_collections_new" id="ip-collections-new" placeholder="' . __('Create new collection...', 'imagepress') . '">
                        <select name="collection_status" id="collection_status">
                            <option value="1">' . __('Public', 'imagepress') . '</option>
                            <option value="0">' . __('Private', 'imagepress') . '</option>
                        </select></span>
                    </p>
                    <hr>';
                }

                if(!empty($ip_video_label))
                    $out .= '<p>
                        <label for="imagepress_video">' . $ip_video_label . '</label>
                        <input type="url" id="imagepress_video" name="imagepress_video" placeholder="' . $ip_video_label . '">
                    </p>';

                // custom fields
                global $wpdb;

                $result = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_fields ORDER BY field_order ASC", ARRAY_A);

                foreach($result as $field) {
                    if((int) $field['field_type'] === 1) {
                        $out .= '<p><label for="' . $field['field_slug'] . '">' . $field['field_name'] . '</label><input type="text" id="' . $field['field_slug'] . '" name="' . $field['field_slug'] . '" placeholder="' . $field['field_name'] . '"></p>';
                    } else if((int) $field['field_type'] === 2) {
                        $out .= '<p><label for="' . $field['field_slug'] . '">' . $field['field_name'] . '</label><input type="url" id="' . $field['field_slug'] . '" name="' . $field['field_slug'] . '" placeholder="' . $field['field_name'] . '"></p>';
                    } else if((int) $field['field_type'] === 3) {
                        $out .= '<p><label for="' . $field['field_slug'] . '">' . $field['field_name'] . '</label><input type="email" id="' . $field['field_slug'] . '" name="' . $field['field_slug'] . '" placeholder="' . $field['field_name'] . '"></p>';
                    } else if((int) $field['field_type'] === 4) {
                        $out .= '<p><label for="' . $field['field_slug'] . '">' . $field['field_name'] . '</label><input type="number" id="' . $field['field_slug'] . '" name="' . $field['field_slug'] . '" placeholder="' . $field['field_name'] . '"></p>';
                    } else if((int) $field['field_type'] === 5) {
                        $out .= '<p><label for="' . $field['field_slug'] . '">' . $field['field_name'] . '</label><textarea id="' . $field['field_slug'] . '" name="' . $field['field_slug'] . '" rows="6" placeholder="' . $field['field_name'] . '"></textarea></p>';
                    } else if((int) $field['field_type'] === 6) {
                        $out .= '<p><label for="' . $field['field_slug'] . '"><input type="checkbox" id="' . $field['field_slug'] . '" name="' . $field['field_slug'] . '" placeholder="' . $field['field_name'] . '" value="1"> ' . $field['field_name'] . '</label></p>';
                    } else if((int) $field['field_type'] === 7) {
                        $out .= '<p><label for="' . $field['field_slug'] . '"><input type="radio" id="' . $field['field_slug'] . '" name="' . $field['field_slug'] . '" placeholder="' . $field['field_name'] . '" value="1">' . $field['field_name'] . '</label></p>';
                    } else if((int) $field['field_type'] === 8) {
                        $out .= '<p><label for="' . $field['field_slug'] . '">' . $field['field_name'] . '</label><select id="' . $field['field_slug'] . '" name="' . $field['field_slug'] . '" placeholder="' . $field['field_name'] . '">';
                            $options = $wpdb->get_var($wpdb->prepare("SELECT field_content FROM  " . $wpdb->prefix . "ip_fields WHERE field_name = '%s'", $field['field_name']));
                            $options = explode(',', $options);
                            foreach($options as $option) {
                                $out .= '<option>' . trim($option) . '</option>';
                            }
                        $out .= '</select></p>';
                    } else if(
                        (int) $field['field_type'] === 20 ||
                        (int) $field['field_type'] === 21 ||
                        (int) $field['field_type'] === 22 ||
                        (int) $field['field_type'] === 23 ||
                        (int) $field['field_type'] === 24
                    ) {
                        $out .= '<p><label for="' . $field['field_slug'] . '">' . $field['field_name'] . '</label><input type="text" id="' . $field['field_slug'] . '" name="' . $field['field_slug'] . '" placeholder="' . $field['field_name'] . '"></p>';
                    }
                }
                //

                $uploadsize = number_format((($ip_upload_size * 1024)/1024000), 0, '.', '');
                $datauploadsize = $uploadsize * 1024000;

                $ip_ezdz_label = get_imagepress_option('ip_ezdz_label');
                $out .= '<hr>';
                $out .= '<div id="imagepress-errors"></div>';
                $out .= '<p><label for="imagepress_image_file"><i class="fas fa-cloud-upload-alt"></i> ' . __('Select a file', 'imagepress') . ' (' . $uploadsize . 'MB ' . __('maximum', 'imagepress') . ')...</label><input type="file" accept="image/*" data-max-size="' . $datauploadsize . '" data-ezdz-label="' . $ip_ezdz_label . '" name="imagepress_image_file" id="imagepress_image_file" required></p>';
                $out .= '<hr>';

                if($ip_dropbox_enable === '1') {
                    $out .= '<script src="https://www.dropbox.com/static/api/2/dropins.js" id="dropboxjs" data-app-key="' . $ip_dropbox_key . '"></script>';
                    $out .= '<p id="droptarget"></p>';
                    $out .= '<script>
                    options = {
                        success: function(files) {
                            document.getElementById("imagepress_dropbox_file").value = files[0].link;
                        },
                        linkType: "direct",
                        extensions: ["images"]
                    };
                    var button = Dropbox.createChooseButton(options); document.getElementById("droptarget").appendChild(button);</script>';
                    $out .= '<input type="hidden" id="imagepress_dropbox_file" name="imagepress_dropbox_file">';
                }

                if(1 == $ip_upload_secondary) {
                    $out .= '<p><label for="imagepress_image_additional"><i class="fas fa-cloud-upload-alt"></i> Select file(s) (' . $uploadsize . 'MB ' . __('maximum', 'imagepress') . ')...</label><input type="file" accept="image/*" name="imagepress_image_additional[]" id="imagepress_image_additional" multiple><br><small>Additional images (variants, making of, progress shots)</small></p><hr>';
                }

                if ($ip_upload_tos == 1 && !empty($ip_upload_tos_content)) {
                    $oninvalid = get_imagepress_option('ip_upload_tos_error');

                    $out .= '<p><input type="checkbox" id="imagepress_agree" name="imagepress_agree" value="1" onchange="this.setCustomValidity(validity.valueMissing ? \'' . $oninvalid . '\' : \'\');" required> ';

                        if(!empty($ip_upload_tos_url)) {
                            $out .= '<a href="' . $ip_upload_tos_url . '" target="_blank">' . $ip_upload_tos_content . '</a>';
                        } else {
                            $out .= $ip_upload_tos_content;
                        }

                    $out .= '</p>';
                    $out .= '<script>document.getElementById("imagepress_agree").setCustomValidity("' . $oninvalid . '");</script>';
                }

                $out .= '<p>';
                    $out .= '<input type="submit" id="imagepress_submit" name="imagepress_submit" value="' . $ip_upload_label . '" class="button noir-secondary">';
                    $out .= ' <span id="ipload"></span>';
                $out .= '</p>';
            $out .= '</form>';
        }
    $out .= '</div>';

    return $out;
}



function imagepress_get_upload_image_form_bulk($imagepress_image_category = 0, $imagepress_hardcoded_category) {
    $current_user = wp_get_current_user();

    // upload form // customize

    // labels
    $ip_caption_label = get_imagepress_option('ip_caption_label');
    $ip_description_label = get_imagepress_option('ip_description_label');

    $ip_request_user_details = get_imagepress_option('ip_request_user_details');
    $ip_upload_size = get_imagepress_option('ip_upload_size');

    $out = '<div class="ip-uploader">';
        $out .= '<form id="imagepress_upload_image_form_bulk" method="post" action="" enctype="multipart/form-data" class="imagepress-upload-form">';
            $out .= wp_nonce_field('imagepress_upload_image_form_bulk', 'imagepress_upload_image_form_submitted_bulk');
            // name and email
            if($ip_request_user_details == 1) {
                $out .= '<p><input type="text" name="imagepress_author" value="' . $current_user->display_name . '" placeholder="' . get_imagepress_option('ip_name_label') . '"></p>';
                $out .= '<p><input type="email" name="imagepress_email" value="' . $current_user->user_email . '" placeholder="' . get_imagepress_option('ip_email_label') . '"></p>';
            } else {
                $out .= '<input type="hidden" name="imagepress_author" value="' . $current_user->display_name . '">';
                $out .= '<input type="hidden" name="imagepress_email" value="' . $current_user->user_email . '">';
            }

            $out .= '<input type="hidden" name="imagepress_author" value="' . $current_user->display_name . '">';
            $out .= '<input type="hidden" name="imagepress_email" value="' . $current_user->user_email . '">';

            $out .= '<div id="fileuploads">';
                if(!empty($ip_caption_label))
                    $out .= '<p><input type="text" id="imagepress_image_caption" name="imagepress_image_caption[]" placeholder="' . $ip_caption_label . '" required></p>';

                if(!empty($ip_description_label)) {
                    $out .= '<p><textarea id="imagepress_image_description" name="imagepress_image_description[]" placeholder="' . get_imagepress_option('ip_description_label') . '" rows="6"></textarea></p>';
                }

                $out .= '<p>';
                    if('' != $imagepress_hardcoded_category) {
                        $iphcc = get_term_by('slug', $imagepress_hardcoded_category, 'imagepress_image_category'); // ImagePress hard-coded category
                        $out .= '<input type="hidden" id="imagepress_image_category" name="imagepress_image_category[]" value="' . $iphcc->term_id . '">';
                    }
                    else {
                        $out .= imagepress_get_image_categories_dropdown_bulk('imagepress_image_category', '') . '';
                    }
                $out .= '</p>';

                $out .= '<hr>';
                $out .= '<div id="imagepress-errors"></div>';

                $uploadsize = number_format((($ip_upload_size * 1024)/1024000), 0, '.', '');
                $datauploadsize = $uploadsize * 1024000;

                $out .= '<p><label for="imagepress_image_file"><i class="fas fa-cloud-upload-alt"></i> ' . __('Select a file', 'imagepress') . ' (' . $uploadsize . 'MB ' . __('maximum', 'imagepress') . ')...</label><br><input type="file" accept="image/*" data-max-size="' . $datauploadsize . '" name="imagepress_image_file_bulk[]" id="imagepress_image_file_bulk"></p>
                <hr>
            </div>
            <div id="endOfForm"></div>';

            $out .= '<div class="ip-addmore"><a href="#" onclick="addMoreFiles(); return false;" class="button noir-secondary"><i class="fas fa-plus-circle"></i> ' . __('Add more', 'imagepress') . '</a></div>';

            $out .= '<p>';
                $out .= '<input type="submit" id="imagepress_submit_bulk" name="imagepress_submit_bulk" value="' . get_imagepress_option('ip_upload_label') . '" class="button noir-secondary">';
                $out .= ' <span id="ipload"></span>';
            $out .= '</p>';
        $out .= '</form>';
    $out .= '</div>';

    return $out;
}






function imagepress_get_image_categories_dropdown($taxonomy, $selected) {
    return wp_dropdown_categories(array(
        'taxonomy' => $taxonomy,
        'name' => 'imagepress_image_category',
        'selected' => $selected,
        'exclude' => get_imagepress_option('ip_cat_exclude'),
        'hide_empty' => 0,
        'echo' => 0,
        'orderby' => 'name',
        'show_option_all' => get_imagepress_option('ip_category_label'),
        'required' => true
    ));
}
function imagepress_get_image_categories_dropdown_bulk($taxonomy, $selected) {
    return wp_dropdown_categories(array(
        'taxonomy' => $taxonomy,
        'name' => 'imagepress_image_category[]',
        'selected' => $selected,
        'exclude' => get_imagepress_option('ip_cat_exclude'),
        'hide_empty' => 0,
        'echo' => 0,
        'orderby' => 'name',
        'show_option_all' => get_imagepress_option('ip_category_label')
    ));
}
function imagepress_get_image_tags_dropdown($taxonomy, $selected) {
    return wp_dropdown_categories(array(
        'taxonomy' => $taxonomy,
        'name' => 'imagepress_image_tag',
        'selected' => $selected,
        'hide_empty' => 0,
        'echo' => 0,
        'orderby' => 'name',
        'show_option_all' => get_imagepress_option('ip_tag_label')
    ));
}

function imagepress_activate() {
    global $wpdb;

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    // notifications table
    $table_name = $wpdb->prefix . 'notifications';
    if ($wpdb->get_var("SHOW TABLES LIKE `$table_name`") != $table_name) {
        $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
            `ID` int(11) NOT NULL AUTO_INCREMENT,
            `userID` int(11) NOT NULL,
            `postID` int(11) NOT NULL,
            `postKeyID` int(11) NOT NULL,
            `actionType` mediumtext COLLATE utf8_unicode_ci NOT NULL,
            `actionIcon` mediumtext COLLATE utf8_unicode_ci NOT NULL,
            `actionTime` datetime NOT NULL,
            `status` tinyint(1) NOT NULL DEFAULT '0',
            PRIMARY KEY (`ID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

        dbDelta($sql);
        maybe_convert_table_to_utf8mb4($table_name);
    }

    // collections table
    $table_name = $wpdb->prefix . 'ip_collections';
    if ($wpdb->get_var("SHOW TABLES LIKE `$table_name`") != $table_name) {
        $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
            `collection_ID` int(11) NOT NULL AUTO_INCREMENT,
            `collection_title` mediumtext COLLATE utf8_unicode_ci NOT NULL,
            `collection_title_slug` mediumtext COLLATE utf8_unicode_ci NOT NULL,
            `collection_status` tinyint(4) NOT NULL DEFAULT '1',
            `collection_views` int(11) NOT NULL,
            `collection_author_ID` int(11) NOT NULL,
            PRIMARY KEY (`collection_ID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

        dbDelta($sql);
        maybe_convert_table_to_utf8mb4($table_name);
    }
    $table_name = $wpdb->prefix . 'ip_collectionmeta';
    if ($wpdb->get_var("SHOW TABLES LIKE `$table_name`") != $table_name) {
        $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
            `image_meta_ID` int(11) NOT NULL AUTO_INCREMENT,
            `image_ID` int(11) NOT NULL,
            `image_collection_ID` int(11) NOT NULL,
            `image_collection_author_ID` int(11) NOT NULL,
            PRIMARY KEY (`image_meta_ID`),
            UNIQUE KEY `image_meta_ID` (`image_meta_ID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

        dbDelta($sql);
        maybe_convert_table_to_utf8mb4($table_name);
    }

    // custom fields table
    $table_name = $wpdb->prefix . 'ip_fields';
    if ($wpdb->get_var("SHOW TABLES LIKE `$table_name`") != $table_name) {
        $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
            `field_id` int(11) NOT NULL AUTO_INCREMENT,
            `field_order` int(11) NOT NULL,
            `field_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
            `field_slug` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
            `field_type` tinyint(4) NOT NULL,
            `field_content` mediumtext COLLATE utf8_unicode_ci NOT NULL,
            PRIMARY KEY (`field_id`),
            UNIQUE KEY `field_id` (`field_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";

        dbDelta($sql);
        maybe_convert_table_to_utf8mb4($table_name);
    }
}

function imagepress_deactivate() {
    flush_rewrite_rules();
}

register_activation_hook(__FILE__, 'imagepress_activate');
register_deactivation_hook(__FILE__, 'imagepress_deactivate');
//register_uninstall_hook( __FILE__, 'imagepress_uninstall');



// enqueue admin scripts and styles
// colour picker
add_action('admin_enqueue_scripts', 'ip_enqueue_color_picker');
function ip_enqueue_color_picker() {
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('fa5', 'https://use.fontawesome.com/releases/v5.0.6/js/all.js', array(), '5.0.6', true);
    wp_enqueue_style('imagepress', plugins_url('css/ip-admin.css', __FILE__));
    wp_enqueue_script('ip.functions', plugins_url('js/functions.js', __FILE__), array('wp-color-picker'), false, true);
}



add_action('wp_enqueue_scripts', 'ip_enqueue_scripts');
function ip_enqueue_scripts() {
    wp_enqueue_script('fa5', 'https://use.fontawesome.com/releases/v5.0.6/js/all.js', array(), '5.0.6', true);

    wp_enqueue_style('ip-reset', plugins_url('css/ip-reset.css', __FILE__));
    wp_enqueue_style('ip-bootstrap', plugins_url('css/ip-bootstrap.css', __FILE__));

    if (get_imagepress_option('ip_ezdz') === '1') {
        wp_enqueue_script('ezdz', plugins_url('js/jquery.ezdz.js', __FILE__), array(), '0.5.1', true);
    }

    if (get_imagepress_option('ip_grid_ui') === 'masonry') {
        wp_enqueue_script('masonry');
        $grid_ui = 'masonry'; // jQuery Masonry
    } else if (get_imagepress_option('ip_grid_ui') === 'default') {
        $grid_ui = 'default'; // jQuery equalHeight
    } else {
        $grid_ui = 'basic';
    }

	$accountPageUri = get_option('cinnamon_account_page');

    wp_enqueue_script('ipjs-main', plugins_url('js/jquery.main.js', __FILE__), array('jquery', 'jquery-ui-core', 'jquery-ui-sortable'), '6.8.0', true);
    wp_localize_script('ipjs-main', 'ipAjaxVar', array(
        'imagesperpage' => get_imagepress_option('ip_ipp'),
        'authorsperpage' => get_imagepress_option('ip_app'),
        'likelabel' => get_imagepress_option('ip_vote_like'),
        'unlikelabel' => get_imagepress_option('ip_vote_unlike'),
        'processing_error' => __('There was a problem processing your request.', 'imagepress'),
        'login_required' => __('Oops, you must be logged-in to follow users.', 'imagepress'),
        'logged_in' => is_user_logged_in() ? 'true' : 'false',
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('ajax-nonce'),
        'ip_url' => IP_PLUGIN_URL,
        'grid_ui' => $grid_ui,

        'redirecturl' => apply_filters('fum_redirect_to', $accountPageUri),
		'loadingmessage' => __('Checking credentials...', 'imagepress'),
		'registrationloadingmessage' => __('Processing registration...', 'imagepress'),
    ));
}
// end

function imagepress_search($atts) {
    extract(shortcode_atts(array(
        'type' => '',
    ), $atts));

    $display = '<form role="search" method="get" action="' . home_url() . '" class="imagepress-form">
            <div>
                <input type="search" name="s" id="s" placeholder="' . __('Search images...', 'imagepress') . '">
                <input type="submit" id="searchsubmit" value="' . __('Search', 'imagepress') . '">
                <input type="hidden" name="post_type" value="' . get_imagepress_option('ip_slug') . '">
            </div>
        </form>';

    return $display;
}



function imagepress_notify_status($new_status, $old_status, $post) {
    global $current_user;
    $contributor = get_userdata($post->post_author);

    $headers[] = "MIME-Version: 1.0\r\n";
    $headers[] = "Content-Type: text/html; charset=\"" . get_option('blog_charset') . "\"\r\n";

    if($old_status != 'pending' && $new_status == 'pending') {
        $emails = get_imagepress_option('ip_notification_email');
        if(strlen($emails)) {
            $subject = '[' . get_option('blogname') . '] "' . $post->post_title . '" pending review';
            $message = "<p>A new post by {$contributor->display_name} is pending review.</p>";
            $message .= "<p>Author: {$contributor->user_login} <{$contributor->user_email}></p>";
            $message .= "<p>Title: {$post->post_title}</p>";
            $category = get_the_category($post->ID);
            if(isset($category[0]))
                $message .= "<p>Category: {$category[0]->name}</p>";
            wp_mail($emails, $subject, $message, $headers);
        }
    }
    elseif($old_status == 'pending' && $new_status == 'publish') {
        if(get_imagepress_option('approvednotification') == 'yes') {
            $subject = '[' . get_option('blogname') . '] "' . $post->post_title . '" approved';
            $message = "<p>{$contributor->display_name}, your post has been approved and published at " . get_permalink($post->ID) . ".</p>";
            wp_mail($contributor->user_email, $subject, $message, $headers);
        }
    }
    elseif($old_status == 'pending' && $new_status == 'draft' && $current_user->ID != $contributor->ID) {
        if(get_imagepress_option('declinednotification') == 'yes') {
            $subject = '[' . get_option('blogname') . '] "' . $post->post_title . '" declined';
            $message = "<p>{$contributor->display_name}, your post has not been approved.</p>";
            wp_mail($contributor->user_email, $subject, $message, $headers);
        }
    }
}

/*
 * Main shortcode function [imagepress]
 *
 */
function imagepress_widget($atts) {
    extract(shortcode_atts(array(
        'type' => 'list', // list, top
        'mode' => 'views', // views, likes
        'count' => 5,
    ), $atts));

    $display = '';
    $mode = (string) sanitize_text_field($mode);
    $type = (string) sanitize_text_field($type);

    $imagepress_meta_key = 'post_views_count';
    if ($mode === 'likes') {
        $imagepress_meta_key = '_like_count';
    }

    if ($type === 'top') {
        $count = 1;
    }

    $args = array(
        'post_type' => get_imagepress_option('ip_slug'),
        'posts_per_page' => $count,
        'orderby' => 'meta_value_num',
        'meta_key' => $imagepress_meta_key,
        'meta_query' => array(
            array(
                'key' => $imagepress_meta_key,
                'type' => 'numeric',
            ),
        ),
    );

    $getImages = get_posts($args);

    if ($getImages && ($type == 'list')) {
        $display .= '<ul>';
            foreach ($getImages as $image) {
                if ($mode == 'likes')
                    $ip_link_value = imagepress_get_like_count($image->ID);
                if ($mode == 'views')
                    $ip_link_value = ip_getPostViews($image);
                if (empty($ip_link_value))
                    $ip_link_value = 0;

                $display .= '<li><a href="' . get_permalink($image->ID) . '">' . get_the_title($image->ID) . '</a> <small>(' . $ip_link_value . ')</small></li>';
            }
        $display .= '</ul>';
    }

    if ($getImages && ($type == 'top')) {
        $display .= '';
        foreach ($getImages as $image) {
            if (get_imagepress_option('ip_comments') == 1)
                $ip_comments = '<i class="fas fa-comments"></i> ' . get_comments_number($image->ID) . '';
            if (get_imagepress_option('ip_comments') == 0)
                $ip_comments = '';

            $post_thumbnail_id = get_post_thumbnail_id($image);
            $image_attributes = wp_get_attachment_image_src($post_thumbnail_id, 'full');

            if (get_imagepress_option('ip_click_behaviour') == 'media')
                $ip_image_link = $image_attributes[0];
            if (get_imagepress_option('ip_click_behaviour') == 'custom')
                $ip_image_link = get_permalink($image->ID);

            $display .= '<div id="ip_container_2"><div class="ip_icon_hover">' .
                    '<div><strong>' . get_the_title($image->ID) . '</strong></div>' .
                    '<div><small><i class="fas fa-eye"></i> ' . ip_getPostViews($image->ID) . ' ' . $ip_comments . ' <i class="fas fa-heart"></i> ' . imagepress_get_like_count($image->ID) . '</small></div>
                </div><a href="' . $ip_image_link . '" class="ip-link">' . wp_get_attachment_image($post_thumbnail_id, 'full') . '</a></div>';
        }
    }

    return $display;
}
