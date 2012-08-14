<?php

// disable direct access to the file	
defined('GAVERN_WP') or die('Access denied');	

/**
 *
 * GavernWP admin panel & page features
 *
 * Functions used to create GavernWP-specific functions 
 *
 **/

/**
 *
 * Code to create shortcodes button
 *
 **/

function add_gavern_shortcode_button() {
    // check if user can edit posts or pages
    if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
   		return;
    }
	// check if the user enabled rich editing mode
	if ( get_user_option('rich_editing') == 'true') {
		add_filter("mce_external_plugins", "add_gavern_tinymce_plugin");
		add_filter('mce_buttons', 'register_gavern_shortcode_button');
	}
}
// 
function register_gavern_shortcode_button($buttons) {
   // add the shortcode button to the list
   array_push($buttons, "|", "gavern_shortcode_button");
   return $buttons;
}
// Load the plugin script
function add_gavern_tinymce_plugin($plugin_array) {
	// add the shortcode button script to the list
   	$plugin_array['GavernWPShortcodes'] = get_template_directory_uri() . '/js/back-end/gavern.shortcode.button.js';
   	return $plugin_array;
}

/**
 *
 * Code to create custom metaboxes with post description and keywords
 *
 **/

function add_gavern_metaboxes() {
	global $tpl;
	// post description custom meta box
	if(get_option($tpl->name . '_seo_use_gk_seo_settings') == 'Y' && get_option($tpl->name . '_seo_post_desc') == 'custom') {
		add_meta_box( 'gavern-post-desc', __('Post description', GKTPLNAME), 'gavern_post_desc_callback', 'post', 'normal', 'high' );
	}
	// post keywords custom meta box
	if(get_option($tpl->name . '_seo_use_gk_seo_settings') == 'Y' && get_option($tpl->name . '_seo_post_keywords') == 'custom') {
		add_meta_box( 'gavern-post-keywords', __('Post keywords', GKTPLNAME), 'gavern_post_keywords_callback', 'post', 'normal', 'high' );
	}
}

function gavern_post_desc_callback($post) { 
	$values = get_post_custom( $post->ID );  
	$value = isset( $values['gavern-post-desc'] ) ? esc_attr( $values['gavern-post-desc'][0] ) : '';    
	// nonce 
	wp_nonce_field( 'gavern-post-desc-nonce', 'gavern_meta_box_desc_nonce' ); 
    // output
    echo '<textarea name="gavern-post-desc-value" id="gavern-post-desc-value" rows="5" style="width:100%;">'.$value.'</textarea>';   
} 

function gavern_post_keywords_callback($post) {   
	$values = get_post_custom( $post->ID );  
	$value = isset( $values['gavern-post-keywords'] ) ? esc_attr( $values['gavern-post-keywords'][0] ) : '';  
	// nonce 
	wp_nonce_field( 'gavern-post-keywords-nonce', 'gavern_meta_box_keywords_nonce' ); 
	// output
    echo '<textarea name="gavern-post-keywords-value" id="gavern-post-keywords-value" rows="5" style="width:100%;">'.$value.'</textarea>';   
} 
 
function gavern_metaboxes_save( $post_id ) {  
    // check the user permissions  
    if( !current_user_can( 'edit_post', $post_id ) ) {
    	return;
    }
    // avoid requests on the autosave 
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
    	return; 
    }
    // check the nonce
    if( !isset( $_POST['gavern_meta_box_desc_nonce'] ) || !wp_verify_nonce( $_POST['gavern_meta_box_desc_nonce'], 'gavern-post-desc-nonce' ) ) {
    	return;
    }
    
    if( !isset( $_POST['gavern_meta_box_keywords_nonce'] ) || !wp_verify_nonce( $_POST['gavern_meta_box_keywords_nonce'], 'gavern-post-keywords-nonce' ) ) {
    	return;
    }
    // check the existing of the fields and save it
    if( isset( $_POST['gavern-post-desc-value'] ) ) {
        update_post_meta( $post_id, 'gavern-post-desc', esc_attr( $_POST['gavern-post-desc-value'] ) );  
    }
  	
    if( isset( $_POST['gavern-post-keywords-value'] ) ) {
        update_post_meta( $post_id, 'gavern-post-keywords', esc_attr( $_POST['gavern-post-keywords-value'] ) ); 
    }
}   


/**
 *
 * Code to create widget showing manager
 *
 **/

