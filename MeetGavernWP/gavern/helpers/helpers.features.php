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
 * Gavern Meta Boxes
 *
 *
 **/
 
global $tpl;
$prefix = 'gavern-meta-';

// Add meta boxes start

// Post - Page options meta box
$meta_boxes[] = array(
	'id' => $prefix.'options',
	'title' => __('GK Gavern Post/Page Options', GKTPLNAME),
	'pages' => array('post', 'page'),
	'context' => 'side',
	'priority' => 'low',
	'fields' => array(
		array(
			'name' => __('Show Title:', GKTPLNAME),
			'id' => $prefix . 'show-title',
			'type' => 'radio',
			'options' => array(
				array('name' => __('Yes', GKTPLNAME), 'value' => 'Y'),
				array('name' => __('No', GKTPLNAME), 'value' => 'N')
			)
		),
		array(
			'name' => __('Show Post Format:', GKTPLNAME),
			'id' => $prefix . 'show-post-format',
			'type' => 'radio',
			'options' => array(
				array('name' => __('Yes', GKTPLNAME), 'value' => 'Y'),
				array('name' => __('No', GKTPLNAME), 'value' => 'N')
			)
		),
		array(
			'name' => __('Show Date:', GKTPLNAME),
			'id' => $prefix . 'show-date',
			'type' => 'radio',
			'options' => array(
				array('name' => __('Yes', GKTPLNAME), 'value' => 'Y'),
				array('name' => __('No', GKTPLNAME), 'value' => 'N')
			)
		),
		array(
			'name' => __('Show Category:', GKTPLNAME),
			'id' => $prefix . 'show-category',
			'type' => 'radio',
			'options' => array(
				array('name' => __('Yes', GKTPLNAME), 'value' => 'Y'),
				array('name' => __('No', GKTPLNAME), 'value' => 'N')
			)
		),
		array(
			'name' => __('Show Author:', GKTPLNAME),
			'id' => $prefix . 'show-author',
			'type' => 'radio',
			'options' => array(
				array('name' => __('Yes', GKTPLNAME), 'value' => 'Y'),
				array('name' => __('No', GKTPLNAME), 'value' => 'N')
			)
		),
		array(
			'name' => __('Show Comment Link:', GKTPLNAME),
			'id' => $prefix . 'show-comment-link',
			'type' => 'radio',
			'options' => array(
				array('name' => __('Yes', GKTPLNAME), 'value' => 'Y'),
				array('name' => __('No', GKTPLNAME), 'value' => 'N')
			)
		),
		array(
			'name' => __('Show Edit Link:', GKTPLNAME),
			'id' => $prefix . 'show-edit-link',
			'type' => 'radio',
			'options' => array(
				array('name' => __('Yes', GKTPLNAME), 'value' => 'Y'),
				array('name' => __('No', GKTPLNAME), 'value' => 'N')
			)
		),
	)
);

// Post description custom meta box
if(get_option($tpl->name . '_seo_use_gk_seo_settings') == 'Y' && get_option($tpl->name . '_seo_post_desc') == 'custom') {
	$meta_boxes[] = array(
		'id' => $prefix.'desc',
		'title' => __('Post description', GKTPLNAME),
		'pages' => array('post', 'page'),
		'context' => 'normal',
		'priority' => 'low',
		'fields' => array(
			array(
				'name' => '',
				'id' => $prefix .'desc-value',
				'type' => 'textarea',
				'desc' => __('Entering any description in here will be displayed in post/page title.', GKTPLNAME),
				'std' => ''
			),
		)
	);
}
// Post keywords custom meta box
if(get_option($tpl->name . '_seo_use_gk_seo_settings') == 'Y' && get_option($tpl->name . '_seo_post_desc') == 'custom') {
	$meta_boxes[] = array(
		'id' => $prefix.'keywords',
		'title' => __('Post keywords', GKTPLNAME),
		'pages' => array('post', 'page'),
		'context' => 'normal',
		'priority' => 'low',
		'fields' => array(
			array(
				'name' => '',
				'id' => $prefix .'keywords-value',
				'type' => 'textarea',
				'desc' => __('Entering any keywords in here will be displayed in post/page title.', GKTPLNAME),
				'std' => ''
			),
		)
	);
}
// Contact page custom meta box
$meta_boxes[] = array(
	'id' => $prefix.'contact_options',
	'title' => __('GK Gavern Contact Page Options', GKTPLNAME),
	'pages' => array('page'),
	'context' => 'side',
	'priority' => 'low',
	'fields' => array(
		array(
			'name' => __('Show Name:', GKTPLNAME),
			'id' => $prefix . 'show-contact-name',
			'type' => 'radio',
			'options' => array(
				array('name' => __('Yes', GKTPLNAME), 'value' => 'Y'),
				array('name' => __('No', GKTPLNAME), 'value' => 'N')
			)
		),
		array(
			'name' => __('Show Email:', GKTPLNAME),
			'id' => $prefix . 'show-contact-email',
			'type' => 'radio',
			'options' => array(
				array('name' => __('Yes', GKTPLNAME), 'value' => 'Y'),
				array('name' => __('No', GKTPLNAME), 'value' => 'N')
			)
		),
		array(
			'name' => __('Show Message:', GKTPLNAME),
			'id' => $prefix . 'show-contact-message',
			'type' => 'radio',
			'options' => array(
				array('name' => __('Yes', GKTPLNAME), 'value' => 'Y'),
				array('name' => __('No', GKTPLNAME), 'value' => 'N')
			)
		),
		array(
			'name' => __('Show Send Copy:', GKTPLNAME),
			'id' => $prefix . 'show-contact-send-copy',
			'type' => 'radio',
			'options' => array(
				array('name' => __('Yes', GKTPLNAME), 'value' => 'Y'),
				array('name' => __('No', GKTPLNAME), 'value' => 'N')
			)
		),
	)
);  
// Add Meta boxes end

