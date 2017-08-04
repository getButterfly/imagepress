<?php
function imagepress_registration() {
    $ip_slug = get_imagepress_option('ip_slug');

    $image_type_labels = array(
        'name'                  => _x( 'Images', 'Post type general name', 'imagepress' ),
        'singular_name'         => _x( 'Image', 'Post type singular name', 'imagepress' ),
        'menu_name'             => __( 'ImagePress', 'imagepress' ),
        'name_admin_bar'        => __( 'Image', 'imagepress' ),
        'archives'              => __( 'Image archives', 'imagepress' ),
        'parent_item_colon'     => __( 'Parent image:', 'imagepress' ),
        'all_items'             => __( 'All images', 'imagepress' ),
        'add_new_item'          => __( 'Add new image', 'imagepress' ),
        'add_new'               => __( 'Add new', 'imagepress' ),
        'new_item'              => __( 'New image', 'imagepress' ),
        'edit_item'             => __( 'Edit image', 'imagepress' ),
        'update_item'           => __( 'Update image', 'imagepress' ),
        'view_item'             => __( 'View image', 'imagepress' ),
        'search_items'          => __( 'Search image', 'imagepress' ),
        'not_found'             => __( 'Not found', 'imagepress' ),
        'not_found_in_trash'    => __( 'Not found in trash', 'imagepress' ),
        'featured_image'        => __( 'Featured image', 'imagepress' ),
        'set_featured_image'    => __( 'Set featured image', 'imagepress' ),
        'remove_featured_image' => __( 'Remove featured image', 'imagepress' ),
        'use_featured_image'    => __( 'Use as featured image', 'imagepress' ),
        'insert_into_item'      => __( 'Insert into image', 'imagepress' ),
        'uploaded_to_this_item' => __( 'Uploaded to this image', 'imagepress' ),
        'items_list'            => __( 'Images list', 'imagepress' ),
        'items_list_navigation' => __( 'Images list navigation', 'imagepress' ),
        'filter_items_list'     => __( 'Filter images list', 'imagepress' ),
    );

    $image_type_args = array(
        'label'                 => __( 'Image', 'imagepress' ),
        'description'           => __( 'Image post type', 'imagepress' ),
        'labels'                => $image_type_labels,
        'supports'              => array( 'title', 'editor', 'author', 'thumbnail', 'comments', 'custom-fields', 'publicize', 'wpcom-markdown' ),
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
    );

    register_post_type($ip_slug, $image_type_args);

    $image_category_labels = array(
        'name'                       => _x( 'Image categories', 'Taxonomy general name', 'imagepress' ),
        'singular_name'              => _x( 'Image category', 'Taxonomy singular name', 'imagepress' ),
        'menu_name'                  => __( 'Image Categories', 'imagepress' ),
        'all_items'                  => __( 'All image categories', 'imagepress' ),
        'parent_item'                => __( 'Parent image category', 'imagepress' ),
        'parent_item_colon'          => __( 'Parent image category:', 'imagepress' ),
        'new_item_name'              => __( 'New image category', 'imagepress' ),
        'add_new_item'               => __( 'Add new image category', 'imagepress' ),
        'edit_item'                  => __( 'Edit image category', 'imagepress' ),
        'update_item'                => __( 'Update image category', 'imagepress' ),
        'view_item'                  => __( 'View image category', 'imagepress' ),
        'separate_items_with_commas' => __( 'Separate image categories with commas', 'imagepress' ),
        'add_or_remove_items'        => __( 'Add or remove image categories', 'imagepress' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'imagepress' ),
        'popular_items'              => __( 'Popular image categories', 'imagepress' ),
        'search_items'               => __( 'Search image categories', 'imagepress' ),
        'not_found'                  => __( 'Not found', 'imagepress' ),
        'no_terms'                   => __( 'No image categories', 'imagepress' ),
        'items_list'                 => __( 'Image categories list', 'imagepress' ),
        'items_list_navigation'      => __( 'Image categories list navigation', 'imagepress' ),
    );

    $image_category_args = array(
        'labels' 				     => $image_category_labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => false,
    );

    register_taxonomy('imagepress_image_category', array($ip_slug), $image_category_args);

    // image tags
    $labels = array(
        'name'                       => _x( 'Image tags', 'Taxonomy ceneral name', 'imagepress' ),
        'singular_name'              => _x( 'Image tag', 'Taxonomy singular name', 'imagepress' ),
        'menu_name'                  => __( 'Image Tags', 'imagepress' ),
        'all_items'                  => __( 'All image tags', 'imagepress' ),
        'parent_item'                => __( 'Parent image tag', 'imagepress' ),
        'parent_item_colon'          => __( 'Parent image tag:', 'imagepress' ),
        'new_item_name'              => __( 'New image tag', 'imagepress' ),
        'add_new_item'               => __( 'Add new image tag', 'imagepress' ),
        'edit_item'                  => __( 'Edit image tag', 'imagepress' ),
        'update_item'                => __( 'Update image tag', 'imagepress' ),
        'view_item'                  => __( 'View image tag', 'imagepress' ),
        'separate_items_with_commas' => __( 'Separate image tags with commas', 'imagepress' ),
        'add_or_remove_items'        => __( 'Add or remove image tags', 'imagepress' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'imagepress' ),
        'popular_items'              => __( 'Popular image tags', 'imagepress' ),
        'search_items'               => __( 'Search image tags', 'imagepress' ),
        'not_found'                  => __( 'Not found', 'imagepress' ),
        'no_terms'                   => __( 'No image tags', 'imagepress' ),
        'items_list'                 => __( 'Image tags list', 'imagepress' ),
        'items_list_navigation'      => __( 'Image tags list navigation', 'imagepress' ),
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => false,
    );

    register_taxonomy('imagepress_image_tag', array($ip_slug), $args);
}


