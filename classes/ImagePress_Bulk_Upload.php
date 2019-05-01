<?php
class ImagePress_Bulk_Upload {
    var $post_type = 'post';
    var $post_status = 'draft';
    var $taxonomy_term = 0;

    public function __construct() {
        register_activation_hook(__FILE__, [$this, 'activate']);

        add_action('admin_menu', [$this, 'add_settings']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('add_attachment', [$this, 'create_post_from_image'], 20);
    }

    public function activate() {
        $current_afip_options = get_option('afip_options');
        $afip_options = [];

        if (empty($current_afip_options['default_post_status']))
            $afip_options['default_post_status'] = $this->post_status;
        else
            $afip_options['default_post_status'] = $current_afip_options['default_post_status'];

        if (empty($current_afip_options['default_post_type']))
            $afip_options['default_post_type'] = $this->post_type;
        else
            $afip_options['default_post_type'] = $current_afip_options['default_post_type'];

        if (empty($current_afip_options['default_taxonomy_term']))
            $afip_options['default_taxonomy_term'] = $this->taxonomy_term;
        else
            $afip_options['default_taxonomy_term'] = $current_afip_options['default_taxonomy_term'];

        update_option('afip_options', $afip_options);
    }

    public function add_settings() {
        add_submenu_page('edit.php?post_type=' . get_imagepress_option('ip_slug'), __('Bulk Upload', 'imagepress'), __('Bulk Upload', 'imagepress'), 'manage_options', 'automatic-featured-image-posts-settings', [$this, 'view_settings']);
    }

    public function view_settings() {
        ?>
        <div class="wrap">
            <h2><?php _e('ImagePress Bulk Upload', 'imagepress'); ?></h2>
            <h3><?php _e('Overview', 'imagepress'); ?></h3>
            <p><?php _e('The default <strong>post status</strong> is set to <strong>publish</strong> by default. This means that as soon as you upload a new image through any interface in WordPress, a new post will appear with that image assigned as the featured image.', 'imagepress'); ?></p>
            <p><?php _e('The default <strong>post type</strong> is set to the most familiar WordPress post type, <strong>Post</strong>. Other custom post types registered by your theme and installed plugins have been automatically detected and will also appear in the drop down menu as options. Note that these custom post types should have support for featured images, or they may not appear as you would like.', 'imagepress' ); ?></p>
            <?php if (!current_theme_supports('post-thumbnails')) : ?>
                <div class="error"><?php _e('<strong>PLEASE NOTE:</strong> Your current theme does <strong>NOT</strong> support featured images and thus this plugin will be severely limited. Images will be attached to posts after upload, but it may be impossible to see this until featured image support is added to your theme.', 'imagepress'); ?></div>
            <?php endif; ?>

            <h3><?php _e('How it works', 'imagepress'); ?></h3>
            <ol>
                <li>Set the default post type to <b><?php echo get_option('ip_slug'); ?></b>.</li>
                <li>Drag 1000 images to your <b>Media Library</b> &gt; <b>Add New</b> section.</li>
                <li><i class="fa fa-coffee" aria-hidden="true"></i> Have a coffee.</li>
                <li>Done! All 1000 images have been created as ImagePress posts.</li>
            </ol>

            <h3><?php _e('Notes', 'imagepress'); ?></h3>
            <p>Deactivate this plugin when you are done uploading images.</p>

            <form method="post" action="options.php">
                <?php
                settings_fields('afip_options');
                do_settings_sections('afip');
                ?>
                <p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes', 'imagepress'); ?>"></p>
            </form>
        </div>
        <?php
    }

    public function register_settings() {
        register_setting('afip_options', 'afip_options', [$this, 'validate_options']);

        add_settings_section('afip_section_main', '', [$this, 'output_section_text'], 'afip');

        add_settings_field('afip_default_post_status', __('Default Post Status:', 'imagepress'), [$this, 'output_default_post_status_text'], 'afip', 'afip_section_main');
        add_settings_field('afip_default_post_type', __('Default Post Type:',   'imagepress'), [$this, 'output_default_post_type_text'], 'afip', 'afip_section_main');
        add_settings_field('afip_default_taxonomy_term', __('Default Taxonomy Term (optional):', 'imagepress'), [$this, 'output_default_taxonomy_term_text'], 'afip', 'afip_section_main');
    }

    public function output_section_text() { }

    public function output_default_post_type_text() {
        $afip_options = get_option('afip_options');
        $all_post_types = get_post_types(['_builtin' => false]);

        if (!isset($afip_options['default_post_type']))
            $afip_options['default_post_type'] = $this->post_type;
        ?>
        <select id="afip-default-post-type" name="afip_options[default_post_type]">
            <option value="post" <?php selected($afip_options['default_post_type'], 'post'); ?>>Post</option>
            <?php foreach($all_post_types as $p) : ?>
                <option value="<?php echo esc_attr($p); ?>" <?php selected($afip_options['default_post_type'], esc_attr($p)); ?>><?php echo esc_html($p); ?></option>
            <?php endforeach; ?>
        </select>
        <?php
    }

    public function output_default_post_status_text() {
        $afip_options = get_option('afip_options');

        if (!isset($afip_options['default_post_status']))
            $afip_options['default_post_status'] = $this->post_status;
        ?>
        <select id="afip_default_post_status" name="afip_options[default_post_status]">
            <option value="draft" <?php selected($afip_options['default_post_status'], 'draft'); ?>>Draft</option>
            <option value="publish" <?php selected($afip_options['default_post_status'], 'publish'); ?>>Publish</option>
            <option value="private" <?php selected($afip_options['default_post_status'], 'private'); ?>>Private</option>
        </select>
        <?php
    }

    public function output_default_taxonomy_term_text() {
        $afip_options = get_option('afip_options');

        if (!isset($afip_options['default_taxonomy_term']))
            $afip_options['default_taxonomy_term'] = $this->taxonomy_term;

        wp_dropdown_categories([
            'taxonomy' => 'imagepress_image_category',
            'name' => 'afip_options[default_taxonomy_term]',
            'hide_empty' => 0,
            'show_count' => true,
            'echo' => 1,
            'orderby' => 'name',
            'show_option_all' => 'None',
            'selected' => $afip_options['default_taxonomy_term'],
            'required' => false
        ]);
    }

    public function validate_options($input) {
        global $_wp_theme_features;

        $valid_post_status_options = ['draft', 'publish', 'private'];
        $valid_post_type_options = get_post_types(['_builtin' => false]);
        $valid_post_type_options[] = $this->post_type;

        if (!in_array($input['default_post_status'], $valid_post_status_options))
            $input['default_post_status'] = $this->post_status;

        if (!in_array($input['default_post_type'], $valid_post_type_options))
            $input['default_post_type'] = $this->post_type;

        return $input;
    }

    public function create_post_from_image($post_id) {
        if (!wp_attachment_is_image($post_id))
            return;

        $new_post_category = [];

        // If an image is being uploaded through an existing post, it will have been assigned a post parent
        if ($parent_post_id = get_post($post_id)->post_parent) {
            /**
             * It doesn't make sense to create a new post with a featured image from an image
             * uploaded to an existing post. By default, we'll return having done nothing if
             * it is detected that this image already has a post parent. The filter allows a
             * plugin or theme to make a different decision here.
             */
            if (false === apply_filters('afip_post_parent_continue', false, $post_id, $parent_post_id))
                return;

            /**
             * If this image is being added through an existing post, make sure that it inherits
             * the category setting from its parent.
             */
            if ($parent_post_categories = get_the_category($parent_post_id)) {
                foreach ($parent_post_categories as $post_cat)
                    $new_post_category[] = $post_cat->cat_ID;
            }
        }

        $afip_options = get_option('afip_options');
        $current_user = wp_get_current_user();

        /* Allow other functions or themes to change the post date before creation. */
        $new_post_date = apply_filters('afip_new_post_date', current_time('mysql'), $post_id);

        /* Allow other functions or themes to change the post title before creation. */
        $new_post_title = apply_filters('afip_new_post_title', get_the_title($post_id), $post_id);

        /* Allow other functions or themes to change the post categories before creation. */
        $new_post_category = apply_filters('afip_new_post_category', $new_post_category, $post_id);

        /* Allow other functions or themes to change the post content before creation. */
        $new_post_content = apply_filters('afip_new_post_content', '', $post_id);

        // Provide a filter to bail before post creation for certain post IDs.
        if (false === apply_filters('afip_continue_new_post', true, $post_id))
            return;

        // Allow others to hook in and perform an action before a post is created.
        do_action('afip_pre_create_post', $post_id);

        $new_post_id = wp_insert_post([
            'post_title' => $new_post_title,
            'post_content' => $new_post_content,
            'post_status' => $afip_options['default_post_status'],
            'post_author' => $current_user->ID,
            'post_date' => $new_post_date,
            'post_category' => $new_post_category,
            'post_type' => $afip_options['default_post_type']
        ]);

        update_post_meta($new_post_id, '_thumbnail_id', $post_id);

        // Update the original image (attachment) to reflect new status.
        wp_update_post([
            'ID' => $post_id,
            'post_parent' => $new_post_id,
            'post_status' => 'inherit'
        ]);

        wp_set_object_terms($new_post_id, (int) $afip_options['default_taxonomy_term'], 'imagepress_image_category');

        /**
         * Allow others to hook in and perform an action as each operation is complete. Passes
         * $new_post_id from the newly created post and $post_id representing the image.
         */
        do_action('afip_created_post', $new_post_id, $post_id);
    }
}
