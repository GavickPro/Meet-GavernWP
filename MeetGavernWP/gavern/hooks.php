<?php

// disable direct access to the file	
defined('GAVERN_WP') or die('Access denied');	

/**
 *
 * Full hooks reference:
 * 
 * gavernwp_doctype
 * gavernwp_html_attributes
 * gavernwp_title
 * gavernwp_metatags
 * gavernwp_fonts
 * gavernwp_ie_scripts
 * gavernwp_head
 * gavernwp_body_attributes
 * gavernwp_footer
 *
 **/

/**
 *
 * Function used to generate the DOCTYPE of the document
 *
 **/

function gavernwp_doctype_hook() {
	// generate the HTML5 doctype
	echo '<!DOCTYPE html>' . "\n";
	
 	// YOUR HOOK CODE HERE
}

add_action('gavernwp_doctype', 'gavernwp_doctype_hook');

/**
 *
 * Function used to generate the DOCTYPE of the document
 *
 **/

function gavernwp_html_attributes_hook() {
	// generate the <html> language attributes
	language_attributes();
	
 	// YOUR HOOK CODE HERE
}

add_action('gavernwp_html_attributes', 'gavernwp_html_attributes_hook');

/**
 *
 * Function used to generate the title content
 *
 **/

function gavernwp_title_hook() {
	// standard function used to generate the page title
	gk_title();
	
 	// YOUR HOOK CODE HERE
}

add_action('gavernwp_title', 'gavernwp_title_hook');

/**
 *
 * Function used to generate the metatags in the <head> section
 *
 **/

function gavernwp_metatags_hook() {
	global $tpl; 
	
	echo '<meta charset="'.get_bloginfo('charset').'" />' . "\n";
	echo '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />' . "\n";
	
	if(get_option($tpl->name . '_chromeframe_state', 'Y') == 'Y') {
		echo '<meta http-equiv="X-UA-Compatible" content="chrome=1"/>' . "\n";
	}
	
	// generates Gavern SEO metatags
	gk_metatags();
	// generates Gavern Open Graph metatags
	gk_opengraph_metatags();
 	// YOUR HOOK CODE HERE
}

add_action('gavernwp_metatags', 'gavernwp_metatags_hook');

/**
 *
 * Function used to generate the font rules in the <head> section
 *
 **/

function gavernwp_fonts_hook() {
	// generates Gavern fonts
	gk_head_fonts();
	
 	// YOUR HOOK CODE HERE
}

add_action('gavernwp_fonts', 'gavernwp_fonts_hook');

/**
 *
 * Function used to generate scripts connected with the IE browser in the <head> section
 *
 **/

function gavernwp_ie_scripts_hook() {
	// generate scripts connected with IE9
	echo '<!--[if lt IE 9]>' . "\n";
	echo '<script src="<?php echo get_template_directory_uri(); ?>/js/html5shiv.js"></script>' . "\n";
	echo '<script src="<?php echo get_template_directory_uri(); ?>/js/respond.js"></script>' . "\n";
	echo '<![endif]-->' . "\n";
	
 	// YOUR HOOK CODE HERE
}

add_action('gavernwp_ie_scripts', 'gavernwp_ie_scripts_hook');

/**
 *
 * Function used to generate the code at the end of the <head> section
 *
 **/
 
function gavernwp_head_hook() {
 	// YOUR HOOK CODE HERE
}

add_action('gavernwp_head', 'gavernwp_head_hook');
 
/**
 *
 * Function used to generate the <body> element attributes
 *
 **/
 
function gavernwp_body_attributes_hook() {
 	global $tpl;
 	
 	// generate the standard body class
 	body_class();
 	// generate the tablet attribute
 	if($tpl->browser->get("tablet") == true) {
 		echo ' data-tablet="true"';
 	} 
 	// generate the table-width attribute
 	echo ' data-tablet-width="'. get_option($tpl->name . '_tablet_width', 800) .'"';
 	
 	// YOUR HOOK CODE HERE
}

add_action('gavernwp_body_attributes', 'gavernwp_body_attributes_hook');
 
/**
 *
 * Function used to generate the code before the closing <body> tag
 *
 **/

function gavernwp_footer_hook() {
	// YOUR HOOK CODE HERE
}
  
add_action('gavernwp_footer', 'gavernwp_footer_hook');
 
// EOF