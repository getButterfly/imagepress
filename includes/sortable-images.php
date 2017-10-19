<?php
$parse_uri = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
require_once $parse_uri[0] . 'wp-load.php';

global $wpdb;

foreach ($_GET['listItem'] as $position => $item) {
    $wpdb->query($wpdb->prepare("UPDATE `" . $wpdb->prefix . "posts` SET `menu_order` = %d WHERE `ID` = %d", $position, $item));
}
echo '<p><i class="fa fa-check"></i> ' . esc_attr__('Image order changed successfully!', 'imagepress') . '</p>';
