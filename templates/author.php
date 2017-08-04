<?php
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
//require_once $parse_uri[0] . 'wp-load.php';
require_once '../../wp-load.php';

$url = "//$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$parseUrl = parse_url($url);
$ext_detect = trim($parseUrl['path']);

if ($ext_detect == '/') {
    echo '<div id="hub-loading"></div>';
    echo do_shortcode('[cinnamon-profile-blank]');
} else {
    echo do_shortcode('[cinnamon-profile]');
}
