<?php
function imagepress_count_user_posts_by_type($userid) {
    global $wpdb;

    $imagepress_slug = imagepress_get_option('ip_slug');

    $where = get_posts_by_author_sql($imagepress_slug, true, $userid);
    $count = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts $where");

    return apply_filters('get_usernumposts', $count, $userid);
}

function imagepress_post_views($userId, $count = true) {
    $axCount = get_user_meta($userId, 'ax_post_views', true);
    if ($axCount == '') {
        $axCount = 0;
    }

    if ($count === true) {
        $axCount++;
        update_user_meta($userId, 'ax_post_views', $axCount);
    }

    return $axCount;
}

function imagepress_author_base() {
    global $wp_rewrite;

    $cinnamon_author_slug = imagepress_get_option('cinnamon_author_slug');

    $wp_rewrite->author_base = $cinnamon_author_slug;
}

function imagepress_get_related_author_posts($author) {
    $imagepress_slug = imagepress_get_option('ip_slug');
    $authors_posts = get_posts([
        'author' => $author,
        'posts_per_page' => 9,
        'post_type' => $imagepress_slug
    ]);

    $output = '';

    if ($authors_posts) {
        $output .= '<div class="cinnamon-grid">
            <ul>';
                foreach ($authors_posts as $authors_post) {
                    $output .= '<li><a href="' . get_permalink($authors_post->ID) . '">' . get_the_post_thumbnail($authors_post->ID, 'thumbnail') . '</a></li>';
                }
            $output .= '</ul>
        </div>';
    }

    return $output;
}

function imagepress_extra_contact_info($contactmethods) {
    unset($contactmethods['aim']);
    unset($contactmethods['yim']);
    unset($contactmethods['jabber']);
    unset($contactmethods['googleplus']);

    $contactmethods['facebook'] = 'Facebook';
    $contactmethods['twitter'] = 'Twitter';

    return $contactmethods;
}



/* CINNAMON CARD SHORTCODE */
function imagepress_card($atts) {
    extract(shortcode_atts([
        'author' => '',
        'count' => 10,
        'sort' => 0,
        'role' => ''
    ], $atts));

    $imagepress_slug = imagepress_get_option('ip_slug');

    $display = '<div id="author-cards">';

    // Filter users by role
    $role = sanitize_text_field($role);
    $role = ((string) $role === '') ? '' : '&role=' . $role;

    $number = $count;
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $offset = ($paged - 1) * $number;
    $users = get_users();
    $query = get_users('&offset=' . $offset . '&number=' . $number . $role);
    $total_users = $users ? count($users) : 0;
    $total_query = $query ? count($query) : 0;
    $total_pages = intval($total_users / $number) + 1;

    $display .= '<ul class="list">';
        foreach ($query as $q) {
            $author = $q->ID;

            $cardArguments = [
                'author' => $author,
                'posts_per_page' => imagepress_get_option('ip_cards_per_author'),
                'post_type' => $imagepress_slug
            ];
            $authors_posts = get_posts($cardArguments);

            if (count($authors_posts) > 0) {
                $display .= '<li class="cinnamon-card">';
                    if ($authors_posts) {
                        $display .= '<div class="mosaicflow">';
                            foreach ($authors_posts as $authors_post) {
                                $display .= '<div><a href="' . get_permalink($authors_post->ID) . '">' . get_the_post_thumbnail($authors_post->ID, imagepress_get_option('ip_image_size')) . '</a></div>';
                            }
                        $display .= '</div>';
                    }
                    $display .= '<div class="avatar-holder"><a href="' . imagepress_get_profile_uri($author, false) . '">' . get_avatar($author, 104) . '</a></div>';

                    $hub_user_info = get_userdata($author);
                    $hubUser = $hub_user_info->display_name;
                    if (!empty($hub_user_info->first_name)) {
                        $hubUser = $hub_user_info->first_name . ' ' . $hub_user_info->last_name;
                    }

                    $display .= '<h3><a href="' . imagepress_get_profile_uri($author, false) . '" class="name">' . $hubUser . '</a></h3>
                    <div class="cinnamon-stats">
                        <div class="cinnamon-meta"><span class="views">' . imagepress_kformat(imagepress_post_views($author, false)) . '</span><br><small>' . __('views', 'imagepress') . '</small></div>
                        <div class="cinnamon-meta"><span class="followers">' . imagepress_kformat(imagepress_get_follower_count($author)) . '</span><br><small>' . __('followers', 'imagepress') . '</small></div>
                        <div class="cinnamon-meta"><span class="uploads">' . imagepress_kformat(imagepress_count_user_posts_by_type($author, $imagepress_slug)) . '</span><br><small>' . __('uploads', 'imagepress') . '</small></div>
                    </div>';
                $display .= '</li>';
            }
        }
    $display .= '</ul>';

    if ((int) $total_users > (int) $total_query) {
        $display .= '<div id="pagination" class="native-pagination">';
        $display .= '<span class="pages">' . __('Pages:', 'imagepress') . '</span>';
        $current_page = max(1, get_query_var('paged'));
        $display .= paginate_links([
            'base' => get_pagenum_link(1) . '%_%',
            'format' => 'page/%#%/',
            'current' => $current_page,
            'total' => $total_pages,
            'prev_next' => false,
            'type' => 'list'
        ]);
        $display .= '</div>';
    }

    $display .= '</div>';

    return $display;
}



