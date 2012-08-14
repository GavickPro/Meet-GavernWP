<?php
	
// disable direct access to the file	
defined('GAVERN_WP') or die('Access denied');	

/**
 *
 * Function to create import/export options page
 *
 * @return null
 *
 **/
	
function gavern_importexport_options() {
	// getting access to the template global object. 
	global $tpl;
	
	// check permissions
	if (!current_user_can('manage_options')) {  
	    wp_die(__('You don\'t have sufficient permissions to access this page!', GKTPLNAME));  
	} 
	  
	include('layouts/importexport.php');
}

/**
 *
 * Function used to load import/export page JS code
 *
 * @return null
 * 
 **/
 
function gavern_importexport_options_js() {
	// variable used for the page detection
	global $pagenow;
	// check the page
	if($pagenow == 'admin.php' && isset($_GET['page']) && ($_GET['page'] == 'importexport_options' || $_GET['page'] == 'importexport_options')) {
		wp_register_script('gk-importexport-js', get_template_directory_uri().'/js/back-end/importexport.options.js');
		wp_enqueue_script('gk-importexport-js');	
	}
}

/**
 *
 * Function used to load import/export options page CSS code
 *
 * @return null
 * 
 **/
 
function gavern_importexport_options_css() {
	// variable used for the page detection
	global $pagenow;
	// check the page
	if($pagenow == 'admin.php' && isset($_GET['page']) && ($_GET['page'] == 'importexport_options' || $_GET['page'] == 'importexport_options')) {
		wp_register_style('gk-importexport-css', get_template_directory_uri().'/css/back-end/importexport.css');
		wp_enqueue_style('gk-importexport-css');
	}
}

/**
 *
 * Function to add template JavaScript in the head section
 *
 * @return null
 *
 **/

function gavern_importexport_save_js() {
	$ajax_nonce = wp_create_nonce('GavernWPNonce');
	echo '<script type="text/javascript">$gk_ajax_nonce = "'.$ajax_nonce.'";</script>';
}

/**
 *
 * Function to create callback for template save ajax request
 *
 * @return null
 *
 **/

function gavern_importexport_save_callback() {
	// getting access to the template global object. 
	global $tpl;
	
	// check user capability to made operation
	if ( current_user_can( 'manage_options' ) ) {
	 	// check security with nonce.
 		if ( function_exists( 'check_ajax_referer' ) ) { 
 			check_ajax_referer( 'GavernWPNonce', 'security' ); 
 		}
 		
 		gavern_use_option_backup(esc_attr($_POST['importexport_import']));	
		// return the results
		_e('Settings saved', GKTPLNAME);
 		// this is required to return a proper result 
 		die();   
	} else {
		wp_die(__('You don\'t have sufficient permissions to access this page!', GKTPLNAME)); 
	}
}

// adding template save callback
add_action( 'wp_ajax_importexport_save', 'gavern_importexport_save_callback' );

/**
 *
 * Function used to get all options
 *
 * @param option_prefix - prefix used for the options
 *
 * @return JSON string with all options
 * 
 **/
 
function gavern_get_option_backup($option_prefix) {
	global $wpdb;
	global $tpl;
	// check if the option prefix isn't empty
	if (empty($option_prefix)) {
		return false;
	}
	// get all rows with options containing specific prefix
	$rows = $wpdb->get_results(  
			'SELECT 
				option_value, 
				option_name 
			FROM 
				'.$wpdb->options.'
			WHERE 
				option_name LIKE \''.$option_prefix.'%\';' 
	);
	// 	
	$value = array();
	//
	if ($rows) {
		foreach ($rows as $option) {
			if(
				$option->option_name != $tpl->name . '_widget_responsive' &&
				$option->option_name != $tpl->name . '_widget_rules' &&
				$option->option_name != $tpl->name . '_widget_rules_type' &&
				$option->option_name != $tpl->name . '_widget_style' && 
				$option->option_name != $tpl->name . '_widget_users'
			) {
				$value[$option->option_name] = $option->option_value;
			}
		}
	}
	//	
	return json_encode($value);
}

/**
 *
 * Function used to load the options
 *
 * @param json - JSON string used to load options
 *
 * @return null
 * 
 **/
function gavern_use_option_backup($json) {
	$settings_array = json_decode($json);
	
	if(is_array($settings_array) && count($settings_array) > 0) {
		foreach($settings_array as $key => $value) {
			update_option($key, esc_attr($value));
		}
	}
}

// EOF