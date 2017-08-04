<?php
function ip_awards_taxonomy() {
    $labels = array(
		'name'                       => _x( 'Awards', 'Taxonomy General Name', 'imagepress' ),
		'singular_name'              => _x( 'Award', 'Taxonomy Singular Name', 'imagepress' ),
		'menu_name'                  => __( 'Awards', 'imagepress' ),
		'all_items'                  => __( 'All awards', 'imagepress' ),
		'parent_item'                => __( 'Parent award', 'imagepress' ),
		'parent_item_colon'          => __( 'Parent award:', 'imagepress' ),
		'new_item_name'              => __( 'New award', 'imagepress' ),
		'add_new_item'               => __( 'Add new award', 'imagepress' ),
		'edit_item'                  => __( 'Edit award', 'imagepress' ),
		'update_item'                => __( 'Update award', 'imagepress' ),
		'view_item'                  => __( 'View award', 'imagepress' ),
		'separate_items_with_commas' => __( 'Separate awards with commas', 'imagepress' ),
		'add_or_remove_items'        => __( 'Add or remove awards', 'imagepress' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'imagepress' ),
		'popular_items'              => __( 'Popular awards', 'imagepress' ),
		'search_items'               => __( 'Search awards', 'imagepress' ),
		'not_found'                  => __( 'Not found', 'imagepress' ),
		'no_terms'                   => __( 'No awards', 'imagepress' ),
		'items_list'                 => __( 'Awards list', 'imagepress' ),
		'items_list_navigation'      => __( 'Awards list navigation', 'imagepress' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => false,
		'rewrite'                    => false,
        'update_count_callback'      => 'my_update_award_count',
	);
	register_taxonomy( 'award', array( 'user' ), $args );
}


function my_update_award_count($terms, $taxonomy) {
	global $wpdb;

	foreach((array)$terms as $term) {
		$count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->term_relationships WHERE term_taxonomy_id = %d", $term));

		do_action('edit_term_taxonomy', $term, $taxonomy);
		$wpdb->update($wpdb->term_taxonomy, compact('count'), array('term_taxonomy_id' => $term));
		do_action('edited_term_taxonomy', $term, $taxonomy);
	}
}

add_filter('parent_file', 'fix_user_tax_page');
function fix_user_tax_page($parent_file = '') {
    global $pagenow;
	if(!empty($_GET['taxonomy']) && $_GET['taxonomy'] == 'award' && $pagenow == 'edit-tags.php')
        $parent_file = 'users.php';
    return $parent_file;
}

add_action('admin_menu', 'my_add_award_admin_page');
function my_add_award_admin_page() {
    $tax = get_taxonomy('award');
    add_users_page(esc_attr($tax->labels->menu_name), esc_attr($tax->labels->menu_name), $tax->cap->manage_terms, 'edit-tags.php?taxonomy=' . $tax->name);
}

add_filter('manage_edit-award_columns', 'my_manage_award_user_column');
function my_manage_award_user_column($columns) {
    unset($columns['posts']);
	$columns['users'] = __('Users', 'imagepress');
	return $columns;
}

add_action('manage_award_custom_column', 'my_manage_award_column', 10, 3);
function my_manage_award_column($display, $column, $term_id) {
    if('users' === $column) {
		$term = get_term($term_id, 'award');
		echo $term->count;
	}
}

add_action('show_user_profile', 'my_edit_user_award_section');
add_action('edit_user_profile', 'my_edit_user_award_section');

function my_edit_user_award_section($user) {
	$tax = get_taxonomy('award');
    if(!current_user_can($tax->cap->assign_terms))
		return;

	$terms = get_terms('award', array('hide_empty' => false));

	if(is_admin()) { ?>
		<h3><?php _e('ImagePress Details', 'imagepress'); ?></h3>
		<table class="form-table">
			<tr>
				<th><label for="user_title"><?php _e('Status', 'imagepress'); ?></label></th>
				<td>
					<select name="user_title" id="user_title">
						<option selected><?php echo esc_attr(get_the_author_meta('user_title', $user->ID)); ?></option>
						<option>Verified</option>
						<option>Regular</option>
					</select>
					<span class="description"><?php _e('Select user verification status', 'imagepress'); ?></span>
				</td>
			</tr>
			<tr>
				<th><label for="ip_upload_limit"><?php _e('User upload limit', 'imagepress'); ?></label></th>
				<td>
					<input type="number" min="0" max="999999" step="1" name="ip_upload_limit" id="ip_upload_limit" value="<?php echo esc_attr(get_the_author_meta('ip_upload_limit', $user->ID)); ?>">
					<span class="description"><?php _e('Set user upload limit', 'imagepress'); ?></span>
				</td>
			</tr>
			<tr>
				<th><label for="award"><?php _e('Select award(s)', 'imagepress'); ?></label></th>
				<td><?php
				if(!empty($terms)) {
					foreach($terms as $term) { ?>
						<input type="checkbox" name="award[]" id="award-<?php echo esc_attr($term->slug); ?>" value="<?php echo esc_attr($term->slug); ?>" <?php checked(true, is_object_in_term($user->ID, 'award', $term)); ?>> <label for="award-<?php echo esc_attr($term->slug); ?>"><?php echo $term->name; ?></label><br>
					<?php }
				}
				else {
					_e('There are no awards available.', 'imagepress');
				}
				?></td>
			</tr>
		</table>
	<?php
	}
}

function my_award_count_text($count) {
	return sprintf(_n('%s user', '%s users', $count), number_format_i18n($count));
}
add_action('init', 'ip_awards_taxonomy', 0);

function extra_edit_tax_fields($tag) {
    $t_id = $tag->term_id;
    $term_meta = get_option("taxonomy_$t_id"); ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="cat_Image_url"><?php _e('Award Icon', 'imagepress'); ?></label></th>
        <td>
            <input type="text" name="term_meta[img]" id="term_meta[img]" value="<?php echo esc_attr($term_meta['img']) ? esc_attr($term_meta['img']) : ''; ?>">
            <p class="description"><?php _e('Enter the FontAwesome icon name (e.g. fa-trophy)', 'imagepress'); ?></p>
        </td>
    </tr>
<?php
}
add_action('award_edit_form_fields', 'extra_edit_tax_fields', 10, 2);

function extra_add_tax_fields($tag) {
    $t_id = $tag->term_id;
    $term_meta = get_option("taxonomy_$t_id"); ?>
    <div class="form-field">
        <label for="cat_Image_url"><?php _e('Award Icon', 'imagepress'); ?></label>
        <input type="text" name="term_meta[img]" id="term_meta[img]" value="<?php echo esc_attr($term_meta['img']) ? esc_attr($term_meta['img']) : ''; ?>">
        <p class="description"><?php _e('Enter the FontAwesome icon name (e.g. fa-trophy)', 'imagepress'); ?></p>
    </div>
<?php
}
add_action('award_add_form_fields', 'extra_add_tax_fields', 10, 2);

function save_extra_taxonomy_fields($term_id) {
    if(isset($_POST['term_meta'])) {
        $t_id = $term_id;
        $term_meta = get_option("taxonomy_$t_id");
        $cat_keys = array_keys($_POST['term_meta']);
        foreach($cat_keys as $key) {
            if(isset($_POST['term_meta'][$key])) {
                $term_meta[$key] = $_POST['term_meta'][$key];
            }
        }
        update_option("taxonomy_$t_id", $term_meta);
    }
}
add_action('edited_award', 'save_extra_taxonomy_fields', 10, 2);
add_action('create_award', 'save_extra_taxonomy_fields', 10, 2);
?>
