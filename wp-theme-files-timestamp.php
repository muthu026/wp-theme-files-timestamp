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