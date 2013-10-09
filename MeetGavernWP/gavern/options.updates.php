<?php

// disable direct access to the file	
defined('GAVERN_WP') or die('Access denied');	

/**
 *
 * Function used to load updates page view
 *
 * @return null
 * 
 **/
	
function gavern_updates_options() {	
	// check permissions
	if (!current_user_can('manage_options')) {  
	    wp_die(__('You don\'t have sufficient permissions to access this page!', GKTPLNAME));  
	} 

	include_once(gavern_file('gavern/layouts/updates.php'));
}

/**
 *
 * Function used to load updates page JS code
 *
 * @return null
 * 
 **/
	
function gavern_updates_options_js() {
	// variable used for the page detection
	global $pagenow;
	// check the page
	if($pagenow == 'admin.php' && isset($_GET['page']) && ($_GET['page'] == 'updates_options' || $_GET['page'] == 'updates_options')) {
		wp_register_script('gk-updates-js', gavern_file_uri('js/back-end/updates.options.js'), array('jquery'));
		wp_enqueue_script('gk-updates-js');	
	}
}

/**
 *
 * Function used to load updates page CSS code
 *
 * @return null
 * 
 **/

function gavern_updates_options_css() {
	// variable used for the page detection
	global $pagenow;
	// check the page
	if($pagenow == 'admin.php' && isset($_GET['page']) && ($_GET['page'] == 'updates_options' || $_GET['page'] == 'updates_options')) {
		wp_register_style('gk-updates-css', gavern_file_uri('css/back-end/updates.css'));
		wp_enqueue_style('gk-updates-css');
	}
}

// EOF