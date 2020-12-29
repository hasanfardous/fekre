<?php

// Create the db table to store applicants data
if ( ! function_exists( 'jam_applicant_submissions_create_db_table' ) ) {
	function jam_applicant_submissions_create_db_table() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();

		// SQL Query
		$sql = "CREATE TABLE `{$wpdb->base_prefix}applicant_submissions` (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		first_name tinytext NOT NULL,
		last_name tinytext NOT NULL,
		present_address text NOT NULL,
		email_address tinytext NOT NULL,
		mobile_no tinytext NOT NULL,
		post_name tinytext NOT NULL,
		cv_path varchar(55) DEFAULT '' NOT NULL,
		apply_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		PRIMARY KEY (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		
	}
}