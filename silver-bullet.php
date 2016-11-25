<?php
/*
Plugin Name: Silver Bullet
Plugin URI: http://wordpress.org/plugins/silver-bullet/
Description: Speedup WordPress website in a smart way.
Version: 1.0
Author: webvitaly
Author URI: http://web-profile.com.ua/wordpress/plugins/
License: GPLv3
*/


define('SILVER_BULLET_PLUGIN_VERSION', '1.0');


if ( ! defined( 'ABSPATH' ) ) { // prevent full path disclosure
	exit;
}


if ( is_admin() ) { // no need to include this file on frontend
	include('inc/sbpro-functions-admin.php');
	include('inc/sbpro-settings-speedup.php');
}
include('inc/sbpro-functions.php');


$silverbullet_get_settings_speedup = silverbullet_get_settings_speedup();


class Silver_Bullet {

	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'silverbullet_admin_menu' ) );
		
		add_action( 'admin_bar_menu', array( __CLASS__, 'silverbullet_info_admin_bar' ), 1000 );
		
		add_action( 'wp_footer', array( __CLASS__, 'silverbullet_info_source_code' ) );
		add_action( 'login_footer', array( __CLASS__, 'silverbullet_info_source_code' ) );
		
		add_action( 'plugin_action_links_' . plugin_basename(__FILE__), array( __CLASS__, 'silverbullet_plugin_actions' ) );
		add_filter( 'plugin_row_meta', array( __CLASS__, 'silverbullet_plugin_row_meta' ), 10, 2 );
	}

	
	public static function silverbullet_admin_menu() {
		//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
		add_menu_page( 'Silver Bullet', 'Silver Bullet', 'manage_options', 'silver-bullet', 'silverbullet_settings_intro_page', 'dashicons-shield', 72 );
		
		//add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
		add_submenu_page( 'silver-bullet', 'Speedup', 'Speedup', 'manage_options', 'silver-bullet-speedup', 'silverbullet_settings_speedup_page' );
	}
	
	
	public static function silverbullet_info_admin_bar() { // add info to admin bar
		global $wp_admin_bar;
		if ( !is_super_admin() || !is_admin_bar_showing() ) {
			return;
		}
		$silverbullet_info = sprintf( '<span title="Memory usage to generate current page">%.3f MB;</span>
				<span title="Number of database queries to generate current page">%d q;</span>
				<span title="Amount of time in seconds to generate current page">%.3f sec;</span>',
			memory_get_peak_usage() / 1024 / 1024,
			get_num_queries(),
			timer_stop( 0, 3 )
		);
		$wp_admin_bar->add_menu( array( 'id' => 'silverbullet_info', 'title' => $silverbullet_info, 'href' => FALSE ) );
		//$wp_admin_bar->add_menu( array( 'parent' => 'silverbullet_info', 'title' => $silverbullet_info, 'href' => FALSE ) );
	}
	
	
	public static function silverbullet_info_source_code() { // add info to source code
		$speedup_info = sprintf( 'Memory: %.3f MB; SQL: %d q; Time: %.3f sec;',
			memory_get_peak_usage() / 1024 / 1024,
			get_num_queries(),
			timer_stop( 0, 3 )
		);
		echo "\n".'<!-- ========== '.$speedup_info.' ========== -->'."\n";
		echo '<!-- Silver Bullet plugin v.'.SILVER_BULLET_PLUGIN_VERSION.' https://wordpress.org/plugins/silver-bullet/ -->'."\n";
		
	}
	
	
	public static function silverbullet_plugin_actions( $links ) {
		$plugin_actions = array(
			'settings' => '<a href="'.admin_url( 'admin.php?page=silver-bullet-pro' ).'"><span class="dashicons dashicons-admin-settings"></span> Settings</a>'
		);
		$links = array_merge( $links, $plugin_actions );
		return (array) $links;
	}
	
}


Silver_Bullet::init();