// Creating meta boxes with our class
foreach ($meta_boxes as $meta_box) {
    $my_box = new gavern_meta_box($meta_box);
}

// Add custom validations in here for required fields
class gavern_meta_box_validate {
    function check_text($text) {
        if ($text != 'hello') {
            return false;
        }
        return true;
    }
}

// Gavern Meta Box Class for creating meta boxes

class gavern_meta_box {

    protected $_meta_box;

    // Create meta box based on given data
    function __construct($meta_box) {
        if (!is_admin()) return;

        $this->_meta_box = $meta_box;
        add_action('admin_menu', array(&$this, 'add'));
        add_action('save_post', array(&$this, 'save'));
    }
    // Add meta box for multiple post types
    function add() {
        $this->_meta_box['context'] = empty($this->_meta_box['context']) ? 'normal' : $this->_meta_box['context'];
        $this->_meta_box['priority'] = empty($this->_meta_box['priority']) ? 'high' : $this->_meta_box['priority'];
        foreach ($this->_meta_box['pages'] as $page) {
            add_meta_box($this->_meta_box['id'], $this->_meta_box['title'], array(&$this, 'show'), $page, $this->_meta_box['context'], $this->_meta_box['priority']);
        }
    }

    // Callback function to show fields in meta box
    function show() {
        global $post;

        // Use nonce for verification
        echo '<input type="hidden" name="gavern_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';

        echo '<table class="form-table gkform">';
        foreach ($this->_meta_box['fields'] as $field) {
            // get current post meta data
            $meta = get_post_meta($post->ID, $field['id'], true);
            echo '<tr id="gk_', $field['id'], '">';
				if ($field['name']) {
					echo '<th style="width:55%"><label for="', $field['id'], '">', $field['name'], '</label></th>';
				}
			echo	'<td>';
            switch ($field['type']) {
                case 'text':
                    echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" />',
                        '<br />', $field['desc'];
                    break;
                case 'textarea':
                    echo '<textarea name="', $field['id'], '" id="', $field['id'], '" rows="5" style="width:100%">', $meta ? $meta : $field['std'], '</textarea>',
                        '<br />', $field['desc'];
                    break;
                case 'select':
                    echo '<select name="', $field['id'], '" id="', $field['id'], '">';
                    foreach ($field['options'] as $option) {
                        echo '<option value="', $option['value'], '"', $meta == $option['value'] ? ' selected="selected"' : '', '>', $option['name'], '</option>';
                    }
                    echo '</select>';
                    break;
                case 'radio':
                    foreach ($field['options'] as $option) {
                        echo '<input style="margin-right: 3px; margin-left: 3px;" type="radio" name="', $field['id'], '" value="', $option['value'], '"', $meta == $option['value'] ? ' checked="checked"' : '', ' />', $option['name'];
                    }
                    break;
                case 'checkbox':
                    echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' />';
                    break;
            }
            echo     '<td>',
                '</tr>';
        }

        echo '</table>';
    }

    // Save data from meta box
    function save($post_id) {
        // Verify nonce
        if (!wp_verify_nonce($_POST['gavern_meta_box_nonce'], basename(__FILE__))) {
            return $post_id;
        }

        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        // Check permissions
        if ('page' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) {
                return $post_id;
            }
        } elseif (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }

