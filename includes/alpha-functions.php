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

function ipUriBuilder($sort, $range, $taxonomy, $query = '') {
    if (empty($sort) && !empty($_GET['sort'])) {
        $sort = sanitize_text_field($_GET['sort']);
    }
    if (empty($range) && !empty($_GET['range'])) {
        $range = sanitize_text_field($_GET['range']);
    }
    if ((string) $taxonomy === 'all') {
        $taxonomy = '';
    } else if (empty($taxonomy) && !empty($_GET['t'])) {
        $taxonomy = sanitize_text_field($_GET['t']);
    }

    $uriParameters = [
        'sort' => $sort,
        'range' => $range,
        't' => $taxonomy
    ];

    // Get position where '/page' text starts
    $pos = strpos($_SERVER['REQUEST_URI'], '/page');
    // Remove string from specific position
    $finalUri = substr($_SERVER['REQUEST_URI'], 0, $pos);

    return strtok($finalUri, '?') . '?' . http_build_query($uriParameters);
}

function getImagePressDiscoverFilters() {
    $out = $queryValue = '';

    if (is_tax()) {
        $term = get_query_var('term');
    }

    $out .= '<div class="poster-filters">
        <div class="ip-sorter-primary" id="ip-sorter-primary">
            <select name="sorter" id="sorter">
                <option value="' . ipUriBuilder('newest', '', '', $queryValue) . '">' . __('Newest', 'imagepress') . '</option>
                <option value="' . ipUriBuilder('oldest', '', '', $queryValue) . '">' . __('Oldest', 'imagepress') . '</option>
                <option value="' . ipUriBuilder('comments', '', '', $queryValue) . '">' . __('Most comments', 'imagepress') . '</option>';

                if ((int) get_imagepress_option('ip_enable_views') === 1) {
                    $out .= '<option value="' . ipUriBuilder('views', '', '', $queryValue) . '">' . __('Most views', 'imagepress') . '</option>';
                }

                $out .= '<option value="' . ipUriBuilder('likes', '', '', $queryValue) . '">' . __('Most liked', 'imagepress') . '</option>
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

            <div id="ip-sorter-loader" class="ip-sorter-loader"></div>
        </div>
    </div>';

    return $out;
}

function imagepress_loop($atts) {
    extract(shortcode_atts([
        'category'      => '',
        'count'         => 0,
        'limit'         => 999999,
        'user'          => 0,
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
    ], $atts));

    $out = '';
    $ipSlug = (string) get_imagepress_option('ip_slug');

    if ((string) trim($filters) === 'yes') {
        $out .= getImagePressDiscoverFilters();
    }

    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

    // Get images per page
    $ipp = (int) get_imagepress_option('ip_ipp');
    if ((int) $count > 0) {
        $ipp = (int) $count;
    }

    $args1 = [
        'post_type' => $ipSlug,
        'paged' => $paged,
        'posts_per_page' => $ipp,
        'post_status' => 'publish',
    ];

    // Search query arguments
    if (isset($_GET['q']) && !empty($_GET['q'])) {
        $query = sanitize_text_field($_GET['q']);

        $args1['s'] = (string) $query;
    }

    if (isset($_GET['t']) && !empty($_GET['t'])) {
        $taxonomy = (string) sanitize_text_field($_GET['t']);
        $tax_query = [
            [
                'taxonomy' => 'imagepress_image_category',
                'field' => 'slug',
                'terms' => $taxonomy,
                'include_children' => true,
                'operator' => 'IN',
            ]
        ];
        $args1['tax_query'] = $tax_query;
    }

    // Check "category" parameter
    if (!empty($category)) {
        $tax_query = [
            [
                'taxonomy' => 'imagepress_image_category',
                'field' => 'slug',
                'terms' => $category,
                'include_children' => true,
                'operator' => 'IN',
            ]
        ];
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
        $field_query = [
            [
                'key' => $fieldname,
                'value' => $fieldvalue
            ]
        ];
        $args1['meta_query'] = $field_query;
    }

    // Most liked images
    if ((string) $sort !== 'no') {
        $args1['meta_query'] = [
            'key' => '_like_count'
        ];
        $args1['meta_key'] = '_like_count';
        $args1['orderby'] = 'meta_value_num';
        $args1['order'] = 'DESC';
    }

    if (isset($_GET['sort']) || isset($_GET['range'])) {
        $sort = (string) trim($_GET['sort']);
        $range = (string) trim($_GET['range']);

        if ($sort === 'likes') {
            $args1['meta_query'] = [
                'key' => '_like_count'
            ];
            $args1['meta_key'] = '_like_count';
            $args1['orderby'] = 'meta_value_num';
            $args1['order'] = 'DESC';
        } else if ($sort === 'views') {
            $args1['meta_query'] = [
                'key' => 'post_views_count'
            ];
            $args1['meta_key'] = 'post_views_count';
            $args1['orderby'] = 'meta_value_num';
            $args1['order'] = 'DESC';
        } else if ($sort === 'comments') {
            $args1['orderby'] = 'comment_count';
            $args1['orderby'] = 'DESC';
        } else if ($sort === 'newest') {
            $args1['orderby'] = 'date';
            $args1['order'] = 'DESC';
        } else if ($sort === 'oldest') {
            $args1['orderby'] = 'date';
            $args1['order'] = 'ASC';
        } else {
            $args1['orderby'] = 'date';
            $args1['order'] = 'DESC';
        }

        // Range filtering
        if ($range === 'lastmonth') {
            $date_query = [
                'date_query' => [
                    'column' => 'post_date',
                    'after' => date('Y-m-d', strtotime('-30 days'))
                ]
            ];
            $args1['date_query'] = $date_query;
        } else if ($range === 'lastweek') {
            $date_query = [
                'date_query' => [
                    'column' => 'post_date',
                    'after' => date('Y-m-d', strtotime('-7 days'))
                ]
            ];
            $args1['date_query'] = $date_query;
        } else if ($range === 'lastday') {
            $date_query = [
                'date_query' => [
                    'column' => 'post_date',
                    'after' => date('Y-m-d', strtotime('-1 days'))
                ]
            ];
            $args1['date_query'] = $date_query;
        }
    }

    $ip_query = new WP_Query($args1);

    // Image box appearance
    $ip_box_ui = (string) get_imagepress_option('ip_box_ui');

    $out .= '<div id="ip-boxes" class="ip-box-container ip-box-container-' . $ip_box_ui . '">';
        if ($ip_query->have_posts()) {
            while ($ip_query->have_posts()) {
                $ip_query->the_post();

                $out .= ipRenderGridElement(get_the_ID());
            }
        }
    $out .= '</div><div class="ip-clear"></div>';

    // Pagination
    if (function_exists('pagination') && (int) $count === 0) {
        $out .= ip_pagination($ip_query->max_num_pages);
    }

    wp_reset_postdata();

    return $out;
}














/*
 * ImagePress numbered pagination
 */
function ip_pagination($pages = '', $range = 2) {
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
        $display .= '</div><div class="ip-clear"></div>';
    }

    return $display;
}
