<?php

function silverbullet_speedup_admin_init() {
	register_setting('silverbullet_settings_speedup_group', 'silverbullet_settings_speedup', 'silverbullet_settings_speedup_validate');

	add_settings_section('silverbullet_settings_speedup_section', '', 'silverbullet_speedup_section_callback', 'silverbullet_speedup_page');

	add_settings_field('disable_deprecated', 'deprecated.php', 'silverbullet_field_file_deprecated_callback', 'silverbullet_speedup_page', 'silverbullet_settings_speedup_section');
	add_settings_field('disable_pluggable_deprecated', 'pluggable-deprecated.php', 'silverbullet_field_file_pluggable_deprecated_callback', 'silverbullet_speedup_page', 'silverbullet_settings_speedup_section');

	add_settings_field('disable_bookmark', 'bookmark.php', 'silverbullet_field_file_bookmark_callback', 'silverbullet_speedup_page', 'silverbullet_settings_speedup_section');
	add_settings_field('disable_bookmark_template', 'bookmark-template.php', 'silverbullet_field_file_bookmark_template_callback', 'silverbullet_speedup_page', 'silverbullet_settings_speedup_section');

}
add_action('admin_init', 'silverbullet_speedup_admin_init');


function silverbullet_settings_speedup_validate($input) {
	$default_settings = silverbullet_get_settings_speedup();

	// checkboxes
	$output['disable_deprecated'] = 0;
	if( !empty( $input['disable_deprecated'] ) ) {
		$output['disable_deprecated'] = $input['disable_deprecated'];
	}
	
	$output['disable_pluggable_deprecated'] = 0;
	if( !empty( $input['disable_pluggable_deprecated'] ) ) {
		$output['disable_pluggable_deprecated'] = $input['disable_pluggable_deprecated'];
	}
	
	$output['disable_bookmark'] = 0;
	if( !empty( $input['disable_bookmark'] ) ) {
		$output['disable_bookmark'] = $input['disable_bookmark'];
	}

	$output['disable_bookmark_template'] = 0;
	if( !empty( $input['disable_bookmark_template'] ) ) {
		$output['disable_bookmark_template'] = $input['disable_bookmark_template'];
	}
	
	
	//$settings_file_path = get_home_path().'wp-settings.php';
	
	if( $output['disable_deprecated'] === 0 ) {
		silverbullet_uncomment_line_in_file( '/deprecated.php' );
	} else {
		silverbullet_comment_line_in_file( '/deprecated.php' );
	}
	
	if( $output['disable_pluggable_deprecated'] === 0 ) {
		silverbullet_uncomment_line_in_file( '/pluggable-deprecated.php' );
	} else {
		silverbullet_comment_line_in_file( '/pluggable-deprecated.php' );
	}
	
	if( $output['disable_bookmark'] === 0 ) {
		silverbullet_uncomment_line_in_file( '/bookmark.php' );
	} else {
		silverbullet_comment_line_in_file( '/bookmark.php' );
	}
	
	if( $output['disable_bookmark_template'] === 0 ) {
		silverbullet_uncomment_line_in_file( '/bookmark-template.php' );
	} else {
		silverbullet_comment_line_in_file( '/bookmark-template.php' );
	}

	return $output;
}


function silverbullet_speedup_section_callback() { // settings description
	echo '<p>This settings page updates <strong>wp-settings.php</strong> file.</p>';
	echo '<p>Settings on this page can cause errors.</p>';
	echo '<p>You will have to reset "Silver Bullet" settings if you will see errors.</p>';
	echo '<p>You need to update this option after WordPress update because WordPress can override wp-settings.php sometimes.</p>';
}


function silverbullet_field_file_deprecated_callback() {
	$settings = silverbullet_get_settings_speedup();
	$default_settings = silverbullet_default_settings_speedup();
	echo '<label><input type="checkbox" name="silverbullet_settings_speedup[disable_deprecated]" '.checked(1, $settings['disable_deprecated'], false).' value="1" /> ';
	echo 'disable deprecated.php</label>';
	echo '<p class="description">Saves about 220kb of memory on every page load. Some functions in this file are since first WordPress release in 2003.</p>';
	echo '<p class="description">Default: '.silverbullet_checkbox_default( $default_settings['disable_deprecated'] ).'</p>';
}


function silverbullet_field_file_pluggable_deprecated_callback() {
	$settings = silverbullet_get_settings_speedup();
	$default_settings = silverbullet_default_settings_speedup();
	echo '<label><input type="checkbox" name="silverbullet_settings_speedup[disable_pluggable_deprecated]" '.checked(1, $settings['disable_pluggable_deprecated'], false).' value="1" /> ';
	echo 'disable pluggable-deprecated.php</label>';
	echo '<p class="description">Saves about 10kb of memory on every page load.</p>';
	echo '<p class="description">Default: '.silverbullet_checkbox_default( $default_settings['disable_pluggable_deprecated'] ).'</p>';
}


function silverbullet_field_file_bookmark_callback() {
	$settings = silverbullet_get_settings_speedup();
	$default_settings = silverbullet_default_settings_speedup();
	echo '<label><input type="checkbox" name="silverbullet_settings_speedup[disable_bookmark]" '.checked(1, $settings['disable_bookmark'], false).' value="1" /> ';
	echo 'disable bookmark.php</label>';
	echo '<p class="description">Saves about 30kb of memory on every page load.</p>';
	echo '<p class="description">Default: '.silverbullet_checkbox_default( $default_settings['disable_bookmark'] ).'</p>';
}


function silverbullet_field_file_bookmark_template_callback() {
	$settings = silverbullet_get_settings_speedup();
	$default_settings = silverbullet_default_settings_speedup();
	echo '<label><input type="checkbox" name="silverbullet_settings_speedup[disable_bookmark_template]" '.checked(1, $settings['disable_bookmark_template'], false).' value="1" /> ';
	echo 'disable bookmark-template.php</label>';
	echo '<p class="description">Saves about 30kb of memory on every page load.</p>';
	echo '<p class="description">Default: '.silverbullet_checkbox_default( $default_settings['disable_bookmark_template'] ).'</p>';
}


function silverbullet_settings_speedup_page() {
	?>
	<div class="wrap">

		<h2><span class="dashicons dashicons-shield" style="position: relative; top: 5px;"></span> Silver Bullet - Speedup Settings</h2>

		<form method="post" action="options.php">
			<?php
			settings_fields('silverbullet_settings_speedup_group');
			do_settings_sections('silverbullet_speedup_page');
			submit_button();
			?>
		</form>
		
	</div><!-- .wrap -->
	<?php
}
