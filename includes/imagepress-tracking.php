<?php
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

if (!defined('PRESSTRENDS_URL')) {
    define('PRESSTRENDS_URL', 'https://www.presstrends.org/collector/');
}


// Scheduled Action Hook
function ipTracking( ) {
    global $wpdb;

    $theme = wp_get_theme();
    $plugin = get_plugin_data(IP_PLUGIN_PATH . '/imagepress.php');

    $trackingArray = array(
        // Theme details
        'theme_name' => $theme->get('Name'),
        'theme_uri' => $theme->get('ThemeURI'),
        'theme_version' => $theme->get('Version'),
        'theme_author' => $theme->get('Author'),
        'theme_author_uri' => $theme->get('AuthorURI'),
        'theme_textdomain' => $theme->get('TextDomain'),

        // Plugin details
        'plugin_name' => $plugin['Name'],
        'plugin_uri' => $plugin['PluginURI'],
        'plugin_version' => $plugin['Version'],
        'plugin_author' => $plugin['AuthorName'],
        'plugin_author_uri' => $plugin['AuthorURI'],

        // Environment details
        'site_url' => site_url(),
        'site_name' => get_bloginfo('name'),
        'site_version' => get_bloginfo('version'),
        'site_users' => count(get_users()),
        'site_lang' => get_locale(),
        'is_multisite' => (is_multisite() ? 1 : 0),
        'php_version' => PHP_VERSION,
        'sql_version' => $wpdb->db_version(),
        'memory_limit' => WP_MEMORY_LIMIT,
        'admin_email' => get_option('admin_email'),
    );

    $response = wp_remote_post(esc_url_raw(PRESSTRENDS_URL), array(
        'method' => 'POST',
        'timeout' => 30,
        'redirection' => 3,
        'blocking' => false,
        'compress' => true,
        'sslverify' => true,
        'body' => $trackingArray,
    ));
}

// Custom Cron Recurrences
function imagepress_tracking_recurrence($schedules) {
    $schedules['monthly'] = array(
        'display' => __('Once Monthly', 'imagepress'),
        'interval' => 2635200,
    );

    return $schedules;
}
add_filter('cron_schedules', 'imagepress_tracking_recurrence');

// Schedule Cron Job Event
function imagepress_tracking() {
    if (!wp_next_scheduled('ipTracking')) {
        wp_schedule_event(time(), 'monthly', 'ipTracking');
    }
}
add_action('wp', 'imagepress_tracking');