/**
// Custom taxonomy fields
function imagepress_category_add_meta_field() {
    ?>
	<div class="form-field">
		<label for="term_meta[ip_category_limit]"><?php _e('Category image limit per user', 'imagepress'); ?></label>
		<input type="number" name="term_meta[ip_category_limit]" id="term_meta[ip_category_limit]" placeholder="0" min="0" step="1">
		<p class="description"><?php _e('Enter a value for this field. Leave blank or add 0 to disable.', 'imagepress'); ?></p>
	</div>
<?php
}

function imagepress_category_edit_meta_field($term) {
    $t_id = $term->term_id;

    $term_meta = get_imagepress_option("taxonomy_$t_id"); ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="term_meta[ip_category_limit]"><?php _e('Category image limit per user', 'imagepress'); ?></label></th>
        <td>
            <input type="number" name="term_meta[ip_category_limit]" id="term_meta[ip_category_limit]" value="<?php echo esc_attr($term_meta['ip_category_limit']) ? esc_attr($term_meta['ip_category_limit']) : ''; ?>" placeholder="0" min="0" step="1">
            <p class="description"><?php _e('Enter a value for this field. Leave blank or add 0 to disable.', 'imagepress'); ?></p>
        </td>
    </tr>
<?php
}

function imagepress_category_save_meta_field($term_id) {
    if(isset($_POST['term_meta'])) {
        $t_id = $term_id;
        $term_meta = get_imagepress_option("taxonomy_$t_id");
        $cat_keys = array_keys($_POST['term_meta']);
        foreach ($cat_keys as $key) {
            if (isset($_POST['term_meta'][$key])) {
                $term_meta[$key] = $_POST['term_meta'][$key];
            }
        }

        update_option("taxonomy_$t_id", $term_meta);
    }
}

add_action('imagepress_image_category_add_form_fields', 'imagepress_category_add_meta_field', 10, 2);
add_action('imagepress_image_category_edit_form_fields', 'imagepress_category_edit_meta_field', 10, 2);

add_action('edited_imagepress_image_category', 'imagepress_category_save_meta_field', 10, 2);
add_action('create_imagepress_image_category', 'imagepress_category_save_meta_field', 10, 2);
/**/
//



