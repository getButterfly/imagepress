<?php
/*
 * ImagePress Module: Collections
 */

function addCollection() {
    global $wpdb;

    $collection_author_ID = intval($_POST['collection_author_id']);
    $collection_title = stripslashes($_POST['collection_title']);
    $collection_title_slug = sanitize_title($_POST['collection_title']);
    $collection_status = intval($_POST['collection_status']);

    $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "ip_collections (collection_title, collection_title_slug, collection_status, collection_author_ID) VALUES ('%s', '%s', %d, %d)", $collection_title, $collection_title_slug, $collection_status, $collection_author_ID));
    die();
}
function editCollectionTitle() {
    global $wpdb;

    $collection_ID = intval($_POST['collection_id']);
    $collection_title = stripslashes($_POST['collection_title']);
    $collection_title_slug = sanitize_title($_POST['collection_title']);

    $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ip_collections SET collection_title = '%s', collection_title_slug = '%s' WHERE collection_ID = %d", $collection_title, $collection_title_slug, $collection_ID));
    die();
}
function editCollectionStatus() {
    global $wpdb;

    $collection_ID = intval($_POST['collection_id']);
    $collection_status = intval($_POST['collection_status']);

    $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ip_collections SET collection_status = '%s' WHERE collection_ID = %d", $collection_status, $collection_ID));
    die();
}
function deleteCollection() {
    global $wpdb;

    $collection_ID = intval($_POST['collection_id']);

    $wpdb->query($wpdb->prepare("DELETE FROM " . $wpdb->prefix . "ip_collections WHERE collection_ID = %d", $collection_ID));
    $wpdb->query($wpdb->prepare("DELETE FROM " . $wpdb->prefix . "ip_collectionmeta WHERE image_collection_ID = %d", $collection_ID));
    die();
}
function deleteCollectionImage() {
    global $wpdb;

    $image_ID = intval($_POST['image_id']);

    $wpdb->query($wpdb->prepare("DELETE FROM " . $wpdb->prefix . "ip_collectionmeta WHERE image_ID = %d", $image_ID));
    die();
}

add_action('wp_ajax_addCollection', 'addCollection');
add_action('wp_ajax_editCollectionTitle', 'editCollectionTitle');
add_action('wp_ajax_editCollectionStatus', 'editCollectionStatus');
add_action('wp_ajax_deleteCollection', 'deleteCollection');
add_action('wp_ajax_deleteCollectionImage', 'deleteCollectionImage');

add_action('wp_ajax_ip_collection_display', 'ip_collection_display');
add_action('wp_ajax_ip_collections_display', 'ip_collections_display');

function ip_collection_display() {
    $collection_ID = intval($_POST['collection_id']);

    echo do_shortcode('[imagepress-collection collection="1" collection_id="' . $collection_ID . '"]');
    die();
}

function ip_collection_count($author) {
    global $wpdb;

    $result = $wpdb->get_results("SELECT collection_ID FROM " . $wpdb->prefix . "ip_collections WHERE collection_author_ID = '" . $author . "'", ARRAY_A);
    $count = (int) $wpdb->num_rows;

    return $count;
}

