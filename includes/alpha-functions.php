<?php
/**
 * ImagePress taxonomy filters
 *
 * Customises taxonomy properties
 *
 * @package ImagePress
 * @subpackage Template
 * @since 7.1.0
 */

add_shortcode('imagepress-loop', 'imagepress_loop');

function ipUriBuilder($sort, $range, $taxonomy, $q = '') {
    if (empty($sort) && !empty($_GET['sort'])) {
        $sort = sanitize_text_field($_GET['sort']);
    }
    if (empty($range) && !empty($_GET['range'])) {
        $range = sanitize_text_field($_GET['range']);
    }
    if ($taxonomy === 'all') {
        $taxonomy = '';
    } else if (empty($taxonomy) && !empty($_GET['t'])) {
        $taxonomy = sanitize_text_field($_GET['t']);
    }
    if (empty($q) && !empty($_GET['q'])) {
        $q = sanitize_text_field($_GET['q']);
    }

    $uriParameters = array(
        'sort' => $sort, 
        'range' => $range,
        't' => $taxonomy,
        'q' => $q
    );

    return strtok($_SERVER['REQUEST_URI'], '?') . '?' . http_build_query($uriParameters);
}

function getImagePressDiscoverFilters() {
    $out = $queryValue = '';

    if (is_tax()) {
        $term = get_query_var('term');
    }

    if (isset($_GET['q'])) {
        $queryValue = sanitize_text_field($_GET['q']);
    }

    $out .= '<div class="poster-filters">
        <div class="ip-sorter-primary" id="ip-sorter-primary">
            <select name="sorter" id="sorter">
                <option value="' . ipUriBuilder('newest', '', '', $queryValue) . '">' . __('Newest', 'imagepress') . '</option>
                <option value="' . ipUriBuilder('oldest', '', '', $queryValue) . '">' . __('Oldest', 'imagepress') . '</option>
                <option value="' . ipUriBuilder('comments', '', '', $queryValue) . '">' . __('Most comments', 'imagepress') . '</option>
                <option value="' . ipUriBuilder('views', '', '', $queryValue) . '">' . __('Most views', 'imagepress') . '</option>
                <option value="' . ipUriBuilder('likes', '', '', $queryValue) . '">' . __('Most liked', 'imagepress') . '</option>
            </select>

            <select name="ranger" id="ranger">
                <option value="' . ipUriBuilder('', 'alltime', '', $queryValue) . '">' . __('All time', 'imagepress') . '</option>
                <option value="' . ipUriBuilder('', 'lastmonth', '', $queryValue) . '">' . __('This month', 'imagepress') . '</option>
                <option value="' . ipUriBuilder('', 'lastweek', '', $queryValue) . '">' . __('This week', 'imagepress') . '</option>
                <option value="' . ipUriBuilder('', 'lastday', '', $queryValue) . '">' . __('Today', 'imagepress') . '</option>
            </select>

            <select name="taxxxer" id="taxxxer">
                <option value="' . ipUriBuilder('', '', 'all', $queryValue) . '">-</a>';
                $terms = get_terms('imagepress_image_category');
                if (!empty($terms) && !is_wp_error($terms)) {
                    foreach ($terms as $term) {
                        if (strtolower($term->name) !== 'featured') {
                            $out .= '<option value="' . ipUriBuilder('', '', $term->slug, $queryValue) . '">' . $term->name . '</option>';
                        }
                    }
                }
            $out .= '</select>

            <input type="text" name="q" id="q" value="' . $queryValue . '" placeholder="' . __('Search...', 'imagepress') . '">

            <div id="ip-sorter-loader" class="ip-sorter-loader"></div>
        </div>
    </div>';

    return $out;
}