/* CINNAMON PROFILE SHORTCODE */
function imagepress_profile($atts) {
    extract(shortcode_atts([
        'author' => '',
        'username' => ''
    ], $atts));

    global $wpdb, $current_user;

    $cinnamon_author_slug = (string) imagepress_get_option('cinnamon_author_slug');

    // If no user is provided, get logged in user
    if (!isset($_GET[$cinnamon_author_slug])) {
        $current_user = wp_get_current_user();
        if (!($current_user instanceof WP_User)) {
            return;
        } else {
            $_GET[$cinnamon_author_slug] = $current_user->user_login;
        }
    }

    $userLogin = (string) sanitize_text_field($_GET[$cinnamon_author_slug]);

    $userArray = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->users WHERE user_nicename = '%s'", $userLogin));

    if (empty($userArray)) {
        $userArray = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->users WHERE user_login = '%s'", $userLogin));
    }

    // If no profile is provided
    if (empty($userLogin) || empty($userArray)) {
        return;
    }

    if (empty($author)) {
        $author = $userArray->ID;
    }
    if (empty($username)) {
        $username = $userArray->user_nicename;
    }

    $imagepress_slug = imagepress_get_option('ip_slug');
    $hub_user_info = get_userdata((int) $author);

    $display = '';

    $hub_facebook = ''; $hub_twitter = ''; $hub_user_url = '';
    if ((string) $hub_user_info->facebook !== '') {
        $hub_facebook = ' <a href="' . $hub_user_info->facebook . '" target="_blank"><i class="fab fa-fw fa-facebook-f"></i></a>';
    }
    if ((string) $hub_user_info->twitter !== '') {
        $hub_twitter = ' <a href="https://twitter.com/' . $hub_user_info->twitter . '" target="_blank"><i class="fab fa-fw fa-twitter"></i></a>';
    }

    $hca = get_the_author_meta('hub_custom_cover', $author);
    $hca = wp_get_attachment_url($hca);
    if (!isset($hca) || empty($hca)) {
        $hca = IMAGEPRESS_PLUGIN_URL . '/img/coverimage.png';
    }

    $logged_in_user = wp_get_current_user();

    $display .= '<div class="profile-hub-container">';

        if (imagepress_get_option('cinnamon_fancy_header') === 'yes') {
            $display .= '<div class="cinnamon-cover" data-background="' . $hca . '">
                <div class="cinnamon-opaque"></div>';

                $display .= '<div class="cinnamon-avatar"><div class="cinnamon-user">' . get_avatar($author, 120) . '';
                    if (is_user_logged_in() && $username != $logged_in_user->user_login)
                        $display .= '<div class="imagepress-follow">' . do_shortcode('[follow_links follow_id="' . $author . '"]') . '</div>';
                    $display .= '</div>';

                    // get custom URL
                    $hubdomain = preg_replace('/^www\./', '', $_SERVER['HTTP_HOST']);
                    $hubuser = get_user_by('id', $author);
                    $hubuser = sanitize_title($hubuser->user_login);
                    $hub_name = $hub_user_info->first_name . ' ' . $hub_user_info->last_name;
                    if (empty($hub_user_info->first_name) && empty($hub_user_info->last_name)) {
                        $hub_name = $hubuser;
                    }

                    if ((string) $hub_user_info->user_url !== '') {
                        $hub_user_url = ' <a href="' . $hub_user_info->user_url . '" rel="external" target="_blank"><i class="fas fa-fw fa-link"></i></a>';
                    }

                    $hub_anchor = imagepress_get_profile_uri($author, false);
                    $display .= '<div>
                        <div class="ph-nametag">
                            ' . $hub_name;
                            if (is_user_logged_in() && (string) strtolower($username) === (string) strtolower($logged_in_user->user_login)) {
                                $display .= ' <a href="' . $hub_anchor . '">#</a>';
                                $display .= ' <small><a href="' . get_permalink(imagepress_get_option('cinnamon_edit_page')) . '">' . __('Edit Profile', 'imagepress') . '</a></small>';
                            }
                        $display .= '</div>
                        <div class="ph-locationtag">' . $hub_facebook . $hub_twitter . $hub_user_url . '</div>
                    </div>';
                $display .= '</div>';
            $display .= '</div>';
        } else {
            $display .= '<div class="cinnamon-cover-blank">
                <p>' . get_avatar($author, 60) . '</p>';
                if (is_user_logged_in() && (string) $username !== (string) $logged_in_user->user_login) {
                    $display .= '<div class="imagepress-follow">' . do_shortcode('[follow_links follow_id="' . $author . '"]') . '</div>';
                }

                // get custom URL
                $hubdomain = preg_replace('/^www\./', '', $_SERVER['HTTP_HOST']);
                $hubuser = get_user_by('id', $author);
                $hubuser = sanitize_title($hubuser->user_login);
                $hub_name = $hub_user_info->first_name . ' ' . $hub_user_info->last_name;
                if (empty($hub_user_info->first_name) && empty($hub_user_info->last_name)) {
                    $hub_name = $hubuser;
                }

                if ($hub_user_info->user_url != '')
                    $hub_user_url = ' <a href="' . $hub_user_info->user_url . '" rel="external" target="_blank"><i class="fas fa-link"></i></a>';

                $display .= '<h2>' . $hub_name . '</h2>
                <p>';
                    $display .= $hub_facebook . $hub_twitter . $hub_user_url;

                    if (is_user_logged_in() && $username == $logged_in_user->user_login) {
                        $display .= ' | <a href="' . get_permalink(imagepress_get_option('cinnamon_edit_page')) . '">' . __('Edit Profile', 'imagepress') . '</a>';
                    }
                $display .= '</p>
            </div>';
        }

        // Cinnamon Stats
        $display .= '<div class="cinnamon-stats">
            <div class="cinnamon-meta"><b>' . imagepress_kformat(imagepress_post_views($author)) . '</b> ' . __('profile views', 'imagepress') . '</div>
            <div class="cinnamon-meta"><b>' . imagepress_kformat(imagepress_get_follower_count($author)) . '</b> ' . __('followers', 'imagepress') . '</div>
            <div class="cinnamon-meta"><b>' . imagepress_kformat(imagepress_count_user_posts_by_type($author, $imagepress_slug)) . '</b> ' . __('uploads', 'imagepress') . '</div>
        </div>';
        $display .= '<div class="ip-profile" data-ipw="' . imagepress_get_option('ip_ipw') . '">';

            if (!empty($hub_user_info->description))
                $display .= wpautop($hub_user_info->description);
            $display .= '<br>';

            $award_terms = wp_get_object_terms($author, 'award');
            if (!empty($award_terms)) {
                if (!is_wp_error($award_terms)) {
                    foreach ($award_terms as $term) {
                    // get custom FontAwesome
                        $t_ID = $term->term_id;
                        $term_data = get_option("taxonomy_$t_ID");

                        $display .= '<span class="cinnamon-award-list-item" title="' . $term->description . '"><i class="fas fa-trophy"></i> ' . $term->name . '</span>';
                    }
                }
            }

            $display .= do_shortcode('[imagepress-loop user="' . $author . '" order="custom" count="999999"]') .
        '</div>
    </div>';

    return $display;
}

