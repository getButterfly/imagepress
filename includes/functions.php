<?php
function imagepress_registration() {
    $ip_slug = sanitize_text_field(imagepress_get_option('ip_slug'));

    if (empty($ip_slug)) {
        $ip_slug = 'image';
    }

    $image_type_labels = [
        'name'                  => _x('Images', 'Post type general name', 'imagepress'),
        'singular_name'         => _x('Image', 'Post type singular name', 'imagepress'),
        'menu_name'             => __('ImagePress', 'imagepress'),
        'name_admin_bar'        => __('Image', 'imagepress'),
        'archives'              => __('Image archives', 'imagepress'),
        'parent_item_colon'     => __('Parent image:', 'imagepress'),
        'all_items'             => __('All images', 'imagepress'),
        'add_new_item'          => __('Add new image', 'imagepress'),
        'add_new'               => __('Add new', 'imagepress'),
        'new_item'              => __('New image', 'imagepress'),
        'edit_item'             => __('Edit image', 'imagepress'),
        'update_item'           => __('Update image', 'imagepress'),
        'view_item'             => __('View image', 'imagepress'),
        'search_items'          => __('Search image', 'imagepress'),
        'not_found'             => __('Not found', 'imagepress'),
        'not_found_in_trash'    => __('Not found in trash', 'imagepress'),
        'featured_image'        => __('Featured image', 'imagepress'),
        'set_featured_image'    => __('Set featured image', 'imagepress'),
        'remove_featured_image' => __('Remove featured image', 'imagepress'),
        'use_featured_image'    => __('Use as featured image', 'imagepress'),
        'insert_into_item'      => __('Insert into image', 'imagepress'),
        'uploaded_to_this_item' => __('Uploaded to this image', 'imagepress'),
        'items_list'            => __('Images list', 'imagepress'),
        'items_list_navigation' => __('Images list navigation', 'imagepress'),
        'filter_items_list'     => __('Filter images list', 'imagepress'),
    ];

    $image_type_args = [
        'label'                 => __('Image', 'imagepress'),
        'description'           => __('Image post type', 'imagepress'),
        'labels'                => $image_type_labels,
        'supports'              => ['title', 'editor', 'author', 'thumbnail', 'comments', 'custom-fields', 'publicize', 'wpcom-markdown'],
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-format-gallery',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
        'rest_base'             => $ip_slug,
        'rest_controller_class' => 'WP_REST_Posts_Controller',
    ];

    register_post_type($ip_slug, $image_type_args);

    $imageTaxonomy = [
        'name'                       => _x('Image categories', 'Taxonomy general name', 'imagepress'),
        'singular_name'              => _x('Image category', 'Taxonomy singular name', 'imagepress'),
        'menu_name'                  => __('Image Categories', 'imagepress'),
        'all_items'                  => __('All image categories', 'imagepress'),
        'parent_item'                => __('Parent image category', 'imagepress'),
        'parent_item_colon'          => __('Parent image category:', 'imagepress'),
        'new_item_name'              => __('New image category', 'imagepress'),
        'add_new_item'               => __('Add new image category', 'imagepress'),
        'edit_item'                  => __('Edit image category', 'imagepress'),
        'update_item'                => __('Update image category', 'imagepress'),
        'view_item'                  => __('View image category', 'imagepress'),
        'separate_items_with_commas' => __('Separate image categories with commas', 'imagepress'),
        'add_or_remove_items'        => __('Add or remove image categories', 'imagepress'),
        'choose_from_most_used'      => __('Choose from the most used', 'imagepress'),
        'popular_items'              => __('Popular image categories', 'imagepress'),
        'search_items'               => __('Search image categories', 'imagepress'),
        'not_found'                  => __('Not found', 'imagepress'),
        'no_terms'                   => __('No image categories', 'imagepress'),
        'items_list'                 => __('Image categories list', 'imagepress'),
        'items_list_navigation'      => __('Image categories list navigation', 'imagepress'),
    ];

    $image_category_args = [
        'labels'                => $imageTaxonomy,
        'hierarchical'          => true,
        'public'                => true,
        'show_ui'               => true,
        'show_admin_column'     => true,
        'show_in_nav_menus'     => true,
        'show_tagcloud'         => false,
        'show_in_rest'          => true,
        'rest_base'             => 'image-category',
        'rest_controller_class' => 'WP_REST_Terms_Controller',
    ];

    register_taxonomy('imagepress_image_category', [$ip_slug], $image_category_args);
}