function ip_getPostViews($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if(empty($count)) {
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, 0);

        return 0;
    }
    return $count;
}
function ip_setPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count === 0 || empty($count)) {
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, 1);
    }
    else {
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}



// front-end image editor
function ip_get_object_terms_exclude_filter($terms, $object_ids, $taxonomies, $args) {
    if(isset($args['exclude']) && (isset($args['fields']) && $args['fields'] == 'all')) {
        foreach($terms as $key => $term) {
            foreach($args['exclude'] as $exclude_term) {
                if($term->term_id == $exclude_term) {
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
    if($post->post_author == $current_user->ID) { ?>
        <span class="ip-editor-display-container">
            <a href="#" class="ip-editor-display thin-ui-button" id="ip-editor-open"><i class="fa fa-wrench"></i> <?php _e('Author tools', 'imagepress'); ?></a>
        </span>
        <?php
        $edit_id = get_the_ID();

        if(isset($_GET['d'])) {
            $post_id = $_GET['d'];
            wp_delete_post($post_id);

            $ip_delete_redirection = get_imagepress_option('ip_delete_redirection');
            if(!empty($ip_delete_redirection)) {
                echo '<script>window.location.href="' . $ip_delete_redirection . '?deleted"</script>';
            }
            else {
                echo '<script>window.location.href="' . home_url() . '?deleted"</script>';
            }
        }
        if('POST' == $_SERVER['REQUEST_METHOD'] && !empty($_POST['post_id']) && !empty($_POST['post_title']) && isset($_POST['update_post_nonce']) && isset($_POST['postcontent'])) {
            $post_id = $_POST['post_id'];
            $post_type = get_post_type($post_id);
            $capability = ('page' == $post_type) ? 'edit_page' : 'edit_post';
            if(current_user_can($capability, $post_id) && wp_verify_nonce($_POST['update_post_nonce'], 'update_post_'. $post_id)) {
                $post = array(
                    'ID'             => esc_sql($post_id),
                    'post_content'   => (stripslashes($_POST['postcontent'])),
                    'post_title'     => esc_sql($_POST['post_title'])
                );
                wp_update_post($post);

                imagepress_process_image('imagepress_image_file', $post_id, $_FILES['imagepress_image_file'], 1);

                // multiple images
                if(1 == get_imagepress_option('ip_upload_secondary')) {
                    $files = $_FILES['imagepress_image_additional'];
                    if($files) {
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
                            $_FILES = array("imagepress_image_additional" => $file);
                            foreach($_FILES as $file => $array) {
                                imagepress_process_image('imagepress_image_additional', $post_id, '');
                            }
                        }
                    }
                }
                // end multiple images

                $images = get_children(array('post_parent' => $post_id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID'));
                $count = count($images);
                if($count == 1 || !has_post_thumbnail($post_id)) {
                    foreach($images as $attachment_id => $image) {
                        set_post_thumbnail($post_id, $image->ID);
                    }
                }

                wp_set_object_terms($post_id, (int) $_POST['imagepress_image_category'], 'imagepress_image_category');
                if(get_imagepress_option('ip_allow_tags') == 1)
                    wp_set_object_terms($post_id, (int) $_POST['imagepress_image_tag'], 'imagepress_image_tag');

                if('' != get_imagepress_option('ip_video_label'))
                    update_post_meta($post_id, 'imagepress_video', (string) $_POST['imagepress_video']);
                if('' != get_imagepress_option('ip_sticky_label'))
                    update_post_meta($post_id, 'imagepress_sticky', (string) $_POST['imagepress_sticky']);

                // custom fields
                global $wpdb;

                $result = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_fields ORDER BY field_order ASC", ARRAY_A);

                foreach($result as $field) {
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

                <p><label for="post_title"><?php _e('Title', 'imagepress'); ?></label><input type="text" id="post_title" name="post_title" value="<?php echo get_the_title($edit_id); ?>"></p>
                <p><label for="postcontent"><?php _e('Description', 'imagepress'); ?></label><textarea id="postcontent" name="postcontent" rows="3"><?php echo strip_tags(get_post_field('post_content', $edit_id)); ?></textarea></p>
                <hr>
                <?php if('' != get_imagepress_option('ip_video_label')) { ?>
                    <p><input type="url" name="imagepress_video" value="<?php echo get_post_meta($edit_id, 'imagepress_video', true); ?>" placeholder="<?php echo get_imagepress_option('ip_video_label'); ?>"></p>
                <?php } ?>

                <?php
                // custom fields
                global $wpdb;

                $result = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_fields ORDER BY field_order ASC", ARRAY_A);

                foreach($result as $field) {
                    $ps_meta = get_post_meta($edit_id, $field['field_slug'], true);

                    if((int) $field['field_type'] === 1) {
                        echo '<p><label for="' . $field['field_slug'] . '">' . $field['field_name'] . '</label><input type="text" id="' . $field['field_slug'] . '" name="' . $field['field_slug'] . '" placeholder="' . $field['field_name'] . '" value="' . $ps_meta . '"></p>';
                    } else if((int) $field['field_type'] === 2) {
                        echo '<p><label for="' . $field['field_slug'] . '">' . $field['field_name'] . '</label><input type="url" id="' . $field['field_slug'] . '" name="' . $field['field_slug'] . '" placeholder="' . $field['field_name'] . '" value="' . $ps_meta . '"></p>';
                    } else if((int) $field['field_type'] === 3) {
                        echo '<p><label for="' . $field['field_slug'] . '">' . $field['field_name'] . '</label><input type="email" id="' . $field['field_slug'] . '" name="' . $field['field_slug'] . '" placeholder="' . $field['field_name'] . '" value="' . $ps_meta . '"></p>';
                    } else if((int) $field['field_type'] === 4) {
                        echo '<p><label for="' . $field['field_slug'] . '">' . $field['field_name'] . '</label><input type="number" id="' . $field['field_slug'] . '" name="' . $field['field_slug'] . '" placeholder="' . $field['field_name'] . '" value="' . $ps_meta . '"></p>';
                    } else if((int) $field['field_type'] === 5) {
                        echo '<p><label for="' . $field['field_slug'] . '">' . $field['field_name'] . '</label><textarea id="' . $field['field_slug'] . '" name="' . $field['field_slug'] . '" rows="6" placeholder="' . $field['field_name'] . '">' . $ps_meta . '</textarea></p>';
                    } else if((int) $field['field_type'] === 6) {
                        if($ps_meta == 1) {
                            $checked = 'checked';
                        } else {
                            $checked = '';
                        }

                        echo '<p><label for="' . $field['field_slug'] . '"><input type="checkbox" id="' . $field['field_slug'] . '" name="' . $field['field_slug'] . '" placeholder="' . $field['field_name'] . '" value="1" ' . $checked . '> ' . $field['field_name'] . '</label></p>';
                    } else if((int) $field['field_type'] === 7) {
                        if($ps_meta == 1) {
                            $checked = 'checked';
                        } else {
                            $checked = '';
                        }

                        echo '<p><label for="' . $field['field_slug'] . '"><input type="radio" id="' . $field['field_slug'] . '" name="' . $field['field_slug'] . '" placeholder="' . $field['field_name'] . '" value="1" ' . $checked . '> ' . $field['field_name'] . '</label></p>';
                    } else if((int) $field['field_type'] === 8) {
                        echo '<p><label for="' . $field['field_slug'] . '">' . $field['field_name'] . '</label><select id="' . $field['field_slug'] . '" name="' . $field['field_slug'] . '" placeholder="' . $field['field_name'] . '">';
                            $options = $wpdb->get_var($wpdb->prepare("SELECT field_content FROM  " . $wpdb->prefix . "ip_fields WHERE field_name = '%s'", $field['field_name']));
                            $options = explode(',', $options);
                            foreach($options as $option) {
                                if($ps_meta == trim($option)) {
                                    $selected = 'selected';
                                } else {
                                    $selected = '';
                                }

                                echo '<option ' . $selected . '>' . trim($option) . '</option>';
                            }
                        echo '</select></p>';
                    } else if(
                        (int) $field['field_type'] === 20 ||
                        (int) $field['field_type'] === 21 ||
                        (int) $field['field_type'] === 22 ||
                        (int) $field['field_type'] === 23 ||
                        (int) $field['field_type'] === 24
                    ) {
                        echo '<p><label for="' . $field['field_slug'] . '">' . $field['field_name'] . '</label><input type="text" id="' . $field['field_slug'] . '" name="' . $field['field_slug'] . '" placeholder="' . $field['field_name'] . '" value="' . $ps_meta . '"></p>';
                    }
                }
                //
                ?>
                <hr>

                <?php if('' != get_imagepress_option('ip_sticky_label')) { ?>
                    <p><label for="imagepress_sticky"><input type="checkbox" id="imagepress_sticky" name="imagepress_sticky" value="1"<?php if(get_post_meta($edit_id, 'imagepress_sticky', true) == 1) echo ' checked'; ?>> <?php echo get_imagepress_option('ip_sticky_label'); ?></label></p>
                <?php } ?>

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
                $ip_width = get_imagepress_option('ip_max_width');
                ?>
                <p><label for="imagepress_image_file"><i class="fa fa-cloud-upload"></i> Replace main image (<?php echo $uploadsize . 'MB ' . __('maximum', 'imagepress'); ?>)...</label><br><input type="file" accept="image/*" data-max-size="<?php echo $datauploadsize; ?>" data-max-width="<?php echo $ip_width; ?>" name="imagepress_image_file" id="imagepress_image_file"></p>

                <?php if(1 == get_imagepress_option('ip_upload_secondary')) { ?>
                    <hr>
                    <p>
                        <?php _e('Select', 'imagepress'); ?> <i class="fa fa-check-circle"></i> <?php _e('main image or', 'imagepress'); ?> <i class="fa fa-times-circle"></i> <?php _e('delete additional images', 'imagepress'); ?>
                        <br><small><?php _e('Main image will appear first in single image listing and as a thumbnail in gallery view', 'imagepress'); ?></small>
                    </p>
                    <div class="ip-hide ip-notice"><p><i class="fa fa-check" aria-hidden="true"></i> <?php _e('Featured image selected succesfully!', 'imagepress'); ?></p></div>
                    <?php
                    $thumbnail_ID = get_post_thumbnail_id();
                    $images = get_children(array('post_parent' => $edit_id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID'));
                    $count = count($images);

                    if($count > 1) {
                        echo '<div>';
                            foreach($images as $attachment_id => $image) {
                                $small_array = image_downsize($image->ID, 'thumbnail');
                                $big_array = image_downsize($image->ID, 'full');

                                if($image->ID == $thumbnail_ID)
                                    echo '<div class="ip-additional-active">';
                                if($image->ID != $thumbnail_ID)
                                    echo '<div class="ip-additional">';
                                    echo '<div class="ip-toolbar">';
                                        echo '<a href="#" data-id="' . $image->ID . '" data-nonce="' . wp_create_nonce('ip_delete_post_nonce') . '" class="delete-post ip-action-icon ip-floatright"><i class="fa fa-times-circle"></i></a>';
                                        echo '<a href="#" data-pid="' . $edit_id . '" data-id="' . $image->ID . '" data-nonce="' . wp_create_nonce('ip_featured_post_nonce') . '" class="featured-post ip-action-icon ip-floatleft"><i class="fa fa-check-circle"></i></a>';
                                    echo '</div>';
                                echo '<img src="' . $small_array[0] . '" alt=""></div>';
                            }
                        echo '</div>';
                    }
                    ?>

                    <p><label for="imagepress_image_additional"><i class="fa fa-cloud-upload"></i> <?php _e('Add more images', 'imagepress'); ?> (<?php echo $uploadsize; ?>MB <?php _e('maximum', 'imagepress'); ?>)...</label><br><input type="file" accept="image/*" data-max-size="<?php echo $datauploadsize; ?>" name="imagepress_image_additional[]" id="imagepress_image_additional" multiple></p>
                <?php } ?>

                <hr>
                <p>
                    <input type="submit" id="submit" value="<?php _e('Update image', 'imagepress'); ?>">
                    <a href="?d=<?php echo get_the_ID(); ?>" class="ask button ip-floatright"><i class="fa fa-trash-o"></i></a>
                </p>
            </form>
        </div>
        <?php wp_reset_query(); ?>
    <?php }
}

// ip_editor() related actions
add_action('wp_ajax_ip_delete_post', 'ip_delete_post');
function ip_delete_post() {
    $permission = check_ajax_referer('ip_delete_post_nonce', 'nonce', false);
    if($permission == false) {
        echo 'error';
    }
    else {
        wp_delete_post($_REQUEST['id']);
        echo 'success';
    }
    die();
}
add_action('wp_ajax_ip_delete_post_simple', 'ip_delete_post_simple');
function ip_delete_post_simple() {
    wp_delete_post($_REQUEST['id']);
    echo 'success';
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
        $ip_comments = '<em> | </em><a href="' . get_permalink($i) . '"><i class="fa fa-comments"></i> ' . get_comments_number($i) . '</a> ';
    if(get_imagepress_option('ip_comments') == 0)
        $ip_comments = '';
    ?>

    <div class="imagepress-container">
        <?php the_post_thumbnail('full'); ?>
        <?php ip_setPostViews($i); ?>
    </div>

    <div class="ip-bar">
        <?php echo ipGetPostLikeLink($i); ?><em> | </em><i class="fa fa-eye"></i> <?php echo ip_getPostViews($i); ?><?php echo $ip_comments; ?>
        <?php if(get_imagepress_option('ip_mod_collections') == 1) { ?>
            <em> | </em>
            <?php if(function_exists('ip_frontend_add_collection')) ip_frontend_add_collection(get_the_ID()); ?>
        <?php } ?>

        <a href="<?php echo $post_thumbnail_url; ?>" class="thin-ui-button"><i class="fa fa-fw fa-arrows-alt"></i></a>

        <?php
        // show image editor
        ip_editor();
        ?>
    </div>

    <h1 class="ip-title">
        <?php
        if(has_term('featured', 'imagepress_image_category'))
            echo '<i class="fa fa-star"></i></span> ';

        echo get_the_title($i);

        if(get_imagepress_option('ip_allow_tags') == 1) {
            $terms = get_the_terms($i, 'imagepress_image_tag');

            if($terms && !is_wp_error($terms)) :
                $term_links = array();
                foreach($terms as $term) {
                    $term_links[] = $term->name;
                }
                $tags = join(', ', $term_links);
                echo '<br><small><i class="fa fa-info-circle"></i> ' . $tags . '</small>';
            endif;
        }
        ?>
    </h1>

    <p>
        <div style="float: left; margin: 0 8px 0 0;">
            <?php echo get_avatar($post->post_author, 40); ?>
        </div>
        <?php
        if(get_the_author_meta('user_title', $post->post_author) == 'Verified')
            $verified = ' <span class="teal hint hint--right" data-hint="' . get_imagepress_option('cms_verified_profile') . '"><i class="fa fa-check-square"></i></span>';
        else
            $verified = '';
        ?>
        <?php _e('by', 'imagepress'); ?> <b><?php the_author_posts_link(); ?></b> <?php echo $verified; ?>
        <br><small><?php _e('Uploaded', 'imagepress'); ?> <time title="<?php the_time(get_imagepress_option('date_format')); ?>"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago'; ?></time> <?php _e('in', 'imagepress'); ?> <?php echo get_the_term_list(get_the_ID(), 'imagepress_image_category', '', ', ', ''); ?></small>
    </p>

    <div class="ip-clear"></div>

    <?php
    // custom preset fields
    $result = $wpdb->get_results("SELECT field_type, field_name, field_slug FROM " . $wpdb->prefix . "ip_fields ORDER BY field_order ASC", ARRAY_A);

    foreach($result as $field) {
        $fs_meta = get_post_meta($i, $field['field_slug'], true);

        if((int) $field['field_type'] === 20 && !empty($fs_meta)) {
            $sketchfabId = $fs_meta;
            echo '<iframe width="100%" height="480" src="https://sketchfab.com/models/' . $sketchfabId . '/embed" frameborder="0" allowfullscreen mozallowfullscreen="true" webkitallowfullscreen="true" onmousewheel=""></iframe><br>via <a href="https://sketchfab.com/models/' . $sketchfabId . '?utm_medium=embed&utm_source=website&utm_campain=share-popup" target="_blank">Sketchfab</a>';
        }
        if((int) $field['field_type'] === 21 && !empty($fs_meta)) {
            $vimeoId = $fs_meta;
            echo '<iframe src="https://player.vimeo.com/video/' . $vimeoId . '" width="100%" height="480" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
        }
        if((int) $field['field_type'] === 22 && !empty($fs_meta)) {
            $youtubeId = $fs_meta;
            echo '<iframe width="100%" height="480" src="https://www.youtube.com/embed/' . $youtubeId . '?rel=0" frameborder="0" allowfullscreen></iframe>';
        }
        if((int) $field['field_type'] === 23 && !empty($fs_meta)) {
            $googleMapsLocation = $fs_meta;
            echo '<p><img class="single-image-map" src="https://maps.googleapis.com/maps/api/staticmap?center=' . $googleMapsLocation . '&scale=2&zoom=13&size=600x300&maptype=terrain" alt="' . $googleMapsLocation . '" width="600"></p>';
        }
        if((int) $field['field_type'] === 24 && !empty($fs_meta)) {
            $roundMeTourId = $fs_meta;
            echo '<p><iframe width="100%" height="480" src="https://round.me/embed/' . $roundMeTourId . '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></p>';
        }
    }
    //
    ?>

    <?php imagepress_get_images($i); ?>

    <?php
    $imagepress_video = get_post_meta($i, 'imagepress_video', true);
    if(!empty($imagepress_video)) {
        echo '<br>';
        $embed_code = wp_oembed_get($imagepress_video);
        echo $embed_code;
        echo '<br>';
    }
    ?>

    <div class="social-hub">
        <div class="clearfix"></div>
    </div>

    <section>
        <?php echo wpautop(make_clickable($post->post_content)); ?>
    </section>

    <section role="navigation">
        <?php previous_post_link('%link', '<i class="fa fa-fw fa-chevron-left"></i> Previous'); ?>
        <?php next_post_link('%link', 'Next <i class="fa fa-fw fa-chevron-right"></i>'); ?>
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
    global $post;

    $thumbnail_ID = get_post_thumbnail_id();
    $images = get_children(array('post_parent' => $post_id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID'));

    if($images && count($images) > 1) {
        echo '<div class="ip-more">';
            foreach($images as $attachment_id => $image) {
                if($image->ID != $thumbnail_ID) {
                    $big_array = image_downsize($image->ID, 'full');

                    echo '<img src="' . $big_array[0] . '" alt="">';
                }
            }
        echo '</div>';
    }
}

function kformat($number) {
    $number = (int) $number;

    /**
    $prefixes = 'KMGTPEZY';
    if($number >= 1000) {
        $log1000 = floor(log10($number)/3);

        return floor($number/pow(1000, (int) $log1000)) . $prefixes[(int) $log1000 - 1];
    }

    return $number;
    /**/
    return number_format($number, 0, '.', ',');
}

function ip_related($i) {
    global $post;
    $post_thumbnail_id = get_post_thumbnail_id($i);
    $author_id = $post->post_author;
    $filesize = filesize(get_attached_file($post_thumbnail_id)) / 1024;
    $filesize = number_format($filesize, 2, '.', ' ');
    $filesize .= ' KB'; ?>
    <h5 class="widget-title"><i class="fa fa-file-text-o"></i> <?php echo __('Image Details', 'imagepress'); ?></h5>
    <div class="textwidget">
        <p><small>
            &copy;<?php echo date('Y'); ?> <a href="<?php echo get_author_posts_url($post->post_author); ?>"><?php echo get_the_author_meta('user_nicename', $post->post_author); ?></a> | <b>Image size:</b> <?php echo $filesize; ?> | <b>Date uploaded:</b> <?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago'; ?> (<?php the_time(get_option('date_format')); ?>) | <b>Category:</b> <?php echo ip_get_the_term_list($i, 'imagepress_image_category', '', ', ', '', array()); ?> | <?php echo get_the_term_list($i, 'imagepress_image_tag', '', ', ', ''); ?>
            <br>
            <b><?php echo ip_getPostViews($i); ?></b> <?php echo __('views', 'imagepress'); ?>, <b><?php echo get_comments_number($i); ?></b> <?php echo __('comments', 'imagepress'); ?>, <b><?php echo imagepress_get_like_count($i); ?></b> <?php echo __('likes', 'imagepress'); ?>
        </small></p>
    </div>

    <hr>

    <div class="widget-container widget_text">
        <h5 class="widget-title"><i class="fa fa-tags"></i> <?php echo __('Related', 'imagepress'); ?></h5>
        <div class="textwidget">
            <p><i class="fa fa-user"></i> <?php echo __('More by the same author', 'imagepress'); ?> (<a href="<?php echo get_author_posts_url($post->post_author); ?>"><?php echo __('view all', 'imagepress'); ?></a>)</p>
            <?php echo cinnamon_get_related_author_posts($post->post_author); ?>
        </div>
    </div>
    <?php
}

function ip_author() {
    // check for external portfolio // if page call is made from subdomain (e.g. username.domain.ext), display external page
    if(isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
        $protocol = 'https://';
    } else {
        $protocol = 'http://';
    }

    $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $parseUrl = parse_url($url);
    $ext_detect = trim($parseUrl['path']);
    if($ext_detect == '/') {
        echo '<div id="hub-loading"></div>';
        echo do_shortcode('[cinnamon-profile-blank]');
    }
    else {
        echo do_shortcode('[cinnamon-profile]');
    }
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
    if(strpos($message, 'Register For This Site') == true) {
        $message = '<p class="message">' . __('Register for ImagePress', 'imagepress') . '</p>';
    }

    return $message;
}



// Frontend login/registration redirect
$cinnamon_mod_login = get_imagepress_option('cinnamon_mod_login');

if((int) $cinnamon_mod_login === 1) {
    //add_action('init', 'imagepress_fe_prevent_wp_login');
}

function imagepress_fe_prevent_wp_login() {
    global $pagenow;

    $action = (isset($_GET['action'])) ? $_GET['action'] : '';
    // Check if we're on the login page, and ensure the action is not 'logout'
    if($pagenow == 'wp-login.php' && (!$action || ($action && !in_array($action, array('logout', 'lostpassword', 'rp'))))) {
        $cinnamon_account_page = (string) get_imagepress_option('cinnamon_account_page');

        wp_redirect($cinnamon_account_page);

        exit();
    }
}
//

$ip_mod_login = get_imagepress_option('ip_mod_login');
if($ip_mod_login == 1) {
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

function ip_get_field($atts, $content = null) {
    extract(shortcode_atts(array(
        'field' => '',
    ), $atts));

    global $post;

    $i = get_the_ID();
    $field = get_post_meta($i, $field, true);

    return $field;
}

function imagepress_image_download($path) {
    $out = '<a href="' . $path . '" class="thin-ui-button" download><i class="fa fa-fw fa-download" aria-hidden="true"></i> ' . __('Download', 'noir-ui') . '</a>';

    return $out;
}

// Hooks and actions
function do_ip_before_show() {
    do_action('do_ip_before_show');
}

// Example hook
function my_ip_function() {
    // echo '<p>This is a hooked action.</p>';
}
add_action('do_ip_before_show', 'my_ip_function');

function imagepress_order_list() {
    global $wpdb;

    foreach($_POST['listItem'] as $position => $item) {
        $wpdb->query("UPDATE `" . $wpdb->prefix . "posts` SET `menu_order` = $position WHERE `ID` = $item");
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
function update_imagepress_option($option) {
    $ipOptions = get_option('imagepress');

    unset($ipOptions[$option]);
}