function imagepress_loop($atts, $content = null) {
    extract(shortcode_atts(array(
        'category'      => '',
        'count'         => 0,
        'limit'         => 999999,
        'user'          => 0,
        'size'          => '',
        'columns'       => '',
        'pending'       => 'no',
        'sort'          => 'no',
        'filters'       => 'no',
        'type'          => '', // 'random'
        'collection'    => '', // new parameter (will extract all images from a certain collection)
        'collection_id' => '', // new parameter (will extract all images from a certain collection)
        'order'         => '', // only used by profile viewer

        'fieldname'     => '',
        'fieldvalue'    => '',
        'mode' => '',
        'perrow' => 0,
    ), $atts));

    $out = '';
    $ipSlug = (string) get_imagepress_option('ip_slug');

    if ((string) trim($filters) === 'yes') {
        $out .= getImagePressDiscoverFilters();
    }

    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

    if ((int) $count > 0) {
        $ipp = (int) $count;
    } else {
        $ipp = (int) get_imagepress_option('ip_ipp');
    }
    $args1 = array(
        'post_type' => $ipSlug,
        'paged' => $paged,
        'posts_per_page' => $ipp,
        //'author__in' => array($user),
        'post_status' => 'publish',
    );

    // Search query arguments
    if (isset($_GET['q'])) {
        $query = sanitize_text_field($_GET['q']);

        $args1['s'] = (string) $query;
    }

    // some alternatives:
    // https://stackoverflow.com/questions/17455484/query-posts-by-custom-meta-and-current-date
    // https://stackoverflow.com/questions/16994364/get-posts-no-older-than-x-days-wordpress
    if (isset($_GET['t']) && !empty($_GET['t'])) {
        $taxonomy = (string) sanitize_text_field($_GET['t']);
        $tax_query = array(
            array(
                'taxonomy' => 'imagepress_image_category',
                'field' => 'slug',
                'terms' => $taxonomy,
                'include_children' => true,
                'operator' => 'IN',
            )
        );
        $args1['tax_query'] = $tax_query;
    }

    // Check "category" parameter
    if (!empty($category)) {
        $tax_query = array(
            array(
                'taxonomy' => 'imagepress_image_category',
                'field' => 'slug',
                'terms' => $category,
                'include_children' => true,
                'operator' => 'IN',
            ),
        );
        $args1['tax_query'] = $tax_query;
    }

    // Check "user/author" parameter
    if (!empty($user)) {
        $args1['author'] = (int) $user;
    }

    if ((string) $order === 'custom') {
        $args1['orderby'] = 'menu_order';
        $args1['order'] = 'ASC';
    }

    if (!empty($fieldname) && !empty($fieldvalue)) {
        $field_query = array(
            array(
                'key' => $fieldname,
                'value' => $fieldvalue,
            ),
        );
        $args1['meta_query'] = $field_query;
    }


    if (isset($_GET['sort']) || isset($_GET['range'])) {
        $sort = (string) trim($_GET['sort']);
        $range = (string) trim($_GET['range']);

        if ($sort == 'likes') {
            $args1['meta_query'] = array(
                'key' => '_like_count'
            );
            $args1['meta_key'] = '_like_count';
            $args1['orderby'] = 'meta_value_num';
            $args1['order'] = 'DESC';
        } else if ($sort == 'views') {
            $args1['meta_query'] = array(
                'key' => 'post_views_count'
            );
            $args1['meta_key'] = 'post_views_count';
            $args1['orderby'] = 'meta_value_num';
            $args1['order'] = 'DESC';
        } else if ($sort == 'comments') {
            $args1['orderby'] = 'comment_count';
            $args1['orderby'] = 'DESC';
        } else if ($sort == 'newest') {
            $args1['orderby'] = 'date';
            $args1['order'] = 'DESC';
        } else if ($sort == 'oldest') {
            $args1['orderby'] = 'date';
            $args1['order'] = 'ASC';
        } else {
            $args1['orderby'] = 'date';
            $args1['order'] = 'DESC';
        }

        // Range filtering
        if ($range == 'lastmonth') {
            $date_query = array(
                'date_query'    => array(
                    'column'  => 'post_date',
                    'after'   => date('Y-m-d', strtotime('-30 days')) 
                )
            );
            $args1['date_query'] = $date_query;
        } else if($range == 'lastweek') {
            $date_query = array(
                'date_query'    => array(
                    'column'  => 'post_date',
                    'after'   => date('Y-m-d', strtotime('-7 days')) 
                )
            );
            $args1['date_query'] = $date_query;
        } else if($range == 'lastday') {
            $date_query = array(
                'date_query'    => array(
                    'column'  => 'post_date',
                    'after'   => date('Y-m-d', strtotime('-1 days')) 
                )
            );
            $args1['date_query'] = $date_query;
        } else if($range == 'alltime') {
        } else {
            // Sorting defaults to newest posters
            //$query->set('orderby', 'date');
            //$query->set('order', 'DESC');
        }
        // Category filter
        // $query->set( 'cat', '-4' );
    }

    $ip_query = new WP_Query($args1);
    //echo '<pre><code>';
    //print_r($ip_query);
    //print_r($args1);
    //echo '</code></pre>';

    // Get loop options
    $ip_rel_tag = get_imagepress_option('ip_rel_tag');

    $ip_click_behaviour = get_imagepress_option('ip_click_behaviour');
    $get_ip_title_optional = get_imagepress_option('ip_title_optional');
    $get_ip_author_optional = get_imagepress_option('ip_author_optional');
    $get_ip_meta_optional = get_imagepress_option('ip_meta_optional');
    $get_ip_views_optional = get_imagepress_option('ip_views_optional');
    $get_ip_likes_optional = get_imagepress_option('ip_likes_optional');
    $get_ip_comments = get_imagepress_option('ip_comments');

    if ((int) $perrow !== 0) {
        $ip_ipw = $perrow;
    } else {
        $ip_ipw = get_imagepress_option('ip_ipw');
    }

    // Image box appearance
    $ip_box_ui = (string) get_imagepress_option('ip_box_ui');

    $out .= '<div id="ip-boxes" class="ip-box-container ip-box-container-' . $ip_box_ui . '">';
        if ($ip_query->have_posts()) {
            while ($ip_query->have_posts()) {
                $ip_query->the_post();
                $i = get_the_ID();

                $user_info = get_userdata(get_the_author_meta('ID'));
                $post_thumbnail_id = get_post_thumbnail_id($i);

                $image_attributes = wp_get_attachment_image_src($post_thumbnail_id, 'imagepress_pt_std_ps');

                if ($ip_click_behaviour === 'media') {
                    // get attachment source
                    $image_attributes = wp_get_attachment_image_src($post_thumbnail_id, 'full');

                    $ip_image_link = $image_attributes[0];
                } else if ($ip_click_behaviour === 'custom') {
                    $ip_image_link = get_permalink($i);
                }

                // make all "brick" elements optional and active by default
                $ip_title_optional = '';
                if ($get_ip_title_optional == 1) {
                    $ip_title_optional = '<span class="imagetitle">' . get_the_title($i) . '</span>';
                }

                $ip_author_optional = '';
                if ($get_ip_author_optional == 1) {
                    if (get_post_meta($i, 'imagepress_author', true) !== '') {
                        $ip_author_optional = '<span class="name">' . get_post_meta($i, 'imagepress_author', true) . '</span>';
                    } else {
                        // get post author ID
                        $post_author_id = get_post_field('post_author', $i);

                        /**
                        $ip_profile_page = (int) get_imagepress_option('ip_profile_page');
                        $cinnamon_author_slug = (string) get_imagepress_option('cinnamon_author_slug');
                        $ip_author_page = get_permalink($ip_profile_page);

                        $ip_author_optional = '<span class="name"><a href="' . $ip_author_page . '?' . $cinnamon_author_slug . '=' . get_the_author_meta('user_nicename', $post_author_id) . '">' . get_the_author_meta('user_nicename', $post_author_id) . '</a></span>';
                        /**/
                        $ip_author_optional = getImagePressProfileUri($post_author_id);
                    }
                }

                $ip_meta_optional = '';
                if ($get_ip_meta_optional == 1)
                    $ip_meta_optional = '<span class="imagecategory" data-tag="' . strip_tags(get_the_term_list($i, 'imagepress_image_category', '', ', ', '')) . '">' . strip_tags(get_the_term_list($i, 'imagepress_image_category', '', ', ', '')) . '</span>';

                $ip_views_optional = '';
                if ($get_ip_views_optional == 1)
                    $ip_views_optional = '<span class="imageviews">' . ip_getPostViews($i) . '</span> ';

                $ip_comments = '';
                if ($get_ip_comments == 1)
                    $ip_comments = '<span class="imagecomments">' . get_comments_number($i) . '</span> ';

                $ip_likes_optional = '';
                if ($get_ip_likes_optional == 1)
                    $ip_likes_optional = '<span class="imagelikes">' . imagepress_get_like_count($i) . '</span> ';

                if (empty($size)) {
                    $size = get_imagepress_option('ip_image_size');
                    $size = (string) $size;
                }
                $image_attributes = wp_get_attachment_image_src($post_thumbnail_id, $size);

                // <img src="' . $image_attributes[0] . '" alt="' . get_the_title($i) . '">
                $out .= '<div class="ip_box ip_box_' . $i . '" style="width: ' . (100/$ip_ipw) . '%;">
                    <a href="' . $ip_image_link . '" rel="' . $ip_rel_tag . '" data-taxonomy="' . strip_tags(get_the_term_list($i, 'imagepress_image_category', '', ', ', '')) . '" data-src="' . $image_attributes[0] . '"></a>
                    <div class="ip_box_top">' . $ip_title_optional . $ip_author_optional . $ip_meta_optional . '</div>
                    <div class="ip_box_bottom">' . $ip_views_optional . $ip_comments . $ip_likes_optional . '</div>
                </div>';
            }

            // Pagination
            if (function_exists('pagination') && (int) $count == 0) {
                $out .= pagination($ip_query->max_num_pages);
            }
        }
    $out .= '</div>';

    wp_reset_postdata();

    return $out;
}














