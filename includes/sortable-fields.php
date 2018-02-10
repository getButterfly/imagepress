<?php
$parse_uri = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
require_once $parse_uri[0] . 'wp-load.php';

global $wpdb;

foreach ($_GET['listItem'] as $position => $item) {
    $wpdb->query($wpdb->prepare("UPDATE `" . $wpdb->prefix . "ip_fields` SET `field_order` = %d WHERE `field_id` = %d", $position, $item));
}
echo '<p><i class="fas fa-check"></i> ' . esc_attr__('Field order changed successfully!', 'imagepress') . '</p>';