        foreach ($this->_meta_box['fields'] as $field) {
            $name = $field['id'];

            $old = get_post_meta($post_id, $name, true);
            $new = $_POST[$field['id']];

            if ($field['type'] == 'textarea') {
                $new = htmlspecialchars($new);
            }

            // Validate meta value
            if (isset($field['validate_func'])) {
                $ok = call_user_func(array('gavern_meta_box_validate', $field['validate_func']), $new);
                if ($ok === false) {
                    continue;
                }
            }

            if ($new && $new != $old) {
                update_post_meta($post_id, $name, $new);
            }
        }
    }
}
/**
 *
 * Hide Meta Boxes and fields based on template or page
 *
 **/
 
add_action('admin_head', 'gavern_metaboxhide_script');

function gavern_metaboxhide_script() {
    global $current_screen;
    if('page' != $current_screen->id) return;

    echo <<<HTML
        <script type="text/javascript">
        jQuery(document).ready( function($) {

            /**
             * Adjust visibility of the Post - Page options at startup
            */
            if(jQuery('#page_template').val() == 'default') {
				jQuery('#gk_gavern-meta-show-title').show();
                jQuery('#gk_gavern-meta-show-post-format').show();
				jQuery('#gk_gavern-meta-show-date').show();
				jQuery('#gk_gavern-meta-show-category').show();
				jQuery('#gk_gavern-meta-show-author').show();
				jQuery('#gk_gavern-meta-show-comment-link').show();
				jQuery('#gk_gavern-meta-show-edit-link').show();
            } else {
                // hide unneccessary fields
                jQuery('#gk_gavern-meta-show-post-format').hide();
				jQuery('#gk_gavern-meta-show-date').hide();
				jQuery('#gk_gavern-meta-show-category').hide();
				jQuery('#gk_gavern-meta-show-author').hide();
				jQuery('#gk_gavern-meta-show-comment-link').hide();
				jQuery('#gk_gavern-meta-show-edit-link').hide();
            }
            /**
             * Live adjustment of the Post - Page options visibility
            */
            jQuery('#page_template').live('change', function(){
                    if(jQuery(this).val() == 'default') {
					jQuery('#gk_gavern-meta-show-title').show();
					jQuery('#gk_gavern-meta-show-post-format').show();
					jQuery('#gk_gavern-meta-show-date').show();
					jQuery('#gk_gavern-meta-show-category').show();
					jQuery('#gk_gavern-meta-show-author').show();
					jQuery('#gk_gavern-meta-show-comment-link').show();
					jQuery('#gk_gavern-meta-show-edit-link').show();
                } else {
                    // hide unneccessary fields
					jQuery('#gk_gavern-meta-show-post-format').hide();
					jQuery('#gk_gavern-meta-show-date').hide();
					jQuery('#gk_gavern-meta-show-category').hide();
					jQuery('#gk_gavern-meta-show-author').hide();
					jQuery('#gk_gavern-meta-show-comment-link').hide();
					jQuery('#gk_gavern-meta-show-edit-link').hide();
				}
            });			
            /**
             * Adjust visibility of the contact meta box at startup
            */
            if(jQuery('#page_template').val() == 'template.contact.php') {
                // show contact meta box
                jQuery('#gavern-meta-contact_options').show();
            } else {
                // hide contact meta box
                jQuery('#gavern-meta-contact_options').hide();
            }
            /**
             * Live adjustment of the contact meta box visibility
            */
            jQuery('#page_template').live('change', function(){
                    if(jQuery(this).val() == 'template.contact.php') {
                    // show contact meta box
                    jQuery('#gavern-meta-contact_options').show();
                } else {
                    // hide contact meta box
                    jQuery('#gavern-meta-contact_options').hide();
                }
            });	
        });    
        </script>
HTML;
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
				 	<option value="archive">'.__('Archive', GKTPLNAME).'</option>
				 	<option value="author:">'.__('Author', GKTPLNAME).'</option>
				 	<option value="search">'.__('Search page', GKTPLNAME).'</option>
				 	<option value="page404">'.__('404 page', GKTPLNAME).'</option>
				 </select>
				 <p><label>'.__('Page ID/Title/slug:', GKTPLNAME).'<input type="text" class="gk_widget_rules_form_input_page" /></label></p>
				 <p><label>'.__('Post ID/Title/slug:', GKTPLNAME).'<input type="text" class="gk_widget_rules_form_input_post" /></label></p>
				 <p><label>'.__('Category ID/Name/slug:', GKTPLNAME).'<input type="text" class="gk_widget_rules_form_input_category" /></label></p>
				 <p><label>'.__('Tag ID/Name:', GKTPLNAME).'<input type="text" class="gk_widget_rules_form_input_tag" /></label></p>
				 <p><label>'.__('Author:', GKTPLNAME).'<input type="text" class="gk_widget_rules_form_input_author" /></label></p>
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