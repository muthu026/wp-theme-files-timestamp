<?php
/**
 * Plugin Name: Wp Theme File Timestamp
 * Plugin URI: 
 * Description: A WordPress plugin that tracks updated theme files.
 * Version: 1.0.0
 * Author: Esakkimuthu
 * Author URI: 
 */


 function theme_file_tracker_install() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'theme_file_tracker';
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
        id int(11) NOT NULL AUTO_INCREMENT,
        file_path varchar(255) NOT NULL,
        modified_date datetime NOT NULL,
        PRIMARY KEY  (id),
        UNIQUE KEY file_path (file_path)
    ) $charset_collate;";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
    add_option( 'theme_file_tracker_db_version', '1.0' );
}
register_activation_hook( __FILE__, 'theme_file_tracker_install' );


function theme_file_tracker() {
global $wpdb;
$theme_dir = get_template_directory();
$updated_files = array();
$previous_dates = $wpdb->get_results("SELECT file_path, modified_date FROM {$wpdb->prefix}theme_file_tracker", ARRAY_A);


foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($theme_dir)) as $file) {
    if ($file->isFile()) {
        $file_path = $file->getPathname();
        $file_modified_date = $file->getMTime();
        $previous_date = isset($previous_dates[$file_path]) ? strtotime($previous_dates[$file_path]) : 0;
        if ($file_modified_date > $previous_date) {
            $updated_files[] = $file_path;
        }
        $wpdb->replace("{$wpdb->prefix}theme_file_tracker", array('file_path' => $file_path, 'modified_date' => date('Y-m-d H:i:s', $file_modified_date)), array('%s', '%s'));
    }
    
}

if (!empty($updated_files)) {
    // Send an email notification or display a message on the dashboard
}

}
add_action('init', 'theme_file_tracker');