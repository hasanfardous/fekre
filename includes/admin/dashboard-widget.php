<?php

// Latest applicants dashboard widget
if ( ! function_exists( 'jam_latest_applicants_dashboard_widget' ) ) {
	function jam_latest_applicants_dashboard_widget() {
		wp_add_dashboard_widget( 
			'jam_dashboard_widget',
			__( 'Latest Applicants', 'job-app-manager' ),
			'jam_latest_applicants_callback'
		);
	}
}

add_action( 'wp_dashboard_setup', 'jam_latest_applicants_dashboard_widget' );


// Latest applicants callback function
if ( ! function_exists( 'jam_latest_applicants_callback' ) ) {
	function jam_latest_applicants_callback() {    
		?>
		<div class="jam-all-applicants-wrap">
			<h3><strong><?php _e( 'Latest 5 Applicant Submissions', 'job-app-manager' ); ?></strong></h3>
			<?php
				// Display latest applicants
				global $wpdb;
				$table_name  	  = "{$wpdb->base_prefix}applicant_submissions";
				$count_query 	  = $wpdb->get_results( "SELECT * FROM {$table_name}" );
				$total_submissons = count($count_query);
				$all_submissions  = $wpdb->get_results( "SELECT * FROM {$table_name} LIMIT 5", ARRAY_A );
				if ( $total_submissons == 0 ) {
					echo "<p class='jam-no-entry'>".__( "Sorry! No Entry Found.", "job-app-manager" )."</p>";
				} else {
					echo '<ul class="jam-applicants">';
						foreach ( $all_submissions as $item ) {
							echo "<li>
									<span><strong>{$item['first_name']} {$item['last_name']}</strong></span>
									<span>{$item['email_address']}</span>
									<span>{$item['mobile_no']}</span>
									<span>{$item['apply_time']}</span>
								</li>";
						}
					echo '</ul>';
				}
				
				$all_submissions_page_url = admin_url("admin.php?page=jam_submissions");
				echo "
					<ul class='jam-view-all'>
						<li><a href='{$all_submissions_page_url}'>".__( "View All Submissions ({$total_submissons}) <span class='dashicons dashicons-external'></span>", "job-app-manager" )."</a></li>
					</ul>";
				?>
		</div>
		<?php
	}
}
