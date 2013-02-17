<?php

/**
 * GavernWP functions and definitions
 *
 * This file contains core framework operations. It is always
 * loaded before the index.php file no the front-end
 *
 * @package WordPress
 * @subpackage GavernWP
 * @since GavernWP 1.0
 **/

if(!function_exists('gavern_file')) {
	/**
	 *
	 * Function used to get the file absolute path - useful when child theme is used
	 *
	 * @return file absolute path (in the original theme or in the child theme if file exists)
	 *
	 **/
	function gavern_file($path) {
		if(is_child_theme()) {
			if($path == false) {
				return get_stylesheet_directory();
			} else {
				if(is_file(get_stylesheet_directory() . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $path))) {
					return get_stylesheet_directory() . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $path);
				} else {
					return get_template_directory() . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $path);
				}
			}
		} else {
			if($path == false) {
				return get_template_directory();
			} else {
				return get_template_directory() . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $path);
			}
		}
	}
}

if(!function_exists('gavern_file_uri')) {
	/**
	 *
	 * Function used to get the file URI - useful when child theme is used
	 *
	 * @return file URI (in the original theme or in the child theme if file exists)
	 *
	 **/
	function gavern_file_uri($path) {
		if(is_child_theme()) {
			if($path == false) {
				return get_stylesheet_directory_uri();
			} else {
				if(is_file(get_stylesheet_directory() . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $path))) {
					return get_stylesheet_directory_uri() . '/' . $path;
				} else {
					return get_template_directory_uri() . '/' . $path;
				}
			}
		} else {
			if($path == false) {
				return get_template_directory_uri();
			} else {
				return get_template_directory_uri() . '/' . $path;
			}
		}
	}
}
//
if(!class_exists('GavernWP')) {
	// include the framework base class
	require(gavern_file('gavern/base.php'));
}
// load and parse template JSON file.
$config_language = 'en_US';
if(get_locale() != '' && is_dir(get_template_directory() . '/gavern/config/'. get_locale()) && is_dir(get_template_directory() . '/gavern/options/'. get_locale())) {
	$config_language = get_locale();	
}
$json_data = json_decode(file_get_contents(get_template_directory() . '/gavern/config/'.$config_language.'/template.json'));
$tpl_name = strtolower(preg_replace("/[^A-Za-z0-9]/", "", $json_data->template->name));
// define constant to use with all __(), _e(), _n(), _x() and _xe() usage
define('GKTPLNAME', $tpl_name);
// create the framework object
$tpl = new GavernWP();
// Including file with helper functions
require_once(gavern_file('gavern/helpers/helpers.base.php'));
// Including file with template hooks
require_once(gavern_file('gavern/hooks.php'));
// Including file with template functions
require_once(gavern_file('gavern/functions.php'));
// Including file with template filters
require_once(gavern_file('gavern/filters.php'));
// Including file with template widgets
require_once(gavern_file('gavern/widgets.comments.php'));
require_once(gavern_file('gavern/widgets.nsp.php'));
require_once(gavern_file('gavern/widgets.social.php'));
require_once(gavern_file('gavern/widgets.tabs.php'));
// Including file with template admin features
require_once(gavern_file('gavern/helpers/helpers.features.php'));
// Including file with template shortcodes
require_once(gavern_file('gavern/helpers/helpers.shortcodes.php'));
// Including file with template layout functions
require_once(gavern_file('gavern/helpers/helpers.layout.php'));
// Including file with template layout functions - connected with template fragments
require_once(gavern_file('gavern/helpers/helpers.layout.fragments.php'));
// Including file with template branding functions
require_once(gavern_file('gavern/helpers/helpers.branding.php'));
// Including file with template customize functions
require_once(gavern_file('gavern/helpers/helpers.customizer.php'));
// initialize the framework
$tpl->init();
// add theme setup function
add_action('after_setup_theme', 'gavern_theme_setup');
// Theme setup function
function gavern_theme_setup(){
	// access to the global template object
	global $tpl;
	// variable used for redirects
	global $pagenow;		
	// check if the themes.php address with goto variable has been used
	if ($pagenow == 'themes.php' && !empty($_GET['goto'])) {
		/**
		 *
		 * IMPORTANT FACT: if you're using few different redirects on a lot of subpages
		 * we recommend to define it as themes.php?goto=X, because if you want to
		 * change the URL for X, then you can change it on one place below :)
		 *
		 **/
		
		// check the goto value
		switch ($_GET['goto']) {
			// make proper redirect
			case 'gavick-com':
				wp_redirect("http://www.gavick.com");
				break;
			case 'wiki':
				wp_redirect("http://www.gavick.com/documentation");
				break;
			// or use default redirect
			default:
				wp_safe_redirect('/wp-admin/');
				break;
		}
		exit;
	}
	// if the normal page was requested do following operations:
	
    // load and parse template JSON file.
    $json_data = $tpl->get_json('config','template');
    // read the configuration
    $template_config = $json_data->template;
    // save the lowercase non-special characters template name				
    $template_name = strtolower(preg_replace("/[^A-Za-z0-9]/", "", $template_config->name));
    // load the template text_domain
    load_theme_textdomain( $template_name, get_template_directory() . '/languages' );
}
// scripts enqueue function
function gavern_enqueue_admin_js_and_css() {
	// opengraph scripts
	wp_enqueue_script('gavern.opengraph.js', gavern_file_uri('js/back-end/gavern.opengraph.js'));
	// widget rules JS
	wp_register_script('widget-rules-js', gavern_file_uri('js/back-end/widget.rules.js'), array('jquery'));
	wp_enqueue_script('widget-rules-js');
	// widget rules CSS
	wp_register_style('widget-rules-css', gavern_file_uri('css/back-end/widget.rules.css'));
	wp_enqueue_style('widget-rules-css');
	// GK News Show Pro Widget back-end CSS
	wp_register_style('nsp-admin-css', gavern_file_uri('css/back-end/nsp.css'));
	wp_enqueue_style('nsp-admin-css');
	// shortcodes database
	if(
		get_locale() != '' && 
		is_dir(get_template_directory() . '/gavern/config/'. get_locale()) && 
		is_dir(get_template_directory() . '/gavern/options/'. get_locale())
	) {
		$language = get_locale();	
	} else {
		$language = 'en_US';
	}
	
	wp_enqueue_script('shortcodes.js', gavern_file_uri('gavern/config/'.$language.'/shortcodes.js'));
}
// this action enqueues scripts and styles: 
// http://wpdevel.wordpress.com/2011/12/12/use-wp_enqueue_scripts-not-wp_print_styles-to-enqueue-scripts-and-styles-for-the-frontend/
add_action('admin_enqueue_scripts', 'gavern_enqueue_admin_js_and_css');

// EOF