function ip_collections_display() {
    global $wpdb;

    $result = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_collections WHERE collection_author_ID = '" . get_current_user_id() . "'", ARRAY_A);

    echo '<div class="the">';
    foreach($result as $collection) {
        echo '<div class="ip_collections_edit ipc' . $collection['collection_ID'] . '" data-collection-edit="' . $collection['collection_ID'] . '">';
            $postslist = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_collectionmeta WHERE image_collection_ID = '" . $collection['collection_ID'] . "' AND image_collection_author_ID = '" . get_current_user_id() . "' LIMIT 4", ARRAY_A);
            $postslistcount = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_collectionmeta WHERE image_collection_ID = '" . $collection['collection_ID'] . "' AND image_collection_author_ID = '" . get_current_user_id() . "'", ARRAY_A);
            echo '<div class="ip_collection_box">';
                foreach($postslist as $collectable) {
                    echo get_the_post_thumbnail($collectable['image_ID'], 'imagepress_ls_std');
                }
            echo '</div>';

            echo '<div class="ip_collections_overlay">' . (($collection['collection_status'] == 0) ? '<svg class="lnr lnr-lock"><use xlink:href="#lnr-lock"></use></svg>' : '') . ' ' . count($postslistcount) . '</div>';

            echo '<div class="collection_details">';
                echo '<h3 class="collection-title" data-collection-id="' . $collection['collection_ID'] . '"><a href="#" class="editCollection" data-collection-id="' . $collection['collection_ID'] . '">' . $collection['collection_title'] . '</a></h3>';

                echo '<div><a href="#" class="changeCollection button noir-secondary" data-collection-id="' . $collection['collection_ID'] . '">' . esc_html__('Edit', 'imagepress') . '</a></div>';
            echo '</div>';

            echo '<div class="collection_details_edit cde' . $collection['collection_ID'] . '">
                <h3>' . esc_html__('Edit collection', 'imagepress') . '</h3>
                <p><label>' . esc_html__('Title', 'imagepress') . '</label><input class="collection-title ct' . $collection['collection_ID'] . '" type="text" data-collection-id="' . $collection['collection_ID'] . '" value="' . $collection['collection_title'] . '"><p>
                <p><label>' . esc_html__('Visibility', 'imagepress') . '</label><select class="collection-status cs' . $collection['collection_ID'] . '" data-collection-id="' . $collection['collection_ID'] . '">';
                    $selected = ($collection['collection_status'] == 0) ? 'selected' : '';
                    echo '<option value="1" ' . $selected . '>' . esc_html__('Public', 'imagepress') . '</option>';
                    echo '<option value="0" ' . $selected . '>' . esc_html__('Private', 'imagepress') . '</option>';
                echo '</select></p>';

                $ipCollectionsPageId = get_imagepress_option('ip_collections_page');
                echo '<p><label>' . esc_html__('Share your collection', 'imagepress') . '</label><input type="url" value="' . get_permalink($ipCollectionsPageId) . '?collection=' . (int) $collection['collection_ID'] . '" readonly></p>';

                echo '<a href="#" class="saveCollection button noir-secondary" data-collection-id="' . $collection['collection_ID'] . '"><svg class="lnr lnr-checkmark-circle"><use xlink:href="#lnr-checkmark-circle"></use></svg></a>';
                echo '<a href="#" class="closeCollectionEdit button noir-secondary" data-collection-id="' . $collection['collection_ID'] . '">' . esc_html__('Close', 'imagepress') . '</a>';
                echo '<a href="#" class="deleteCollection button" data-collection-id="' . $collection['collection_ID'] . '"><svg class="lnr lnr-trash"><use xlink:href="#lnr-trash"></use></svg></a>';
            echo '</div>';
        echo '</div>';
    }
    echo '</div><div style="clear:both;"></div>';

    die();
}
function ip_collections_display_public($author_ID) {
    global $wpdb;

    $result = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_collections WHERE collection_status = 1 AND collection_author_ID = '" . $author_ID . "'", ARRAY_A);

    $out = '<div class="the">';
    foreach($result as $collection) {
        $out .= '<div class="ip_collections_edit ipc' . $collection['collection_ID'] . '" data-collection-edit="' . $collection['collection_ID'] . '">';
            $postslist = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_collectionmeta WHERE image_collection_ID = '" . $collection['collection_ID'] . "' AND image_collection_author_ID = '" . $author_ID . "' LIMIT 4", ARRAY_A);
            $postslistcount = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "ip_collectionmeta WHERE image_collection_ID = '" . $collection['collection_ID'] . "' AND image_collection_author_ID = '" . $author_ID . "'", ARRAY_A);
            $out .= '<div class="ip_collection_box">';
                foreach($postslist as $collectable) {
                    $out .= get_the_post_thumbnail($collectable['image_ID'], 'imagepress_ls_std');
                }
            $out .= '</div>';

            $out .= '<div class="ip_collections_overlay">' . count($postslistcount) . '</div>';

            $out .= '<div class="collection_details">';
                $ipCollectionsPageId = get_imagepress_option('ip_collections_page');
                $out .= '<h3><a href="' . get_permalink($ipCollectionsPageId) . '?collection=' . $collection['collection_ID'] . '/">' . $collection['collection_title'] . '</a></h3>';
                $out .= '<div>' . esc_html__('By', 'imagepress') . ' ' . getImagePressProfileUri($collection['collection_author_ID']) . '</div>';
            $out .= '</div>';
        $out .= '</div>';
    }
    $out .= '</div><div style="clear:both;"></div>';

    return $out;
}
function ip_collections_display_custom($atts) {
    extract(shortcode_atts(array(
        'mode' => 'random', // random, latest
        'count' => 1,
    ), $atts));

    global $wpdb;
    $collectionCount = 0;

    if ($mode == 'random')
        $mode = 'RAND()';

    $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ip_collections WHERE collection_status = %d ORDER BY $mode", 1), ARRAY_A);

    $out = '<div class="the">';
    foreach($result as $collection) {
        $postslistcount = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ip_collectionmeta WHERE image_collection_ID = %d", $collection['collection_ID']), ARRAY_A);

        if(count($postslistcount) >= 1) {
            if($collectionCount < $count) {
                $out .= '<div class="ip_collections_edit ipc' . $collection['collection_ID'] . '" data-collection-edit="' . $collection['collection_ID'] . '">';
                    $postslist = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ip_collectionmeta WHERE image_collection_ID = %d LIMIT 4", $collection['collection_ID']), ARRAY_A);

                    $out .= '<div class="ip_collection_box">';
                        foreach($postslist as $collectable) {
                            $out .= get_the_post_thumbnail($collectable['image_ID'], 'imagepress_ls_std');
                        }
                    $out .= '</div>';

                    $out .= '<div class="ip_collections_overlay">' . count($postslistcount) . '</div>';

                    $out .= '<div class="collection_details">';
                        $ipCollectionsPageId = get_imagepress_option('ip_collections_page');
                        $out .= '<h3><a href="' . get_permalink($ipCollectionsPageId) . '?collection=' . (int) $collection['collection_ID'] . '">' . $collection['collection_title'] . '</a></h3>';
                        $out .= '<div>' . esc_html__('By', 'imagepress') . ' ' . getImagePressProfileUri($collection['collection_author_ID']) . '</div>';
                    $out .= '</div>';
                $out .= '</div>';
            }
            ++$collectionCount;
        }
    }
    $out .= '</div><div style="clear:both;"></div>';

    return $out;
}