function imagepress_profile_edit($atts) {
    extract(shortcode_atts([
        'author' => ''
    ], $atts));

    global $wpdb;

    $current_user = wp_get_current_user();
    $userid = $current_user->ID;

    $error = [];
    $out = '';

    if (!empty($_POST['action']) && (string) $_POST['action'] === 'update-user') {
        if (!empty($_POST['pass1']) && !empty($_POST['pass2'])) {
            if ((string) $_POST['pass1'] === (string) $_POST['pass2']) {
                wp_update_user(['ID' => $userid, 'user_pass' => esc_attr($_POST['pass1'])]);
            } else {
                $error[] = __('The passwords you entered do not match. Your password was not updated.', 'imagepress');
            }
        }

        wp_update_user(['ID' => $userid, 'user_url' => esc_url($_POST['url'])]);

        if (!is_email(esc_attr($_POST['email']))) {
            $error[] = __('The email you entered is not valid. Please try again.', 'imagepress');
        } else if (email_exists(esc_attr($_POST['email'])) != $userid) {
            $error[] = __('This email is already used by another user. Try a different one.', 'imagepress');
        } else {
            wp_update_user(['ID' => $userid, 'user_email' => esc_attr($_POST['email'])]);
        }

        update_user_meta($userid, 'first_name', esc_attr($_POST['first-name']));
        update_user_meta($userid, 'last_name', esc_attr($_POST['last-name']));

        update_user_meta($userid, 'nickname', esc_attr($_POST['nickname']));
        $wpdb->update($wpdb->users, ['display_name' => $_POST['nickname']], ['ID' => $userid], null, null);

        update_user_meta($userid, 'description', esc_attr($_POST['description']));

        update_user_meta($userid, 'facebook', esc_attr($_POST['facebook']));
        update_user_meta($userid, 'twitter', esc_attr($_POST['twitter']));

        // Avatar and cover upload
        if ($_FILES) {
            require_once ABSPATH . '/wp-admin/includes/image.php';
            require_once ABSPATH . '/wp-admin/includes/file.php';
            require_once ABSPATH . '/wp-admin/includes/media.php';

            foreach ($_FILES as $file => $array) {
                if (!empty($_FILES[$file]['name'])) {
                    $file_id = media_handle_upload($file, 0);
                    if ($file_id > 0) {
                        update_user_meta($userid, $file, $file_id);
                    }
                }
            }
        }
        //

        if (count($error) == 0) {
            do_action('edit_user_profile_update', $userid);
            $out .= '<p class="message noir-success">' . __('Profile updated successfully!', 'imagepress') . '</p>';
        }
    }

    $out .= '<div id="post-' . get_the_ID() . '">
        <div class="entry-content entry cinnamon">';
            if (count($error) > 0) {
                $out .= '<p class="error">' . implode('<br>', $error) . '</p>';
            }

            $out .= '<form method="post" id="adduser" action="" enctype="multipart/form-data" class="thin-ui-form">
                <ul class="tabs">
                    <li><a href="#summary" class="is-active">' . __('Summary', 'imagepress') . '</a></li>
                    <li><a href="#account">' . __('Account Details', 'imagepress') . '</a></li>
                    <li><a href="#collections" class="imagepress-collections">' . __('Collections', 'imagepress') . '</a></li>
                    <li><a href="#editor">' . __('Image Editor', 'imagepress') . '</a></li>
                </ul>
                <div class="tab-content" id="summary">
                    <h3>' . __('Statistics', 'imagepress') . '</h3>';
                    $imagepress_slug = imagepress_get_option('ip_slug');

                    // get current user uploads
                    $user_uploads = imagepress_count_user_posts_by_type($userid, $imagepress_slug);

                    $out .= '<div class="ip-user-dashboard">
                        <div class="ip-user-dashboard-stat">
                            <span>' . imagepress_kformat(imagepress_post_views($userid, false)) . '</span>' .
                            __('total profile views', 'imagepress') . '
                        </div>
                        <div class="ip-user-dashboard-stat">
                            <span>' . imagepress_kformat(imagepress_get_follower_count($userid)) . '</span>' .
                            __('followers', 'imagepress') . '
                        </div>
                        <div class="ip-user-dashboard-stat">
                            <span>' . imagepress_kformat(imagepress_get_following_count($userid)) . '</span>' .
                            __('following', 'imagepress') . '
                        </div>
                        <div class="ip-user-dashboard-stat">
                            <span>' . imagepress_kformat(imagepress_count_user_posts_by_type($userid, $imagepress_slug)) . '</span>' .
                            __('uploads', 'imagepress') . '
                        </div>';

                        $out .= '<div class="ip-user-dashboard-stat">
                            <span>' . imagepress_kformat(imagepress_collection_count($userid)) . '</span>' .
                            __('collections', 'imagepress') . '
                        </div>';

                    $out .= '</div>';
                    $out .= '<div class="ip_clear"></div>';

                    $arr = imagepress_get_followers($userid);
                    if ($arr) {
                        $out .= '<div class="cinnamon-followers">';
                            foreach ($arr as $value) {
                                $user = get_user_by('id', $value);
                                $out .= '<a href="' . imagepress_get_profile_uri($value, false) . '">' . get_avatar($value, 40) . '</a> ';
                            }
                            unset($value);
                        $out .= '</div>';
                    }

                    $arr = imagepress_get_following($userid);
                    if ($arr) {
                        $out .= '<div class="cinnamon-followers">';
                            foreach ($arr as $value) {
                                $user = get_user_by('id', $value);
                                $out .= '<a href="' . imagepress_get_profile_uri($value, false) . '">' . get_avatar($value, 40) . '</a> ';
                            }
                            unset($value);
                        $out .= '</div>';
                    }

                    $out .= imagepress_frontend_user_likes($userid);

                $out .= '</div>

                <div class="tab-content" id="account">
                    <table class="form-table">
                        <tr>
                            <th><label for="first-name">' . __('First name', 'imagepress') . '</label></th>
                            <td><input name="first-name" type="text" id="first-name" value="' . get_the_author_meta('first_name', $userid) . '"></td>
                        </tr>
                        <tr>
                            <th><label for="last-name">' . __('Last name', 'imagepress') . '</label></th>
                            <td><input name="last-name" type="text" id="last-name" value="' . get_the_author_meta('last_name', $userid) . '"></td>
                        </tr>
                        <tr>
                            <th><label for="nickname">' . __('Nickname', 'imagepress') . '</label></th>
                            <td><input name="nickname" type="text" id="nickname" value="' . get_the_author_meta('nickname', $userid) . '"></td>
                        </tr>
                        <tr>
                            <th><label for="email">' . __('E-mail *', 'imagepress') . '</label></th>
                            <td><input name="email" type="text" id="email" value="' . get_the_author_meta('user_email', $userid) . '"></td>
                        </tr>
                        <tr>
                            <th><label for="url">' . __('Website', 'imagepress') . '</label></th>
                            <td><input name="url" type="text" id="url" value="' . get_the_author_meta('user_url', $userid) . '"></td>
                        </tr>
                        <tr>
                            <th><label for="pass1">' . __('Password *', 'imagepress') . '</label></th>
                            <td><input name="pass1" type="password" id="pass1"></td>
                        </tr>
                        <tr>
                            <th><label for="pass2">' . __('Repeat password *', 'imagepress') . '</label></th>
                            <td><input name="pass2" type="password" id="pass2"></td>
                        </tr>
                        <tr>
                            <th><label for="description">' . __('About', 'imagepress') . '</label></th>
                            <td><textarea name="description" id="description" rows="4">' . get_the_author_meta('description', $userid) . '</textarea></td>
                        </tr>
                        <tr>
                            <th><label for="facebook">' . __('Facebook profile URL', 'imagepress') . '</label></th>
                            <td><input name="facebook" type="url" id="facebook" value="' . get_the_author_meta('facebook', $userid) . '"></td>
                        </tr>
                        <tr>
                            <th><label for="twitter">' . __('Twitter username', 'imagepress') . '</label></th>
                            <td><input name="twitter" type="text" id="twitter" value="' . get_the_author_meta('twitter', $userid) . '"></td>
                        </tr>
                        <tr><td colspan="2"><hr></td></tr>';

                        if (!is_admin()) {
                            $out .= '<tr>
                                <th>' . __('Cover/avatar preview', 'imagepress') . '</th>
                                <td>';
                                    $hcc = get_the_author_meta('hub_custom_cover', $userid);
                                    $hca = get_the_author_meta('hub_custom_avatar', $userid);
                                    $hcc = wp_get_attachment_url($hcc);
                                    $hca = wp_get_attachment_url($hca);

                                    $out .= '<div class="cinnamon-cover-preview" data-background="' . $hcc . '"><img src="' . $hca . '" alt=""></div>
                                </td>
                            </tr>';
                        }
                        $out .= '<tr>
                            <th><label for="hub_custom_cover">' . __('Profile cover image', 'imagepress') . '</label></th>
                            <td>
                                <input type="file" name="hub_custom_cover" id="hub_custom_cover" value="' . get_the_author_meta('hub_custom_cover', $userid) . '" class="regular-text">
                            </td>
                        </tr>
                        <tr>
                            <th><label for="hub_custom_avatar">' . __('Profile avatar image', 'imagepress') . '</label></th>
                            <td>
                                <input type="file" name="hub_custom_avatar" id="hub_custom_avatar" value="' .  get_the_author_meta('hub_custom_avatar', $userid) . '" class="regular-text">
                                <br><small>' . __('Recommended cover size is 1080x300.', 'imagepress') . '</small>
                                <br><small>' . __('Recommended avatar size is 240x240. If there is no custom avatar, your Gravatar will be used.', 'imagepress') . '</small>
                            </td>
                        </tr>
                        <tr><td colspan="2"><hr></td></tr>
                    </table>
                </div>';

                $out .= '<div class="tab-content" id="collections">
                    <p>
                        <a href="#" class="toggleModal button noir-secondary">' . __('Create new collection', 'imagepress') . '</a>
                        <span class="ip-loadingCollections">' . __('Loading collections...', 'imagepress') . '</span>
                        <span class="ip-loadingCollectionImages">' . __('Loading collection images...', 'imagepress') . '</span>
                        <a href="#" class="imagepress-collections imagepress-float-right button"><i class="fas fa-cog fa-spin"></i></a>
                    </p>
                    <div class="ip-modal">
                        <h2>' . __('Create new collection', 'imagepress') . '</h2>
                        <a href="#" class="close toggleModal">' . __('Close', 'imagepress') . '</a>

                        <input type="hidden" id="collection_author_id" name="collection_author_id" value="' . $userid . '">
                        <p><input type="text" id="collection_title" name="collection_title" placeholder="' . __('Collection title', 'imagepress') . '"></p>
                        <p><label>Make this collection</label> <select id="collection_status"><option value="1">' . __('Public', 'imagepress') . '</option><option value="0">' . __('Private', 'imagepress') . '</option></select></p>
                        <p>
                            <input type="submit" value="' . __('Create', 'imagepress') . '" class="addCollection">
                            <label class="collection-progress">' . __('Creating collection...', 'imagepress') . '</label>
                            <label class="showme">' . __('Collection created!', 'imagepress') . '</label>
                        </p>
                    </div>

                    <div class="collections-display"></div>
                </div>';

                // Image Editor
                // View, delete, reorder
                $out .= '<div class="tab-content" id="editor">
                    <div id="ip-info">' . __('Drag images to reorder them', 'imagepress') . '<br><small>' . __('Click titles to rename images', 'imagepress') . '</small></div>';

                    $args = [
                        'post_type' => imagepress_get_option('ip_slug'),
                        'post_status' => ['publish', 'pending'],
                        'posts_per_page' => '-1',
                        'orderby' => 'menu_order',
                        'order' => 'ASC',
                        'author' => $userid,
                        'cache_results' => false,
                        'no_found_rows' => true
                    ];
                    $posts = get_posts($args);

                    $ip_click_behaviour = imagepress_get_option('ip_click_behaviour');

                    $out .= '<div class="editor-image-manager">';
                        if ($posts) {
                            foreach($posts as $user_image) {
                                $i = $user_image->ID;

                                $post_thumbnail_id = get_post_thumbnail_id($i);
                                $image_attributes = wp_get_attachment_image_src($post_thumbnail_id, 'thumbnail');

                                if ($ip_click_behaviour == 'media')
                                    $ip_image_link = $image_attributes[0];
                                if ($ip_click_behaviour == 'custom')
                                    $ip_image_link = get_permalink($i);

                                $out .= '<div class="editor-image ip_box_' . $i . '" id="listItem_' . $i . '" data-id="' . $i . '">
                                    <div class="editor-image-handle"><i class="fas fa-arrows-alt"></i></div>
                                    <div class="editor-image-thumbnail">
                                        <a href="' . $ip_image_link . '"><img src="' . $image_attributes[0] . '" alt="' . get_the_title($i) . '"></a>
                                    </div>
                                    <input type="text" class="editableImage" id="listImage_' . $i . '" data-image-id="' . $i . '" value="' . get_the_title($i) . '">
                                    <span class="editableImageStatus editableImageStatus_' . $i . '"></span>
                                    <br><small>' . __('in', 'imagepress') . ' ' .  strip_tags(get_the_term_list($i, 'imagepress_image_category', '', ', ', '')) . ' ' . __('on', 'imagepress') . ' ' . get_the_date('Y-m-d H:i', $i) . '</small>
                                    <br><small><a href="' . $ip_image_link . '">' . __('View/Edit', 'imagepress') . '</a> | <span class="clickable-span" data-image-id="' . $i . '">' . __('Delete', 'imagepress') . '</span></small>
                                </div>';
                            }
                        }
                    $out .= '</div>';
                $out .= '</div>

                <!-- Begin do_confirm -->
                <div id="aep_ww"><div id="aep_win"><div id="aep_prompt"></div><input type="button" class="aep_ok" value="OK"><input type="button" class="aep_cancel" value="Cancel"></div></div>
                <!-- End do_confirm -->';

                do_action('edit_user_profile', $current_user);

                $out .= '<hr>
                <table class="form-table">
                    <tr>
                        <td colspan="2">
                            <input name="updateuser" type="submit" class="button" id="updateuser" value="' . __('Update', 'imagepress') . '">';
                            wp_nonce_field('update-user');
                            $out .= '<input name="action" type="hidden" id="action" value="update-user">
                            <a href="' . imagepress_get_profile_uri($userid, false) . '">' . __('View profile', 'imagepress') . '</a>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>';

    if (!is_user_logged_in()) {
        $out = '<p class="warning">' . __('You must be logged in to edit your profile.', 'imagepress') . '</p>';
    }

    return $out;
}







