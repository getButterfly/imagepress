<?php
$parse_uri = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
require_once $parse_uri[0] . 'wp-load.php';

global $wpdb;

foreach($_GET['listItem'] as $position => $item) {
    $wpdb->query("UPDATE `" . $wpdb->prefix . "posts` SET `menu_order` = $position WHERE `ID` = $item");
}
echo '<p><i class="fa fa-check"></i> ' . __('Image order changed successfully!', 'imagepress') . '</p>';
