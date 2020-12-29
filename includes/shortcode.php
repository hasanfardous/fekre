<?php

// Applicant form shortcode
add_action( 'init', 'jam_add_applicant_form_shortcode_callback' );
 
function jam_add_applicant_form_shortcode_callback() {
    add_shortcode( 'applicant_form', 'jam_add_applicant_form_shortcode' );
}
function jam_add_applicant_form_shortcode() {
	?>
	<div class="applicant-form-wrapper">
		<div class="jam-confirmation-message"></div>
		<form method="post" class="jam-applicant-form" enctype="multipart/form-data">
			<?php wp_nonce_field( 'jam_applicant_data_nonce' );?>
			<div class="single-entry">
				<label for="firstName">First Name</label>
				<input type="text" name="firstName" id="firstName" required>
			</div>
			<div class="single-entry">
				<label for="lastName">Last Name</label>
				<input type="text" name="lastName" id="lastName" required>
			</div>
			<div class="single-entry presentAddress">
				<label for="presentAddress">Present Address</label>
				<input type="text" name="presentAddress" id="presentAddress" required>
			</div>
			<div class="single-entry">
				<label for="emailAddress">Email Address</label>
				<input type="email" name="emailAddress" id="emailAddress" required>
			</div>
			<div class="single-entry">
				<label for="mobileNo">Mobile No</label>
				<input type="text" name="mobileNo" id="mobileNo" required>
			</div>
			<div class="single-entry postName">
				<label for="postName">Post Name</label>
				<input type="text" name="postName" id="postName" required>
			</div>
			<div class="single-entry yourCv">
				<label for="yourCv">Upload Your CV</label>
				<input type="file" name="yourCv" id="yourCv" required>
			</div>
			<div class="single-entry submitBtn">
				<input type="submit" name="submitBtn" id="submitBtn" value="Submit Entry">
			</div>
		</form>
	</div>
	<?php
}