/*
 * ImagePress numbered pagination
 */
function pagination($pages = '', $range = 2) {
    global $paged;

    $showitems = ($range * 2) + 1;
    $display = '';

    if (empty($paged)) {
        $paged = 1;
    }

    if (1 != $pages) {
        $display .= '<div class="native-pagination">
            <span>' . $paged . '/' . $pages . '</span>';
            if ($paged > 2 && $paged > $range + 1 && $showitems < $pages) {
                $display .= '<a href="' . get_pagenum_link(1) . '">&laquo;</a>';
            }
            if ($paged > 1 && $showitems < $pages) {
                $display .= '<a href="' . get_pagenum_link($paged - 1) . '">&lsaquo;</a>';
            }
 
            for ($i=1; $i <= $pages; $i++) {
                if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems)) {
                    $display .= ($paged == $i) ? '<span class="current">' . $i . '</span>' : '<a href="' . get_pagenum_link($i) . '" class="inactive">' . $i . '</a>';
                }
            }

            if ($paged < $pages && $showitems < $pages) {
                $display .= '<a href="' . get_pagenum_link($paged + 1) . '">&rsaquo;</a>';
            }
            if ($paged < $pages-1 && $paged + $range - 1 < $pages && $showitems < $pages) {
                $display .= '<a href="' . get_pagenum_link($pages) . '">&raquo;</a>';
            }
        $display .= '</div>';
    }

    return $display;
}