/* CINNAMON CUSTOM PROFILE FIELDS */
function imagepress_save_profile_fields($user_id) {
    if (!current_user_can('edit_user', $user_id))
        return false;

    // awards
    if (current_user_can('manage_options', $user_id)) {
        if (!empty($_POST['award'])) {
            $term = $_POST['award'];
            wp_set_object_terms($user_id, $term, 'award', false);
            clean_object_term_cache($user_id, 'award');
        }
    }
}

function imagepress_hub_gravatar_filter($avatar, $id_or_email, $size) {
    // do not use email for get_avatar(), use ID
    $current_user = wp_get_current_user();

    $image_url = get_user_meta($id_or_email, 'hub_custom_avatar', true);
    $custom_avatar = wp_get_attachment_url($image_url);

    if (!empty($image_url)) {
        return '<img src="' . $custom_avatar . '" class="avatar" width="' . $size . '" height="' . $size . '" alt="' . $current_user->display_name . '">';
    }

    return $avatar;
}

function imagepress_awards() {
    $args = [
        'hide_empty' => false,
        'pad_counts' => true
    ];
    $terms = get_terms('award', $args);

    if (!empty($terms) && !is_wp_error($terms)) {
        foreach ($terms as $term) {
            // get custom FontAwesome
            $t_ID = $term->term_id;
            $term_data = get_option("taxonomy_$t_ID");

            echo '<p>
                <span class="cinnamon-award-list-item" title="' . $term->description . '"> ' .
                $term->name . '</span> <span>' . $term->description . '<br><small>(' . $term->count . ' author(s) received this award)</small></span>
            </p>';
        }
    }
}