// FRONT END BUTTON
function ip_frontend_add_collection($ip_id) {
    if (isset($_POST['collectme'])) {
        global $wpdb, $current_user;

        $ip_collections = intval($_POST['ip_collections']);

        $current_user = wp_get_current_user();
        $ipCollectionAuthorId = $current_user->ID;

        if (!empty($_POST['ip_collections_new'])) {
            $ip_collections_new = sanitize_text_field($_POST['ip_collections_new']);
            $ip_collection_status = intval($_POST['collection_status']);

            $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "ip_collections (collection_title, collection_status, collection_author_ID) VALUES (%s, %d, %d)", $ip_collections_new, $ip_collection_status, $ipCollectionAuthorId));
            $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "ip_collectionmeta (image_ID, image_collection_ID, image_collection_author_ID) VALUES (%d, %d, %d)", $ip_id, $wpdb->insert_id, $ipCollectionAuthorId));
            $ipc = $wpdb->insert_id;
        } else {
            $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "ip_collectionmeta (image_ID, image_collection_ID, image_collection_author_ID) VALUES (%d, %d, %d)", $ip_id, $ip_collections, $ipCollectionAuthorId));
            $ipc = $ip_collections;
        }

        // add notification
        $collection_time = current_time('mysql', true);
        $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "notifications (ID, userID, postID, postKeyID, actionType, actionIcon, actionTime) VALUES (null, %d, %d, %d, 'collected', '', %s)", $ipCollectionAuthorId, $ip_id, $ipc, $collection_time));
    }
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        ?>
        <a href="#" class="toggleFrontEndModal toggleFrontEndModalButton thin-ui-button"><?php echo esc_html__('Add to collection', 'imagepress'); ?></a> <?php if (isset($_POST['collectme'])) { echo ' <svg class="lnr lnr-checkmark-circle"><use xlink:href="#lnr-checkmark-circle"></use></svg>'; } ?>

        <div class="frontEndModal ui">
            <h2><?php echo esc_html__('Add to collection', 'imagepress'); ?></h2>
            <a href="#" class="close toggleFrontEndModal"><?php echo esc_html__('Close', 'imagepress'); ?></a>

            <form method="post" class="thin-ui-form">
                <input type="hidden" id="collection_author_id" name="collection_author_id" value="<?php echo $current_user->ID; ?>">

                <p>
                    <?php
                    global $wpdb;

                    $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ip_collections WHERE collection_author_ID = %d", get_current_user_id()), ARRAY_A);

                    echo '<select name="ip_collections">
                        <option value="">' . esc_html__('Choose a collection...', 'imagepress') . '</option>';
                        foreach($result as $collection) {
                            $disabled = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ip_collectionmeta WHERE image_ID = %d AND image_collection_ID = %d", get_the_ID(), $collection['collection_ID']), ARRAY_A);

                            echo '<option value="' . $collection['collection_ID'] . '"';
                            if ($disabled && count($disabled) > 0)
                                echo ' disabled';
                            echo '>' . $collection['collection_title'];
                            echo '</option>';
                        }
                    echo '</select>';
                    ?>
                </p>
                <p><?php echo esc_html__('or', 'imagepress'); ?></p>
                <p>
                    <input type="text" name="ip_collections_new" placeholder="<?php echo esc_html__('Create new collection...', 'imagepress'); ?>">
                    <select name="collection_status" id="collection_status">
                        <option value="1"><?php echo esc_html__('Public', 'imagepress'); ?></option>
                        <option value="0"><?php echo esc_html__('Private', 'imagepress'); ?></option>
                    </select>
                </p>
                <p>
                    <input type="submit" name="collectme" value="<?php echo esc_html__('Add', 'imagepress'); ?>">
                    <label class="collection-progress"><svg class="lnr lnr-sync"><use xlink:href="#lnr-sync"></use></svg></label>
                    <label class="showme"><?php echo esc_html__('Collection created!', 'imagepress'); ?></label>
                </p>
            </form>
        </div>
        <?php
    }
}