// define an additional operation when save the widget
add_filter( 'widget_update_callback', 'gavern_widget_update', 10, 4);

// definition of the additional operation
function gavern_widget_update($instance, $new_instance, $old_instance, $widget) {	
	global $tpl;
	// check if param was set
	if ( isset( $_POST[$tpl->name . '_widget_rules_' . $widget->id] ) ) {	
		// get option and style value
		$options_type = get_option($tpl->name . '_widget_rules_type');
		$options = get_option($tpl->name . '_widget_rules');
		$styles = get_option($tpl->name . '_widget_style');
		$responsive = get_option($tpl->name . '_widget_responsive');
		$users = get_option($tpl->name . '_widget_users');
		// if this option is set at first time
		if(!is_array($options_type) ) {
			$options_type = array();
		}
		// if this option is set at first time
		if(!is_array($options) ) {
			$options = array();
		}
		// if this styles is set at first time
		if( !is_array($styles) ) {
			$styles = array();
		}
		// if this responsive is set at first time
		if( !is_array($responsive) ) {
			$responsive = array();
		}
		// if this users is set at first time
		if( !is_array($users) ) {
			$users = array();
		}
		// set the new key in the array
		$options_type[$widget->id] = $_POST[$tpl->name . '_widget_rules_type_' . $widget->id];
		$options[$widget->id] = $_POST[$tpl->name . '_widget_rules_' . $widget->id];
		$styles[$widget->id] = $_POST[$tpl->name . '_widget_style_' . $widget->id];
		$responsive[$widget->id] = $_POST[$tpl->name . '_widget_responsive_' . $widget->id];
		$users[$widget->id] = $_POST[$tpl->name . '_widget_users_' . $widget->id];
		// update the settings
		update_option($tpl->name . '_widget_rules_type', $options_type);
		update_option($tpl->name . '_widget_rules', $options);
		update_option($tpl->name . '_widget_style', $styles);
		update_option($tpl->name . '_widget_responsive', $responsive);
		update_option($tpl->name . '_widget_users', $users);
	}	
	// return the widget instance
	return $instance;
}

