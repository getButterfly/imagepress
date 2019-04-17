<?php
function imagepress_registration() {
    $ip_slug = sanitize_text_field(get_imagepress_option('ip_slug'));

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

    $labels = [
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
    ];

    $args = [
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
    ];

    register_taxonomy('imagepress_image_tag', [$ip_slug], $args);
}

function ip_getPostViews($postID) {
    $count = get_post_meta($postID, 'post_views_count', true);
    $count = empty($count) ? 0 : $count;

    update_post_meta($postID, 'post_views_count', $count);

    return $count;
}
function ip_setPostViews($postID) {
    $count = get_post_meta($postID, 'post_views_count', true);
    $count = empty($count) ? 1 : $count + 1;

    update_post_meta($postID, 'post_views_count', $count);
}



// frontend image editor
function ip_editor() {
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
                ip_upload_secondary($_FILES['imagepress_image_additional'], $post_id);

                wp_set_object_terms($post_id, (int) $_POST['imagepress_image_category'], 'imagepress_image_category');
                if (get_imagepress_option('ip_allow_tags') == 1)
                    wp_set_object_terms($post_id, (int) $_POST['imagepress_image_tag'], 'imagepress_image_tag');

                // Custom fields
                $result = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_fields ORDER BY field_order ASC", ARRAY_A);

                foreach ($result as $field) {
                    update_post_meta($post_id, $field['field_slug'], $_POST[$field['field_slug']]);
                }
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

                // custom fields
                $result = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_fields ORDER BY field_order ASC", ARRAY_A);

                foreach ($result as $field) {
                    $ps_meta = get_post_meta($edit_id, $field['field_slug'], true);

                    $fieldType = (int) sanitize_text_field($field['field_type']);
                    $fieldSlug = sanitize_text_field($field['field_slug']);
                    $fieldName = sanitize_text_field($field['field_name']);

                    if ($fieldType === 1) {
                        $out .= '<p><label for="' . $fieldSlug . '">' . $fieldName . '</label><br><input type="text" id="' . $fieldSlug . '" name="' . $fieldSlug . '" placeholder="' . $fieldName . '" value="' . $ps_meta . '"></p>';
                    } else if ($fieldType === 2) {
                        $out .= '<p><label for="' . $fieldSlug . '">' . $fieldName . '</label><br><input type="url" id="' . $fieldSlug . '" name="' . $fieldSlug . '" placeholder="' . $fieldName . '" value="' . $ps_meta . '"></p>';
                    } else if ($fieldType === 3) {
                        $out .= '<p><label for="' . $fieldSlug . '">' . $fieldName . '</label><br><input type="email" id="' . $fieldSlug . '" name="' . $fieldSlug . '" placeholder="' . $fieldName . '" value="' . $ps_meta . '"></p>';
                    } else if ($fieldType === 4) {
                        $out .= '<p><label for="' . $fieldSlug . '">' . $fieldName . '</label><br><input type="number" id="' . $fieldSlug . '" name="' . $fieldSlug . '" placeholder="' . $fieldName . '" value="' . $ps_meta . '"></p>';
                    } else if ($fieldType === 5) {
                        $out .= '<p><label for="' . $fieldSlug . '">' . $fieldName . '</label><br><textarea id="' . $fieldSlug . '" name="' . $fieldSlug . '" rows="6" placeholder="' . $fieldName . '">' . $ps_meta . '</textarea></p>';
                    } else if ($fieldType === 6) {
                        $checked = ((int) $ps_meta === 1) ? 'checked' : '';

                        $out .= '<p><label for="' . $fieldSlug . '"><input type="checkbox" id="' . $fieldSlug . '" name="' . $fieldSlug . '" placeholder="' . $fieldName . '" value="1" ' . $checked . '> ' . $fieldName . '</label></p>';
                    } else if ($fieldType === 7) {
                        $checked = ((int) $ps_meta === 1) ? 'checked' : '';

                        $out .= '<p><label for="' . $fieldSlug . '"><input type="radio" id="' . $fieldSlug . '" name="' . $fieldSlug . '" placeholder="' . $fieldName . '" value="1" ' . $checked . '> ' . $fieldName . '</label></p>';
                    } else if ($fieldType === 8) {
                        $out .= '<p><label for="' . $fieldSlug . '">' . $fieldName . '</label><select id="' . $fieldSlug . '" name="' . $fieldSlug . '" placeholder="' . $fieldName . '">';
                            $options = $wpdb->get_var($wpdb->prepare("SELECT field_content FROM  " . $wpdb->prefix . "ip_fields WHERE field_name = '%s'", $fieldName));
                            $options = explode(',', $options);
                            foreach ($options as $option) {
                                $selected = ($ps_meta == trim($option)) ? 'selected' : '';

                                $out .= '<option ' . $selected . '>' . trim($option) . '</option>';
                            }
                        $out .= '</select></p>';
                    } else if (
                        $fieldType === 20 ||
                        $fieldType === 21 ||
                        $fieldType === 22 ||
                        $fieldType === 23 ||
                        $fieldType === 24
                    ) {
                        $out .= '<p><label for="' . $fieldSlug . '">' . $fieldName . '</label><br><input type="text" id="' . $fieldSlug . '" name="' . $fieldSlug . '" placeholder="' . $fieldName . '" value="' . $ps_meta . '"></p>';
                    }
                }
                //

                $out .= '<hr>';

                $ip_category = wp_get_object_terms($edit_id, 'imagepress_image_category');
                if ((int) get_imagepress_option('ip_allow_tags') === 1) {
                    $ip_tag = wp_get_post_terms($edit_id, 'imagepress_image_tag');
                }

                $out .= imagepress_get_image_categories_dropdown('imagepress_image_category', $ip_category[0]->term_id);
                if ((int) get_imagepress_option('ip_allow_tags') === 1) {
                    $out .= '<p>' . imagepress_get_image_tags_dropdown('imagepress_image_tag', $ip_tag[0]->term_id) . '</p>';
                }

                $ip_upload_size = get_imagepress_option('ip_upload_size');
                $uploadsize = number_format((($ip_upload_size * 1024)/1024000), 0, '.', '');
                $datauploadsize = $uploadsize * 1024000;

                if ((int) get_imagepress_option('ip_upload_secondary') === 1) {
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

                                if ($image->ID != $thumbnail_ID)
                                    $out .= '<div class="ip-additional ip-additional-' . $image->ID . '">';
                                    $out .= '<div class="ip-toolbar">';
                                        $out .= '<a href="#" data-image-id="' . $image->ID . '" data-redirect="' . get_permalink() . '" class="ip-delete-post ip-action-icon ip-floatright"><i class="fas fa-trash-alt"></i></a>';
                                    $out .= '</div>';
                                $out .= '<img src="' . $small_array[0] . '" alt=""></div>';
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

// ip_editor() related actions
add_action('wp_ajax_ip_delete_post', 'ip_delete_post');
function ip_delete_post() {
    $imageId = (int) $_POST['id'];

    if (wp_delete_post($imageId)) {
        echo 'success';
    }

    die();
}
add_action('wp_ajax_ip_update_post_title', 'ip_update_post_title');
function ip_update_post_title() {
    $updated_post = [
        'ID' => (int) $_REQUEST['id'],
        'post_title' => (string) $_REQUEST['title']
    ];

    wp_update_post($updated_post);

    echo 'success';
    die();
}



// main ImagePress image function
function ip_main($imageId) {
    global $wpdb, $post;

    $postThumbnailId = get_post_thumbnail_id($imageId);
    $image_attributes = wp_get_attachment_image_src($postThumbnailId, 'full');
    $post_thumbnail_url = $image_attributes[0];

    $ip_comments = '';
    if ((int) get_imagepress_option('ip_comments') === 1) {
        $ip_comments = '<em> | </em><a href="' . get_permalink($imageId) . '"><i class="fas fa-comments"></i> ' . get_comments_number($imageId) . '</a> ';
    }
    ?>

    <div class="imagepress-container">
        <a href="<?php echo $post_thumbnail_url; ?>">
            <?php the_post_thumbnail('full'); ?>
        </a>
        <?php
        if ((int) get_imagepress_option('ip_enable_views') === 1) {
            ip_setPostViews($imageId);
        }
        ?>
    </div>

    <div class="ip-bar">
        <?php echo ipGetPostLikeLink($imageId); ?><em> | </em>
        <?php if ((int) get_imagepress_option('ip_enable_views') === 1) { ?>
            <i class="far fa-eye"></i> <?php echo ip_getPostViews($imageId); ?>
        <?php } ?>
        <?php echo $ip_comments; ?>
        <em> | </em>
        <?php if (function_exists('ip_frontend_add_collection')) {
            echo ip_frontend_add_collection(get_the_ID());
        }

        /*
         * Image editor
         */
        echo ip_editor();
        ?>
    </div>

    <h1 class="ip-title">
        <?php
        echo get_the_title($imageId);

        if (get_imagepress_option('ip_allow_tags') == 1) {
            $terms = get_the_terms($imageId, 'imagepress_image_tag');

            if ($terms && !is_wp_error($terms)) :
                $term_links = [];
                foreach($terms as $term) {
                    $term_links[] = $term->name;
                }
                $tags = join(', ', $term_links);
                echo '<br><small>' . $tags . '</small>';
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
        $fs_meta = get_post_meta($imageId, $field['field_slug'], true);

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

    imagepress_get_images($imageId, 1);
    ?>

    <section>
        <?php the_content(); ?>
    </section>

    <section role="navigation">
        <?php
        previous_post_link('%link', esc_html__('Previous', 'imagepress'));
        next_post_link('%link', esc_html__('Next', 'imagepress'));
        ?>
    </section>
    <?php
}



// main ImagePress image function
function ip_main_return($imageId) {
    global $wpdb, $post;

    $out = '';
    if ((int) get_imagepress_option('ip_enable_views') === 1) {
        ip_setPostViews($imageId);
    }

    $postThumbnailId = get_post_thumbnail_id($imageId);
    $image_attributes = wp_get_attachment_image_src($postThumbnailId, 'full');
    $post_thumbnail_url = $image_attributes[0];

    $ip_comments = '';

    if ((int) get_imagepress_option('ip_comments') === 1) {
        $ip_comments = '<em> | </em><a href="' . get_permalink($imageId) . '"><i class="fas fa-comments"></i> ' . get_comments_number($imageId) . '</a> ';
    }

    $out .= '<div class="imagepress-container">
        <a href="' . $post_thumbnail_url . '">' . get_the_post_thumbnail($imageId, 'full') . '</a>
    </div>

    <div class="ip-bar">' .
        ipGetPostLikeLink($imageId) . '<em> | </em>';
        if ((int) get_imagepress_option('ip_enable_views') === 1) {
            $out .= '<i class="far fa-eye"></i> ' . ip_getPostViews($imageId);
        }
        $out .= $ip_comments;

        $out .= '<em> | </em>';
        if (function_exists('ip_frontend_add_collection')) {
            $out .= ip_frontend_add_collection(get_the_ID());
        }

        $out .= ip_editor();
    $out .= '</div>

    <h1 class="ip-title">';
        if ((int) get_imagepress_option('ip_allow_tags') === 1) {
            $terms = get_the_terms($imageId, 'imagepress_image_tag');

            if ($terms && !is_wp_error($terms)) :
                $term_links = [];
                foreach ($terms as $term) {
                    $term_links[] = $term->name;
                }
                $tags = join(', ', $term_links);
                $out .= '<br><small>' . $tags . '</small>';
            endif;
        }
    $out .= '</h1>

    <p>
        <div style="float: left; margin: 0 8px 0 0;">' . get_avatar($post->post_author, 40) . '</div>' .
        __('by', 'imagepress') . ' <b>' . getImagePressProfileUri($post->post_author) . '</b>
        <br><small><time title="' . date_i18n(get_option('time_format'), get_the_time('U')) . '">' . date_i18n(get_option('date_format'), get_the_time('U')) . '</time> ' . __('in', 'imagepress') . ' ' . get_the_term_list(get_the_ID(), 'imagepress_image_category', '', ', ', '') . '</small>
    </p>

    <div class="ip-clear"></div>';

    // custom preset fields
    $result = $wpdb->get_results("SELECT field_type, field_name, field_slug FROM " . $wpdb->prefix . "ip_fields ORDER BY field_order ASC", ARRAY_A);

    foreach ($result as $field) {
        $fs_meta = get_post_meta($imageId, $field['field_slug'], true);

        if ((int) $field['field_type'] === 20 && !empty($fs_meta)) {
            $sketchfabId = $fs_meta;
            $out .= '<iframe width="100%" height="480" src="https://sketchfab.com/models/' . $sketchfabId . '/embed" frameborder="0" allowfullscreen mozallowfullscreen="true" webkitallowfullscreen="true" onmousewheel=""></iframe><br>via <a href="https://sketchfab.com/models/' . $sketchfabId . '?utm_medium=embed&utm_source=website&utm_campain=share-popup" target="_blank">Sketchfab</a>';
        }
        if ((int) $field['field_type'] === 21 && !empty($fs_meta)) {
            $vimeoId = $fs_meta;
            $out .= '<iframe src="https://player.vimeo.com/video/' . $vimeoId . '" width="100%" height="480" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
        }
        if ((int) $field['field_type'] === 22 && !empty($fs_meta)) {
            $youtubeId = $fs_meta;
            $out .= '<iframe width="100%" height="480" src="https://www.youtube.com/embed/' . $youtubeId . '?rel=0" frameborder="0" allowfullscreen></iframe>';
        }
        if ((int) $field['field_type'] === 23 && !empty($fs_meta)) {
            $googleMapsLocation = $fs_meta;
            $out .= '<p><img class="single-image-map" src="https://maps.googleapis.com/maps/api/staticmap?center=' . $googleMapsLocation . '&scale=2&zoom=13&size=600x300&maptype=terrain" alt="' . $googleMapsLocation . '" width="600"></p>';
        }
        if ((int) $field['field_type'] === 24 && !empty($fs_meta)) {
            $roundMeTourId = $fs_meta;
            $out .= '<p><iframe width="100%" height="480" src="https://round.me/embed/' . $roundMeTourId . '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></p>';
        }
    }

    $out .= '<section>' .
        get_the_content() .
    '</section>';

    $out .= imagepress_get_images($imageId, 0);

    $out .= '<hr><section role="navigation"><p>' .
        get_previous_post_link('%link', esc_html__('Previous', 'imagepress')) . ' | ' .
        get_next_post_link('%link', esc_html__('Next', 'imagepress')) .
    '</p></section>';

    return $out;
}



function ip_get_the_term_list($imageId = 0, $taxonomy, $before = '', $sep = '', $after = '', $exclude = []) {
    $terms = get_the_terms($imageId, $taxonomy);

    if (is_wp_error($terms))
        return $terms;

    if (empty($terms))
        return false;

    foreach ($terms as $term) {
        if (!in_array($term->term_id, $exclude)) {
            $link = get_term_link($term, $taxonomy);
            if (is_wp_error($link))
                return $link;
            $term_links[] = '<a href="' . $link . '" rel="tag">' . $term->name . '</a>';
        }
    }

    $term_links = apply_filters("term_links-$taxonomy", $term_links);

    return $before . join($sep, $term_links) . $after;
}

function imagepress_get_images($postId, $show) {
    $thumbnail_ID = get_post_thumbnail_id();
    $images = get_children(['post_parent' => $postId, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID']);
    $out = '';

    if ($images && count($images) > 0) {
        $out .= '<div class="ip-more">';
            foreach ($images as $attachment_id => $image) {
                if ($image->ID != $thumbnail_ID) {
                    $big_array = image_downsize($image->ID, 'full');

                    $out .= '<img src="' . $big_array[0] . '" alt="">';
                }
            }
        $out .= '</div>';
    }

    $videos = get_children(['post_parent' => $postId, 'post_status' => 'inherit', 'post_type' => 'attachment', 'order' => 'ASC', 'orderby' => 'menu_order ID']);

    if ($videos && count($videos) > 1) {
        $out .= '<div class="ip-more">';
            foreach ($videos as $attachment_id => $video) {
                if (strpos(get_post_mime_type($video->ID), 'video') !== false) {
                    $out .= '<video width="100%" class="ip-video-secondary" controls>
                        <source src="' . wp_get_attachment_url($video->ID) . '" type="' . get_post_mime_type($video->ID) . '">
                        Your browser does not support HTML5 video.
                    </video>';
                }
            }
        $out .= '</div>';
    }

    if ((int) $show === 1) {
        echo $out;
    } else {
        return $out;
    }
}

function kformat($number) {
    $number = (int) $number;

    return number_format($number, 0, '.', ',');
}

function ip_related() {
    global $post;

    $out = '<h3>' . __('More by the same author', 'imagepress') . '</h3>' .
    cinnamon_get_related_author_posts($post->post_author);

    return $out;
}

function ip_author() {
    echo do_shortcode('[cinnamon-profile]');
}





function ip_return_image_sizes() {
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

function ip_get_user_role() {
    global $current_user;

    $user_roles = $current_user->roles;
    $user_role = array_shift($user_roles);

    return $user_role;
}

function ip_get_field($atts) {
    extract(shortcode_atts([
        'field' => ''
    ], $atts));

    $field = get_post_meta(get_the_ID(), $field, true);

    return $field;
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

function ipRenderGridElement($elementId) {
    $postThumbnailId = get_post_thumbnail_id($elementId);

    $ip_click_behaviour = get_imagepress_option('ip_click_behaviour');
    $getImagePressTitle = get_imagepress_option('ip_title_optional');
    $getImagePressAuthor = get_imagepress_option('ip_author_optional');
    $getImagePressMeta = get_imagepress_option('ip_meta_optional');
    $getImagePressViews = get_imagepress_option('ip_views_optional');
    $getImagePressLikes = get_imagepress_option('ip_likes_optional');
    $get_ip_comments = get_imagepress_option('ip_comments');
    $size = get_imagepress_option('ip_image_size');

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

        $ip_author_optional = getImagePressProfileUri($post_author_id);
    }

    $ip_meta_optional = '';
    if ((int) $getImagePressMeta === 1)
        $ip_meta_optional = '<span class="imagecategory" data-tag="' . strip_tags(get_the_term_list($elementId, 'imagepress_image_category', '', ', ', '')) . '">' . strip_tags(get_the_term_list($elementId, 'imagepress_image_category', '', ', ', '')) . '</span>';

    $ip_views_optional = '';
    if ((int) $getImagePressViews === 1 && (int) get_imagepress_option('ip_enable_views') === 1) {
        $ip_views_optional = '<span class="imageviews"><i class="far fa-eye"></i> ' . ip_getPostViews($elementId) . '</span> ';
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
function ip_collection_dropdown() {
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
function ip_upload_secondary($filesArray, $postId) {
    if ((int) get_imagepress_option('ip_upload_secondary') === 1) {
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

















function ip_login() {
    global $user_ID, $user_identity;

    $out = '';

	if (!$user_ID) {
        $out .= '<ul class="tabs">
            <li><a href="#login" class="is-active">' . __('Login', 'imagepress') . '</a></li>
            <li><a href="#register">' . __('Register', 'imagepress') . '</a></li>
            <li><a href="#forgot-password">' . __('Forgot your password?', 'imagepress') . '</a></li>
        </ul>
        <div id="login" class="tab-content">';
            $register = $_GET['register'];
            $reset = $_GET['reset'];

            if ($register == true) {
                $out .= '<h3>Success!</h3>
                <p>Check your email for the password and then return to log in.</p>';
			} else if ($reset == true) {
                $out .= '<h3>Success!</h3>
                <p>Check your email to reset your password.</p>';
			} else {
                $out .= '<h3>Have an account?</h3>
                <p>Log in or sign up!</p>';
			}

			$out .= '<form method="post" action="' . home_url() . '/wp-login.php" class="wp-user-form">
				<div class="username">
					<label for="user_login">Username: </label>
					<input type="text" name="log" value="' . esc_attr(stripslashes($user_login)) . '" size="20" id="user_login">
				</div>
				<div class="password">
					<label for="user_pass">Password: </label>
					<input type="password" name="pwd" value="" size="20" id="user_pass">
				</div>
				<div class="login_fields">
					<div class="rememberme">
						<label for="rememberme">
							<input type="checkbox" name="rememberme" value="forever" checked="checked" id="rememberme"> Remember me
						</label>
					</div>';

                    //do_action('login_form');

                    $out .= '<input type="submit" name="user-submit" value="Login" class="user-submit">
					<input type="hidden" name="redirect_to" value="' . $_SERVER['REQUEST_URI'] . '">
					<input type="hidden" name="user-cookie" value="1">
				</div>
			</form>
		</div>
		<div id="register" class="tab-content">
			<h3>Register for this site!</h3>
			<p>Sign up now for the good stuff.</p>
			<form method="post" action="' . site_url('wp-login.php?action=register', 'login_post') . '" class="wp-user-form">
				<div class="username">
					<label for="user_login">Username: </label>
					<input type="text" name="user_login" value="' . esc_attr(stripslashes($user_login)) . '" size="20" id="user_login">
				</div>
				<div class="password">
					<label for="user_email">Your Email: </label>
					<input type="text" name="user_email" value="' . esc_attr(stripslashes($user_email)) . '" size="25" id="user_email">
				</div>
				<div class="login_fields">';
					// do_action('register_form');
					$out .= '<input type="submit" name="user-submit" value="Sign up!" class="user-submit">';
					$register = $_GET['register'];
                    if ($register == true) {
                        $out .= '<p>Check your email for the password!</p>';
                    }
					$out .= '<input type="hidden" name="redirect_to" value="' . $_SERVER['REQUEST_URI'] . '?register=true">
					<input type="hidden" name="user-cookie" value="1">
				</div>
			</form>
		</div>
		<div id="forgot-password" class="tab-content">
			<h3>Lose something?</h3>
			<p>Enter your username or email to reset your password.</p>
			<form method="post" action="' . site_url('wp-login.php?action=lostpassword', 'login_post') . '" class="wp-user-form">
				<div class="username">
					<label for="user_login" class="hide">Username or Email: </label>
					<input type="text" name="user_login" value="" size="20" id="user_login">
				</div>
				<div class="login_fields">';
					// do_action('login_form', 'resetpass');
					$out .= '<input type="submit" name="user-submit" value="Reset my password" class="user-submit">';
					$reset = $_GET['reset'];
                    if ($reset == true) {
                        echo '<p>A message will be sent to your email address.</p>';
                    }
					$out .= '<input type="hidden" name="redirect_to" value="' . $_SERVER['REQUEST_URI'] . '?reset=true">
					<input type="hidden" name="user-cookie" value="1">
				</div>
			</form>
		</div>';

	} else { // is logged in

	$out .= '<div class="sidebox">
		<h3>Welcome, ' . $user_identity . '</h3>
		<div class="userinfo">
		</div>
	</div>';

	}

    return $out;
}

add_shortcode('cinnamon-login', 'ip_login');