function ip_frontend_view_image_collection($ip_id) {
    ?>
    <div class="textwidget">
        <?php
        global $wpdb;
        $ipCollectionsPageId = (int) get_imagepress_option('ip_collections_page');

        $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ip_collectionmeta WHERE image_ID = %d", $ip_id), ARRAY_A);

        foreach($result as $collection) {
            $which = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ip_collections WHERE collection_status = 1 AND collection_ID = %d", $collection['image_collection_ID']), ARRAY_A);
            if(!empty($which['collection_title'])) {
                $featured = $wpdb->get_row($wpdb->prepare("SELECT image_ID FROM " . $wpdb->prefix . "ip_collectionmeta WHERE image_collection_ID = %d ORDER BY RAND()", $collection['image_collection_ID']), ARRAY_A);
                echo '<div class="ip-featured-collection">';
                    echo get_the_post_thumbnail($featured['image_ID'], 'thumbnail');
                    echo '<div class="ip-featured-collection-meta"><a href="' . get_permalink($ipCollectionsPageId) . '?collection=' . (int) $which['collection_ID'] . '">' . $which['collection_title'] . '</a><br><small>' . esc_html__('by', 'imagepress') . ' ' . getImagePressProfileUri($which['collection_author_ID']) . '</small></div>';
                    echo '<div class="ip_clear"></div>';
                echo '</div>';
            }
        }
        ?>

    </div>
    <?php
}



