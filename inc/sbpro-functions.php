<?php
// general "Silver Bullet" functions

function silverbullet_default_settings_speedup() {
	$settings = array(
		'disable_deprecated' => 0,
		'disable_pluggable_deprecated' => 0,
		'disable_bookmark' => 0,
		'disable_bookmark_template' => 0
	);
	return $settings;
}


function silverbullet_get_settings_speedup() {
	$silverbullet_settings = (array) get_option('silverbullet_settings_speedup');
	$default_settings = silverbullet_default_settings_speedup();
	$silverbullet_settings = array_merge($default_settings, $silverbullet_settings); // set empty options with default values
	return $silverbullet_settings;
}