// function to add the widget control 
function gavern_widget_control() {	
	// get the access to the registered widget controls
	global $wp_registered_widget_controls;
	global $tpl;
	
	// check if the widget rules are enabled
	if(get_option($tpl->name . '_widget_rules_state') == 'Y') {
		// get the widget parameters
		$params = func_get_args();
		// find the widget ID
		$id = $params[0]['widget_id'];
		$unique_id = $id . '-' . rand(10000000, 99999999);
		// get option value
		$options_type = get_option($tpl->name . '_widget_rules_type');
		$options = get_option($tpl->name . '_widget_rules');
		$styles = get_option($tpl->name . '_widget_style');
		$responsive = get_option($tpl->name . '_widget_responsive');
		$users = get_option($tpl->name . '_widget_users');
		// if this option is set at first time
		if( !is_array($options_type) ) {
			$options_type = array();
		}
		// if this option is set at first time
		if( !is_array($options) ) {
			$options = array();
		}
		// if this styles is set at first time
		if( !is_array($styles) ) {
			$styles = array();
		}
		// if this responsive is set at first time
		if( !is_array($responsive) ) {
			$responsive = array();
		}
		// if this users is set at first time
		if( !is_array($users) ) {
			$users = array();
		}
		// get the widget form callback
		$callback = $wp_registered_widget_controls[$id]['callback_redir'];
		// if the callbac exist - run it with the widget parameters
		if (is_callable($callback)) {
			call_user_func_array($callback, $params);
		}
		// value of the option
		$value_type = !empty($options_type[$id]) ? htmlspecialchars(stripslashes($options_type[$id]),ENT_QUOTES) : '';
		$value = !empty($options[$id]) ? htmlspecialchars(stripslashes($options[$id]),ENT_QUOTES) : '';	
		$style = !empty($styles[$id]) ? htmlspecialchars(stripslashes($styles[$id]),ENT_QUOTES) : '';	
		$responsiveMode = !empty($responsive[$id]) ? htmlspecialchars(stripslashes($responsive[$id]),ENT_QUOTES) : '';	
		$usersMode = !empty($users[$id]) ? htmlspecialchars(stripslashes($users[$id]),ENT_QUOTES) : '';	
		// 
		echo '<p>
				<label for="' . $tpl->name . '_widget_rules_'.$id.'">'.__('Visible at: ', GKTPLNAME).'</label>
				<select name="' . $tpl->name . '_widget_rules_type_'.$id.'" id="' . $tpl->name . '_widget_rules_type_'.$id.'" class="gk_widget_rules_select">
					<option value="all"'.(($value_type != "include" && $value_type != 'exclude') ? " selected=\"selected\"":"").'>'.__('All pages', GKTPLNAME).'</option>
					<option value="exclude"'.(($value_type == "exclude") ? " selected=\"selected\"":"").'>'.__('All pages expecting:', GKTPLNAME).'</option>
					<option value="include"'.(($value_type == "include") ? " selected=\"selected\"":"").'>'.__('No pages expecting:', GKTPLNAME).'</option>
				</select>
			</p>
			<fieldset class="gk_widget_rules_form" id="gk_widget_rules_form_'.$unique_id.'">
				<legend>'.__('Select page to add', GKTPLNAME).'</legend>
				 <select class="gk_widget_rules_form_select">
				 	<option value="homepage">'.__('Homepage', GKTPLNAME).'</option>
				 	<option value="page:">'.__('Page', GKTPLNAME).'</option>
				 	<option value="post:">'.__('Post', GKTPLNAME).'</option>
				 	<option value="category:">'.__('Category', GKTPLNAME).'</option>
				 	<option value="tag:">'.__('Tag', GKTPLNAME).'</option>
				 </select>
				 <p><label>'.__('Page ID/Title/slug:', GKTPLNAME).'<input type="text" class="gk_widget_rules_form_input_page" /></label></p>
				 <p><label>'.__('Post ID/Title/slug:', GKTPLNAME).'<input type="text" class="gk_widget_rules_form_input_post" /></label></p>
				 <p><label>'.__('Category ID/Name/slug:', GKTPLNAME).'<input type="text" class="gk_widget_rules_form_input_category" /></label></p>
				 <p><label>'.__('Tag ID/Name:', GKTPLNAME).'<input type="text" class="gk_widget_rules_form_input_tag" /></label></p>
				 <p><button class="gk_widget_rules_btn button-secondary">'.__('Add page', GKTPLNAME).'</button></p>
				 <input type="text" name="' . $tpl->name . '_widget_rules_'.$id.'"  id="' . $tpl->name . '_widget_rules_'.$id.'" value="'.$value.'" class="gk_widget_rules_output" />
				 <fieldset class="gk_widget_rules_pages">
				 	<legend>'.__('Selected pages', GKTPLNAME).'</legend>
				 	<span class="gk_widget_rules_nopages">'.__('No pages', GKTPLNAME).'</span>
				 	<div></div>
				 </fieldset>
			</fieldset>
			<script type="text/javascript">gk_widget_control_init(\'#gk_widget_rules_form_'.$unique_id.'\');</script>';
		// create the list of suffixes
		gavern_widget_control_styles_list($params[0]['widget_id'], $id, $style, $responsiveMode, $usersMode);
	} else {
		// get the widget parameters
		$params = func_get_args();
		// find the widget ID
		$id = $params[0]['widget_id'];
		//
		$styles = get_option($tpl->name . '_widget_style');
		$responsive = get_option($tpl->name . '_widget_responsive');
		// if this styles is set at first time
		if( !is_array($styles) ) {
			$styles = array();
		}
		// if this responsive is set at first time
		if( !is_array($responsive) ) {
			$responsive = array();
		}
		// get the widget form callback
		$callback = $wp_registered_widget_controls[$id]['callback_redir'];
		// if the callbac exist - run it with the widget parameters
		if (is_callable($callback)) {
			call_user_func_array($callback, $params);
		}
		//
		$style = !empty($styles[$id]) ? htmlspecialchars(stripslashes($styles[$id]),ENT_QUOTES) : '';
		$responsiveMode = !empty($responsive[$id]) ? htmlspecialchars(stripslashes($responsive[$id]),ENT_QUOTES) : '';	
		// create the list of suffixes
		gavern_widget_control_styles_list($params[0]['widget_id'], $id, $style, $responsiveMode, null);
	}
}

add_action( 'sidebar_admin_setup', 'gavern_add_widget_control'); 

function gavern_add_widget_control() {	
	global $tpl;
	global $wp_registered_widgets; 
	global $wp_registered_widget_controls;
	// get option value
	$options_type = get_option($tpl->name . '_widget_rules_type');
	$options = get_option($tpl->name . '_widget_rules');
	$styles = get_option($tpl->name . '_widget_style');
	$responsive = get_option($tpl->name . '_widget_responsive');
	$users = get_option($tpl->name . '_widget_users');
	// if this option is set at first time
	if( !is_array($options) ) {
		$options = array();
	}
	// if this styles is set at first time
	if( !is_array($styles) ) {
		$styles = array();
	}
	// if this responsive is set at first time
	if( !is_array($responsive) ) {
		$responsive = array();
	}
	// if this users is set at first time
	if( !is_array($users) ) {
		$users = array();
	}
	// AJAX updates
	if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {	
		foreach ( (array) $_POST['widget-id'] as $widget_number => $widget_id ) {
			// save widget rules type
			if (isset($_POST[$tpl->name . '_widget_rules_type_' . $widget_id])) {
				$options_type[$widget_id] = $_POST[$tpl->name . '_widget_rules_type_' . $widget_id];
			}
			// save widget rules
			if (isset($_POST[$tpl->name . '_widget_rules_' . $widget_id])) {
				$options[$widget_id] = $_POST[$tpl->name . '_widget_rules_' . $widget_id];
			}
			// save widget style
			if (isset($_POST[$tpl->name . '_widget_style_' . $widget_id])) {
				$styles[$widget_id] = $_POST[$tpl->name . '_widget_style_' . $widget_id];
			}
			// save widget responsive
			if (isset($_POST[$tpl->name . '_widget_responsive_' . $widget_id])) {
				$responsive[$widget_id] = $_POST[$tpl->name . '_widget_responsive_' . $widget_id];
			}
			// save widget users
			if (isset($_POST[$tpl->name . '_widget_users_' . $widget_id])) {
				$users[$widget_id] = $_POST[$tpl->name . '_widget_users_' . $widget_id];
			}
		}
	}
	// save the widget id
	foreach ( $wp_registered_widgets as $id => $widget ) {	
		// save the widget id		
		$wp_registered_widget_controls[$id]['params'][0]['widget_id'] = $id;
		// do the redirection
		$wp_registered_widget_controls[$id]['callback_redir'] = $wp_registered_widget_controls[$id]['callback'];
		$wp_registered_widget_controls[$id]['callback'] = 'gavern_widget_control';		
	}
}

function gavern_widget_control_styles_list($widget_name, $id, $value1, $value2, $value3) {
	// getting access to the template global object. 
	global $tpl;
	// clear the widget name - get the name without number at end
	$widget_name = preg_replace('/\-[0-9]+$/mi', '', $widget_name);
	// load and parse widgets JSON file.
	$json_data = $tpl->get_json('config','widgets.styles');
	// prepare an array of options
	$items = array('<option value="" selected="selected">'.__('None', GKTPLNAME).'</option>');
	$for_only_array = array();
	$exclude_array = array();
	// iterate through all styles in the file
	foreach ($json_data as $style) {
		// flag
		$add_the_item = true;
		// check the for_only tag
		if(isset($style->for_only)) {
			$for_only_array = explode(',', $style->for_only);
			if(array_search($widget_name, $for_only_array) === FALSE) {
				$add_the_item = false;
			}
		// check the exclude tag
		} else if(isset($style->exclude)) {
			$exclude_array = explode(',', $style->exclude);
			
			if(array_search($widget_name, $exclude_array) !== FALSE) {
				$add_the_item = false;
			}
		} 
		// check the flag state
		if($add_the_item) {
			// add the item if the module isn't excluded
			array_push($items, '<option value="'.$style->css_class.'"'.(($style->css_class == $value1) ? ' selected="selected"' : '').'>'.$style->name.'</option>');
		}
	}
	// check if the items array is blank - the prepare a basic field
	if(count($items) == 1) {
		$items = array('<option value="" selected="selected">'.__('No styles available', GKTPLNAME).'</option>');
	}
	// output the control
	echo '<p><label for="' . $tpl->name . '_widget_style_'.$id.'">'.__('Widget style: ', GKTPLNAME).'<select name="' . $tpl->name . '_widget_style_'.$id.'"  id="' . $tpl->name . '_widget_style_'.$id.'">';
	foreach($items as $item) echo $item;
	echo '</select></label></p>';
	// output the responsive select
	$items = array(
		'<option value="all"'.((!$value2 || $value2 == 'all') ? ' selected="selected"' : '').'>'.__('All devices', GKTPLNAME).'</option>',
		'<option value="onlyDesktop"'.(($value2 == 'onlyDesktop') ? ' selected="selected"' : '').'>'.__('Desktop', GKTPLNAME).'</option>',
		'<option value="onlyTablets"'.(($value2 == 'onlyTablets') ? ' selected="selected"' : '').'>'.__('Tablets', GKTPLNAME).'</option>',
		'<option value="onlySmartphones"'.(($value2 == 'onlySmartphones') ? ' selected="selected"' : '').'>'.__('Smartphones', GKTPLNAME).'</option>',
		'<option value="onlyTabltetsAndSmartphones"'.(($value2 == 'onlyTabletsAndSmartphones') ? ' selected="selected"' : '').'>'.__('Tablet/Smartphones', GKTPLNAME).'</option>'
	);
	//
	echo '<p><label for="' . $tpl->name . '_widget_responsive_'.$id.'">'.__('Visible on: ', GKTPLNAME).'<select name="' . $tpl->name . '_widget_responsive_'.$id.'"  id="' . $tpl->name . '_widget_responsive_'.$id.'">';
	//
	foreach($items as $item) {
		echo $item;
	}
	//
	echo '</select></label></p>';
	// output the user groups select
	$items = array(
		'<option value="all"'.(($value3 == null || !$value3 || $value3 == 'all') ? ' selected="selected"' : '').'>'.__('All users', GKTPLNAME).'</option>',
		'<option value="guests"'.(($value3 == 'guests') ? ' selected="selected"' : '').'>'.__('Only guests', GKTPLNAME).'</option>',
		'<option value="registered"'.(($value3 == 'registered') ? ' selected="selected"' : '').'>'.__('Only registered users', GKTPLNAME).'</option>',
		'<option value="administrator"'.(($value3 == 'administrator') ? ' selected="selected"' : '').'>'.__('Only administrator', GKTPLNAME).'</option>'
	);
	//
	echo '<p><label for="' . $tpl->name . '_widget_users_'.$id.'">'.__('Visible for: ', GKTPLNAME).'<select name="' . $tpl->name . '_widget_users_'.$id.'"  id="' . $tpl->name . '_widget_users_'.$id.'">';
	//
	foreach($items as $item) {
		echo $item;
	}
	//
	echo '</select></label></p>';
}
 
// Add the Meta Box
function gavern_add_og_meta_box() {
    add_meta_box(
		'gavern_og_meta_box',
		'Open Graph metatags',
		'gavern_show_og_meta_box',
		'post',
		'normal',
		'high'
	);
}
add_action('add_meta_boxes', 'gavern_add_og_meta_box');

// The Callback
function gavern_show_og_meta_box() {
	global $tpl, $post;
	// load custom meta fields
	$custom_meta_fields = $tpl->get_json('config', 'opengraph');
	// Use nonce for verification
	echo '<input type="hidden" name="custom_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';
	// Begin the field table and loop
	echo '<table class="form-table">';
	foreach ($custom_meta_fields as $field) {
		// get value of this field if it exists for this post
		$meta = get_post_meta($post->ID, $field->id, true);
		// begin a table row with
		echo '<tr>
				<th><label for="'.$field->id.'">'.$field->label.'</label></th>
				<td>';
				switch($field->type) {
					// case items will go here
					// text
					case 'text':
						echo '<input type="text" name="'.$field->id.'" id="'.$field->id.'" value="'.$meta.'" size="30" />
							<br /><span class="description">'.$field->desc.'</span>';
					break;
					
					// textarea
					case 'textarea':
						echo '<textarea name="'.$field->id.'" id="'.$field->id.'" cols="60" rows="4">'.$meta.'</textarea>
							<br /><span class="description">'.$field->desc.'</span>';
					break;
					
					// image
					case 'image':
						$image = 'none';
						echo '<span class="gavern_opengraph_default_image" style="display:none">'.$image.'</span>';
						if ($meta) { 
							$image = wp_get_attachment_image_src($meta, 'medium');	
							$image = $image[0];
						}
						echo	'<input name="'.$field->id.'" type="hidden" class="gavern_opengraph_upload_image" value="'.$meta.'" />
									<img src="'.$image.'" class="gavern_opengraph_preview_image" alt="" /><br />
										<input class="gavern_opengraph_upload_image_button button" type="button" value="Choose Image" />
										<small><a href="#" class="gavern_opengraph_clear_image">Remove Image</a></small>
										<br clear="all" /><span class="description">'.$field->desc.'';
					break;
				} //end switch
		echo '</td></tr>';
	} // end foreach
	echo '</table>'; // end table
}
 
// Save the Data
function gavern_save_custom_meta($post_id) {
    global $tpl;
    
    if(isset($post_id)) {
		// load custom meta fields
		$custom_meta_fields = $tpl->get_json('config', 'opengraph');
		// verify nonce
		if (isset($_POST['custom_meta_box_nonce']) && !wp_verify_nonce($_POST['custom_meta_box_nonce'], basename(__FILE__)))
			return $post_id;
		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
			return $post_id;
		// check permissions
		if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id))
				return $post_id;
			} elseif (!current_user_can('edit_post', $post_id)) {
				return $post_id;
		}
	
		// loop through fields and save the data
		foreach ($custom_meta_fields as $field) {
			$old = get_post_meta($post_id, $field->id, true);
			
			if(isset($_POST[$field->id])) {
				$new = $_POST[$field->id];
				if ($new && $new != $old) {
					update_post_meta($post_id, $field->id, $new);
				} elseif ('' == $new && $old) {
					delete_post_meta($post_id, $field->id, $old);
				}
			}
		} // end foreach
	}
}

