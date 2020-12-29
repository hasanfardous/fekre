<?php

add_action('wp_ajax_jam_datas', 'jam_applicant_form_datas');
add_action('wp_ajax_nopriv_jam_datas', 'jam_applicant_form_datas');

// Applicant form data handling
if ( ! function_exists( 'jam_applicant_form_datas' ) ) {
	function jam_applicant_form_datas() {
		$response 	 = [];
		$posted_data = isset( $_POST ) ? $_POST : array();
		$file_data   = isset( $_FILES ) ? $_FILES : array();
		$data 		 = array_merge( $posted_data, $file_data );
		check_ajax_referer( 'jam_applicant_data_nonce', 'nonce' );

		// Catch our datas and sanitize them
		$firstName		= isset( $data['firstName'] ) ? sanitize_text_field( $data['firstName'] ) : '';
		$lastName		= isset( $data['lastName'] ) ? sanitize_text_field( $data['lastName'] ) : '';
		$presentAddress = isset( $data['presentAddress'] ) ? sanitize_text_field ($data['presentAddress']) : '';
		$emailAddress	= isset( $data['emailAddress'] ) ? sanitize_email( $data['emailAddress'] ) : '';
		$mobileNo		= isset( $data['mobileNo'] ) ? sanitize_text_field( $data['mobileNo'] ) : '';
		$postName		= isset( $data['postName'] ) ? sanitize_text_field( $data['postName'] ) : '';
		$yourCv			= isset( $data['yourCv'] ) ?  $data['yourCv'] : '';

		// Error handling & storing datas
		if ( $yourCv['error'] ) {
			$response['response'] = 'error';
			$response['message']  = __( 'Sorry! Error found, please try again.', 'job-app-manager' );
		} elseif ( $yourCv['type'] !== 'application/pdf' ) {
			$response['response'] = 'error';
			$response['message']  = __( 'Sorry! File format not supported, only PDF allowed.', 'job-app-manager' );
		} else {
			$yourCv['name'] = sanitize_file_name($yourCv['name']);
			$attachment_id  = media_handle_upload( 'yourCv', 0 );
			if ( is_wp_error( $attachment_id ) ) {
				$response['response'] = 'error';
				$response['message']  = __( 'Sorry! Error during the file upload.', 'job-app-manager' );
			} else {
				global $wpdb;
				$table_name = $wpdb->base_prefix.'applicant_submissions';
				$submission_insert = $wpdb->insert( 
					$table_name, 
					array( 
						'first_name' 		=> $firstName, 
						'last_name' 		=> $lastName, 
						'present_address' 	=> $presentAddress, 
						'email_address' 	=> $emailAddress, 
						'mobile_no' 		=> $mobileNo, 
						'post_name' 		=> $postName, 
						'cv_path' 			=> $attachment_id, 
						'apply_time' 		=> current_time('mysql', 1),
					), 
					array( 
						'%s', 
						'%s', 
						'%s', 
						'%s', 
						'%s', 
						'%s', 
						'%s', 
						'%s', 
					) 
				);

				if ( $submission_insert ) {
					$to 	 	= $emailAddress;
					$siteTitle  = get_option( 'blogname' );
					$adminEmail = get_option( 'admin_email' );
					$subject 	= "Received your Job Application";
					$message 	= "Hello " . $firstName . " " . $lastName . ", We have just received your job application. Thanks for your application. We will contact you soon.";
					$headers 	= array('Content-Type: text/html; charset=UTF-8');
				
					// Send an email to the user
					wp_mail( $to, $subject, $message, $headers );
					$response['response'] = 'success';
					$response['message'] = __( 'Success! Your Request has been received. Please check your Email.', 'job-app-manager' );
				} else {
					$response['response'] = 'error';
					$response['message'] = __( 'Sorry! Could not process your request, please try again.', 'job-app-manager' );
				}
			}
		}

		// Return response
		echo json_encode( $response );
		exit();
	}
}