function imagepress_collection($atts) {
    extract(shortcode_atts(array(
        'count'         => 0,
        'limit'         => 999999,
        'columns'       => '',
        'type'          => '', // 'random'
        'collection'    => '', // new parameter (will extract all images from a certain collection)
        'collection_id' => '', // new parameter (will extract all images from a certain collection)
        'order'         => '', // only used by profile viewer
    ), $atts));

    global $wpdb;

    $ip_order_asc_desc = 'DESC';
    $ip_order = empty($type) ? 'date' : 'rand';

    $collectedArray = [];

    if (!isset($_GET['collection']) || empty($_GET['collection'])) {
        return;
    }

    $collection_page = (int) sanitize_text_field($_GET['collection']);

    $collectionables = $wpdb->get_results($wpdb->prepare("SELECT image_ID, image_collection_ID FROM " . $wpdb->prefix . "ip_collectionmeta WHERE image_collection_ID = %d", $collection_page), ARRAY_A);

    foreach ($collectionables as $collectable) {
        $collectedArray[] = $collectable['image_ID'];
    }

    $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ip_collections SET collection_views = collection_views + 1 WHERE collection_ID = %d", $collection_page));

    $collection_row = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ip_collections WHERE collection_ID = %d", $collection_page), ARRAY_A);

    $out = '<div class="ip-template-collection-meta">';
        $last_image_row = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ip_collectionmeta WHERE image_collection_ID = %d ORDER BY image_meta_ID DESC LIMIT 1", $collection_row['collection_ID']), ARRAY_A);

        $out .= '<div class="imagepress-float-right">' . $collection_row['collection_views'] . ' ' . esc_html__('views', 'imagepress') . ' | ' . count($collectionables) . ' ' . esc_html__('images', 'imagepress') . '</div>';
        $out .= '<div class="imagepress-float-left"><a href="' . get_permalink($last_image_row['image_ID']) . '">' . get_the_post_thumbnail($last_image_row['image_ID'], 'thumbnail') . '</a></div>';
        $out .= '<div class="tcm-title">' . $collection_row['collection_title'] . '</div>';
        $out .= getImagePressProfileUri($collection_row['collection_author_ID']);
        $out .= '<div class="ipclear"></div>';
    $out .= '</div>';

    $hmc = count($collectionables);

    if ($hmc == 0 or empty($hmc)) {
        $out .= '<p>' . esc_html__('This collection is empty.', 'imagepress') . '</p>';

        return $out;
    }

    $args = array(
        'post_type'                 => get_imagepress_option('ip_slug'),
        'post_status'               => array('publish'),
        'posts_per_page'            => $limit,
        'orderby'                   => $ip_order,
        'order'                     => $ip_order_asc_desc,
        'post__in'                  => $collectedArray,
        'fields'                    => 'ids',
        'no_found_rows'             => true
    );

    $posts = get_posts($args);

    if ($posts) {
        // Image box appearance
        $ip_box_ui = (string) get_imagepress_option('ip_box_ui');

        $out .= '<div id="ip-boxes" class="ip-box-container ip-box-container-' . $ip_box_ui . '">';
            foreach ($posts as $user_image) {
                // Check if post has a featured image. If not, something has gone wrong and remove it.
                if (has_post_thumbnail($user_image)) { // $user_image->ID
                    // image ID

                    $out .= ipRenderGridElement($user_image);

                    /**
                    $logged_in_user = wp_get_current_user();
                    if ($collection_row['collection_author_ID'] == $logged_in_user->ID) {
                        $out .= '<div class="ip_box_bottom"><a href="#" class="deleteCollectionImage" data-image-id="' . $i . '">Remove</a></div>';
                    }
                    /**/
                }
            }
            // end loop

        $out .= '</div><div style="clear: both;"></div>';
        if ($count == 0) {
            $out .= '<ul class="pagination"></ul>';
        }
        $out .= '<div class="ip_clear"></div>';

        return $out;
    } else {
        $out .= '<div class="imagepress-not-found">' . esc_html__('No images found!', 'imagepress') . '</div>';
        return $out;
    }
}
