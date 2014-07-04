<?php
	
// disable direct access to the file	
defined('GAVERN_WP') or die('Access denied');	

/**
 *
 * Function to create template options page
 *
 * @return null
 *
 **/
	
function gavern_template_options() {
	// getting access to the template global object. 
	global $tpl;
	
	// check permissions
	if (!current_user_can('manage_options')) {  
	    wp_die(__('You don\'t have sufficient permissions to access this page!', GKTPLNAME));  
	} 
	  
	include_once(gavern_file('gavern/layouts/template.php'));
}

/**
 *
 * Function used to load template options page JS code
 *
 * @return null
 * 
 **/
 
function gavern_template_options_js() {
	// variable used for the page detection
	global $pagenow;
	// template object
	global $tpl;
	// check the page
	if($pagenow == 'admin.php' && isset($_GET['page']) && ($_GET['page'] == 'template_options' || $_GET['page'] == 'gavern-menu')) {
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
		wp_register_script('gk-tips-js', gavern_file_uri('js/back-end/libraries/miniTip/miniTip.min.js'), array('jquery'));
		wp_register_script('gk-upload', gavern_file_uri('js/back-end/template.options.js'), array('jquery','media-upload','thickbox', 'gk-tips-js'));
		wp_enqueue_script('gk-upload');
		wp_enqueue_script('gk-tips-js');
		wp_enqueue_media();
		// register and load external components scripts
		$tabs = $tpl->get_json('options','tabs');
		// iterate through tabs
		foreach($tabs as $tab) {
			if($tab[2] == 'enabled') {
				// load file
				$loaded_data = $tpl->get_json('options', $tab[1]);	
				// check the loaded JSON data
				if($loaded_data != null && count($loaded_data != 0)) {
					$standard_fields = array('Text', 'Select', 'Switcher', 'Textarea', 'Media', 'WidthHeight', 'TextBlock');
					// iterate through groups
					foreach($loaded_data as $group) {
						// 
						foreach($group->fields as $field) {
							if(!in_array($field->type, $standard_fields)) {
								// load field config
								$file_config = $tpl->get_json('form_elements/'.$field->type, 'config', false);
								// check if the file is correct
								if((is_array($file_config) && count($file_config) > 0) || is_object($file_config)) {
									// load the JS file
									if($file_config->js != '') {
										wp_register_script('gk_'.strtolower($file_config->name).'.js', gavern_file_uri('gavern/form_elements/').($field->type).'/'.($file_config->js));
										wp_enqueue_script('gk_'.strtolower($file_config->name).'.js');
									}
								}
							}
						}
					}
				}
			}
		}	
	}
}

/**
 *
 * Function used to load template options CSS code
 *
 * @return null
 * 
 **/
 
function gavern_template_options_css() {
	// variable used for the page detection
	global $pagenow;
	// template object
	global $tpl;
	// check the page
	if($pagenow == 'admin.php' && isset($_GET['page']) && ($_GET['page'] == 'template_options' || $_GET['page'] == 'gavern-menu')) {
		wp_enqueue_style('thickbox');
		wp_register_style('gk-tips-css', gavern_file_uri('js/back-end/libraries/miniTip/miniTip.css'));
		wp_register_style('gk-template-css', gavern_file_uri('css/back-end/template.css'));
		wp_enqueue_style('gk-tips-css');
		wp_enqueue_style('gk-template-css');
		// register and load external components scripts
		$tabs = $tpl->get_json('options','tabs');
		// iterate through tabs
		foreach($tabs as $tab) {
			if($tab[2] == 'enabled') {
				// load file
				$loaded_data = $tpl->get_json('options', $tab[1]);	
				// check the loaded JSON data
				if($loaded_data != null && count($loaded_data != 0)) {
					$standard_fields = array('Text', 'Select', 'Switcher', 'Textarea', 'Media', 'WidthHeight', 'TextBlock');
					// iterate through groups
					foreach($loaded_data as $group) {
						// 
						foreach($group->fields as $field) {
							if(!in_array($field->type, $standard_fields)) {
								// load field config
								$file_config = $tpl->get_json('form_elements/'.$field->type, 'config', false);
								// check if the file is correct
								if((is_array($file_config) && count($file_config) > 0) || is_object($file_config)) {
									// load the CSS file
									if($file_config->css != '') {
										wp_register_style('gk_'.strtolower($file_config->name).'.css', gavern_file_uri('gavern/form_elements/').($field->type).'/'.($file_config->css));
										wp_enqueue_style('gk_'.strtolower($file_config->name).'.css');
									}
								}
							}
						}
					}
				}
			}
		}	
	}
}

/**
 *
 * Function used define template JS callback for saving options
 *
 * @return null
 * 
 **/

function gavern_template_save_js() {
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

function gavern_template_save_callback() {
	// getting access to the template global object. 
	global $tpl;
	
	// check user capability to made operation
	if ( current_user_can( 'manage_options' ) ) {
	 	// check security with nonce.
 		if ( function_exists( 'check_ajax_referer' ) ) { 
 			check_ajax_referer( 'GavernWPNonce', 'security' ); 
 		}
 		// save the settings - iterate throught all $_POST variables
 		foreach($_POST as $key => $value) {
 			if(strpos($key, $tpl->name . '_') !== false) {
 				update_option($key, esc_attr($value)); 
 			}
			}
			// return the results
			_e('Settings saved', GKTPLNAME);
 		// this is required to return a proper result 
 		die();   
	} else {
		wp_die(__('You don\'t have sufficient permissions to access this page!', GKTPLNAME)); 
	}
}
	
// adding template save callback
add_action( 'wp_ajax_template_save', 'gavern_template_save_callback' );

// EOF