add_action('save_post', 'gavern_save_custom_meta');  

/**
 *
 * Code used to implement the OpenSearch
 *
 **/

// function used to put in the page header the link to the opensearch XML description file
function gavern_opensearch_head() {
	echo '<link href="'.get_bloginfo('url').'/?opensearch_description=1" title="'.get_bloginfo('name').'" rel="search" type="application/opensearchdescription+xml" />';
}

// function used to add the opensearch_description variable
function gavern_opensearch_query_vars($vars) {
	$vars[] = 'opensearch_description';
	return $vars;
}

// function used to generate the openserch XML description output 
function gavern_opensearch() {
	// access to the wp_query variable
	global $wp_query;
	// check if there was an variable opensearch_description in the query vars
	if (!empty($wp_query->query_vars['opensearch_description']) ) {
		// if yes - return the XML with OpenSearch description
		header('Content-Type: text/xml');
		// the XML content
		echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
		echo "<OpenSearchDescription xmlns=\"http://a9.com/-/spec/opensearch/1.1/\">\n";
		echo "\t<ShortName>".get_bloginfo('name')."</ShortName>\n";
		echo "\t<LongName>".get_bloginfo('name')."</LongName>\n";
		echo "\t<Description>Search &quot;".get_bloginfo('name')."&quot;</Description>\n";
		echo "\t<Image width=\"16\" height=\"16\" type=\"image/x-icon\">".get_template_directory_uri()."/favicon.ico</Image>\n";
		echo "\t<Contact>".get_bloginfo('admin_email')."</Contact>\n";
		echo "\t<Url type=\"text/html\" template=\"".get_bloginfo('url')."/?s={searchTerms}\"/>\n";
		echo "\t<Url type=\"application/atom+xml\" template=\"".get_bloginfo('url')."/?feed=atom&amp;s={searchTerms}\"/>\n";
		echo "\t<Url type=\"application/rss+xml\" template=\"".get_bloginfo('url')."/?feed=rss2&amp;s={searchTerms}\"/>\n";
		echo "\t<Language>".get_bloginfo('language')."</Language>\n";
		echo "\t<OutputEncoding>".get_bloginfo('charset')."</OutputEncoding>\n";
		echo "\t<InputEncoding>".get_bloginfo('charset')."</InputEncoding>\n";
		echo "</OpenSearchDescription>";
		exit;
	}
	// if not just end the function
	return;
}

// add necessary actions and filters if OpenSearch is enabled
if(get_option($tpl->name . "_opensearch_use_opensearch", "Y") == "Y") {
	add_action('wp_head', 'gavern_opensearch_head');
	add_action('template_redirect', 'gavern_opensearch');
	add_filter('query_vars', 'gavern_opensearch_query_vars');
}

/**
 *
 * Code used to implement parsing shortcodes and emoticons in the text widgets
 *
 **/

if(get_option($tpl->name . "_shortcodes_widget_state", "Y") == "Y") {
	add_filter('widget_text', 'do_shortcode');
}
	
if(get_option($tpl->name . "_emoticons_widget_state", "Y") == "Y") {
	add_filter('widget_text', 'convert_smilies');
}

// EOF