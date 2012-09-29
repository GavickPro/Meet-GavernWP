<?php

// disable direct access to the file	
defined('GAVERN_WP') or die('Access denied');	

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
 * Function used to generate the code before the closing <body> tag
 *
 **/

function gavernwp_footer_hook() {
	// YOUR HOOK CODE HERE
}
  
add_action('gavernwp_footer', 'gavernwp_footer_hook');
 
// EOF