function imagepress_get_post_views($postID) {
    $count = get_post_meta($postID, 'post_views_count', true);
    $count = empty($count) ? 0 : $count;

    update_post_meta($postID, 'post_views_count', $count);

    return $count;
}
function imagepress_set_post_views($postID) {
    $count = get_post_meta($postID, 'post_views_count', true);
    $count = empty($count) ? 1 : $count + 1;

    update_post_meta($postID, 'post_views_count', $count);
}



// frontend image editor
function imagepress_editor() {
    global $wpdb, $post;

    $out = '';

    $current_user = wp_get_current_user();

    // check if user is author // show author tools
    if ($post->post_author == $current_user->ID) {
        $out .= ' | <a href="#" class="ip-editor-display" id="ip-editor-open">' . __('Author tools', 'imagepress') . '</a>';

        $edit_id = get_the_ID();

        if (!empty($_POST['post_id']) && !empty($_POST['post_title']) && isset($_POST['update_post_nonce']) && isset($_POST['postcontent'])) {
            $post_id = $_POST['post_id'];
            $post_type = get_post_type($post_id);
            $capability = ('page' == $post_type) ? 'edit_page' : 'edit_post';
            if (current_user_can($capability, $post_id) && wp_verify_nonce($_POST['update_post_nonce'], 'update_post_'. $post_id)) {
                $post = [
                    'ID' => esc_sql($post_id),
                    'post_content' => (stripslashes($_POST['postcontent'])),
                    'post_title' => esc_sql($_POST['post_title']),
                    'post_name' => sanitize_text_field($_POST['post_title'])
                ];
                wp_update_post($post);

                // Multiple images
                imagepress_upload_secondary($_FILES['imagepress_image_additional'], $post_id);

                wp_set_object_terms($post_id, (int) $_POST['imagepress_image_category'], 'imagepress_image_category');
            }
        }

        $out .= '<div id="info" class="ip-editor">
            <form id="post" class="post-edit front-end-form imagepress-form thin-ui-form" method="post" enctype="multipart/form-data">
                <input type="hidden" name="post_id" value="' . $edit_id . '">';
                $out .= wp_nonce_field('update_post_' . $edit_id, 'update_post_nonce', true, false);

                $out .= '<p>
                    <label for="post_title">' . __('Title', 'imagepress') . '</label><br>
                    <input type="text" id="post_title" name="post_title" value="' . get_the_title($edit_id) . '">
                </p>
                <p>
                    <label for="postcontent">' . __('Description', 'imagepress') . '</label><br>
                    <textarea id="postcontent" name="postcontent" rows="3">' . strip_tags(get_post_field('post_content', $edit_id)) . '</textarea></p>
                <hr>';

                $out .= '<hr>';

                $ip_category = wp_get_object_terms($edit_id, 'imagepress_image_category');

                $out .= imagepress_get_image_categories_dropdown('imagepress_image_category', $ip_category[0]->term_id);

                $ip_upload_size = imagepress_get_option('ip_upload_size');
                $uploadsize = number_format((($ip_upload_size * 1024)/1024000), 0, '.', '');
                $datauploadsize = $uploadsize * 1024000;

                if ((int) imagepress_get_option('imagepress_upload_secondary') === 1) {
                    $out .= '<hr>
                    <p>' .
                        __('Delete additional images', 'imagepress') .
                    '</p>';

                    $thumbnail_ID = get_post_thumbnail_id();
                    $images = get_children(['post_parent' => $edit_id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID']);
                    $count = $images ? count($images) : 0;

                    if ($count >= 1) {
                        $out .= '<div>';
                            foreach ($images as $attachment_id => $image) {
                                $small_array = image_downsize($image->ID, 'thumbnail');

                                if ($image->ID != $thumbnail_ID) {
                                    $out .= '<div class="ip-additional ip-additional-' . $image->ID . '">
                                        <div class="ip-toolbar">
                                            <a href="#" data-image-id="' . $image->ID . '" data-redirect="' . get_permalink() . '" class="ip-delete-post ip-action-icon ip-floatright"><i class="fas fa-trash-alt"></i></a>
                                        </div>
                                        <img src="' . $small_array[0] . '" alt="">
                                    </div>';
                                }
                            }
                        $out .= '</div>';
                    }

                    $out .= '<p><label for="imagepress_image_additional">' . __('Add more images', 'imagepress') . ' (' . $uploadsize . 'MB ' . __('maximum', 'imagepress') . ')...</label><br><input type="file" accept="image/*" data-max-size="' . $datauploadsize . '" name="imagepress_image_additional[]" id="imagepress_image_additional" multiple></p>';
                }

                $out .= '<hr>';

                $out .= '<p>
                    <input type="submit" id="submit" value="' . __('Update', 'imagepress') . '">
                </p>
            </form>
        </div>';

        wp_reset_query();

        return $out;
    }
}

// imagepress_editor() related actions
add_action('wp_ajax_imagepress_delete_post', 'imagepress_delete_post');
function imagepress_delete_post() {
    $imageId = (int) $_POST['id'];

    if (wp_delete_post($imageId)) {
        echo 'success';
    }

    die();
}
add_action('wp_ajax_imagepress_update_post_title', 'imagepress_update_post_title');
function imagepress_update_post_title() {
    $updated_post = [
        'ID' => (int) $_REQUEST['id'],
        'post_title' => (string) $_REQUEST['title']
    ];

    wp_update_post($updated_post);

    echo 'success';
    die();
}



// main ImagePress image function
function imagepress_main($imageId) {
    global $wpdb, $post;

    $postThumbnailId = get_post_thumbnail_id($imageId);
    $image_attributes = wp_get_attachment_image_src($postThumbnailId, 'full');
    $post_thumbnail_url = $image_attributes[0];

    $ip_comments = '';
    if ((int) imagepress_get_option('ip_comments') === 1) {
        $ip_comments = '<em> | </em><a href="' . get_permalink($imageId) . '"><i class="fas fa-comments"></i> ' . get_comments_number($imageId) . '</a> ';
    }
    ?>

    <div class="imagepress-container">
        <a href="<?php echo $post_thumbnail_url; ?>">
            <?php the_post_thumbnail('full'); ?>
        </a>
        <?php imagepress_set_post_views($imageId); ?>
    </div>

    <div class="ip-bar">
        <?php echo ipGetPostLikeLink($imageId); ?><em> | </em>
        <i class="far fa-eye"></i> <?php echo imagepress_get_post_views($imageId); ?>
        <?php echo $ip_comments; ?>
        <em> | </em>
        <?php if (function_exists('ip_frontend_add_collection')) {
            echo ip_frontend_add_collection(get_the_ID());
        }

        /*
         * Image editor
         */
        echo imagepress_editor();
        ?>
    </div>

    <h1 class="ip-title">
        <?php echo get_the_title($imageId); ?>
    </h1>

    <p>
        <?php echo get_avatar($post->post_author, 40); ?><br>
        <?php _e('by', 'imagepress'); ?> <b><?php echo imagepress_get_profile_uri($post->post_author); ?></b>
        <br><small><?php _e('Uploaded', 'imagepress'); ?> <time title="<?php the_time(get_option('date_format')); ?>"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago'; ?></time> <?php _e('in', 'imagepress'); ?> <?php echo get_the_term_list(get_the_ID(), 'imagepress_image_category', '', ', ', ''); ?></small>
    </p>

    <?php imagepress_get_images($imageId, 1); ?>

    <section>
        <?php the_content(); ?>
    </section>

    <section role="navigation">
        <?php
        previous_post_link('%link', __('Previous', 'imagepress'));
        next_post_link('%link', __('Next', 'imagepress'));
        ?>
    </section>
    <?php
}



// main ImagePress image function
function imagepress_main_return($imageId) {
    global $wpdb, $post;

    $out = '';
    imagepress_set_post_views($imageId);

    $postThumbnailId = get_post_thumbnail_id($imageId);
    $image_attributes = wp_get_attachment_image_src($postThumbnailId, 'full');
    $post_thumbnail_url = $image_attributes[0];

    $ip_comments = '';

    if ((int) imagepress_get_option('ip_comments') === 1) {
        $ip_comments = '<em> | </em><a href="' . get_permalink($imageId) . '"><i class="fas fa-comments"></i> ' . get_comments_number($imageId) . '</a> ';
    }

    $out .= '<div class="imagepress-container">
        <a href="' . $post_thumbnail_url . '">' . get_the_post_thumbnail($imageId, 'full') . '</a>
    </div>

    <div class="ip-bar">' .
        ipGetPostLikeLink($imageId) . '<em> | </em>';
        $out .= '<i class="far fa-eye"></i> ' . imagepress_get_post_views($imageId);
        $out .= $ip_comments;

        $out .= '<em> | </em>';
        if (function_exists('ip_frontend_add_collection')) {
            $out .= ip_frontend_add_collection(get_the_ID());
        }

        $out .= imagepress_editor();
    $out .= '</div>

    <p>
        ' . get_avatar($post->post_author, 40) . '<br>' .
        __('Added by', 'imagepress') . ' <b>' . imagepress_get_profile_uri($post->post_author) . '</b>
        <br><small><time title="' . date_i18n(get_option('time_format'), get_the_time('U')) . '">' . date_i18n(get_option('date_format'), get_the_time('U')) . '</time> ' . __('in', 'imagepress') . ' ' . get_the_term_list(get_the_ID(), 'imagepress_image_category', '', ', ', '') . '</small>
    </p>';

    $out .= '<section>' .
        get_the_content() .
    '</section>';

    $out .= imagepress_get_images($imageId, 0);

    $out .= '<hr><section role="navigation"><p>' .
        get_previous_post_link('%link', __('Previous', 'imagepress')) . ' | ' .
        get_next_post_link('%link', __('Next', 'imagepress')) .
    '</p></section>';

    return $out;
}



function imagepress_get_images($postId, $show) {
    $thumbnail_ID = get_post_thumbnail_id();
    $images = get_children(['post_parent' => $postId, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID']);
    $out = $galleryImages = '';
    $galleryImagesIds = [];

    if ($images && count($images) > 0) {
        foreach ($images as $attachment_id => $image) {
            if ($image->ID != $thumbnail_ID) {
                $big_array = image_downsize($image->ID, 'full');

                $galleryImagesIds[] = $image->ID;
                $galleryImages .= '<li class="blocks-gallery-item">
                    <figure>
                        <a href="' . get_permalink($image->ID) . '"><img src="' . $big_array[0] . '" alt="" data-id="' . $image->ID . '" data-link="' . get_permalink($image->ID) . '" class="wp-image-' . $image->ID . '"></a>
                    </figure>
                </li>';
            }
        }

        $out .= '<!-- wp:gallery {"ids":[' . implode(',', $galleryImagesIds) . '],"linkTo":"media"} -->';
        $out .= '<ul class="wp-block-gallery columns-3 is-cropped">';
            $out .= $galleryImages;
        $out .= '</ul>';
    }

    $videos = get_children(['post_parent' => $postId, 'post_status' => 'inherit', 'post_type' => 'attachment', 'order' => 'ASC', 'orderby' => 'menu_order ID']);

    if ((int) $show === 1) {
        echo $out;
    } else {
        return $out;
    }
}

function imagepress_kformat($number) {
    $number = (int) $number;

    return number_format($number, 0, '.', ',');
}

function imagepress_related() {
    global $post;

    $out = '<h3>' . __('More by the same author', 'imagepress') . '</h3>' .
    imagepress_get_related_author_posts($post->post_author);

    return $out;
}

function imagepress_author() {
    echo do_shortcode('[cinnamon-profile]');
}





function imagepress_return_image_sizes() {
    global $_wp_additional_image_sizes;

    $image_sizes = [];
    foreach (get_intermediate_image_sizes() as $size) {
        $image_sizes[$size] = [0, 0];
        if (in_array($size, ['thumbnail', 'medium', 'large'])) {
            $image_sizes[$size][0] = get_option($size . '_size_w');
            $image_sizes[$size][1] = get_option($size . '_size_h');
        } else if (isset($_wp_additional_image_sizes) && isset($_wp_additional_image_sizes[$size])) {
            $image_sizes[$size] = [$_wp_additional_image_sizes[$size]['width'], $_wp_additional_image_sizes[$size]['height']];
        }
    }
    return $image_sizes;
}

function imagepress_order_list() {
    global $wpdb;

    $order = explode(',', $_POST['order']);

    foreach ($order as $key => $value) {
        $wpdb->query($wpdb->prepare("UPDATE `" . $wpdb->prefix . "posts` SET `menu_order` = %d WHERE `ID` = %d", $key, $value));
    }
}
add_action('wp_ajax_imagepress_list_update_order','imagepress_order_list');
add_action('wp_ajax_nopriv_imagepress_list_update_order','imagepress_order_list');



/*
 * Refactoring of option management functions.
 * Use a get_option() wrapper.
 */
function imagepress_get_option($option) {
    $ipOptions = get_option('imagepress');

    return $ipOptions[$option];
}

function imagepress_update_option($optionArray) {
    $imagePressOption = get_option('imagepress');
    $updatedArray = array_merge($imagePressOption, $optionArray);

    update_option('imagepress', $updatedArray);
}

function imagepress_get_profile_uri($authorId, $structure = true) {
    $ipProfilePageId = (int) imagepress_get_option('ip_profile_page');
    $ipProfilePageUri = get_permalink($ipProfilePageId);
    $ipProfileSlug = (string) imagepress_get_option('cinnamon_author_slug');

    $ipProfileLink = '<span class="name"><a href="' . $ipProfilePageUri . '?' . $ipProfileSlug . '=' . get_the_author_meta('user_nicename', $authorId) . '">' . get_the_author_meta('user_nicename', $authorId) . '</a></span>';

    if ($structure === false) {
        $ipProfileLink = $ipProfilePageUri . '?' . $ipProfileSlug . '=' . get_the_author_meta('user_nicename', $authorId);
    }

    return $ipProfileLink;
}

function imagepress_render_grid_element($elementId) {
    $postThumbnailId = get_post_thumbnail_id($elementId);

    $ip_click_behaviour = imagepress_get_option('ip_click_behaviour');
    $getImagePressTitle = imagepress_get_option('ip_title_optional');
    $getImagePressAuthor = imagepress_get_option('ip_author_optional');
    $getImagePressMeta = imagepress_get_option('ip_meta_optional');
    $getImagePressViews = imagepress_get_option('ip_views_optional');
    $getImagePressLikes = imagepress_get_option('ip_likes_optional');
    $get_ip_comments = imagepress_get_option('ip_comments');
    $size = imagepress_get_option('ip_image_size');

    if ($ip_click_behaviour === 'media') {
        // Get attachment source
        $image_attributes = wp_get_attachment_image_src($postThumbnailId, 'full');

        $ip_image_link = $image_attributes[0];
    } else if ($ip_click_behaviour === 'custom') {
        $ip_image_link = get_permalink($elementId);
    }

    // Make all "brick" elements optional and active by default
    $ip_title_optional = '';
    if ((int) $getImagePressTitle === 1) {
        $ip_title_optional = '<span class="imagetitle">' . get_the_title($elementId) . '</span>';
    }

    $ip_author_optional = '';
    if ((int) $getImagePressAuthor === 1) {
        // Get post author ID
        $post_author_id = get_post_field('post_author', $elementId);

        $ip_author_optional = imagepress_get_profile_uri($post_author_id);
    }

    $ip_meta_optional = '';
    if ((int) $getImagePressMeta === 1)
        $ip_meta_optional = '<span class="imagecategory" data-tag="' . strip_tags(get_the_term_list($elementId, 'imagepress_image_category', '', ', ', '')) . '">' . strip_tags(get_the_term_list($elementId, 'imagepress_image_category', '', ', ', '')) . '</span>';

    $ip_views_optional = '';
    if ((int) $getImagePressViews === 1) {
        $ip_views_optional = '<span class="imageviews"><i class="far fa-eye"></i> ' . imagepress_get_post_views($elementId) . '</span> ';
    }

    $ip_comments = '';
    if ($get_ip_comments == 1)
        $ip_comments = '<span class="imagecomments"><i class="fas fa-comments"></i> ' . get_comments_number($elementId) . '</span> ';

    $ip_likes_optional = '';
    if ((int) $getImagePressLikes === 1)
        $ip_likes_optional = '<span class="imagelikes"><i class="fas fa-heart"></i> ' . imagepress_get_like_count($elementId) . '</span> ';

    $image_attributes = wp_get_attachment_image_src($postThumbnailId, $size);

    $out = '<div class="ip_box ip_box_' . $elementId . '">
        <a href="' . $ip_image_link . '" data-taxonomy="' . strip_tags(get_the_term_list($elementId, 'imagepress_image_category', '', ', ', '')) . '" data-src="' . $image_attributes[0] . '" title="' . get_the_title($elementId) . '" class="ip-anchor"><img src="' . $image_attributes[0] . '" alt="' . get_the_title($elementId) . '"></a>
        <div class="ip_box_top">' . $ip_title_optional . $ip_author_optional . $ip_meta_optional . '</div>
        <div class="ip_box_bottom">' . $ip_views_optional . $ip_comments . $ip_likes_optional . '</div>
    </div>';

    return $out;
}

/**
 * Display all collections based on current user ID
 */
function imagepress_collection_dropdown() {
    global $wpdb;

    $result = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_collections WHERE collection_author_ID = '" . get_current_user_id() . "'", ARRAY_A);

    $out = '<hr>
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

    return $out;
}

/**
 * Upload secondary images
 */
function imagepress_upload_secondary($filesArray, $postId) {
    if ((int) imagepress_get_option('imagepress_upload_secondary') === 1) {
        if ($filesArray) {
            require_once ABSPATH . "wp-admin" . '/includes/image.php';
            require_once ABSPATH . "wp-admin" . '/includes/file.php';
            require_once ABSPATH . "wp-admin" . '/includes/media.php';

            foreach ($filesArray['name'] as $key => $value) {
                if ($filesArray['name'][$key]) {
                    $file = [
                        'name' => $filesArray['name'][$key],
                        'type' => $filesArray['type'][$key],
                        'tmp_name' => $filesArray['tmp_name'][$key],
                        'error' => $filesArray['error'][$key],
                        'size' => $filesArray['size'][$key]
                    ];
                }
                $_FILES = ["attachment" => $file];
                foreach ($_FILES as $file => $array) {
                    $attach_id = media_handle_upload($file, $postId);
                }
            }
        }
    }
}
















/**
 * Log into WordPress
 *
 * Logs the user into WordPress, registers a new account or recovers the password
 *
 * @return string $out
 */
function imagepress_login() {
    global $user_ID, $user_identity;

    $out = '';

	if (!$user_ID) {
        $register = sanitize_text_field($_GET['register']);
        $reset = sanitize_text_field($_GET['reset']);

        if ((bool) $register === true) {
            $out .= '<h3>' . __('Success!', 'imagepress') . '</h3>
            <p>' . __('Check your email for the password and then return to log in.', 'imagepress') . '</p>';
        } else if ((bool) $reset === true) {
            $out .= '<h3>' . __('Success!', 'imagepress') . '</h3>
            <p>' . __('Check your email to reset your password.', 'imagepress') . '</p>';
        } else {
            $out .= '<h3>' . __('Have an account?', 'imagepress') . '</h3>
            <p>' . __('Log in or sign up!', 'imagepress') . '</p>';
        }

        $out .= '<div class="wp-block-columns">
            <div class="wp-block-column">
                <h3>' . __('Login', 'imagepress') . '</h3>

                <form method="post" action="' . home_url() . '/wp-login.php">
                    <p>
                        <label for="user_login">' . __('Username', 'imagepress') . '</label><br>
                        <input type="text" name="log" value="' . esc_attr(stripslashes($user_login)) . '" size="20" id="user_login">
                    </p>
                    <p>
                        <label for="user_pass">' . __('Password', 'imagepress') . '</label><br>
                        <input type="password" name="pwd" value="" size="20" id="user_pass">
                    </p>

                    <div class="login_fields">
                        <label for="rememberme">
                            <input type="checkbox" name="rememberme" value="forever" id="rememberme" checked> ' . __('Remember me', 'imagepress') . '
                        </label>

                        <input type="submit" name="user-submit" value="' . __('Login', 'imagepress') . '">
                        <input type="hidden" name="redirect_to" value="' . $_SERVER['REQUEST_URI'] . '">
                        <input type="hidden" name="user-cookie" value="1">
                    </div>
                </form>
            </div>
            <div class="wp-block-column">
                <h3>' . __('Register', 'imagepress') . '</h3>

                <form method="post" action="' . site_url('wp-login.php?action=register', 'login_post') . '">
                    <p>
                        <label for="user_login_signup">' . __('Username', 'imagepress') . '</label>
                        <input type="text" name="user_login" value="' . esc_attr(stripslashes($user_login)) . '" size="20" id="user_login_signup">
                    </p>
                    <p>
                        <label for="user_email">' . __('Email Address', 'imagepress') . '</label>
                        <input type="text" name="user_email" value="' . esc_attr(stripslashes($user_email)) . '" size="25" id="user_email">
                    </p>

                    <div class="login_fields">';
                        $out .= '<input type="submit" name="user-submit" value="' . __('Sign Up', 'imagepress') . '">';
                        $register = sanitize_text_field($_GET['register']);
                        if ((bool) $register === true) {
                            $out .= '<p>' . __('Check your email for the password!', 'imagepress') . '</p>';
                        }
                        $out .= '<input type="hidden" name="redirect_to" value="' . $_SERVER['REQUEST_URI'] . '?register=true">
                        <input type="hidden" name="user-cookie" value="1">
                    </div>
                </form>
            </div>
            <div class="wp-block-column">
                <h3>' . __('Forgot your password?', 'imagepress') . '</h3>

                <p>Enter your username or email to reset your password.</p>
                <form method="post" action="' . site_url('wp-login.php?action=lostpassword', 'login_post') . '">
                    <p>
                        <label for="user_login" class="hide">' . __('Username or Email Address', 'imagepress') . '</label>
                        <input type="text" name="user_login" value="" size="20" id="user_login">
                    </p>

                    <div class="login_fields">';
                        $out .= '<input type="submit" name="user-submit" value="' . __('Reset Password', 'imagepress') . '">';
                        $reset = sanitize_text_field($_GET['reset']);
                        if ((bool) $reset === true) {
                            echo '<p>' . __('An email message will be sent to your email address.', 'imagepress') . '</p>';
                        }
                        $out .= '<input type="hidden" name="redirect_to" value="' . $_SERVER['REQUEST_URI'] . '?reset=true">
                        <input type="hidden" name="user-cookie" value="1">
                    </div>
                </form>
            </div>
        </div>';
	} else {
        $out .= '<h3>' . __('Welcome', 'imagepress') . ', ' . $user_identity . '</h3>
        <div class="userinfo"></div>';
	}

    return $out;
}

add_shortcode('cinnamon-login', 'imagepress_login');
add_shortcode('imagepress-login', 'imagepress_login');
