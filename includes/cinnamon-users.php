<?php
function cinnamon_count_user_posts_by_type($userid, $post_type = 'post') {
    global $wpdb;

    $ip_slug = get_imagepress_option('ip_slug');

    $where = get_posts_by_author_sql($ip_slug, true, $userid);
    $count = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts $where");

    return apply_filters('get_usernumposts', $count, $userid);
}

function cinnamon_PostViews($id, $count = true) {
    $axCount = get_user_meta($id, 'ax_post_views', true);
    if ($axCount == '')
        $axCount = 0;

    if ($count == true) {
        $axCount++;
        update_user_meta($id, 'ax_post_views', $axCount);
    }

    return $axCount;
}

function cinnamon_author_base() {
    global $wp_rewrite;

    $cinnamon_author_slug = get_imagepress_option('cinnamon_author_slug');

    $wp_rewrite->author_base = $cinnamon_author_slug;
}

function cinnamon_get_related_author_posts($author) {
    $ip_slug = get_imagepress_option('ip_slug');
    $authors_posts = get_posts(array(
        'author' => $author,
        'posts_per_page' => 9,
        'post_type' => $ip_slug
    ));

    $output = '';
    if($authors_posts) {
        $output .= '
        <div class="cinnamon-grid"><ul>';
            foreach($authors_posts as $authors_post) {
                $output .= '<li><a href="' . get_permalink($authors_post->ID) . '">' . get_the_post_thumbnail($authors_post->ID, 'thumbnail') . '</a></li>';
            }
        $output .= '</ul></div>';
    }

    return $output;
}

function cinnamon_extra_contact_info($contactmethods) {
    unset($contactmethods['aim']);
    unset($contactmethods['yim']);
    unset($contactmethods['jabber']);

    $contactmethods['facebook'] = 'Facebook';
    $contactmethods['twitter'] = 'Twitter';
    $contactmethods['googleplus'] = 'Google+';

    return $contactmethods;
}



/* CINNAMON CARD SHORTCODE */
function cinnamon_card($atts) {
    extract(shortcode_atts(array(
        'author' => '',
        'count' => 10,
        'sort' => 0
    ), $atts));

    $ip_slug = get_imagepress_option('ip_slug');

    $display = '<div style="clear: both;"></div>
    <div id="author-cards">';

    $number = $count;
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $offset = ($paged - 1) * $number;
    $users = get_users();
    $query = get_users('&offset='.$offset.'&number='.$number);
    $total_users = count($users);
    $total_query = count($query);
    $total_pages = intval($total_users / $number) + 1;

    $display .= '<ul class="list">';
        foreach ($query as $q) {
            $author = $q->ID;

            $display .= '<li class="cinnamon-card">';
                $cardArguments = array(
                    'author' => $author,
                    'posts_per_page' => get_imagepress_option('ip_cards_per_author'),
                    'post_type' => $ip_slug,
                );
                $authors_posts = get_posts($cardArguments);
                if ($authors_posts) {
                    $display .= '<div class="mosaicflow">';
                        foreach($authors_posts as $authors_post) {
                            $display .= '<div><a href="' . get_permalink($authors_post->ID) . '">' . get_the_post_thumbnail($authors_post->ID, get_imagepress_option('ip_image_size')) . '</a></div>';
                        }
                    $display .= '</div>';
                }
                $display .= '<div class="avatar-holder"><a href="' . getImagePressProfileUri($author, false) . '">' . get_avatar($author, 104) . '</a></div>';

                if (get_the_author_meta('user_title', $author) == 'Verified')
                    $verified = ' <span class="teal hint hint--right" data-hint="' . get_imagepress_option('cms_verified_profile') . '"><i class="fa fa-check-square"></i></span>';
                else
                    $verified = '';

                $hub_user_info = get_userdata($author);
                $hub_location = get_the_author_meta('hub_location', $author);

                $display .= '<h3>
                    <a href="' . getImagePressProfileUri($author, false) . '" class="name">';
                        if (!empty($hub_user_info->first_name)) {
                            $display .= $hub_user_info->first_name . ' ' . $hub_user_info->last_name;
                        } else {
                            $display .= $hub_user_info->display_name;
                        }
                    $display .= '</a>' . $verified . ' ';
                $display .= '</h3>';

                if (!empty($hub_location))
                    $display .= '<div class="location-holder"><small><i class="fa fa-map-marker teal"></i> <span class="location">' . get_the_author_meta('hub_location', $author) . '</span></small></div>';

                $display .= '<div class="cinnamon-stats">
                    <div class="cinnamon-meta"><span class="views">' . kformat(cinnamon_PostViews($author, false)) . '</span><br><small>' . __('views', 'imagepress') . '</small></div>
                    <div class="cinnamon-meta"><span class="followers">' . kformat(pwuf_get_follower_count($author)) . '</span><br><small>' . __('followers', 'imagepress') . '</small></div>
                    <div class="cinnamon-meta"><span class="uploads">' . kformat(cinnamon_count_user_posts_by_type($author, $ip_slug)) . '</span><br><small>' . __('uploads', 'imagepress') . '</small></div>
                </div>';
            $display .= '</li>';
        }
    $display .= '</ul>';

    if ($total_users > $total_query) {
        $display .= '<div id="pagination" class="native-pagination">';
        $display .= '<span class="pages">Pages:</span>';
        $current_page = max(1, get_query_var('paged'));
        $display .= paginate_links(array(
            'base' => get_pagenum_link(1) . '%_%',
            'format' => 'page/%#%/',
            'current' => $current_page,
            'total' => $total_pages,
            'prev_next'    => false,
            'type'         => 'list',
        ));
        $display .= '</div>';
    }

    $display .= '</div><div style="clear: both;"></div>';

    return $display;
}



