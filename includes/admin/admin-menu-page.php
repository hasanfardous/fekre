<?php

/**
 * Admin menu page
 */
add_action( 'admin_menu', 'jam_adding_admin_menu_page' );
function jam_adding_admin_menu_page() {
	add_menu_page(
		__( 'All Submissions', 'job-app-manager' ),
		__( 'All Submissions', 'job-app-manager' ),
		'manage_options',
		'jam_submissions',
		'jam_all_submissions_page_callback',
		'dashicons-portfolio',
		6
	);
}

function jam_show_admin_notice( $message, $type )  {
	echo "
		<div class='notice notice-{$type} is-dismissible'>
			<p>" . __("{$message}", 'job-app-manager') . "</p>
		</div>
	";
}
function jam_all_submissions_page_callback() {
	?>
	<div class="jam-submissions-wrapper">
		<div class="jam-section-header">
			<h1><?php _e( 'All Submissions', 'job-app-manager' ); ?></h1>
		</div>
		<div class="jam-section-content">
			<?php
				global $wpdb;

				// Delete a submission
				if ( isset( $_GET['action'] ) && $_GET['action'] === 'delete' ) {
					if ( ! isset( $_GET['nonce'] ) || ! wp_verify_nonce( $_GET['nonce'], 'delete-submission' ) ) {
						jam_show_admin_notice( 'Sorry! You are not Authorized!', 'error' );
					} else {
                        // Delete attachment
						$attachment_id = absint( $_GET['attachment_id'] );
                        wp_delete_attachment( $attachment_id );
                        
                        // Delete query
                        $delete_id = absint( $_GET['id'] );
                        $wpdb->delete( "{$wpdb->base_prefix}applicant_submissions", [ 'id' => $delete_id ] );
                        jam_show_admin_notice( 'Success! The item has been deleted.', 'success' );
					}
				}

				// Search function
				function jam_search_filter( $item ){
					$first_name 	= strtolower($item['first_name']);
					$last_name  	= strtolower($item['last_name']);
					$search_query 	= sanitize_text_field($_REQUEST['s']);
					if ( strpos( $first_name, $search_query ) !== false || strpos( $last_name, $search_query ) !== false ) {
						return true;
					} else {
						return false;
					}
				}

				// Display all submissions
				$all_submissions = $wpdb->get_results( "SELECT * FROM {$wpdb->base_prefix}applicant_submissions", ARRAY_A );

                // When searched
				if ( isset( $_REQUEST['s'] ) ) {
					$all_submissions = array_filter( $all_submissions, 'jam_search_filter' );
				}

				// When sorting
				if ( isset( $_REQUEST['order'] ) ) {
					$orderby = sanitize_key($_REQUEST['orderby']) ?? '';
					$order   = sanitize_key($_REQUEST['order']) ?? '';
					if ( 'apply_time' == $orderby ) {
						if ( 'asc' == $order ) {
							usort( $all_submissions, function( $item1, $item2 ){
								return $item1['apply_time'] <=> $item2['apply_time'];
							});
						} else {
							usort( $all_submissions, function( $item1, $item2 ){
								return $item2['apply_time'] <=> $item1['apply_time'];
							});
						}
					}
				}
				$table = new Jam_All_Submissions( $all_submissions );
				$table->prepare_items();
			?>

			<div class="wrap">
				<form method="GET">
					<?php
						$table->search_box( 'Search', 'search_id' );
						$table->display();
					?>
					<input type="hidden" name="page" value="<?php echo $_REQUEST['page'];?>">
				</form>
			</div>
		</div>
	</div>

	<?php
}