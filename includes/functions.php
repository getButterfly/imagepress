<?php
function imagepress_registration() {
    $ip_slug = sanitize_text_field(get_imagepress_option('ip_slug'));

    if (empty($ip_slug)) {
        $ip_slug = 'image';
    }

    $image_type_labels = array(
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
    );

    $image_type_args = array(
        'label'                 => __('Image', 'imagepress'),
        'description'           => __('Image post type', 'imagepress'),
        'labels'                => $image_type_labels,
        'supports'              => array('title', 'editor', 'author', 'thumbnail', 'comments', 'custom-fields', 'publicize', 'wpcom-markdown'),
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
    );

    register_post_type($ip_slug, $image_type_args);

    $imageTaxonomy = array(
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
    );

    $image_category_args = array(
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
    );

    register_taxonomy('imagepress_image_category', array($ip_slug), $image_category_args);

    $labels = array(
        'name'                       => _x('Image tags', 'Taxonomy ceneral name', 'imagepress'),
        'singular_name'              => _x('Image tag', 'Taxonomy singular name', 'imagepress'),
        'menu_name'                  => __('Image Tags', 'imagepress'),
        'all_items'                  => __('All image tags', 'imagepress'),
        'parent_item'                => __('Parent image tag', 'imagepress'),
        'parent_item_colon'          => __('Parent image tag:', 'imagepress'),
        'new_item_name'              => __('New image tag', 'imagepress'),
        'add_new_item'               => __('Add new image tag', 'imagepress'),
        'edit_item'                  => __('Edit image tag', 'imagepress'),
        'update_item'                => __('Update image tag', 'imagepress'),
        'view_item'                  => __('View image tag', 'imagepress'),
        'separate_items_with_commas' => __('Separate image tags with commas', 'imagepress'),
        'add_or_remove_items'        => __('Add or remove image tags', 'imagepress'),
        'choose_from_most_used'      => __('Choose from the most used', 'imagepress'),
        'popular_items'              => __('Popular image tags', 'imagepress'),
        'search_items'               => __('Search image tags', 'imagepress'),
        'not_found'                  => __('Not found', 'imagepress'),
        'no_terms'                   => __('No image tags', 'imagepress'),
        'items_list'                 => __('Image tags list', 'imagepress'),
        'items_list_navigation'      => __('Image tags list navigation', 'imagepress'),
    );

    $args = array(
        'labels'                => $labels,
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_admin_column'     => true,
        'show_in_nav_menus'     => true,
        'show_tagcloud'         => false,
        'show_in_rest'          => true,
        'rest_base'             => 'image-tag',
        'rest_controller_class' => 'WP_REST_Terms_Controller',
    );

    register_taxonomy('imagepress_image_tag', array($ip_slug), $args);
}

function ip_getPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);

    if (empty($count)) {
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, 0);

        return 0;
    }

    return $count;
}
function ip_setPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);

    if ($count === 0 || empty($count)) {
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, 1);
    } else {
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}



// front-end image editor
function ip_get_object_terms_exclude_filter($terms, $object_ids, $taxonomies, $args) {
    if (isset($args['exclude']) && (isset($args['fields']) && $args['fields'] == 'all')) {
        foreach ($terms as $key => $term) {
            foreach ($args['exclude'] as $exclude_term) {
                if ($term->term_id == $exclude_term) {
                    unset($terms[$key]);
                }
            }
        }
    }

    $terms = array_values($terms);

    return $terms;
}
add_filter('wp_get_object_terms', 'ip_get_object_terms_exclude_filter', 10, 4);

