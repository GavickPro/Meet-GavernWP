<?php

// disable direct access to the file	
defined('GAVERN_WP') or die('Access denied');	

/**
 *
 * Menu Walker class
 *
 * Used to generate the proper menu structure
 *
 **/
 
class GKMenuWalkerMobile extends Walker {
	// tree types for the menu
	var $tree_type = array( 'post_type', 'taxonomy', 'custom' );
	// database fields map
	var $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );

	/**
	 *
	 * Function used to generate the start code of the submenu
	 *
	 * @param output - reference to the variable with the output
	 * @param depth - depth of the element
	 *
	 * @return null (use reference instead of returning values)
	 * 
	 **/
	function start_lvl(&$output, $depth = 0, $args = array()) {
		// in the mobile menu we don't need the additional output
		$output .= '';
	}
	
	/**
	 *
	 * Function used to generate the end code of the submenu
	 *
	 * @param output - reference to the variable with the output
	 * @param depth - depth of the element
	 *
	 * @return null (use reference instead of returning values)
	 * 
	 **/
	function end_lvl(&$output, $depth = 0, $args = array()) {
		// in the mobile menu we don't need the additional output
		$output .= '';
	}

	/**
	 *
	 * Function used to generate the start code of the menu element
	 *
	 * @param output - reference to the variable with the output
	 * @param item - the menu item to show
	 * @param depth - depth of the element
	 * @param args - additional arguments
	 *
	 * @return null (use reference instead of returning values)
	 * 
	 **/
	function start_el(&$output, $item, $depth = 0, $args = array(),  $current_object_id = 0) {
		// access to the WordPress Query
		global $wp_query;
		// generate the indent
		$indent = ($depth) ? str_repeat( "\t", $depth ) : '';
		// generate the dashes for the menu items on the deeper levels
		$itemindent = ($depth) ? str_repeat( "&mdash;", $depth ) : '';
		// generate the only one attribute - with value - it is a URL
		$attributes = !empty($item->url) ? ' value="'.esc_attr($item->url).'"' : '';
		// check if the current menu item is the active element - if yes, use the selected attribute
		if(isset($item->classes) && in_array('current-menu-item', $item->classes)) {
			$attributes .= ' selected="selected"';
		}
		// generate the opening OPTION tag
		$item_output = '<option'. $attributes .'>';
		// generate the OPTION tag content
		$item_output .= $itemindent . ' ' . apply_filters('the_title', $item->title, $item->ID);
		// generate the closing OPTION tag
		$item_output .= '</option>';
		// generate the final output
		$output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
	}

	/**
	 *
	 * Function used to generate the end code of the menu element
	 *
	 * @param output - reference to the variable with the output
	 * @param item - menu item - here not used but required by API
	 * @param depth - depth of the element
	 *
	 * @return null (use reference instead of returning values)
	 * 
	 **/
	function end_el(&$output, $item, $depth = 0, $args = array()) {
		// in the mobile menu we don't need the additional output
		$output .= '';
	}
}

// EOF