/* CINNAMON PROFILE SHORTCODE */
function cinnamon_profile($atts) {
    extract(shortcode_atts(array(
        'author' => '',
        'username' => '',
    ), $atts));

    global $wpdb;

    $cinnamon_author_slug = (string) get_imagepress_option('cinnamon_author_slug');

    if (!isset($_GET[$cinnamon_author_slug])) {
        return;
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

    $ip_slug = get_imagepress_option('ip_slug');
    $hub_user_info = get_userdata((int) $author);

    $display = '';

    $hub_googleplus = ''; $hub_facebook = ''; $hub_twitter = ''; $hub_user_url = '';
    if ($hub_user_info->googleplus != '')
        $hub_googleplus = ' <a href="' . $hub_user_info->googleplus . '" target="_blank"><i class="fa fa-google-plus-square"></i></a>';
    if ($hub_user_info->facebook != '')
        $hub_facebook = ' <a href="' . $hub_user_info->facebook . '" target="_blank"><i class="fa fa-facebook-square"></i></a>';
    if ($hub_user_info->twitter != '')
        $hub_twitter = ' <a href="https://twitter.com/' . $hub_user_info->twitter . '" target="_blank"><i class="fa fa-twitter-square"></i></a>';

    $hca = get_the_author_meta('hub_custom_cover', $author);
    $hca = wp_get_attachment_url($hca);
    if (!isset($hca) || empty($hca))
        $hca = IP_PLUGIN_URL . '/img/coverimage.png';

    $logged_in_user = wp_get_current_user();

    $display .= '<div class="profile-hub-container">';
        $hub_field = get_the_author_meta('hub_field', $author);
        $hub_employer = get_the_author_meta('hub_employer', $author);
        $hub_location = get_the_author_meta('hub_location', $author);

        if (get_imagepress_option('cinnamon_fancy_header') === 'yes') {
            $display .= '<div class="cinnamon-cover" style="background: url(' . $hca . ') no-repeat center center; background-size: cover;"><div class="cinnamon-opaque"></div>';

                $display .= '<div class="cinnamon-avatar"><div class="cinnamon-user">' . get_avatar($author, 120) . '';
                    if (is_user_logged_in() && $username != $logged_in_user->user_login)
                        $display .= '<div class="imagepress-follow">' . do_shortcode('[follow_links follow_id="' . $author . '"]') . '</div>';
                    $display .= '</div>';

                    if (get_the_author_meta('user_title', $author) == 'Verified')
                        $verified = ' <span class="teal hint hint--right" data-hint="' . get_imagepress_option('cms_verified_profile') . '"><i class="fa fa-check-square"></i></span>';
                    else
                        $verified = '';

                    // get custom URL
                    $hubdomain = preg_replace('/^www\./', '', $_SERVER['HTTP_HOST']);
                    $hubuser = get_user_by('id', $author);
                    $hubuser = sanitize_title($hubuser->user_login);
                    $hub_name = $hub_user_info->first_name . ' ' . $hub_user_info->last_name;
                    if (empty($hub_user_info->first_name) && empty($hub_user_info->last_name))
                        $hub_name = $hubuser;

                    if ($hub_user_info->user_url != '')
                        $hub_user_url = ' <a href="' . $hub_user_info->user_url . '" rel="external" target="_blank"><i class="fa fa-link"></i></a>';

                    $display .= '<div>
                        <div class="ph-nametag">
                            ' . $hub_name . $verified;
                            if(is_user_logged_in() && $username == $logged_in_user->user_login) {
                                $display .= ' <small><a href="' . get_imagepress_option('cinnamon_edit_page') . '">' . get_imagepress_option('cinnamon_edit_label') . '</a></small>';
                            }
                        $display .= '</div>
                        <div class="ph-locationtag">';
                            if(!empty($hub_location))
                                $display .= '<b>' . __('Location', 'imagepress') . '</b> ' . $hub_location;
                            if(!empty($hub_field))
                                $display .= '<br><b>' . __('Field', 'imagepress') . '</b> ' . $hub_field;
                            $display .= '<br><b>' . __('Connect', 'imagepress') . '</b> ' . $hub_facebook . $hub_twitter . $hub_googleplus . $hub_user_url;

                            if(get_the_author_meta('hub_status', $author) == 1)
                                $display .= ' <a href="mailto:' . get_the_author_meta('email', $author) . '"><i class="fa fa-envelope"></i></a>';
                        $display .= '</div>
                    </div>';
                $display .= '</div>';
            $display .= '</div>';
        } else {
            $display .= '<div class="cinnamon-cover-blank">';
                $display .= '<p>' . get_avatar($author, 60) . '</p>';
                if(is_user_logged_in() && $username != $logged_in_user->user_login)
                    $display .= '<div class="imagepress-follow">' . do_shortcode('[follow_links follow_id="' . $author . '"]') . '</div>';

                if(get_the_author_meta('user_title', $author) == 'Verified')
                    $verified = ' <span class="teal hint hint--right" data-hint="' . get_imagepress_option('cms_verified_profile') . '"><i class="fa fa-check-square"></i></span>';
                else
                    $verified = '';

                // get custom URL
                $hubdomain = preg_replace('/^www\./', '', $_SERVER['HTTP_HOST']);
                $hubuser = get_user_by('id', $author);
                $hubuser = sanitize_title($hubuser->user_login);
                $hub_name = $hub_user_info->first_name . ' ' . $hub_user_info->last_name;
                if(empty($hub_user_info->first_name) && empty($hub_user_info->last_name))
                    $hub_name = $hubuser;

                if($hub_user_info->user_url != '')
                    $hub_user_url = ' <a href="' . $hub_user_info->user_url . '" rel="external" target="_blank"><i class="fa fa-link"></i></a>';

                $display .= '<h2>' . $hub_name . $verified . '</h2>
                <p>';
                    if(!empty($hub_field))
                        $display .= $hub_field . ' | ';
                    if(!empty($hub_location))
                        $display .= $hub_location . ' | ';
                    $display .= $hub_facebook . $hub_twitter . $hub_googleplus . $hub_user_url;

                    if(is_user_logged_in() && $username == $logged_in_user->user_login)
                        $display .= ' | <a href="' . get_imagepress_option('cinnamon_edit_page') . '"><i class="fa fa-pencil-square-o"></i> ' . get_imagepress_option('cinnamon_edit_label') . '</a>';
                $display .= '</p>';
            $display .= '</div>';
        }

        $display .= '<div class="ip-tab">
            <ul class="ip-tabs active">';
                if ((int) get_imagepress_option('cinnamon_show_uploads') === 1)
                    $display .= '<li><a href="#">' . __('Uploads', 'imagepress') . '</a></li>';

                if(get_imagepress_option('cinnamon_show_about') == 1)
                    $display .= '<li><a href="#">' . __('About', 'imagepress') . '</a></li>';

                if(get_imagepress_option('cinnamon_show_followers') == 1)
                    $display .= '<li><a href="#">' . __('Followers', 'imagepress') . '</a></li>';
                if(get_imagepress_option('cinnamon_show_following') == 1)
                    $display .= '<li><a href="#">' . __('Following', 'imagepress') . '</a></li>';
                if(get_imagepress_option('cinnamon_show_likes') == 1)
                    $display .= '<li><a href="#">' . __('Loved', 'imagepress') . ' ' . get_imagepress_option('ip_slug') . 's</a></li>';
                if(get_imagepress_option('cinnamon_show_awards') == 1)
                    $display .= '<li><a href="#">' . __('Awards', 'imagepress') . '</a></li>';

                if ((int) get_imagepress_option('ip_mod_collections') === 1) {
                    if ((int) get_imagepress_option('cinnamon_show_collections') === 1) {
                        $display .= '<li><a href="#">' . __('Collections', 'imagepress') . '</a></li>';
                    }
                }

                $display .= '<li class="cinnamon-stats-column">';
                    // Cinnamon Stats
                    $display .= '<div class="cinnamon-stats">';
                        $display .= '<div class="cinnamon-meta"><b>' . kformat(cinnamon_PostViews($author)) . '</b> ' . __('profile views', 'imagepress') . '</div>';
                        $display .= '<div class="cinnamon-meta"><b>' . kformat(pwuf_get_follower_count($author)) . '</b> ' . __('followers', 'imagepress') . '</div>';
                        $display .= '<div class="cinnamon-meta"><b>' . kformat(cinnamon_count_user_posts_by_type($author, $ip_slug)) . '</b> ' . __('uploads', 'imagepress') . '</div>';
                    $display .= '</div>';
                $display .= '</li>';
            $display .= '</ul>
            <div class="tab_content">';
                if ((int) get_imagepress_option('cinnamon_show_uploads') === 1) {
                    $display .= '<div class="ip-tabs-item ip-profile" data-ipw="' . get_imagepress_option('ip_ipw') . '" style="display: block;">' .
                        do_shortcode('[imagepress-loop user="' . $author . '" order="custom" count="999999"]') .
                    '</div>
                    <div class="ip-clear"></div>
                    <div class="thin-ui-button ip-clear" id="ipProfileShowMore">' . get_imagepress_option('ip_load_more_label') . '</div>';
                }

                if(get_imagepress_option('cinnamon_show_about') == 1) {
                    $display .= '<div class="ip-tabs-item" style="display: none;">';
                        if(!empty($hub_user_info->description))
                            $display .= wpautop($hub_user_info->description);
                        $display .= '<br>';
                        if(!empty($hub_employer))
                            $display .= '<p><b>' . __('Employer', 'imagepress') . ':</b><br>' . $hub_employer . '</p>';
                    $display .= '</div>';
                }

                if(get_imagepress_option('cinnamon_show_followers') == 1) {
                    $display .= '<div class="ip-tabs-item" style="display: none;">';
                        $arr = pwuf_get_followers($author);
                        if($arr) {
                            $display .= '<div class="cinnamon-followers">';
                                foreach($arr as $value) {
                                    $user = get_user_by('id', $value);
                                    $display .= '<a href="' . getImagePressProfileUri($value, false) . '">' . get_avatar($value, 40) . '</a> ';
                                }
                                unset($value);
                            $display .= '</div>';
                        }
                    $display .= '</div>';
                }

                if(get_imagepress_option('cinnamon_show_following') == 1) {
                    $display .= '<div class="ip-tabs-item" style="display: none;">';
                        $arr = pwuf_get_following($author);
                        if($arr) {
                            $display .= '<div class="cinnamon-followers">';
                                foreach($arr as $value) {
                                    $user = get_user_by('id', $value);
                                    $display .= '<a href="' . getImagePressProfileUri($value, false) . '">' . get_avatar($value, 40) . '</a> ';
                                }
                                unset($value);
                            $display .= '</div>';
                        }
                    $display .= '</div>';
                }

                if(get_imagepress_option('cinnamon_show_likes') == 1) {
                    $display .= '<div class="ip-tabs-item" style="display: none;">';
                        $display .= ipFrontEndUserLikes($author);
                    $display .= '</div>';
                }

                if(get_imagepress_option('cinnamon_show_awards') == 1) {
                    $display .= '<div class="ip-tabs-item" style="display: none;">';
                        $award_terms = wp_get_object_terms($author, 'award');
                        if(!empty($award_terms)) {
                            if(!is_wp_error($award_terms)) {
                                foreach($award_terms as $term) {
                                    // get custom FontAwesome
                                    $t_ID = $term->term_id;
                                    $term_data = get_option("taxonomy_$t_ID");

                                    $display .= '<span class="cinnamon-award-list-item" title="' . $term->description . '">';
                                        if(isset($term_data['img']))
                                            $display .= '<i class="fa ' . $term_data['img'] . '"></i> ';
                                        else
                                            $display .= '<i class="fa fa-trophy"></i> ';
                                    $display .= $term->name . '</span>';
                                }
                            }
                        }
                    $display .= '</div>';
                }

                if ((int) get_imagepress_option('ip_mod_collections') === 1) {
                    if ((int) get_imagepress_option('cinnamon_show_collections') === 1) {
                        $display .= '<div class="ip-tabs-item" style="display: none;">';
                            $display .= ip_collections_display_public($author);
                        $display .= '</div>';
                    }
                }

            $display .= '</div>
        </div>';





        $display .= '<div style="clear: both;"></div>';


    $display .= '</div>';

    return $display;
}

function cinnamon_profile_edit($atts) {
    extract(shortcode_atts(array('author' => ''), $atts));

    global $wpdb;

    $current_user = wp_get_current_user();
    $userid = $current_user->ID;

    $error = array();
    $out = '';

    if('POST' == $_SERVER['REQUEST_METHOD'] && !empty($_POST['action']) && $_POST['action'] == 'update-user') {
        if(!empty($_POST['pass1']) && !empty($_POST['pass2'])) {
            if($_POST['pass1'] == $_POST['pass2'])
                wp_update_user(array('ID' => $userid, 'user_pass' => esc_attr($_POST['pass1'])));
            else
                $error[] = __('The passwords you entered do not match. Your password was not updated.', 'imagepress');
        }

        if(!empty($_POST['url']))
            wp_update_user(array('ID' => $userid, 'user_url' => esc_url($_POST['url'])));
        if(!empty($_POST['email'])) {
            if(!is_email(esc_attr($_POST['email'])))
                $error[] = __('The email you entered is not valid. Please try again.', 'imagepress');
            elseif(email_exists(esc_attr($_POST['email'])) != $userid)
                $error[] = __('This email is already used by another user. Try a different one.', 'imagepress');
            else {
                wp_update_user(array('ID' => $userid, 'user_email' => esc_attr($_POST['email'])));
            }
        }

        if(!empty($_POST['first-name']))
            update_user_meta($userid, 'first_name', esc_attr($_POST['first-name']));
        if(!empty($_POST['last-name']))
            update_user_meta($userid, 'last_name', esc_attr($_POST['last-name']));

        if(!empty($_POST['nickname'])) {
            update_user_meta($userid, 'nickname', esc_attr($_POST['nickname']));
            $wpdb->update($wpdb->users, array('display_name' => $_POST['nickname']), array('ID' => $userid), null, null);
        }

        if(!empty($_POST['description']))
            update_user_meta($userid, 'description', esc_attr($_POST['description']));

        if(!empty($_POST['facebook']))
            update_user_meta($userid, 'facebook', esc_attr($_POST['facebook']));
        if(!empty($_POST['twitter']))
            update_user_meta($userid, 'twitter', esc_attr($_POST['twitter']));
        if(!empty($_POST['googleplus']))
            update_user_meta($userid, 'googleplus', esc_attr($_POST['googleplus']));

        // avatar and cover upload
        if($_FILES) {
            require_once(ABSPATH . "wp-admin" . '/includes/image.php');
            require_once(ABSPATH . "wp-admin" . '/includes/file.php');
            require_once(ABSPATH . "wp-admin" . '/includes/media.php');

            foreach($_FILES as $file => $array) {
                if(!empty($_FILES[$file]['name'])) {
                    $file_id = media_handle_upload($file, 0);
                    if($file_id > 0) {
                        update_user_meta($userid, $file, $file_id);
                    }
                }
            }
        }
        //

        if(count($error) == 0) {
            do_action('edit_user_profile_update', $userid);
            $out .= '<p class="message noir-success">' . __('Profile updated successfully!', 'imagepress') . '</p>';
        }
    }

    $out .= '<div id="post-' . get_the_ID() . '">
        <div class="entry-content entry cinnamon">';
            if(count($error) > 0) {
                $out .= '<p class="error">' . implode('<br>', $error) . '</p>';
            }

            $out .= '<form method="post" id="adduser" action="" enctype="multipart/form-data" class="thin-ui-form">
                <div class="ip-tab">
                    <ul class="ip-tabs active">
                        <li class="current"><a href="#">' . __('Summary', 'imagepress') . '</a></li>
                        <li><a href="#">' . get_imagepress_option('cinnamon_pt_account') . '</a></li>
                        <li><a href="#">' . get_imagepress_option('cinnamon_pt_social') . '</a></li>
                        <li><a href="#">' . get_imagepress_option('cinnamon_pt_author') . '</a></li>
                        <li><a href="#">' . get_imagepress_option('cinnamon_pt_profile') . '</a></li>';
                        if(get_imagepress_option('ip_mod_collections') == 1) {
                            $out .= '<li><a href="#" class="imagepress-collections">' . get_imagepress_option('cinnamon_pt_collections') . '</a></li>';
                        }
                        $out .= '<li><a href="#">' . get_imagepress_option('cinnamon_pt_images') . '</a></li>';
                    $out .= '</ul>
                    <div class="tab_content">
                        <div class="ip-tabs-item" style="display: block;">
                            <h3>' . __('Statistics', 'imagepress') . '</h3>';
                            $ip_slug = get_imagepress_option('ip_slug');

                            // get global upload limit
                            $ip_global_upload_limit = get_imagepress_option('ip_global_upload_limit');
                            if (empty($ip_global_upload_limit)) {
                                $ip_global_upload_limit = 999999;
                            }
                            $ip_global_upload_limit_message = get_imagepress_option('ip_global_upload_limit_message');

                            // get current user uploads
                            $user_uploads = cinnamon_count_user_posts_by_type($userid, $ip_slug);

                            // get upload limit for current user
                            $ip_upload_limit = $ip_global_upload_limit;

                            $ip_user_upload_limit = get_the_author_meta('ip_upload_limit', $userid);
                            if (!empty($ip_user_upload_limit)) {
                                $ip_upload_limit = $ip_user_upload_limit;
                            }

                            if ($user_uploads >= $ip_upload_limit) {
                                $out .= '<p>' . $ip_global_upload_limit_message . ' (' . $user_uploads . '/' . $ip_upload_limit . ')</p>';
                            }

                            $out .= '<div class="ip-user-dashboard">
                                <div class="ip-user-dashboard-stat">
                                    <span>' . kformat(cinnamon_PostViews($userid, false)) . '</span>' .
                                    __('total profile views', 'imagepress') . '
                                </div>
                                <div class="ip-user-dashboard-stat">
                                    <span>' . kformat(pwuf_get_follower_count($userid)) . '</span>' .
                                    __('followers', 'imagepress') . '
                                </div>
                                <div class="ip-user-dashboard-stat">
                                    <span>' . kformat(pwuf_get_following_count($userid)) . '</span>' .
                                    __('following', 'imagepress') . '
                                </div>
                                <div class="ip-user-dashboard-stat">
                                    <span>' . kformat(cinnamon_count_user_posts_by_type($userid, $ip_slug)) . '<small>/' . $ip_upload_limit . '</small></span>' .
                                    __('uploads', 'imagepress') . '
                                </div>';

                                if(get_imagepress_option('ip_mod_collections') == 1) {
                                    $out .= '<div class="ip-user-dashboard-stat">
                                        <span>' . kformat(ip_collection_count($userid)) . '</span>' .
                                        __('collections', 'imagepress') . '
                                    </div>';
                                }

                                $out .= '<div class="ip_clear"></div>
                            </div>
                        </div>

                        <div class="ip-tabs-item" style="display: none;">
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
                                    <td><textarea name="description" id="description" rows="4" style="width: 100%;">' . get_the_author_meta('description', $userid) . '</textarea></td>
                                </tr>
                            </table>
                        </div>
                        <div class="ip-tabs-item" style="display: none;">
                            <table class="form-table">
                                <tr>
                                    <th><label for="facebook"><i class="fa fa-facebook-square"></i> ' . __('Facebook profile URL', 'imagepress') . '</label></th>
                                    <td><input name="facebook" type="url" id="facebook" value="' . get_the_author_meta('facebook', $userid) . '"></td>
                                </tr>
                                <tr>
                                    <th><label for="twitter"><i class="fa fa-twitter-square"></i> ' . __('Twitter username', 'imagepress') . '</label></th>
                                    <td><input name="twitter" type="text" id="twitter" value="' . get_the_author_meta('twitter', $userid) . '"></td>
                                </tr>
                                <tr>
                                    <th><label for="googleplus"><i class="fa fa-google-plus-square"></i> ' . __('Google+ profile URL', 'imagepress') . '</label></th>
                                    <td><input name="googleplus" type="url" id="googleplus" value="' . get_the_author_meta('googleplus', $userid) . '"></td>
                                </tr>
                            </table>
                        </div>
                        <div class="ip-tabs-item" style="display: none;">
                            <table class="form-table">
                                <tr>
                                    <th><label for="hub_location">' . __('Location', 'imagepress') . '</label></th>
                                    <td>
                                        <input type="text" name="hub_location" id="hub_location" value="' .  esc_attr(get_the_author_meta('hub_location', $userid)) . '" class="regular-text">
                                    </td>
                                </tr>
                                <tr>
                                    <th><label for="hub_employer">' . __('Employer', 'imagepress') . '</label></th>
                                    <td>
                                        <input type="text" name="hub_employer" id="hub_employer" value="' .  esc_attr(get_the_author_meta('hub_employer', $userid)) . '" class="regular-text">
                                    </td>
                                </tr>
                                <tr>
                                    <th><label for="hub_field">' . __('Occupational field', 'imagepress') . '</label></th>
                                    <td>
                                        <input type="text" name="hub_field" id="hub_field" value="' . esc_attr(get_the_author_meta('hub_field', $userid)) . '" class="regular-text">
                                    </td>
                                </tr>
                                <tr>
                                    <th><label for="hub_status">' . __('Status', 'imagepress') . '</label></th>
                                    <td>
                                        <select name="hub_status" id="hub_status">
                                            <option value="1" ' . ((get_the_author_meta('hub_status', $userid) == 1) ? 'selected' : '') . '>' . __('Available for hire', 'imagepress') . '</option>
                                            <option value="0" ' . ((get_the_author_meta('hub_status', $userid) == 0) ? 'selected' : '') . '>' . __('Not available for hire', 'imagepress') . '</option>
                                        </select>
                                        <br><small>' . __('Being available for hire will show an additional email icon on your profile, emails will be sent to the email address you have registered with the site.', 'imagepress') . '</small>
                                    </td>
                                </tr>
                                <tr><td colspan="2"><hr></td></tr>
                            </table>
                        </div>
                        <div class="ip-tabs-item" style="display: none;">
                            <table class="form-table">';
                                if(!is_admin()) {
                                    $out .= '<tr>
                                        <th>' . __('Cover/avatar preview', 'imagepress') . '</th>
                                        <td>';
                                            $hcc = get_the_author_meta('hub_custom_cover', $userid);
                                            $hca = get_the_author_meta('hub_custom_avatar', $userid);
                                            $hcc = wp_get_attachment_url($hcc);
                                            $hca = wp_get_attachment_url($hca);

                                            $out .= '<div class="cinnamon-cover-preview" style="background: url(' . $hcc . ') no-repeat center center; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover;"><img src="' . $hca . '" alt=""></div>
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
                        if(get_imagepress_option('ip_mod_collections') == 1) {
                            $out .= '<div class="ip-tabs-item" style="display: none;">
                                <p>
                                    <a href="#" class="toggleModal button noir-secondary"><i class="fa fa-plus"></i> ' . __('Create new collection', 'imagepress') . '</a>
                                    <span class="ip-loadingCollections"><i class="fa fa-cog fa-spin"></i> ' . __('Loading collections...', 'imagepress') . '</span>
                                    <span class="ip-loadingCollectionImages"><i class="fa fa-cog fa-spin"></i> ' . __('Loading collection images...', 'imagepress') . '</span>
                                    <a href="#" class="imagepress-collections imagepress-float-right button"><i class="fa fa-refresh"></i></a>
                                </p>
                                <div class="modal">
                                    <h2>' . __('Create new collection', 'imagepress') . '</h2>
                                    <a href="#" class="close toggleModal"><i class="fa fa-times"></i> ' . __('Close', 'imagepress') . '</a>

                                    <input type="hidden" id="collection_author_id" name="collection_author_id" value="' . $userid . '">
                                    <p><input type="text" id="collection_title" name="collection_title" placeholder="' . __('Collection title', 'imagepress') . '"></p>
                                    <p><label>Make this collection</label> <select id="collection_status"><option value="1">' . __('Public', 'imagepress') . '</option><option value="0">' . __('Private', 'imagepress') . '</option></select></p>
                                    <p>
                                        <input type="submit" value="' . __('Create', 'imagepress') . '" class="addCollection">
                                        <label class="collection-progress"><i class="fa fa-cog fa-spin"></i></label>
                                        <label class="showme"> <i class="fa fa-check"></i> ' . __('Collection created!', 'imagepress') . '</label>
                                    </p>
                                </div>

                                <div class="collections-display"></div>
                            </div>';
                        }
                        // Image Editor
                        // View, delete, reorder
                        $out .= '<div class="ip-tabs-item" style="display: none;">';

                            $out .= '<div id="ip-info">' . __('Drag images to reorder them', 'imagepress') . '<br><small>' . __('Click titles to rename images', 'imagepress') . '</small></div>';

                            $args = array(
                                'post_type' 				=> get_imagepress_option('ip_slug'),
                                'post_status' 				=> array('publish', 'pending'),
                                'posts_per_page' 			=> '-1',
                                'orderby' 					=> 'menu_order',
                                'order' 					=> 'ASC',
                                'author' 					=> $userid,
                                'cache_results'             => false,
                                'no_found_rows'             => true,
                            );
                            $posts = get_posts($args);

                            $ip_click_behaviour = get_imagepress_option('ip_click_behaviour');

                            $out .= '<div class="editor-image-manager">';
                                if($posts) {
                                    foreach($posts as $user_image) {
                                        $i = $user_image->ID;

                                        $post_thumbnail_id = get_post_thumbnail_id($i);
                                        $image_attributes = wp_get_attachment_image_src($post_thumbnail_id, 'thumbnail');

                                        if($ip_click_behaviour == 'media')
                                            $ip_image_link = $image_attributes[0];
                                        if($ip_click_behaviour == 'custom')
                                            $ip_image_link = get_permalink($i);

                                        $out .= '<div class="editor-image ip_box_' . $i . '" id="listItem_' . $i . '">';
                                        $out .= '<div class="editor-image-thumbnail">
                                                <span class="editor-image-handle"><span class="dashicons dashicons-move"></span></span>
                                                <a href="' . $ip_image_link . '"><img src="' . $image_attributes[0] . '" alt="' . get_the_title($i) . '"></a>
                                            </div>
                                            <div class="editor-image-tools">
                                                <a href="#" class="editor-image-delete" data-image-id="' . $i . '"><i class="fa fa-trash" aria-hidden="true"></i> ' . __('Delete', 'imagepress') . '</a>
                                            </div>
                                            <input type="text" class="editableImage" id="listImage_' . $i . '" data-image-id="' . $i . '" value="' . get_the_title($i) . '">
                                            <span class="editableImageStatus editableImageStatus_' . $i . '"></span>
                                            <br><small>' . __('in', 'imagepress') . ' ' .  strip_tags(get_the_term_list($i, 'imagepress_image_category', '', ', ', '')) . ' ' . __('on', 'imagepress') . ' ' . get_the_date('Y-m-d H:i', $i) . '</small>
                                            <br><a href="' . $ip_image_link . '">' . __('View/Edit', 'imagepress') . '</a>';
                                        $out .= '</div>';
                                    }
                                }
                            $out .= '</div>';
                        $out .= '</div>';
                    $out .= '</div>
                </div>';

                do_action('edit_user_profile', $current_user);

                $out .= '<hr>
                <table class="form-table">
                    <tr>
                        <td colspan="2">
                            <input name="updateuser" type="submit" class="button" id="updateuser" value="' . __('Update', 'imagepress') . '">';
                            wp_nonce_field('update-user');
                            $out .= '<input name="action" type="hidden" id="action" value="update-user">
                            <i class="fa fa-share-square"></i> <a href="' . getImagePressProfileUri($userid, false) . '">' . __('View and share your profile', 'imagepress') . '</a>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>';

    if(!is_user_logged_in()) {
        $out = '<p class="warning">' . __('You must be logged in to edit your profile.', 'imagepress') . '</p>';
    }

    return $out;
}







/* CINNAMON CUSTOM PROFILE FIELDS */
function save_cinnamon_profile_fields($user_id) {
    if(!current_user_can('edit_user', $user_id))
        return false;

    if(!empty($_POST['hub_location'])) {
        update_user_meta($user_id, 'hub_location', $_POST['hub_location']);
    }

    if(!empty($_POST['hub_employer'])) {
        update_user_meta($user_id, 'hub_employer', $_POST['hub_employer']);
    }

    if(!empty($_POST['hub_field'])) {
        update_user_meta($user_id, 'hub_field', $_POST['hub_field']);
    }

    if(!empty($_POST['hub_status'])) {
        update_user_meta($user_id, 'hub_status', $_POST['hub_status']);
    }

    // awards
    if (current_user_can('manage_options', $user_id)) {
        if (!empty($_POST['user_title'])) {
            update_user_meta($user_id, 'user_title', $_POST['user_title']);
        }
        if (!empty($_POST['ip_upload_limit'])) {
            update_user_meta($user_id, 'ip_upload_limit', $_POST['ip_upload_limit']);
        }

        if (!empty($_POST['award'])) {
            $term = $_POST['award'];
            wp_set_object_terms($user_id, $term, 'award', false);
            clean_object_term_cache($user_id, 'award');
        }
    }
}

function hub_gravatar_filter($avatar, $id_or_email, $size) {
    // do not use email for get_avatar(), use ID
    $current_user = wp_get_current_user();

    $image_url = get_user_meta($id_or_email, 'hub_custom_avatar', true);
    $custom_avatar = wp_get_attachment_url($image_url);

    if(!empty($image_url)) {
        return '<img src="' . $custom_avatar . '" class="avatar" width="' . $size . '" height="' . $size . '" alt="' . $current_user->display_name . '">';
    }

    return $avatar;
}

function cinnamon_awards() {
    $args = array(
        'hide_empty' => false,
        'pad_counts' => true
    );
    $terms = get_terms('award', $args);

    if(!empty($terms) && !is_wp_error($terms)) {
        foreach($terms as $term) {
            // get custom FontAwesome
            $t_ID = $term->term_id;
            $term_data = get_option("taxonomy_$t_ID");

            echo '<p><span class="cinnamon-award-list-item" title="' . $term->description . '">';
                if(isset($term_data['img']))
                    echo '<i class="fa ' . $term_data['img'] . '"></i> ';
                else
                    echo '<i class="fa fa-trophy"></i> ';
                echo $term->name . '</span> <span>' . $term->description . '<br><small>(' . $term->count . ' author(s) received this award)</small></span>';
            echo '</p>';
        }
    }
}