// frontend image editor
function ip_editor() {
    global $post;

    $current_user = wp_get_current_user();

    // check if user is author // show author tools
    if ($post->post_author == $current_user->ID) { ?>
        <span class="ip-editor-display-container">
            <a href="#" class="ip-editor-display thin-ui-button" id="ip-editor-open"><i class="fas fa-wrench"></i><span class="ip-icon-label"> <?php esc_html_e('Author tools', 'imagepress'); ?></span></a>
        </span>
        <?php
        $edit_id = get_the_ID();

        if ('POST' == $_SERVER['REQUEST_METHOD'] && !empty($_POST['post_id']) && !empty($_POST['post_title']) && isset($_POST['update_post_nonce']) && isset($_POST['postcontent'])) {
            $post_id = $_POST['post_id'];
            $post_type = get_post_type($post_id);
            $capability = ('page' == $post_type) ? 'edit_page' : 'edit_post';
            if (current_user_can($capability, $post_id) && wp_verify_nonce($_POST['update_post_nonce'], 'update_post_'. $post_id)) {
                $post = array(
                    'ID'             => esc_sql($post_id),
                    'post_content'   => (stripslashes($_POST['postcontent'])),
                    'post_title'     => esc_sql($_POST['post_title'])
                );
                wp_update_post($post);

                imagepress_process_image('imagepress_image_file', $post_id, 1);

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
                            $_FILES = array("imagepress_image_additional" => $file);
                            foreach ($_FILES as $file => $array) {
                                imagepress_process_image('imagepress_image_additional', $post_id);
                            }
                        }
                    }
                }
                // end multiple images

                $images = get_children(array('post_parent' => $post_id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID'));
                $count = $images ? count($images) : 0;
                if ($count == 1 || !has_post_thumbnail($post_id)) {
                    foreach ($images as $attachment_id => $image) {
                        set_post_thumbnail($post_id, $image->ID);
                    }
                }

                wp_set_object_terms($post_id, (int) $_POST['imagepress_image_category'], 'imagepress_image_category');
                if (get_imagepress_option('ip_allow_tags') == 1)
                    wp_set_object_terms($post_id, (int) $_POST['imagepress_image_tag'], 'imagepress_image_tag');

                // custom fields
                global $wpdb;

                $result = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_fields ORDER BY field_order ASC", ARRAY_A);

                foreach ($result as $field) {
                    update_post_meta($post_id, $field['field_slug'], $_POST[$field['field_slug']]);
                }
                //
            }
            else {
                wp_die("You can't do that");
            }
        }
        ?>
        <div id="info" class="ip-editor">
            <form id="post" class="post-edit front-end-form imagepress-form thin-ui-form" method="post" enctype="multipart/form-data">
                <input type="hidden" name="post_id" value="<?php echo $edit_id; ?>">
                <?php wp_nonce_field('update_post_' . $edit_id, 'update_post_nonce'); ?>

                <p>
                    <label for="post_title"><?php esc_html_e('Title', 'imagepress'); ?></label><br>
                    <input type="text" id="post_title" name="post_title" value="<?php echo get_the_title($edit_id); ?>">
                </p>
                <p>
                    <label for="postcontent"><?php esc_html_e('Description', 'imagepress'); ?></label><br>
                    <textarea id="postcontent" name="postcontent" rows="3"><?php echo strip_tags(get_post_field('post_content', $edit_id)); ?></textarea></p>
                <hr>

                <?php
                // custom fields
                global $wpdb;

                $result = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_fields ORDER BY field_order ASC", ARRAY_A);

                foreach ($result as $field) {
                    $ps_meta = get_post_meta($edit_id, $field['field_slug'], true);

                    $fieldType = (int) sanitize_text_field($field['field_type']);
                    $fieldSlug = sanitize_text_field($field['field_slug']);
                    $fieldName = sanitize_text_field($field['field_name']);

                    if ($fieldType === 1) {
                        echo '<p><label for="' . $fieldSlug . '">' . $fieldName . '</label><br><input type="text" id="' . $fieldSlug . '" name="' . $fieldSlug . '" placeholder="' . $fieldName . '" value="' . $ps_meta . '"></p>';
                    } else if ($fieldType === 2) {
                        echo '<p><label for="' . $fieldSlug . '">' . $fieldName . '</label><br><input type="url" id="' . $fieldSlug . '" name="' . $fieldSlug . '" placeholder="' . $fieldName . '" value="' . $ps_meta . '"></p>';
                    } else if ($fieldType === 3) {
                        echo '<p><label for="' . $fieldSlug . '">' . $fieldName . '</label><br><input type="email" id="' . $fieldSlug . '" name="' . $fieldSlug . '" placeholder="' . $fieldName . '" value="' . $ps_meta . '"></p>';
                    } else if ($fieldType === 4) {
                        echo '<p><label for="' . $fieldSlug . '">' . $fieldName . '</label><br><input type="number" id="' . $fieldSlug . '" name="' . $fieldSlug . '" placeholder="' . $fieldName . '" value="' . $ps_meta . '"></p>';
                    } else if ($fieldType === 5) {
                        echo '<p><label for="' . $fieldSlug . '">' . $fieldName . '</label><br><textarea id="' . $fieldSlug . '" name="' . $fieldSlug . '" rows="6" placeholder="' . $fieldName . '">' . $ps_meta . '</textarea></p>';
                    } else if ($fieldType === 6) {
                        if ($ps_meta == 1) {
                            $checked = 'checked';
                        } else {
                            $checked = '';
                        }

                        echo '<p><label for="' . $fieldSlug . '"><input type="checkbox" id="' . $fieldSlug . '" name="' . $fieldSlug . '" placeholder="' . $fieldName . '" value="1" ' . $checked . '> ' . $fieldName . '</label></p>';
                    } else if ($fieldType === 7) {
                        if ($ps_meta == 1) {
                            $checked = 'checked';
                        } else {
                            $checked = '';
                        }

                        echo '<p><label for="' . $fieldSlug . '"><input type="radio" id="' . $fieldSlug . '" name="' . $fieldSlug . '" placeholder="' . $fieldName . '" value="1" ' . $checked . '> ' . $fieldName . '</label></p>';
                    } else if ($fieldType === 8) {
                        echo '<p><label for="' . $fieldSlug . '">' . $fieldName . '</label><select id="' . $fieldSlug . '" name="' . $fieldSlug . '" placeholder="' . $fieldName . '">';
                            $options = $wpdb->get_var($wpdb->prepare("SELECT field_content FROM  " . $wpdb->prefix . "ip_fields WHERE field_name = '%s'", $fieldName));
                            $options = explode(',', $options);
                            foreach ($options as $option) {
                                if ($ps_meta == trim($option)) {
                                    $selected = 'selected';
                                } else {
                                    $selected = '';
                                }

                                echo '<option ' . $selected . '>' . trim($option) . '</option>';
                            }
                        echo '</select></p>';
                    } else if (
                        $fieldType === 20 ||
                        $fieldType === 21 ||
                        $fieldType === 22 ||
                        $fieldType === 23 ||
                        $fieldType === 24
                    ) {
                        echo '<p><label for="' . $fieldSlug . '">' . $fieldName . '</label><br><input type="text" id="' . $fieldSlug . '" name="' . $fieldSlug . '" placeholder="' . $fieldName . '" value="' . $ps_meta . '"></p>';
                    }
                }
                //
                ?>
                <hr>

                <?php $ip_category = wp_get_object_terms($edit_id, 'imagepress_image_category', array('exclude' => array(4))); ?>
                <?php if(get_imagepress_option('ip_allow_tags') == 1) $ip_tag = wp_get_post_terms($edit_id, 'imagepress_image_tag'); ?>

                <p>
                    <?php echo imagepress_get_image_categories_dropdown('imagepress_image_category', $ip_category[0]->term_id); ?>
                    <?php if(get_imagepress_option('ip_allow_tags') == 1) echo imagepress_get_image_tags_dropdown('imagepress_image_tag', $ip_tag[0]->term_id); ?>
                </p>

                <?php
                $ip_upload_size = get_imagepress_option('ip_upload_size');
                $uploadsize = number_format((($ip_upload_size * 1024)/1024000), 0, '.', '');
                $datauploadsize = $uploadsize * 1024000;
                ?>
                <p><label for="imagepress_image_file"><i class="fas fa-cloud-upload-alt"></i> Replace main image (<?php echo $uploadsize . 'MB ' . __('maximum', 'imagepress'); ?>)...</label><br><input type="file" accept="image/*" data-max-size="<?php echo $datauploadsize; ?>" name="imagepress_image_file" id="imagepress_image_file"></p>

                <?php if(1 == get_imagepress_option('ip_upload_secondary')) { ?>
                    <hr>
                    <p>
                        <?php esc_html_e('Select', 'imagepress'); ?> <i class="fas fa-check-circle"></i> <?php esc_html_e('main image or', 'imagepress'); ?> <i class="fas fa-times-circle"></i> <?php esc_html_e('delete additional images', 'imagepress'); ?>
                        <br><small><?php esc_html_e('Main image will appear first in single image listing and as a thumbnail in gallery view', 'imagepress'); ?></small>
                    </p>
                    <div class="ip-hide ip-notice"><p><i class="fas fa-check" aria-hidden="true"></i> <?php esc_html_e('Featured image selected succesfully!', 'imagepress'); ?></p></div>
                    <?php
                    $thumbnail_ID = get_post_thumbnail_id();
                    $images = get_children(array('post_parent' => $edit_id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID'));
                    $count = $images ? count($images) : 0;

                    if($count > 1) {
                        echo '<div>';
                            foreach($images as $attachment_id => $image) {
                                $small_array = image_downsize($image->ID, 'thumbnail');

                                if($image->ID == $thumbnail_ID)
                                    echo '<div class="ip-additional-active">';
                                if($image->ID != $thumbnail_ID)
                                    echo '<div class="ip-additional">';
                                    echo '<div class="ip-toolbar">';
                                        echo '<a href="#" data-id="' . $image->ID . '" data-nonce="' . wp_create_nonce('ip_delete_post_nonce') . '" class="delete-post ip-action-icon ip-floatright"><i class="fas fa-times-circle"></i></a>';
                                        echo '<a href="#" data-pid="' . $edit_id . '" data-id="' . $image->ID . '" data-nonce="' . wp_create_nonce('ip_featured_post_nonce') . '" class="featured-post ip-action-icon ip-floatleft"><i class="fas fa-check-circle"></i></a>';
                                    echo '</div>';
                                echo '<img src="' . $small_array[0] . '" alt=""></div>';
                            }
                        echo '</div>';
                    }
                    ?>

                    <p><label for="imagepress_image_additional"><i class="fas fa-cloud-upload-alt"></i> <?php esc_html_e('Add more images', 'imagepress'); ?> (<?php echo $uploadsize; ?>MB <?php esc_html_e('maximum', 'imagepress'); ?>)...</label><br><input type="file" accept="image/*" data-max-size="<?php echo $datauploadsize; ?>" name="imagepress_image_additional[]" id="imagepress_image_additional" multiple></p>
                <?php } ?>

                <hr>
                <?php
                $ipDeleteRedirection = get_imagepress_option('ip_delete_redirection');
                if (empty($ipDeleteRedirection)) {
                    $ipDeleteRedirection = home_url();
                }
                ?>
                <p>
                    <input type="submit" id="submit" value="<?php esc_html_e('Update image', 'imagepress'); ?>">
                    <a href="#" data-redirect="<?php echo $ipDeleteRedirection; ?>" data-image-id="<?php echo get_the_ID(); ?>" class="button ip-floatright" id="ip-editor-delete-image"><i class="fas fa-trash-alt"></i></a>
                </p>
            </form>
        </div>
        <?php wp_reset_query(); ?>
    <?php }
}

// ip_editor() related actions
add_action('wp_ajax_ip_delete_post', 'ip_delete_post');
function ip_delete_post() {
    $id = (int) $_POST['id'];

    if (wp_delete_post($id)) {
        echo 'success';
    } else {
        echo '';
    }
    die();
}
add_action('wp_ajax_ip_update_post_title', 'ip_update_post_title');
function ip_update_post_title() {
    $updated_post = array(
        'ID' => (int) $_REQUEST['id'],
        'post_title' => (string) $_REQUEST['title'],
    );

    wp_update_post($updated_post);

    echo 'success';
    die();
}
add_action('wp_ajax_ip_featured_post', 'ip_featured_post');
function ip_featured_post() {
    $permission = check_ajax_referer('ip_featured_post_nonce', 'nonce', false);
    if($permission == false) {
        echo 'error';
    }
    else {
        update_post_meta($_REQUEST['pid'], '_thumbnail_id', $_REQUEST['id']);
        echo 'success';
    }
    die();
}



// main ImagePress image function
function ip_main($i) {
    global $wpdb, $post;

    $post_thumbnail_id = get_post_thumbnail_id($i);
    $image_attributes = wp_get_attachment_image_src($post_thumbnail_id, 'full');
    $post_thumbnail_url = $image_attributes[0];

    if(get_imagepress_option('ip_comments') == 1)
        $ip_comments = '<em> | </em><a href="' . get_permalink($i) . '"><i class="fas fa-comments"></i> ' . get_comments_number($i) . '</a> ';
    if(get_imagepress_option('ip_comments') == 0)
        $ip_comments = '';
    ?>

    <div class="imagepress-container">
        <a href="<?php echo $post_thumbnail_url; ?>">
            <?php the_post_thumbnail('full'); ?>
        </a>
        <?php ip_setPostViews($i); ?>
    </div>

    <div class="ip-bar">
        <?php echo ipGetPostLikeLink($i); ?><em> | </em><i class="fas fa-eye"></i> <?php echo ip_getPostViews($i); ?><?php echo $ip_comments; ?>
        <?php if (get_imagepress_option('ip_mod_collections') == 1) { ?>
            <em> | </em>
            <?php if (function_exists('ip_frontend_add_collection')) ip_frontend_add_collection(get_the_ID()); ?>
        <?php } ?>

        <a href="<?php echo $post_thumbnail_url; ?>" class="thin-ui-button"><i class="fas fa-fw fa-arrows-alt"></i></a>
        <?php echo imagepress_image_download(get_the_post_thumbnail_url()); ?>

        <?php
        // show image editor
        ip_editor();
        ?>
    </div>

    <h1 class="ip-title">
        <?php
        if(has_term('featured', 'imagepress_image_category'))
            echo '<i class="fas fa-star"></i></span> ';

        echo get_the_title($i);

        if (get_imagepress_option('ip_allow_tags') == 1) {
            $terms = get_the_terms($i, 'imagepress_image_tag');

            if ($terms && !is_wp_error($terms)) :
                $term_links = array();
                foreach($terms as $term) {
                    $term_links[] = $term->name;
                }
                $tags = join(', ', $term_links);
                echo '<br><small><i class="fas fa-info-circle"></i> ' . $tags . '</small>';
            endif;
        }
        ?>
    </h1>

    <p>
        <div style="float: left; margin: 0 8px 0 0;">
            <?php echo get_avatar($post->post_author, 40); ?>
        </div>
        <?php esc_html_e('by', 'imagepress'); ?> <b><?php echo getImagePressProfileUri($post->post_author); ?></b>
        <br><small><?php esc_html_e('Uploaded', 'imagepress'); ?> <time title="<?php the_time(get_option('date_format')); ?>"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago'; ?></time> <?php esc_html_e('in', 'imagepress'); ?> <?php echo get_the_term_list(get_the_ID(), 'imagepress_image_category', '', ', ', ''); ?></small>
    </p>

    <div class="ip-clear"></div>

    <?php
    // custom preset fields
    $result = $wpdb->get_results("SELECT field_type, field_name, field_slug FROM " . $wpdb->prefix . "ip_fields ORDER BY field_order ASC", ARRAY_A);

    foreach ($result as $field) {
        $fs_meta = get_post_meta($i, $field['field_slug'], true);

        if ((int) $field['field_type'] === 20 && !empty($fs_meta)) {
            $sketchfabId = $fs_meta;
            echo '<iframe width="100%" height="480" src="https://sketchfab.com/models/' . $sketchfabId . '/embed" frameborder="0" allowfullscreen mozallowfullscreen="true" webkitallowfullscreen="true" onmousewheel=""></iframe><br>via <a href="https://sketchfab.com/models/' . $sketchfabId . '?utm_medium=embed&utm_source=website&utm_campain=share-popup" target="_blank">Sketchfab</a>';
        }
        if ((int) $field['field_type'] === 21 && !empty($fs_meta)) {
            $vimeoId = $fs_meta;
            echo '<iframe src="https://player.vimeo.com/video/' . $vimeoId . '" width="100%" height="480" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
        }
        if ((int) $field['field_type'] === 22 && !empty($fs_meta)) {
            $youtubeId = $fs_meta;
            echo '<iframe width="100%" height="480" src="https://www.youtube.com/embed/' . $youtubeId . '?rel=0" frameborder="0" allowfullscreen></iframe>';
        }
        if ((int) $field['field_type'] === 23 && !empty($fs_meta)) {
            $googleMapsLocation = $fs_meta;
            echo '<p><img class="single-image-map" src="https://maps.googleapis.com/maps/api/staticmap?center=' . $googleMapsLocation . '&scale=2&zoom=13&size=600x300&maptype=terrain" alt="' . $googleMapsLocation . '" width="600"></p>';
        }
        if ((int) $field['field_type'] === 24 && !empty($fs_meta)) {
            $roundMeTourId = $fs_meta;
            echo '<p><iframe width="100%" height="480" src="https://round.me/embed/' . $roundMeTourId . '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></p>';
        }
    }
    //
    ?>

    <?php imagepress_get_images($i); ?>

    <section>
        <?php the_content(); ?>
    </section>

    <section role="navigation">
        <?php previous_post_link('%link', '<i class="fas fa-fw fa-chevron-left"></i> Previous'); ?>
        <?php next_post_link('%link', 'Next <i class="fas fa-fw fa-chevron-right"></i>'); ?>
    </section>
    <?php
}

function ip_get_the_term_list( $id = 0, $taxonomy, $before = '', $sep = '', $after = '', $exclude = array() ) {
    $terms = get_the_terms( $id, $taxonomy );

    if ( is_wp_error( $terms ) )
        return $terms;

    if ( empty( $terms ) )
        return false;

    foreach ( $terms as $term ) {

        if(!in_array($term->term_id,$exclude)) {
            $link = get_term_link( $term, $taxonomy );
            if ( is_wp_error( $link ) )
                return $link;
            $term_links[] = '<a href="' . $link . '" rel="tag">' . $term->name . '</a>';
        }
    }

    $term_links = apply_filters( "term_links-$taxonomy", $term_links );

    return $before . join( $sep, $term_links ) . $after;
}

function imagepress_get_images($post_id) {
    $thumbnail_ID = get_post_thumbnail_id();
    $images = get_children(array('post_parent' => $post_id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID'));

    if ($images && count($images) > 1) {
        echo '<div class="ip-more">';
            foreach ($images as $attachment_id => $image) {
                if ($image->ID != $thumbnail_ID) {
                    $big_array = image_downsize($image->ID, 'full');

                    echo '<img src="' . $big_array[0] . '" alt="">';
                }
            }
        echo '</div>';
    }
}

function kformat($number) {
    $number = (int) $number;

    return number_format($number, 0, '.', ',');
}

function ip_related() {
    global $post;
    ?>
    <h3><?php echo __('More by the same author', 'imagepress'); ?></h3>
    <?php echo cinnamon_get_related_author_posts($post->post_author); ?>

    <?php
}

function ip_author() {
    echo do_shortcode('[cinnamon-profile]');
}





function imagepress_login_logo_url() {
    return get_bloginfo( 'url' );
}
function imagepress_login_logo_url_title() {
    return __('Powered by ImagePress', 'imagepress');
}
function imagepress_login_error_override() {
    return __('Incorrect login details.', 'imagepress');
}
function imagepress_login_head() {
    // https://codex.wordpress.org/Plugin_API/Action_Reference/login_enqueue_scripts
    $ip_login_image = get_imagepress_option('ip_login_image');

    echo '<style>';
        if(!empty($ip_login_image))
            echo 'body.login { background-image: url("' . $ip_login_image . '"); background-repeat: no-repeat; background-attachment: fixed; background-position: center; background-size: cover; }';
        else
            echo 'body.login { background-color: ' .  get_imagepress_option('ip_login_bg') . '; }';

        echo '.login form { background-color: ' .  get_imagepress_option('ip_login_box_bg') . '; }';

        if(get_imagepress_option('ip_login_flat_mode') == 1) {
            echo '.login form { box-shadow: none; border-radius: 0; }';
            echo '.login .button-primary { box-shadow: none; border: 0 none; border-radius: 0; } .login .button-primary:hover, .login .button-primary:active, .login .button-primary:focus { box-shadow: none; }';
            echo '.login input[type="text"], .login input[type="password"] { box-shadow: none; }';
        }

        echo '.login .button-primary { box-shadow: none; border-color: ' . get_imagepress_option('ip_login_button_bg') . '; background-color: ' . get_imagepress_option('ip_login_button_bg') . '; color: ' . get_imagepress_option('ip_login_button_text') . '; }';
        echo '.login .button-primary:hover { box-shadow: none; border-color: ' . get_imagepress_option('ip_login_button_bg') . '; background-color: ' . get_imagepress_option('ip_login_button_bg') . '; color: ' . get_imagepress_option('ip_login_button_text') . '; }';
        echo '.login .button-primary:focus { box-shadow: none; border-color: ' . get_imagepress_option('ip_login_button_bg') . '; background-color: ' . get_imagepress_option('ip_login_button_bg') . '; color: ' . get_imagepress_option('ip_login_button_text') . '; }';
        echo '.login .button-primary:active { box-shadow: none; border-color: ' . get_imagepress_option('ip_login_button_bg') . '; background-color: ' . get_imagepress_option('ip_login_button_bg') . '; color: ' . get_imagepress_option('ip_login_button_text') . '; }';
        echo '.login input[type="text"]:focus, .login input[type="password"]:focus { border-color: ' . get_imagepress_option('ip_login_button_bg') . '; }';

        echo '.login h1 a { background: none !important; color: ' . get_imagepress_option('ip_login_page_text') . ' !important; height: auto; font-size: 24px; font-weight: 300; line-height: initial; margin: 0 auto 25px; padding: 0; text-decoration: none; width: auto; text-indent: 0; overflow: visible; display: block; }';
        echo '.login label { color: ' . get_imagepress_option('ip_login_box_text') . '; }';
        echo 'p#backtoblog { display: none; }';
        echo '.imagepress-login-footer { text-align: center; margin-top: 1em; color: ' . get_imagepress_option('ip_login_page_text') . '; }';
        echo '.imagepress-login-footer a, .login a, .login a:hover, #nav a { color: ' . get_imagepress_option('ip_login_page_text') . '; }';
        echo '.imagepress-login-footer, #nav a { color: ' . get_imagepress_option('ip_login_page_text') . ' !important; }';
    echo '</style>';

    remove_action('login_head', 'wp_shake_js', 12);
}
function imagepress_admin_login_redirect( $redirect_to, $request, $user ) {
    global $user;
    if(isset($user->roles) && is_array($user->roles)) {
        if(in_array('administrator', $user->roles)) {
            return $redirect_to;
        } else {
            return home_url(); // customize this link
        }
    }
    else {
        return $redirect_to;
    }
}
function imagepress_login_checked_remember_me() {
    add_filter('login_footer', 'imagepress_rememberme_checked');
}
function imagepress_rememberme_checked() {
    echo '<script>document.getElementById("rememberme").checked = true;</script>';
}
function imagepress_login_footer() {
    echo '<p class="imagepress-login-footer">' . get_imagepress_option('ip_login_copyright') . '</p>';
}
function imagepress_change_register_page_msg($message) {
    if (strpos($message, 'Register For This Site') == true) {
        $message = '<p class="message">' . __('Register for ImagePress', 'imagepress') . '</p>';
    }

    return $message;
}

$ip_mod_login = get_imagepress_option('ip_mod_login');

if ($ip_mod_login == 1) {
    add_action('init', 'imagepress_login_checked_remember_me');

    add_action('login_head', 'imagepress_login_head');
    add_action('login_footer','imagepress_login_footer');

    add_filter('login_headerurl', 'imagepress_login_logo_url');
    add_filter('login_headertitle', 'imagepress_login_logo_url_title');
    add_filter('login_errors', 'imagepress_login_error_override');
    add_filter('login_redirect', 'imagepress_admin_login_redirect', 10, 3);
    add_filter('login_message', 'imagepress_change_register_page_msg');
}

function ip_return_image_sizes() {
    global $_wp_additional_image_sizes;

    $image_sizes = array();
    foreach(get_intermediate_image_sizes() as $size) {
        $image_sizes[$size] = array(0, 0);
        if(in_array($size, array('thumbnail', 'medium', 'large'))) {
            $image_sizes[$size][0] = get_option($size . '_size_w');
            $image_sizes[$size][1] = get_option($size . '_size_h');
        }
        else
            if(isset($_wp_additional_image_sizes) && isset($_wp_additional_image_sizes[$size]))
                $image_sizes[$size] = array($_wp_additional_image_sizes[$size]['width'], $_wp_additional_image_sizes[$size]['height']);
    }
    return $image_sizes;
}

add_filter('wp_dropdown_cats', 'ip_wp_dropdown_categories_required', 10, 2);
function ip_wp_dropdown_categories_required($output, $args) {
    if(isset($args['required']) && $args['required']) {
        $output = preg_replace(
            '^' . preg_quote( '<select ' ) . '^',
            '<select required ',
            $output
        );
    }

    return $output;
}

function ip_get_user_role() {
    global $current_user;

    $user_roles = $current_user->roles;
    $user_role = array_shift($user_roles);

    return $user_role;
}

function ip_get_field($atts) {
    extract(shortcode_atts(array(
        'field' => '',
    ), $atts));

    $field = get_post_meta(get_the_ID(), $field, true);

    return $field;
}

function imagepress_image_download($path) {
    $out = '<a href="' . $path . '" class="thin-ui-button" download><i class="fas fa-fw fa-download" aria-hidden="true"></i><span class="ip-icon-label"> ' . __('Download', 'noir-ui') . '</span></a>';

    return $out;
}

function imagepress_order_list() {
    global $wpdb;

    foreach ($_POST['listItem'] as $position => $item) {
        $wpdb->query($wpdb->prepare("UPDATE `" . $wpdb->prefix . "posts` SET `menu_order` = %d WHERE `ID` = %d", $position, $item));
    }
}
add_action('wp_ajax_imagepress_list_update_order','imagepress_order_list');
add_action('wp_ajax_nopriv_imagepress_list_update_order','imagepress_order_list');



/*
 * Refactoring of option management functions.
 * Use a get_option() wrapper.
 */
function get_imagepress_option($option) {
    $ipOptions = get_option('imagepress');

    return $ipOptions[$option];
}

function updateImagePressOption($optionArray) {
    $imagePressOption = get_option('imagepress');
    $updatedArray = array_merge($imagePressOption, $optionArray);

    update_option('imagepress', $updatedArray);
}

function getImagePressProfileUri($authorId, $structure = true) {
    $ipProfilePageId = (int) get_imagepress_option('ip_profile_page');
    $ipProfilePageUri = get_permalink($ipProfilePageId);
    $ipProfileSlug = (string) get_imagepress_option('cinnamon_author_slug');

    $ipProfileLink = '<span class="name"><a href="' . $ipProfilePageUri . '?' . $ipProfileSlug . '=' . get_the_author_meta('user_nicename', $authorId) . '">' . get_the_author_meta('user_nicename', $authorId) . '</a></span>';

    if ($structure === false) {
        $ipProfileLink = $ipProfilePageUri . '?' . $ipProfileSlug . '=' . get_the_author_meta('user_nicename', $authorId);
    }

    return $ipProfileLink;
}
