<?php
function imagepress_admin_page() {
    ?>
    <div class="wrap">
        <h1>ImagePress Settings</h1>

        <?php
        $ipSlug = get_imagepress_option('ip_slug');

        $tab = (filter_has_var(INPUT_GET, 'tab')) ? filter_input(INPUT_GET, 'tab') : 'dashboard_tab';
        $section = 'edit.php?post_type=' . $ipSlug . '&page=imagepress_admin_page&amp;tab=';
        ?>
        <h2 class="nav-tab-wrapper ip-nav-tab-wrapper">
            <a href="<?php echo $section; ?>dashboard_tab" class="nav-tab <?php echo $tab === 'dashboard_tab' ? 'nav-tab-active' : ''; ?>"><?php _e('Dashboard', 'imagepress'); ?></a>
            <a href="<?php echo $section; ?>install_tab" class="nav-tab <?php echo $tab === 'install_tab' ? 'nav-tab-active' : ''; ?>"><?php _e('Installation', 'imagepress'); ?></a>
            <a href="<?php echo $section; ?>settings_tab" class="nav-tab <?php echo $tab === 'settings_tab' ? 'nav-tab-active' : ''; ?>"><?php _e('Settings', 'imagepress'); ?></a>
            <a href="<?php echo $section; ?>configurator_tab" class="nav-tab <?php echo $tab === 'configurator_tab' ? 'nav-tab-active' : ''; ?>"><?php _e('Configurator', 'imagepress'); ?></a>

            <a href="<?php echo $section; ?>collections_tab" class="nav-tab <?php echo $tab === 'collections_tab' ? 'nav-tab-active' : ''; ?>"><?php _e('Collections', 'imagepress'); ?></a>

            <a href="<?php echo $section; ?>label_tab" class="nav-tab <?php echo $tab === 'label_tab' ? 'nav-tab-active' : ''; ?>"><?php _e('Labels', 'imagepress'); ?></a>
            <a href="<?php echo $section; ?>upload_tab" class="nav-tab <?php echo $tab === 'upload_tab' ? 'nav-tab-active' : ''; ?>"><?php _e('Upload', 'imagepress'); ?></a>
            <a href="<?php echo $section; ?>authors_tab" class="nav-tab <?php echo $tab === 'authors_tab' ? 'nav-tab-active' : ''; ?>"><?php _e('Authors', 'imagepress'); ?></a>

            <a href="<?php echo $section; ?>fields_tab" class="nav-tab <?php echo $tab === 'fields_tab' ? 'nav-tab-active' : ''; ?>"><?php _e('Fields', 'imagepress'); ?></a>
            <a href="<?php echo $section; ?>notifications_tab" class="nav-tab <?php echo $tab === 'notifications_tab' ? 'nav-tab-active' : ''; ?>"><?php _e('Notifications', 'imagepress'); ?></a>
        </h2>

        <?php if ($tab === 'dashboard_tab') {
            global $wpdb;

            // Get the WP built-in version
            $ipdata = get_plugin_data(IP_PLUGIN_FILE_PATH);

            echo '<div id="gb-ad">
                <h3 class="gb-handle">Thank you for using ImagePress!</h3>
                <div id="gb-ad-content">
                    <div class="inside">
                        <p>If you enjoy this plugin, do not forget to <a href="https://codecanyon.net/item/imagepress/4252736" rel="external">rate it on CodeCanyon</a>! We work hard to update it, fix bugs, add new features and make it compatible with the latest web technologies.</p>
                    </div>
                    <div class="gb-footer">
                        <p>For support, feature requests and bug reporting, please visit the <a href="https://getbutterfly.com/" rel="external">official website</a>.<br>Built by <a href="https://getbutterfly.com/" rel="external"><strong>getButterfly</strong>.com</a> &middot; <a href="https://getbutterfly.com/support/documentation/imagepress/">Documentation</a> &middot; <small>Code wrangling since 2005</small></p>
                    </div>
                </div>
            </div>

            <p>
                <small>You are using ImagePress plugin version <strong>' . (string) trim($ipdata['Version']) . '</strong>.</small><br>
                <small>You are using PHP version ' . (string) trim(PHP_VERSION) . ' and MySQL server version ' . $wpdb->db_version() . '.</small>
            </p>

            <h3>Shortcodes</h3>
            <p>
                <code>[imagepress-add]</code> - show the submission form.<br>
                <code>[imagepress-add category="landscapes"]</code> - show the submission form with a fixed (hidden) category. Use the category <b>slug</b>.<br>
                <br>
                <code>[imagepress-search]</code> - show the search form.<br>
                <br>
                <code>[imagepress-loop]</code> - display all images.<br>
                <code>[imagepress-loop user="7"]</code> - filter images by user ID.<br>
                <code>[imagepress-loop count="4"]</code> - display a specific number of images.<br>
                <code>[imagepress-loop filters="yes"]</code> - display all images with filters/sorters.<br>
                <code>[imagepress-loop sort="likes" count="10"]</code> - display images sorted by likes.<br>
                <code>[imagepress-loop category="landscapes"]</code> - display all images in a specific category. Use the category <b>slug</b>.<br>
                <code>[imagepress-loop fieldname="album" fieldvalue="red"]</code> - display all images with a specific custom field value.<br>
                <br>
                <code>[imagepress mode="views" count="10"]</code> - display most viewed images (list).<br>
                <code>[imagepress mode="views" type="top" count="1"]</code> - display the most viewed image.<br>
                <code>[imagepress mode="likes" count="10"]</code> - display most liked/voted images (list).<br>
                <code>[imagepress mode="likes" type="top" count="1"]</code> - display the most voted image.<br>
                <br>
                <code>[notifications]</code> - display the notifications.<br>
                <br>
                <code>[imagepress-collections count="X"]</code> - display X collections.<br>
                <br>
                <code>[cinnamon-profile]</code> - show user profile on a custom page, such as <b>My Profile</b> or <b>View My Portfolio</b>.<br>
                <code>[cinnamon-profile author="17"]</code> - show a certain user profile on a page, where <b>17</b> is the user ID.<br>
            </p>';
        } else if ($tab === 'install_tab') { ?>
            <h2><?php esc_html_e('Installation', 'imagepress'); ?></h2>
            <p>Check the installation steps below and make the required changes.</p>
            <?php
            $author_slug = get_imagepress_option('cinnamon_author_slug');
            $author_login_url = get_imagepress_option('cinnamon_account_page');
            $author_edit_url = get_imagepress_option('cinnamon_edit_page');
            $ip_profile_page = get_imagepress_option('ip_profile_page');

            $single_template = 'single-' . $ipSlug . '.php';

            echo '<div class="gb-assistant">';
                if ('' != locate_template($single_template)) {
                    echo '<p><div class="dashicons dashicons-yes"></div> <b>Note:</b> You have a custom image template available.</p>';
                }

                if (empty($author_slug)) {
                    echo '<p><div class="dashicons dashicons-no"></div> <b>Error:</b> Your author slug is not set. Go to <b>Authors</b> section and set it.</p>';
                } else {
                    echo '<p><div class="dashicons dashicons-yes"></div> <b>Note:</b> Your author slug is <code>' . sanitize_text_field($author_slug) . '</code>. If you changed it recently, visit your <b>Permalinks</b> section and resave the changes.</p>';
                }
                if ((int) $ip_profile_page === 0) {
                    echo '<p><div class="dashicons dashicons-no"></div> <b>Error:</b> Your profile page is not set. Go to <b>Authors</b> section and set it.</p>';
                } else {
                    echo '<p><div class="dashicons dashicons-yes"></div> <b>Note:</b> Your profile page is <b>' . get_the_title($ip_profile_page) . '</b>. If you changed it recently, visit your <b>Permalinks</b> section and resave the changes.</p>';
                }
                if (empty($author_edit_url)) {
                    echo '<p><div class="dashicons dashicons-no"></div> <b>Error:</b> Your author profile edit URL is not set. Go to <b>Authors</b> section and set it.</p>';
                } else {
                    echo '<p><div class="dashicons dashicons-yes"></div> <b>Note:</b> Your author profile edit URL is <code>' . esc_url(get_permalink($author_edit_url)) . '</code>.</p>';
                }
                if ((string) get_option('default_role') === 'author') {
                    echo '<p><div class="dashicons dashicons-yes"></div> <b>Note:</b> New user default role is <code>author</code>. Subscribers and contributors are not able to edit their uploaded images.</p>';
                } else {
                    echo '<p><div class="dashicons dashicons-no"></div> <b>Error:</b> New user default role should be <code>author</code> in order to allow for front-end image editing. Subscribers and contributors are not able to edit their uploaded images. <a href="' . esc_url(admin_url('options-general.php')) . '">Change it</a>.</p>';
                }
            echo '</div>';

            if (isset($_POST['isResetLikesSubmit'])) {
                global $wpdb;

                $wpdb->query("UPDATE " . $wpdb->prefix . "postmeta SET meta_value = '0' WHERE meta_key = '_like_count'");
                echo '<div class="updated notice is-dismissible"><p>Action completed successfully!</p></div>';
            }
            if (isset($_POST['isDeleteLikesSubmit'])) {
                global $wpdb;

                $wpdb->query("DELETE FROM " . $wpdb->prefix . "postmeta WHERE meta_key = '_like_count'");
                echo '<div class="updated notice is-dismissible"><p>Action completed successfully!</p></div>';
            }
            if (isset($_POST['isResetViewsSubmit'])) {
                global $wpdb;

                $wpdb->query("DELETE FROM " . $wpdb->prefix . "postmeta WHERE meta_key = 'post_views_count'");
                echo '<div class="updated notice is-dismissible"><p>Action completed successfully!</p></div>';
            }
            ?>

            <h3><?php esc_html_e('Maintenance', 'imagepress'); ?></h3>
            <form method="post" action="">
                <p>
                    <input type="submit" name="isResetLikesSubmit" value="Reset all likes" class="button-primary">
                    <br><small>This option resets all image likes to 0. This action is irreversible.</small>
                </p>
                <p>
                    <input type="submit" name="isDeleteLikesSubmit" value="Delete all likes" class="button-primary">
                    <br><small>This option deletes all image likes. This action is irreversible.</small>
                </p>
                <p>
                    <input type="submit" name="isResetViewsSubmit" value="Delete all views" class="button-primary">
                    <br><small>This option deletes all image views. This action is irreversible.</small>
                </p>
            </form>
        <?php } else if ($tab === 'configurator_tab') {
            if (isset($_POST['isGSSubmit'])) {
                $ipUpdatedOptions = [
                    'ip_box_ui' => $_POST['ip_box_ui'],
                    'ip_ipp' => $_POST['ip_ipp'],
                    'ip_app' => $_POST['ip_app'],
                    'ip_order' => $_POST['ip_order'],
                    'ip_orderby' => $_POST['ip_orderby'],
                    'ip_slug' => sanitize_title($_POST['ip_slug']),
                    'ip_image_size' => $_POST['ip_image_size'],
                    'ip_title_optional' => $_POST['ip_title_optional'],
                    'ip_meta_optional' => $_POST['ip_meta_optional'],
                    'ip_views_optional' => $_POST['ip_views_optional'],
                    'ip_comments' => $_POST['ip_comments'],
                    'ip_likes_optional' => $_POST['ip_likes_optional'],
                    'ip_author_optional' => $_POST['ip_author_optional']
                ];
                $ipOptions = get_option('imagepress');
                $ipUpdate = array_merge($ipOptions, $ipUpdatedOptions);
                update_option('imagepress', $ipUpdate);

                echo '<div class="updated notice is-dismissible"><p>Settings updated successfully!</p></div>';
            }
            ?>
            <form method="post" action="">
                <h2><?php esc_html_e('Grid Configurator', 'imagepress'); ?></h2>
                <p>The <b>Grid configurator</b> allows you to select which information will be visible inside the image box.</p>
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><label>Image box appearance</label></th>
                            <td>
                                <select name="ip_box_ui" id="ip_box_ui">
                                    <option value="default"<?php if ((string) get_imagepress_option('ip_box_ui') === 'default') echo ' selected'; ?>>Default</option>
                                    <option value="overlay"<?php if ((string) get_imagepress_option('ip_box_ui') === 'overlay') echo ' selected'; ?>>Overlay</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label>Image box details</label></th>
                            <td>
                            <p>
                                <input name="ip_slug" id="slug" type="text" class="regular-text" placeholder="Image slug" value="<?php echo (string) get_imagepress_option('ip_slug'); ?>" required> <label for="ip_slug"><b>Image</b> slug</label>
                                <br><small>Use an appropriate slug for your image (e.g. <b>image</b> in <code>domain.com/<b>image</b>/myimage</code>).</small>
                                <br><small>Tip: use a singular term, one word only, lowercase, letters only (examples: image, poster, illustration).</small>
                            </p>
                            <p>
                                <select name="ip_image_size" id="ip_image_size">
                                    <optgroup label="WordPress (default)">
                                        <option value="thumbnail"<?php if ((string) get_imagepress_option('ip_image_size') === 'thumbnail') echo ' selected'; ?>>Thumbnail</option>
                                        <option value="medium"<?php if ((string) get_imagepress_option('ip_image_size') === 'medium') echo ' selected'; ?>>Medium</option>
                                    </optgroup>
                                    <optgroup label="ImagePress (default)">
                                        <option value="imagepress_sq_std"<?php if ((string) get_imagepress_option('ip_image_size') === 'imagepress_sq_std') echo ' selected'; ?>>Standard (Square)</option>
                                        <option value="imagepress_pt_std"<?php if ((string) get_imagepress_option('ip_image_size') === 'imagepress_pt_std') echo ' selected'; ?>>Standard (Portrait)</option>
                                        <option value="imagepress_ls_std"<?php if ((string) get_imagepress_option('ip_image_size') === 'imagepress_ls_std') echo ' selected'; ?>>Standard (Landscape)</option>
                                    </optgroup>
                                    <optgroup label="Other registered sizes (use with care)">
                                        <?php
                                        $ip_image_size = get_imagepress_option('ip_image_size');
                                        $thumbsize = isset($ip_image_size) ? esc_attr($ip_image_size) : '';
                                        $image_sizes = ip_return_image_sizes();
                                        foreach ($image_sizes as $size => $atts) {
                                            if ((int) $atts[0] !== 0 && (int) $atts[1] !== 0) {
                                                ?>
                                                <option value="<?php echo (string) $size ;?>" <?php selected($thumbsize, $size); ?>><?php echo (string) $size . ' - ' . implode('x', $atts); ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </optgroup>
                                </select> <label for="ip_image_size"><b>Image box</b> thumbnail size</label>
                                <br><small>Use <b>thumbnail</b>, adjust the column size to match your thumbnail size and hide the description in order to have uniform sizes</small>
                            </p>
                            <p>
                                <select name="ip_title_optional" id="ip_title_optional">
                                    <option value="0"<?php if(get_imagepress_option('ip_title_optional') == 0) echo ' selected'; ?>>Hide image title</option>
                                    <option value="1"<?php if(get_imagepress_option('ip_title_optional') == 1) echo ' selected'; ?>>Show image title</option>
                                </select>
                                <label for="ip_title_optional">Show/hide image title</label>
                            </p>
                            <p>
                                <select name="ip_meta_optional" id="ip_meta_optional">
                                    <option value="0"<?php if(get_imagepress_option('ip_meta_optional') == 0) echo ' selected'; ?>>Hide image meta</option>
                                    <option value="1"<?php if(get_imagepress_option('ip_meta_optional') == 1) echo ' selected'; ?>>Show image meta</option>
                                </select>
                                <label for="ip_meta_optional">Show/hide the image meta (category/taxonomy)</label>
                            </p>
                            <p>
                                <select name="ip_views_optional" id="ip_views_optional">
                                    <option value="0"<?php if(get_imagepress_option('ip_views_optional') == 0) echo ' selected'; ?>>Hide image views</option>
                                    <option value="1"<?php if(get_imagepress_option('ip_views_optional') == 1) echo ' selected'; ?>>Show image views</option>
                                </select>
                                <label for="ip_views_optional">Show/hide the number of image views</label>
                            </p>
                            <p>
                                <select name="ip_likes_optional" id="ip_likes_optional">
                                    <option value="0"<?php if(get_imagepress_option('ip_likes_optional') == 0) echo ' selected'; ?>>Hide image likes</option>
                                    <option value="1"<?php if(get_imagepress_option('ip_likes_optional') == 1) echo ' selected'; ?>>Show image likes</option>
                                </select>
                                <label for="ip_likes_optional">Show/hide the number of image likes</label>
                            </p>
                            <p>
                                <select name="ip_comments" id="ip_comments">
                                    <option value="0"<?php if(get_imagepress_option('ip_comments') == '0') echo ' selected'; ?>>Hide image comments</option>
                                    <option value="1"<?php if(get_imagepress_option('ip_comments') == '1') echo ' selected'; ?>>Show image comments</option>
                                </select>
                                <label for="ip_comments">Show/hide the number of image comments</label>
                            </p>
                            <p>
                                <select name="ip_author_optional" id="ip_author_optional">
                                    <option value="0"<?php if(get_imagepress_option('ip_author_optional') == 0) echo ' selected'; ?>>Hide image author</option>
                                    <option value="1"<?php if(get_imagepress_option('ip_author_optional') == 1) echo ' selected'; ?>>Show image author</option>
                                </select>
                                <label for="ip_author_optional">Show/hide the author name and link</label>
                            </p>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <hr>
                <h2>Grid Settings</h2>
                <p>These settings apply globally for the image and author grid.</p>
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><label>Image grid details</label></th>
                            <td>
                                <input name="ip_ipp" id="ip_ipp" type="number" value="<?php echo (int) get_imagepress_option('ip_ipp'); ?>" min="1" max="65536">
                                <label for="ip_ipp">Images per page (0-256)</label>
                                <br><small>How many images per page you want to display using the <code>[imagepress-loop]</code> shortcode.</small>

                                <p>
                                    <label for="ip_order">Sort images</label>
                                    <select name="ip_order" id="ip_order">
                                        <option value="ASC"<?php if (get_imagepress_option('ip_order') == 'ASC') echo ' selected'; ?>>ASC</option>
                                        <option value="DESC"<?php if (get_imagepress_option('ip_order') == 'DESC') echo ' selected'; ?>>DESC</option>
                                    </select> <label for="ip_orderby">by</label> <select name="ip_orderby" id="ip_orderby">
                                        <option value="none"<?php if (get_imagepress_option('ip_orderby') == 'none') echo ' selected'; ?>>none</option>
                                        <option value="ID"<?php if (get_imagepress_option('ip_orderby') == 'ID') echo ' selected'; ?>>ID</option>
                                        <option value="author"<?php if (get_imagepress_option('ip_orderby') == 'author') echo ' selected'; ?>>author</option>
                                        <option value="title"<?php if (get_imagepress_option('ip_orderby') == 'title') echo ' selected'; ?>>title</option>
                                        <option value="name"<?php if (get_imagepress_option('ip_orderby') == 'name') echo ' selected'; ?>>name</option>
                                        <option value="date"<?php if (get_imagepress_option('ip_orderby') == 'date') echo ' selected'; ?>>date</option>
                                        <option value="rand"<?php if (get_imagepress_option('ip_orderby') == 'rand') echo ' selected'; ?>>rand</option>
                                        <option value="comment_count"<?php if (get_imagepress_option('ip_orderby') == 'comment_count') echo ' selected'; ?>>comment_count</option>
                                    </select>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label>Author grid details</label></th>
                            <td>
                                <p>
                                    <input type="number" name="ip_app" id="ip_app" min="1" max="9999" value="<?php echo (int) get_imagepress_option('ip_app'); ?>">
                                    <label for="ip_app">Authors per page</label>
                                    <br><small>How many authors per page you want to display using the <code>[cinnamon-card]</code> shortcode.</small>
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <hr>
                <p><input type="submit" name="isGSSubmit" value="Save Changes" class="button-primary"></p>
            </form>
        <?php } else if ($tab === 'collections_tab') {
            global $wpdb;

            $orphan_count = $wpdb->get_var("SELECT COUNT(*) FROM `" . $wpdb->prefix . "ip_collectionmeta` WHERE `image_ID` NOT IN (SELECT `ID` FROM `" . $wpdb->posts . "`)");

            if (isset($_POST['isGSSubmit'])) {
                $ipUpdatedOptions = [
                    'ip_collections_page' => $_POST['ip_collections_page']
                ];
                $ipOptions = get_option('imagepress');
                $ipUpdate = array_merge($ipOptions, $ipUpdatedOptions);
                update_option('imagepress', $ipUpdate);

                echo '<div class="updated notice is-dismissible"><p>Settings updated successfully!</p></div>';
            } else if (isset($_POST['isCollectionCU'])) {
                $wpdb->query("DELETE FROM `" . $wpdb->prefix . "ip_collectionmeta` WHERE `image_ID` NOT IN (SELECT `ID` FROM `" . $wpdb->posts . "`)");

                echo '<div class="updated notice is-dismissible"><p>Collection images cleaned up successfully!</p></div>';
            }
            ?>
            <form method="post" action="">
                <h2><?php _e('Collections', 'imagepress'); ?></h2>
                <p><?php _e('Use the shortcode tag <code>[imagepress-collections count="X"]</code> in any post or page to display X collections. Note that in order for a collection to be visible, it needs to contain at least one image.', 'imagepress'); ?></p>
                <p><?php _e('<b>Note:</b> In order to view collection images, you need to create a viewer page, which should contain the collections shortcode: <code>[imagepress-collection collection="1"]</code>.', 'imagepress'); ?></p>

                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><label for="ip_collections_page"><?php _e('Collection viewer page', 'imagepress'); ?></label></th>
                            <td>
                                <?php
                                wp_dropdown_pages([
                                    'name' => 'ip_collections_page',
                                    'echo' => 1,
                                    'show_option_none' => __('Select collection viewer page...', 'imagepress'),
                                    'option_none_value' => '0',
                                    'selected' => (int) get_imagepress_option('ip_collections_page')
                                ]);
                                ?>
                                <input type="submit" name="isGSSubmit" value="<?php _e('Save Changes', 'imagepress'); ?>" class="button button-primary">
                            </td>
                        </tr>
                    </tbody>
                </table>

                <hr>
                <h2><?php _e('Manage Collections', 'imagepress'); ?></h2>
                <p><?php _e('Manage existing collections, see how many images they contain, see visibility status and delete selected ones.', 'imagepress'); ?></p>

                <?php
                if (isset($_GET['c'])) {
                    $collection_ID = (int) $_GET['c'];

                    $wpdb->query($wpdb->prepare("DELETE FROM " . $wpdb->prefix . "ip_collections WHERE collection_ID = %d", $collection_ID));
                    $wpdb->query($wpdb->prepare("DELETE FROM " . $wpdb->prefix . "ip_collectionmeta WHERE image_collection_ID = %d", $collection_ID));

                    echo '<div class="updated notice is-dismissible"><p>Collection removed successfully!</p></div>';
                }
                if (isset($_POST['ip_new_collection_add'])) {
                    $collectionAuthorId = get_current_user_id();
                    $collectionTitle = stripslashes($_POST['ip_new_collection']);
                    $collectionTitleSlug = sanitize_title($_POST['ip_new_collection']);
                    $collectionStatus = intval($_POST['ip_new_collection_visibility']);

                    $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "ip_collections (collection_title, collection_title_slug, collection_status, collection_author_ID) VALUES ('%s', '%s', %d, %d)", $collectionTitle, $collectionTitleSlug, $collectionStatus, $collectionAuthorId));

                    echo '<div class="updated notice is-dismissible"><p>Collection added successfully!</p></div>';
                }

                $ipCollectionsPageId = get_imagepress_option('ip_collections_page');

                $result = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_collections", ARRAY_A);

                echo '<table class="wp-list-table widefat striped posts">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Collection Name</th>
                            <th scope="col">Author</th>
                            <th scope="col">Images</th>
                            <th scope="col">Visibility</th>
                            <th scope="col"><div class="dashicons dashicons-admin-generic"></div></th>
                        </tr>
                    </thead>';
                    foreach ($result as $collection) {
                        echo '<tr>';
                            $postslistcount = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_collectionmeta WHERE image_collection_ID = '" . $collection['collection_ID'] . "' AND image_collection_author_ID = '" . get_current_user_id() . "'", ARRAY_A);
                            $collectionUser = get_user_by('id', $collection['collection_author_ID']);

                            echo '<td>' . $collection['collection_ID'] . '</td>';
                            echo '<td><b><a href="' . get_permalink($ipCollectionsPageId) . '?collection=' . (int) $collection['collection_ID'] . '">' . $collection['collection_title'] . '</a></b></td>';
                            echo '<td><a href="' . admin_url('user-edit.php?user_id=' . $collectionUser->ID) . '">' . $collectionUser->user_nicename . '</a></td>';
                            echo '<td>' . ((count($postslistcount) === 1) ? count($postslistcount) . ' image' : count($postslistcount) . ' images') . '</td>';
                            echo '<td>' . (($collection['collection_status'] == 0) ? 'private' : 'public') . '</td>';
                            echo '<td><a href="' . admin_url('edit.php?post_type=' . $ipSlug . '&page=imagepress_admin_page&tab=collections_tab&c=' . $collection['collection_ID']) . '"><span class="dashicons dashicons-trash"></span></a></td>';
                        echo '</tr>';
                    }
                echo '</table>';
                ?>
            </form>

            <hr>
            <h2><?php _e('Add New Collection', 'imagepress'); ?></h2>
            <p><?php _e('Add new collections here. Use short and concise names.', 'imagepress'); ?></p>

            <form method="post">
                <p>
                    <input type="text" name="ip_new_collection" id="ip_new_collection" class="regular-text" placeholder="<?php _e('New collection', 'imagepress'); ?>">
                    <label for="ip_new_collection"><?php _e('New collection name', 'imagepress'); ?></label>
                </p>
                <p>
                    <select name="ip_new_collection_visibility" id="ip_new_collection_visibility">
                        <option value="1"><?php _e('Public', 'imagepress'); ?></option>
                        <option value="0"><?php _e('Private', 'imagepress'); ?></option>
                    </select>
                    <input type="submit" name="ip_new_collection_add" class="button button-secondary" value="<?php _e('Add new collection', 'imagepress'); ?>">
                </p>

                <hr>
                <h2><?php _e('Maintenance', 'imagepress'); ?></h2>
                <p><?php _e('Whenever users permanently delete an image, the collection reference is not updated. Use the button below to remove all references to missing images from the collections table.', 'imagepress'); ?></p>
                <p>
                    <input type="submit" name="isCollectionCU" value="Remove <?php echo $orphan_count; ?> missing image references" class="button button-secondary">
                </p>
            </form>
        <?php } else if ($tab === 'settings_tab') {
            if (isset($_POST['isGSSubmit'])) {
                $ipUpdatedOptions = [
                    'ip_moderate' => $_POST['ip_moderate'],
                    'ip_registration' => $_POST['ip_registration'],
                    'ip_click_behaviour' => $_POST['ip_click_behaviour'],
                    'ip_cat_moderation_include' => $_POST['ip_cat_moderation_include'],
                    'ip_upload_redirection' => $_POST['ip_upload_redirection'],
                    'ip_notification_email' => $_POST['ip_notification_email'],
                    'ip_enable_views' => $_POST['ip_enable_views']
                ];
                $ipOptions = get_option('imagepress');
                $ipUpdate = array_merge($ipOptions, $ipUpdatedOptions);
                update_option('imagepress', $ipUpdate);

                echo '<div class="updated notice is-dismissible"><p>Settings updated successfully!</p></div>';
            }
            ?>
            <form method="post" action="">
                <h2>General Settings</h2>
                <p>These settings apply globally for all ImagePress users.</p>
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><label for="ip_enable_views">Image views</label></th>
                            <td>
                                <select name="ip_enable_views" id="ip_enable_views">
                                    <option value="0"<?php if ((int) get_imagepress_option('ip_enable_views') === 0) echo ' selected'; ?>>Disable image views</option>
                                    <option value="1"<?php if ((int) get_imagepress_option('ip_enable_views') === 1) echo ' selected'; ?>>Enable image views</option>
                                </select>
                                <br><small>Note that disabling image views will render some sorting functions and/or widgets unusable.</small>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="ip_registration">User registration</label></th>
                            <td>
                                <select name="ip_registration" id="ip_registration">
                                    <option value="0"<?php if ((int) get_imagepress_option('ip_registration') === 0) echo ' selected'; ?>>Require user registration (recommended)</option>
                                    <option value="1"<?php if ((int) get_imagepress_option('ip_registration') === 1) echo ' selected'; ?>>Do not require user registration</option>
                                </select>
                                <br><small>Require users to be registered and logged in to upload images (recommended).</small>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="ip_click_behaviour">Image behaviour</label></th>
                            <td>
                                <select name="ip_click_behaviour" id="ip_click_behaviour">
                                    <option value="media"<?php if ((string) get_imagepress_option('ip_click_behaviour') === 'media') echo ' selected'; ?>>Open media (image)</option>
                                    <option value="custom"<?php if ((string) get_imagepress_option('ip_click_behaviour') === 'custom') echo ' selected'; ?>>Open image page</option>
                                </select>
                                <br><small>What to open when clicking on an image (single image or custom post template).</small>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="ip_moderate">Image moderation</label></th>
                            <td>
                                <select name="ip_moderate" id="ip_moderate">
                                    <option value="0"<?php if ((int) get_imagepress_option('ip_moderate') === 0) echo ' selected'; ?>>Moderate all images (recommended)</option>
                                    <option value="1"<?php if ((int) get_imagepress_option('ip_moderate') === 1) echo ' selected'; ?>>Do not moderate images</option>
                                </select>
                                <br><small>Moderate all submitted images (recommended).</small>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="ip_cat_moderation_include">Moderate entries in this category</label></th>
                            <td>
                                <input type="number" name="ip_cat_moderation_include" id="ip_cat_moderation_include" value="<?php echo get_imagepress_option('ip_cat_moderation_include'); ?>">
                                <br><small>Always moderate entries in this category (use category ID).</small>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <hr>
                <h2>Redirection</h2>
                <p>Optionally redirect users to various pages after image submission/removal. Examples are: a thank you page, a confirmation page, a payment page, a newsletter page or another call to action.</p>
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><label for="ip_upload_redirection">Upload redirect</label></th>
                            <td>
                                <input type="url" name="ip_upload_redirection" id="ip_upload_redirection" placeholder="https://" class="regular-text" value="<?php echo get_imagepress_option('ip_upload_redirection'); ?>">
                                <br><small>Redirect users to this page after image upload (optional, leave blank to disable).</small>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <hr>
                <h2>Email Settings</h2>
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><label for="ip_notification_email">Administrator email<br><small>(used for new image notification)</small></label></th>
                            <td>
                                <input type="text" name="ip_notification_email" id="ip_notification_email" value="<?php echo get_imagepress_option('ip_notification_email'); ?>" class="regular-text">
                                <br><small>The administrator will receive an email notification each time a new image is uploaded.</small>
                                <br><small>Separate multiple addresses with comma.</small>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <hr>
                <p><input type="submit" name="isGSSubmit" value="Save Changes" class="button-primary"></p>
            </form>
        <?php } else if ($tab === 'authors_tab') {
            if (isset($_POST['cinnamon_submit'])) {
                $ipUpdatedOptions = [
                    'ip_profile_page' => (int) sanitize_text_field($_POST['ip_profile_page']),
                    'cinnamon_author_slug' => $_POST['cinnamon_author_slug'],
                    'ip_cards_per_author' => $_POST['ip_cards_per_author'],
                    'ip_et_login' => $_POST['ip_et_login'],
                    'cinnamon_hide_admin' => $_POST['cinnamon_hide_admin'],
                    'cinnamon_account_page' => $_POST['cinnamon_account_page'],
                    'cinnamon_edit_page' => (int) sanitize_text_field($_POST['cinnamon_edit_page']),
                    'cinnamon_fancy_header' => $_POST['cinnamon_fancy_header'],
                    'approvednotification' => $_POST['approvednotification'],
                    'declinednotification' => $_POST['declinednotification'],
                ];
                $ipOptions = get_option('imagepress');
                $ipUpdate = array_merge($ipOptions, $ipUpdatedOptions);
                update_option('imagepress', $ipUpdate);

                echo '<div class="updated notice is-dismissible"><p><strong>Settings saved.</strong></p></div>';
            }
            ?>
            <form method="post" action="">
                <h2>General Settings</h2>
                <p>These settings apply globally for all ImagePress users.</p>

                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row">
                                <label for="ip_profile_page">
                                    <?php _e('Profile page and author slug', 'imagepress'); ?>
                                    <br><small>(<?php _e('required for single user profiles', 'imagepress'); ?>)</small>
                                </label>
                            </th>
                            <td>
                                <p>
                                    <?php
                                    wp_dropdown_pages([
                                        'name' => 'ip_profile_page',
                                        'echo' => 1,
                                        'show_option_none' => __('Select profile page...', 'imagepress'),
                                        'option_none_value' => 0,
                                        'selected' => get_imagepress_option('ip_profile_page'),
                                    ]);

                                    $ipProfilePage = (int) get_imagepress_option('ip_profile_page')
                                    ?>
                                    <br><small>Make sure you add the <code>[cinnamon-profile]</code> shortcode on this page.</small>
                                </p>
                                <p>
                                    <input type="text" name="cinnamon_author_slug" id="cinnamon_author_slug" value="<?php echo get_imagepress_option('cinnamon_author_slug'); ?>" class="regular-text">
                                    <br><small>Default is <b>author</b> (usage exemples: <b>author</b>, <b>profile</b> or <b>hub</b>).</small>
                                    <br><small>User profile URL will look like <code class="codor"><?php echo get_permalink($ipProfilePage) . '?<b>' . get_imagepress_option('cinnamon_author_slug') . '</b>='; ?>username</code>.</small>
                                    <br><small>Tip: use a singular term, one word only, lowercase, letters only</small>
                                    <br><small>After changing any of the values above, you might need to resave your permalinks, in order to avoid 404 errors.</small>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="cinnamon_account_page">Author account login page</label></th>
                            <td>
                                <input type="url" name="cinnamon_account_page" id="cinnamon_account_page" value="<?php echo get_imagepress_option('cinnamon_account_page'); ?>" class="regular-text" placeholder="https://">
                                <br><small>Create a new page and add the <code>[cinnamon-login]</code> shortcode.</small>
                                <br><small>This shortcode will display a login/registration tabbed section.</small>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="cinnamon_edit_page">Author profile edit page URL</label></th>
                            <td>
                                <?php
                                wp_dropdown_pages([
                                    'name' => 'cinnamon_edit_page',
                                    'echo' => 1,
                                    'show_option_none' => __('Select profile editor page...', 'imagepress'),
                                    'option_none_value' => 0,
                                    'selected' => get_imagepress_option('cinnamon_edit_page'),
                                ]);
                                ?>
                                <br><small>Create a new page and add the <code>[cinnamon-profile-edit]</code> shortcode.</small>
                                <br><small>This shortcode will display all user fields in a tabbed section.</small>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="ip_et_login">WordPress login URL<br><small>(optional)</small></label></th>
                            <td>
                                <input type="url" name="ip_et_login" id="ip_et_login" value="<?php echo get_imagepress_option('ip_et_login'); ?>" class="regular-text">
                                <br><small>Use this option to define a different login URL than <code>/wp-login.php</code>.</small>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <hr>
                <h2>Author Cards</h2>
                <p>These settings apply to author cards. Use the <code>[cinnamon-card]</code> shortcode to display the cards.</p>
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><label for="ip_cards_per_author">Number of images</label></th>
                            <td>
                                <input type="number" name="ip_cards_per_author" id="ip_cards_per_author" value="<?php echo get_imagepress_option('ip_cards_per_author'); ?>" min="0" max="32">
                            </td>
                        </tr>
                    </tbody>
                </table>

                <hr>
                <h2>Author Awards</h2>
                <p>Create a new page and add the <code>[cinnamon-awards]</code> shortcode. This shortcode will list all available awards and their description.</p>
                <p><span class="dashicons dashicons-awards"></span> <a href="<?php echo admin_url('edit-tags.php?taxonomy=award'); ?>" class="button button-secondary">Add/Edit Awards</a></p>

                <hr>
                <h2>Author Profile</h2>
                <p>These settings apply to author profiles.</p>
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><label>Profile Settings</label></th>
                            <td>
                                <p>
                                    <select name="cinnamon_hide_admin" id="cinnamon_hide_admin">
                                        <option value="1"<?php if(get_imagepress_option('cinnamon_hide_admin') == 1) echo ' selected'; ?>>Hide admin bar for non-admin users</option>
                                        <option value="0"<?php if(get_imagepress_option('cinnamon_hide_admin') == 0) echo ' selected'; ?>>Show admin bar for non-admin users</option>
                                    </select>
                                </p>
                                <hr>
                                <p>
                                    <input type="checkbox" id="cinnamon_fancy_header" name="cinnamon_fancy_header" value="yes" <?php if(get_imagepress_option('cinnamon_fancy_header') == 'yes') echo 'checked'; ?>> <label for="cinnamon_fancy_header">Use a fancy header to display user data (cover image and styled avatar)</label>
                                    <br><small>Unchecking this option will show a basic header, with avatar, name and user links</small>
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <hr>
                <h2>Email Settings</h2>
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><label>Email Settings</label></th>
                            <td>
                                <p>
                                    <input type="checkbox" id="approvednotification" name="approvednotification" value="yes" <?php if(get_imagepress_option('approvednotification') == 'yes') echo 'checked'; ?>> <label for="approvednotification">Notify author when image is approved</label>
                                    <br>
                                    <input type="checkbox" id="declinednotification" name="declinednotification" value="yes" <?php if(get_imagepress_option('declinednotification') == 'yes') echo 'checked'; ?>> <label for="declinednotification">Notify author when image is rejected</label>
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <hr>
                <p><input name="cinnamon_submit" type="submit" class="button-primary" value="Save Changes"></p>
            </form>
        <?php } else if ($tab === 'label_tab') {
            if (isset($_POST['isGSSubmit'])) {
                $ipUpdatedOptions = [
                    'ip_caption_label' => $_POST['ip_caption_label'],
                    'ip_category_label' => $_POST['ip_category_label'],
                    'ip_description_label' => $_POST['ip_description_label'],
                    'ip_upload_label' => $_POST['ip_upload_label'],
                    'ip_image_label' => $_POST['ip_image_label'],
                    'ip_notifications_mark' => $_POST['ip_notifications_mark'],
                    'ip_notifications_all' => $_POST['ip_notifications_all'],
                    'ip_upload_success_title' => $_POST['ip_upload_success_title'],
                    'ip_upload_success' => $_POST['ip_upload_success'],
                    'ip_vote_like' => stripslashes_deep($_POST['ip_vote_like']),
                    'ip_vote_unlike' => stripslashes_deep($_POST['ip_vote_unlike']),
                ];
                $ipOptions = get_option('imagepress');
                $ipUpdate = array_merge($ipOptions, $ipUpdatedOptions);
                update_option('imagepress', $ipUpdate);

                echo '<div class="updated notice is-dismissible"><p>Settings updated successfully!</p></div>';
            }
            ?>
            <form method="post" action="">
                <h2><?php _e('Label Settings', 'imagepress'); ?></h2>
                <p><?php _e('Configure, set or translate any of ImagePress labels. Leave a label blank to disable/hide it.', 'imagepress'); ?></p>
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><label for="ip_caption_label">Image caption label<br><small>Leave blank to disable</small></label></th>
                            <td>
                                <input type="text" name="ip_caption_label" id="ip_caption_label" value="<?php echo get_imagepress_option('ip_caption_label'); ?>" class="regular-text">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="ip_category_label">Image category label<br><small>(dropdown)</small></label></th>
                            <td>
                                <input type="text" name="ip_category_label" id="ip_category_label" value="<?php echo get_imagepress_option('ip_category_label'); ?>" class="regular-text">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="ip_description_label">Image description label<br><small>(textarea)<br>Leave blank to disable</small></label></th>
                            <td>
                                <input type="text" name="ip_description_label" id="ip_description_label" value="<?php echo get_imagepress_option('ip_description_label'); ?>" class="regular-text">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="ip_upload_label">Image upload button label<br><small>(button)</small></label></th>
                            <td>
                                <input type="text" name="ip_upload_label" id="ip_upload_label" value="<?php echo get_imagepress_option('ip_upload_label'); ?>" class="regular-text">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="ip_image_label">Image upload selection label<br><small>(link)</small></label></th>
                            <td>
                                <input type="text" name="ip_image_label" id="ip_image_label" value="<?php echo get_imagepress_option('ip_image_label'); ?>" class="regular-text">
                            </td>
                        </tr>
                    </tbody>
                </table>

                <hr>
                <h2><?php _e('Notifications', 'imagepress'); ?></h2>
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><label for="ip_notifications_mark">"Mark all as read" label</label></th>
                            <td>
                                <input type="text" name="ip_notifications_mark" id="ip_notifications_mark" value="<?php echo get_imagepress_option('ip_notifications_mark'); ?>" class="regular-text">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="ip_notifications_all">"View all notifications" label</label></th>
                            <td>
                                <input type="text" name="ip_notifications_all" id="ip_notifications_all" value="<?php echo get_imagepress_option('ip_notifications_all'); ?>" class="regular-text">
                            </td>
                        </tr>
                    </tbody>
                </table>

                <hr>
                <h2><?php _e('Image Upload', 'imagepress'); ?></h2>
                <p>This text will appear when the image upload is successful. Leave blank to disable.</p>
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><label for="ip_upload_success_title">Upload success title</label></th>
                            <td>
                                <input type="text" name="ip_upload_success_title" id="ip_upload_success_title" value="<?php echo get_imagepress_option('ip_upload_success_title'); ?>" class="regular-text">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="ip_upload_success">Upload success</label></th>
                            <td>
                                <input type="text" name="ip_upload_success" id="ip_upload_success" value="<?php echo get_imagepress_option('ip_upload_success'); ?>" class="regular-text">
                            </td>
                        </tr>
                    </tbody>
                </table>

                <hr>
                <h2><?php _e('Like/Unlike', 'imagepress'); ?></h2>
                <p>This text will appear when the image upload is successful. Leave blank to disable.</p>
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><label>Like/Unlike</label></th>
                            <td>
                            <p>
                                <input type="text" name="ip_vote_like" id="ip_vote_like" value="<?php echo get_imagepress_option('ip_vote_like'); ?>" placeholder="I like this image" class="regular-text">
                                <label for="ip_vote_like">"Like" label</label>
                                <br><small>Examples: Like, Appreciate, Love, Vote</small>
                                <br>
                                <input type="text" name="ip_vote_unlike" id="ip_vote_unlike" value="<?php echo get_imagepress_option('ip_vote_unlike'); ?>" placeholder="Oops! I don't like this" class="regular-text">
                                <label for="ip_vote_unlike">"Unlike" label</label>
                            </p>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <hr>
                <p><input type="submit" name="isGSSubmit" value="Save Changes" class="button-primary"></p>
            </form>
        <?php } else if ($tab === 'upload_tab') {
            global $wp_roles;

            $all_roles = $wp_roles->roles;
            $editable_roles = apply_filters('editable_roles', $all_roles);

            if (isset($_POST['isGSSubmit'])) {
                $ipUpdatedOptions = [
                    'ip_upload_secondary' => $_POST['ip_upload_secondary'],
                    'ip_upload_tos' => $_POST['ip_upload_tos'],
                    'ip_upload_tos_url' => $_POST['ip_upload_tos_url'],
                    'ip_upload_tos_error' => $_POST['ip_upload_tos_error'],
                    'ip_upload_tos_content' => $_POST['ip_upload_tos_content'],
                    'ip_upload_size' => $_POST['ip_upload_size'],
                    'ip_cat_exclude' => $_POST['ip_cat_exclude'],
                    'ip_max_quality' => $_POST['ip_max_quality'],
                    'ip_dropbox_enable' => $_POST['ip_dropbox_enable'],
                    'ip_dropbox_key' => $_POST['ip_dropbox_key'],
                ];
                $ipOptions = get_option('imagepress');
                $ipUpdate = array_merge($ipOptions, $ipUpdatedOptions);
                update_option('imagepress', $ipUpdate);

                echo '<div class="updated notice is-dismissible"><p>Settings updated successfully!</p></div>';
            }
            ?>
            <form method="post" action="">
                <h2><?php _e('Upload Settings', 'imagepress'); ?></h2>
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><label for="ip_max_quality">Image quality</label></th>
                            <td>
                                <input name="ip_max_quality" id="ip_max_quality" type="number" value="<?php echo get_imagepress_option('ip_max_quality')?>" min="0" max="100">
                                <br><small>Set image quality when uploading image.</small>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="ip_upload_size">Maximum image upload size<br><small>(in kilobytes)</small></label></th>
                            <td>
                                <input type="number" name="ip_upload_size" id="ip_upload_size" min="0" max="65536" step="1024" value="<?php echo get_imagepress_option('ip_upload_size'); ?>">
                                <br><small>Try 4096 for most configurations.</small>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="ip_cat_exclude">Exclude categories</label></th>
                            <td>
                                <input type="text" name="ip_cat_exclude" id="ip_cat_exclude" value="<?php echo get_imagepress_option('ip_cat_exclude'); ?>">
                                <br><small>Exclude these categories from the upload form (separate IDs with comma).</small>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label>Upload features</label></th>
                            <td>
                                <select name="ip_upload_secondary" id="ip_upload_secondary">
                                    <option value="1"<?php if(get_imagepress_option('ip_upload_secondary') == 1) echo ' selected'; ?>>Enable secondary upload button</option>
                                    <option value="0"<?php if(get_imagepress_option('ip_upload_secondary') == 0) echo ' selected'; ?>>Disable secondary upload button</option>
                                </select> <label for="ip_upload_secondary">Enable/disable additional images (variants, progress shots, making of, etc.)</label>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label>Terms and conditions of use</label></th>
                            <td>
                                <select name="ip_upload_tos" id="ip_upload_tos">
                                    <option value="1"<?php if(get_imagepress_option('ip_upload_tos') == 1) echo ' selected'; ?>>Enable terms and conditions</option>
                                    <option value="0"<?php if(get_imagepress_option('ip_upload_tos') == 0) echo ' selected'; ?>>Disable terms and conditions</option>
                                </select> <label for="ip_upload_tos">Enable/disable terms and conditions of use</label>
                                <br>
                                <input type="text" name="ip_upload_tos_content" id="ip_upload_tos_content" class="regular-text" value="<?php echo get_imagepress_option('ip_upload_tos_content'); ?>" placeholder="I agree with the terms and conditions"> <label for="ip_upload_tos_content">Terms and conditions of use body</label>
                                <br>
                                <input type="text" name="ip_upload_tos_error" id="ip_upload_tos_error" class="regular-text" value="<?php echo get_imagepress_option('ip_upload_tos_error'); ?>" placeholder="Please indicate that you accept the terms and conditions of use"> <label for="ip_upload_tos_error">Terms and conditions of use error</label>
                                <br>
                                <input type="url" name="ip_upload_tos_url" id="ip_upload_tos_url" class="regular-text" value="<?php echo get_imagepress_option('ip_upload_tos_url'); ?>" placeholder="https://"> <label for="ip_upload_tos_url">Terms and conditions of use URL</label>
                                <br><small>Opens in new tab/window</small>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <hr>
                <h2>Integrations</h2>
                <p>Allow third-party modules to hook into the upload functions.</p>
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><label>Dropbox</label></th>
                            <td>
                                <p>
                                    <input type="checkbox" name="ip_dropbox_enable" value="1" <?php if(get_imagepress_option('ip_dropbox_enable') === '1') echo 'checked'; ?>> <label>Enable Dropbox upload</label>
                                </p>
                                <p>
                                    <input name="ip_dropbox_key" id="ip_dropbox_key" type="text" class="regular-text" value="<?php echo get_imagepress_option('ip_dropbox_key'); ?>"> <label for="ip_dropbox_key">Dropbox API Key</label>
                                    <br><small>Allow users to upload images from their Dropbox accounts. Requires an <a href="https://www.dropbox.com/developers/dropins/chooser/js" rel="external">API key</a>. <a href="https://www.dropbox.com/developers/apps/create?app_type_checked=dropins" rel="external">Create new Dropbox app.</a></small>
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <p><input type="submit" name="isGSSubmit" value="Save Changes" class="button-primary"></p>
            </form>
        <?php } else if ($tab === 'notifications_tab') { ?>
            <script>
            jQuery(document).ready(function () {
                jQuery('.ajax_trash').click(function (e) {
                    var data = {
                        action: 'ajax_trash_action',
                        odvm_post: jQuery(this).attr('data-post'),
                    };

                    jQuery.post(ajaxurl, data, function (response) {
                        // Success (response)
                    });
                    fade_vote = jQuery(this).attr('data-post');
                    jQuery('#notification-' + fade_vote).fadeOut('slow');
                    e.preventDefault();
                });
            });
            </script>

            <h3>All notifications</h3>
            <?php
            global $wpdb;

            $limit = 256;

            $sql = "SELECT * FROM " . $wpdb->prefix . "notifications ORDER BY ID DESC LIMIT " . $limit . "";
            $results = $wpdb->get_results($sql);
            foreach($results as $result) {
                ?>
                <div id="notification-<?php echo $result->ID; ?>">
                    <a href="#" class="ajax_trash" data-post="<?php echo $result->ID; ?>"><span class="dashicons dashicons-trash"></span></a>&nbsp;
                    <?php
                    $display = '';
                    $action = $result->actionType;
                    $nickname = get_the_author_meta('nickname', $result->userID);
                    $time = human_time_diff(strtotime($result->actionTime), current_time('timestamp')) . ' ago';
                    $ipCollectionsPageId = get_imagepress_option('ip_collections_page');

                    if ((string) $action === 'loved')
                        $display .= get_avatar($result->userID, 16) . ' <a href="' . getImagePressProfileUri($result->userID, false) . '">' . $nickname . '</a> ' . $action . ' <a href="' . get_permalink($result->postID) . '">' . get_the_title($result->postID) . '</a> <time>' . $time . '</time>';

                    else if ((string) $action === 'collected')
                        $display .= get_avatar($result->userID, 16) . ' <a href="' . getImagePressProfileUri($result->userID, false) . '">' . $nickname . '</a> ' . $action . ' <a href="' . get_permalink($result->postID) . '">' . get_the_title($result->postID) . '</a> into a <a href="' . get_permalink($ipCollectionsPageId) . '?collection=' .  $result->postKeyID . '">collection</a> <time>' . $time . '</time>';

                    else if ((string) $action === 'added')
                        $display .= get_avatar($result->userID, 16) . ' <a href="' . getImagePressProfileUri($result->userID, false) . '">' . $nickname . '</a> ' . $action . ' <a href="' . get_permalink($result->postID) . '">' . get_the_title($result->postID) . '</a> <time>' . $time . '</time>';

                    else if ((string) $action === 'followed')
                        $display .= get_avatar($result->userID, 16) . ' <a href="' . getImagePressProfileUri($result->userID, false) . '">' . $nickname . '</a> ' . $result->actionType . ' you <time>' . $time . '</time>';

                    else if ((string) $action === 'commented on') {
                        $who = '<a href="' . getImagePressProfileUri($result->userID, false) . '">' . $nickname . '</a>';
                        if ((int) $result->userID === 0) {
                            $who = 'Someone';
                        }
                        $display .= get_avatar($result->userID, 16) . ' ' . $who . ' ' . $action . ' <a href="' . get_permalink($result->postID) . '">' . get_the_title($result->postID) . '</a> <time>' . $time . '</time>';
                    }

                    else if ((string) $action == 'replied to a comment on') {
                        $comment_id = get_comment($result->postID);
                        $comment_post_ID = $comment_id->comment_post_ID;

                        $display .= get_avatar($result->userID, 16) . ' <a href="' . getImagePressProfileUri($result->userID, false) . '">' . $nickname . '</a> replied to a comment on <a href="' . get_permalink($comment_post_ID) . '">' . get_the_title($comment_post_ID) . '</a> <time>' . $time . '</time>';
                    }

                    // custom
                    else if (0 == $result->postID || '-1' == $result->postID) {
                        $display .= $result->actionType . ' <time>' . $time . '</time>';
                    }

                    echo $display;
                    ?>
                </div>
            <?php } ?>
        <?php } else if ($tab === 'fields_tab') {
            global $wpdb;

            if (isset($_POST['isCFSubmit'])) {
                $ip_field_type = intval($_POST['ip_field_type']);
                $ip_field_order = intval($_POST['ip_field_order']);
                $ip_field_name = stripslashes($_POST['ip_field_name']);
                $ip_field_slug = sanitize_text_field($_POST['ip_field_slug']);
                $ip_field_content = sanitize_text_field($_POST['ip_field_content']);

                $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "ip_fields (field_type, field_order, field_name, field_slug, field_content) VALUES (%d, %d, '%s', '%s', '%s')", $ip_field_type, $ip_field_order, $ip_field_name, $ip_field_slug, $ip_field_content));

                echo '<div class="updated notice is-dismissible"><p>Field added successfully!</p></div>';
            }
            ?>
            <form method="post" action="">
                <h2><?php _e('Custom Fields', 'imagepress'); ?></h2>
                <p>ImagePress custom fields are additional/optional fields used by the upload form to add/select options and values. For example, an image can have five additional fields, like copyright, size, location, country and price.</p>

                <hr>
                <h2><?php _e('Add New Field', 'imagepress'); ?></h2>
                <p>Add new custom field to your upload form.</p>
                <script>
                jQuery(document).ready(function() {
                    jQuery("#ip_field_name").keyup(function() {
                        var ip_slug = jQuery.trim(this.value);
                        $ip_slug = ip_slug.toLowerCase().replace(/[^a-z0-9-]/gi, '_').replace(/-+/g, '_').replace(/^-|-$/g, '');

                        jQuery("#ip_field_slug").val($ip_slug ? $ip_slug : "");
                    });
                });
                </script>
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><label for="ip_field_type">Field type</label></th>
                            <td>
                                <select name="ip_field_type" id="ip_field_type">
                                    <option value="1">Input (text)</option>
                                    <option value="2">Input (URL)</option>
                                    <option value="3">Input (email)</option>
                                    <option value="4">Input (number)</option>
                                    <option value="5">Textarea</option>
                                    <option value="6">Checkbox</option>
                                    <option value="9">Checkbox group</option>
                                    <option value="7">Radiobox</option>
                                    <option value="8">Dropdown</option>

                                    <optgroup label="Field presets">
                                        <option value="20">Sketchfab (model ID)</option>
                                        <option value="21">Vimeo (video ID)</option>
                                        <option value="22">Youtube (video ID)</option>
                                        <option value="23">Google Maps location (address)</option>
                                        <option value="24">Round.me (tour ID)</option>
                                    </optgroup>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="ip_field_order">Field order</label></th>
                            <td>
                                <input type="number" name="ip_field_order" id="ip_field_order" value="" min="0" max="99999" step="1" placeholder="0">
                                <br><small>This is the field order</small>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="ip_field_name">Field name</label></th>
                            <td>
                                <input type="text" name="ip_field_name" id="ip_field_name" class="regular-text" value="">
                                <br><small>This is the field label</small>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="ip_field_slug">Field slug</label></th>
                            <td>
                                <input type="text" name="ip_field_slug" id="ip_field_slug" class="regular-text" value="">
                                <br><small>This is the field slug</small>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="ip_field_content">Field content<br><small>(optional)</small></label></th>
                            <td>
                                <textarea class="large-text code" rows="4" name="ip_field_content" id="ip_field_content"></textarea>
                                <br><small>Use dropdown options, separated by comma (e.g. <code>Value 1, Value 2, Value 3</code>)</small>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <hr>
                <p><input type="submit" name="isCFSubmit" value="Add Field" class="button-primary"></p>

                <hr>
                <h2><?php _e('Manage Custom Fields', 'imagepress'); ?></h2>

                <?php wp_enqueue_script("jquery"); ?>
                <?php wp_enqueue_script("jquery-ui-core"); ?>
                <?php wp_enqueue_script("jquery-ui-sortable"); ?>
                <script>
                jQuery(document).ready(function() {
                    var fixHelperModified = function(e, tr) {
                        var $originals = tr.children();
                        var $helper = tr.clone();
                        $helper.children().each(function(index) {
                            jQuery(this).width($originals.eq(index).width())
                        });
                        return $helper;
                    };

                    jQuery("#ip-sortable tbody").sortable({
                        helper: fixHelperModified,
                        handle: '.handle',
                        opacity: 0.75,
                        update: function() {
                            var order = jQuery("#ip-sortable tbody").sortable('serialize');
                            //console.log(order);
                            jQuery("#ip-info").load('<?php echo IP_PLUGIN_URL; ?>/includes/sortable-fields.php?' + order);
                        }
                    }).enableSelection();
                });
                </script>
                <?php
                if(isset($_GET['cf'])) {
                    $field_id = (int) $_GET['cf'];

                    $wpdb->query($wpdb->prepare("DELETE FROM " . $wpdb->prefix . "ip_fields WHERE field_id = %d", $field_id));

                    echo '<div class="updated notice is-dismissible"><p>Custom field removed successfully!</p></div>';
                }

                $result = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_fields ORDER BY field_order ASC", ARRAY_A);

                echo '<div id="ip-info"><p><span class="dashicons dashicons-move"></span> ' . __('Drag custom fields to reorder them', 'imagepress') . '</p></div>
                <table id="ip-sortable" class="wp-list-table widefat striped posts">
                    <thead>
                        <tr>
                            <th scope="col"></th>
                            <th scope="col">Field Name</th>
                            <th scope="col">Field Slug</th>
                            <th scope="col">Field Type</th>
                            <th scope="col">Field Content</th>
                            <th scope="col">Shortcode</th>
                            <th scope="col"><div class="dashicons dashicons-admin-generic"></div></th>
                        </tr>
                    </thead>
                    <tbody>';
                    foreach ($result as $field) {
                        echo '<tr id="listItem_' . $field['field_id'] . '">
                            <td><span class="handle"><span class="dashicons dashicons-move"></span></span></td>
                            <td><b>' . $field['field_name'] . '</b></td>
                            <td>' . $field['field_slug'] . '</td>';

                            if ((int) $field['field_type'] === 1) {
                                echo '<td>Input (text)</td>';
                            } else if ((int) $field['field_type'] === 2) {
                                echo '<td>Input (URL)</td>';
                            } else if ((int) $field['field_type'] === 3) {
                                echo '<td>Input (email)</td>';
                            } else if ((int) $field['field_type'] === 4) {
                                echo '<td>Input (number)</td>';
                            } else if ((int) $field['field_type'] === 5) {
                                echo '<td>Textarea</td>';
                            } else if ((int) $field['field_type'] === 6) {
                                echo '<td>Checkbox</td>';
                            } else if ((int) $field['field_type'] === 7) {
                                echo '<td>Radiobox</td>';
                            } else if ((int) $field['field_type'] === 8) {
                                echo '<td>Dropdown</td>';
                            } else if ((int) $field['field_type'] === 20) {
                                echo '<td>Sketchfab ID (text)</td>';
                            } else if ((int) $field['field_type'] === 21) {
                                echo '<td>Vimeo ID (text)</td>';
                            } else if ((int) $field['field_type'] === 22) {
                                echo '<td>Youtube ID (text)</td>';
                            } else if ((int) $field['field_type'] === 23) {
                                echo '<td>Google Maps location (text)</td>';
                            } else if ((int) $field['field_type'] === 24) {
                                echo '<td>Round.me Tour ID (text)</td>';
                            }

                            echo '<td>' . $field['field_content'] . '</td>
                            <td><code>[ip-field field="' . $field['field_slug'] . '"]</code></td>
                            <td><a href="' . admin_url('edit.php?post_type=' . $ipSlug . '&page=imagepress_admin_page&tab=fields_tab&cf=' . $field['field_id']) . '"><span class="dashicons dashicons-trash"></span></a></td>
                        </tr>';
                    }
                echo '</tbody></table>';
                ?>
            </form>
        <?php } ?>
    </div>
    <?php
}
