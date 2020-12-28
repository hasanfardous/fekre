<?php

/**
 * Plugin Name:       Job App Manager
 * Plugin URI:        https://github.com/hasanfardous/job-app-manager
 * Description:       A simple job app manager for wordpress plugin. The plugin provides a shortcode for displaying a job application form where people can apply with their details. All submissions are available in wordpress backend.
 * Version:           1.0.0
 * Author:            Md Hasan Fardous
 * Author URI:        https://me.hasanfardous.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       job-app-manager
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'JOB_APP_MANAGER_VERSION', '1.0.0' );

function jam_load_textdomain() {
	load_plugin_textdomain( 'job-app-manager', false, dirname( __FILE__ ) . "/languages" );
}

add_action( "plugins_loaded", "jam_load_textdomain" );

// Enqueue Front-end scripts
add_action( 'wp_enqueue_scripts', 'jam_enqueue_scripts', 99 );
function jam_enqueue_scripts() {
	wp_enqueue_style( 'jam-styles', plugins_url( 'assets/css/styles.css', __FILE__ ) );
	wp_enqueue_script( 'jam-script', plugins_url( 'assets/js/applicants-data.js', __FILE__ ), array( 'jquery' ), '1.0', true );
	wp_localize_script(
		'jam-script', 
		'jam_datas', 
		array(
			'ajax_url' => admin_url( 'admin-ajax.php' )
		) 
	);
}

/**
 * Enqueue admin scripts
 */
function jam_admin_enqueue_scripts( $hook ) {
    if ( 'index.php' != $hook ) {
        return;
    }
    wp_enqueue_style( 'jam_admin_styles', plugin_dir_url( __FILE__ ) . 'includes/admin/assets/css/styles.css' );
}
add_action( 'admin_enqueue_scripts', 'jam_admin_enqueue_scripts' );

/**
 * Including plugin files
 */
require plugin_dir_path( __FILE__ ) . 'includes/shortcode.php';
require plugin_dir_path( __FILE__ ) . 'includes/form-handling.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-all-submissions.php';
require plugin_dir_path( __FILE__ ) . 'includes/admin/create-db-table.php';
require plugin_dir_path( __FILE__ ) . 'includes/admin/dashboard-widget.php';
require plugin_dir_path( __FILE__ ) . 'includes/admin/admin-menu-page.php';

/**
 * The code that runs during plugin activation.
 */
register_activation_hook( __FILE__, 'jam_create_table' );
function jam_create_table() {
	// Saving our plugin current version
	add_option( "job_app_manager_version", JOB_APP_MANAGER_VERSION );
	
	// Making the table
	applicant_submissions_create_table();
}
