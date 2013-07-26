<?php

// disable direct access to the file	
defined('GAVERN_WP') or die('Access denied');	

/**
 *
 * Layout functions
 *
 * Group of functions used in the layout - help to create the page structure
 *
 **/
 
/**
 *
 * Function used to load specific layout parts
 *
 * @return null
 *
 **/
function gk_load($part_name, $assets = null, $args = null) {	
	
	if($assets !== null) {
		foreach($assets as $key => $value) {
			if($key == 'css') {
				wp_enqueue_style('gavern-gallery-template', $value, array('gavern-style'));
			} elseif($key == 'js') {
				wp_enqueue_script('gavern-gallery-template', $value, array('jquery'));
			}
		}
	}

	include(gavern_file('layouts/' . $part_name . '.php'));
	
	if ($part_name = 'header') {
	    do_action( 'get_header', $part_name );
	}
}
 
/**
 *
 * Function used to generate the template full title
 *
 * @return null
 *
 **/
function gk_title() {
	// The $paged global variable contains the page number of a listing of posts.
	// The $page global variable contains the page number of a single post that is paged.
	// We'll display whichever one applies, if we're not looking at the first page.
	global $paged, $page;
	// access to the template object
	global $tpl;
	// check if the page is a search result
	if ( is_search() ) {
		// If we're a search, let's start over:
		$title = sprintf( __( 'Search results for %s', GKTPLNAME ), '"' . get_search_query() . '"' );
		// Add a page number if we're on page 2 or more:
		if ( $paged >= 2 ) {
			$title .= " " . sprintf( __( 'Page %s', GKTPLNAME ), $paged );
		}
		// return the title			
		echo $title;
	}
	// if user enabled our SEO override
	if(get_option($tpl->name . '_seo_use_gk_seo_settings') == 'Y') {
		// get values from panel if enabled
		$blogname = get_option($tpl->name . '_seo_blogname');
		$desc = get_option($tpl->name . '_seo_description');
		// create the first part of the title
		$prepared = str_replace_once(get_bloginfo( 'name', 'Display' ), '', wp_title('', false));
		$title = is_home() ? $desc : $prepared;
		// return first part with site name without space characters at beginning
		echo ltrim($title); 
		// if showing blogname in title is enabled - show second part
		if(get_option($tpl->name . '_seo_use_blogname_in_title') == 'Y') {
			// separator defined by user (from recommended list): '|', ',', '-', ' ' 
			echo ' ' . get_option($tpl->name . '_seo_separator_in_title') . ' '; 
			echo $blogname;
		}
	} else { // in other case
		// return the standard title
		if(is_home()) { 
			bloginfo('name');
			echo ' &raquo; '; 
			bloginfo('description');
		} else { 
			wp_title( '|', true, 'right' );
		}
	}
}

/**
 *
 * Function used to generate the template blog name
 *
 * @return string
 *
 **/
function gk_blog_name() {
	// access to the template object
	global $tpl;
	// if user enabled our SEO override
	if(get_option($tpl->name . '_seo_use_gk_seo_settings') == 'Y') {
		// blog name from template SEO options
		return apply_filters('gavern_blog_name', get_option($tpl->name . '_seo_blogname'));
	} else { // in other case
		// output standard blog name
		return apply_filters('gavern_blog_name', get_bloginfo( 'name' ));
	}
}

/**
 *
 * Function used to generate the template description
 *
 * @return string
 *
 **/
function gk_blog_desc() {
	// access to the template object
	global $tpl;
	// if user enabled our SEO override
	if(get_option($tpl->name . '_seo_use_gk_seo_settings') == 'Y') {
		// description from template SEO options
		return apply_filters('gavern_blog_desc', get_option($tpl->name . '_seo_description'));
	} else { // in other case
		// output standard blog description
		return apply_filters('gavern_blog_desc', get_bloginfo( 'description' ));
	}
}

/**
 *
 * Function used to generate a Logo image based on the branding options
 *
 * @return null
 *
 **/
function gk_blog_logo() {
	// access to the template object
	global $tpl;
	// variable for the logo text
	$logo_text = '';
	// check the logo image type:
	if(get_option($tpl->name . "_branding_logo_type", 'css') == 'image') {
		// check the logo text type
		if(get_option($tpl->name . "_branding_logo_text_type", 'wp') == 'wp') {
			$logo_text = gk_blog_name() . ' - ' . gk_blog_desc();	
		} else {
			$logo_text = get_option($tpl->name . "_branding_logo_text_value", '') . ' - ' . get_option($tpl->name . "_branding_logo_slogan_value", '');
		}
		// return the logo output
		echo '<img src="'.get_option($tpl->name . "_branding_logo_image", '').'" alt="' . $logo_text . '" width="'.get_option($tpl->name . "_branding_logo_image_width", 128).'" height="'.get_option($tpl->name . "_branding_logo_image_height", 128).'" />';
	} else { // text logo
		// get the logo text type
		if(get_option($tpl->name . "_branding_logo_text_type", 'wp') == 'wp') {
			$logo_text = gk_blog_name() . ' <small>' . gk_blog_desc() . '</small>';	
		} else {
			$logo_text = get_option($tpl->name . "_branding_logo_text_value", '') . '<small>' . get_option($tpl->name . "_branding_logo_slogan_value", '') . '</small>';
		}
		// return the logo output
		echo apply_filters('gavern_logo_html', $logo_text);
	}
}

/**
 *
 * Function used to generate the template metatags
 *
 * @return null 
 *
 **/
function gk_metatags() {
	// access to the template object
	global $tpl;
	// check if the SEO settings are enabled
	if(get_option($tpl->name . '_seo_use_gk_seo_settings') == 'Y') {
		if(is_home() || is_front_page()) {
			if(get_option($tpl->name . '_seo_homepage_desc') == 'custom') {
				echo apply_filters('gavern_meta_description', '<meta name="description" content="'.get_option($tpl->name . '_seo_homepage_desc_value').'" />');
			}
			
			if(get_option($tpl->name . '_seo_homepage_keywords') == 'custom') {
				echo apply_filters('gavern_meta_keywords', '<meta name="keywords" content="'.get_option($tpl->name . '_seo_homepage_keywords_value').'" />');
			}
		}
		
		if(is_single()) {
			global $wp_query;
			$postID = $wp_query->post->ID;
		
			if(get_post_meta($postID, 'gavern-post-desc', true) != '') {
				if(get_option($tpl->name . '_seo_post_desc') == 'custom') {
					echo apply_filters('gavern_meta_description', '<meta name="description" content="'.get_post_meta($postID, 'gavern-post-desc',true).'" />');
				}
			}
			 			
			if(get_post_meta($postID, 'gavern-post-keywords', true) != '') {
				if(get_option($tpl->name . '_seo_post_keywords') == 'custom') {
					echo apply_filters('gavern_meta_keywords', '<meta name="keywords" content="'.get_post_meta($postID, 'gavern-post-keywords',true).'" />');
				}
			}
		}
	}
}

/**
 *
 * Function used to generate the template Open Graph tags
 *
 * @return null
 *
 **/
function gk_opengraph_metatags() {
	// access to the template object
	global $tpl;
	// check if the Open Graph is enabled
	if(get_option($tpl->name . '_opengraph_use_opengraph') == 'Y') {
		if(is_single() || is_page()) {
			global $wp_query;
			//
			$postID = $wp_query->post->ID;
			//
			$title = get_post_meta($postID, 'gavern_opengraph_title', true);
			$type = get_post_meta($postID, 'gavern_opengraph_type', true);
			$image = wp_get_attachment_url(get_post_meta($postID, 'gavern_opengraph_image', true));
			
			if($image == '') {
				$image = wp_get_attachment_image_src( get_post_thumbnail_id( $postID ), 'single-post-thumbnail' );
				$image = $image[0];
			}
			
			$desc = get_post_meta($postID, 'gavern_opengraph_desc', true);
			$other = get_post_meta($postID, 'gavern_opengraph_other', true);
			//
			echo apply_filters('gavern_og_title', '<meta name="og:title" content="'.(($title == '') ? $wp_query->post->post_title : $title).'" />' . "\n");
			//
			if($image != '') {
				echo apply_filters('gavern_og_image', '<meta name="og:image" content="'.$image.'" />' . "\n");
			}
			//
			echo apply_filters('gavern_og_type', '<meta name="og:type" content="'.(($type == '') ? 'article' : $type).'" />' . "\n");
			//
			echo apply_filters('gavern_og_description', '<meta name="og:description" content="'.(($desc == '') ? substr(str_replace("\"", '', strip_tags($wp_query->post->post_content)), 0, 200) : $desc).'" />' . "\n");
			//
			echo apply_filters('gavern_og_url', '<meta name="og:url" content="'.get_permalink($postID).'" />' . "\n");
			//
			if($other != '') {
				$other = preg_split('/\r\n|\r|\n/', $other);
				//
				foreach($other as $item) {
					//
					$item = explode('=', $item);
					//	
					if(count($item) >= 2) {
						echo apply_filters('gavern_og_custom', '<meta name="'.$item[0].'" content="'.$item[1].'" />' . "\n");
					}
				}
			}
		}
	}
}

/**
 *
 * Function used to check if menu should be displayed
 *
 * @param name - name of the menu to check
 *
 * @return bool
 *
 **/
function gk_show_menu($name) {
	global $tpl;
	
	// check if specific theme_location has assigned menu
	if (has_nav_menu($name)) {
		// if yes - please check menu confition function
		$conditional_function = false;
		
		if($tpl->menu[$name]['state_rule'] != '') {
			$conditional_function = create_function('', 'return '.$tpl->menu[$name]['state_rule'].';');
		}
		
		if(
			$tpl->menu[$name]['state'] == 'Y' ||
			(
				$tpl->menu[$name]['state'] == 'rule' && $conditional_function()
			)
		) {
			return true;
		} else {
			return false;
		}
	} else {
		// if there is no assigned menu for specific theme_location
		return false;
	}
}

/**
 *
 * Function used to generate some template settings
 *
 * @return null
 *
 **/
function gk_head_config() {
	// access the main template object
	global $tpl;
	// output the start script tag
	echo "<script type=\"text/javascript\">\n";
	echo "           \$GK_PAGE_URL = '".home_url()."';\n";
	echo "           \$GK_TMPL_URL = '".gavern_file_uri(false)."';\n";
	echo "           \$GK_TMPL_NAME = '".$tpl->name."';\n";
	echo "           \$GK_MENU = [];\n";
	// output the menu config
	foreach($tpl->menu as $menuname => $settings) {
		echo "           \$GK_MENU[\"".$menuname."\"] = [];\n";
		echo "           \$GK_MENU[\"".$menuname."\"][\"animation\"] = \"".$settings['animation']."\";\n";
		echo "           \$GK_MENU[\"".$menuname."\"][\"animation_speed\"] = \"".$settings['animation_speed']."\";\n";
	}
	// output the finish script tag
	echo "        </script>\n";
}

/**
 *
 * Function used to check if breadcrumbs should be displayed
 *
 * @return bool
 *
 **/
function gk_show_breadcrumbs() {
	// access the template object
	global $tpl;
	
	$conditional_function = false;
	
	if(get_option($tpl->name . '_breadcrumbs_state', 'Y') == 'rule') {
		$state_rule = str_replace('\&#039;', "'", get_option($tpl->name . '_breadcrumbs_staterule', ''));
		$is_correct = preg_match("@^[a-zA-Z0-9\_\s\(\)'\"\-&|!]+$@ms", $state_rule);
		
		if($is_correct) {
			$conditional_function = create_function('', 'return '. $state_rule .';');
		} else {
			$conditional_function = create_function('', 'return FALSE;');
		}
	}
	
	return (get_option($tpl->name . '_breadcrumbs_state', 'Y') == 'Y' || 
		(get_option($tpl->name . '_breadcrumbs_state', 'Y') == 'rule' && $conditional_function()));
}

/**
 *
 * Function used to generate the breadcrumbs output
 *
 * @return null
 *
 **/
function gk_breadcrumbs_output() {
	// open the breadcrumbs tag
	$output = '<nav class="gk-breadcrumbs">';
	// check if we are on the post or normal page
	if (!is_home()) {
		// return the Home link
		$output .= '<a href="' . home_url() . '" class="gk-home">' . apply_filters('gavern_breadcrumb_home', get_bloginfo('name')) . "</a>";
		// if page is category or post
		if (is_category() || is_singular()) {
			// return the category link
			$output .= get_the_category_list(' ');
			// if it is a post page
			if (is_singular()) {
				// return link the name of current post
				$output .= '<span class="gk-current">' . get_the_title() . '</span>';
			}			
		// if it is a normal page
		} elseif (is_page()) { 
			// output the page name
			$output .= get_the_title('<span class="gk-current">', '</span>');
		} elseif (is_tag() && isset($_GET['tag'])) {
			// output the tag name
			$output .= '<span class="gk-current">' . __('Tag: ', GKTPLNAME) . strip_tags($_GET['tag']) . '</span>';
		} elseif (is_author() && isset($_GET['author'])) {
			// get the author name
			$id = strip_tags($_GET['author']);
			if(is_numeric($id)) {
				// output the author name
				$output .= '<span class="gk-current">' . __('Published by: ', GKTPLNAME) . get_the_author_meta('display_name', $id) . '</span>';
			}
		} elseif(is_404()) {
			$output .= '<span class="gk-current">' . __('Page not found', GKTPLNAME) . '</span>';
		} elseif(is_archive()) {
			$output .= '<span class="gk-current">' . __('Archives', GKTPLNAME) . '</span>';
		} elseif(is_search() && isset($_GET['s'])) {
			// output the author name
			$output .= '<span class="gk-current">' . __('Searching for: ', GKTPLNAME) . strip_tags($_GET['s']) . '</span>';
		}
	// if the page is a home
	} else {
		// output the home link only
		$output .= '<a href="' . home_url() . '" class="gk-home">' . get_bloginfo('name') . "</a>";
	}
	// close the breadcrumbs container
	$output .= '</nav>';
	
	echo apply_filters('gavern_breadcrumb', $output);
}

/**
 *
 * Function used to create url to the template style CSS files
 *
 * @return null
 *
 **/
function gk_head_style_css() {
	// get access to the template object
	global $tpl;
	// get access to the WP Customizer
	global $wp_customize;
	// iterate through template styles
	for($i = 0; $i < count($tpl->styles); $i++) {
		// get the value for the specific style
		$stylevalue = get_option($tpl->name . '_template_style_' . $tpl->styles[$i], '');
		// find an url for the founded style
		$url = $tpl->style_colors[$tpl->styles[$i]][$stylevalue];
		// if the customizer is enabled - not use the Cookies to set the styles
		// if the cookies works then the style switcher in the back-end won't work
		if(isset($wp_customize) && $wp_customize->is_preview()) {
			$url = esc_attr($url);
		} else { // when the page isn't previewed
			$url = esc_attr(isset($_COOKIE[$tpl->name.'_style']) ? $_COOKIE[$tpl->name.'_style'] : $url);
		}
		// output the LINK element
		wp_enqueue_style('gavern-style', gavern_file_uri('css/' . $url), array('gavern-mobile'));
	}
}

/**
 *
 * Function used to create urls for stylesheets and scripts for Shortcodes
 *
 * @return null
 *
 **/
function gk_head_shortcodes() {
	// get access to the template object
	global $tpl;
	// check if shortcodes group are enabled
	// typography
	if(get_option($tpl->name . "_shortcodes1_state", 'Y') == 'Y') {
		wp_enqueue_style('gavern-shortcodes-typography', gavern_file_uri('css/shortcodes.typography.css'), array('gavern-extensions'));
		wp_enqueue_script('gavern-shortcodes-typography', gavern_file_uri('js/shortcodes.typography.js'), array('jquery', 'gavern-scripts'), false, true);
	}
	// interactive
	if(get_option($tpl->name . "_shortcodes2_state", 'Y') == 'Y') {
		wp_enqueue_style('gavern-shortcodes-elements', gavern_file_uri('css/shortcodes.elements.css'), array('gavern-extensions'));
		wp_enqueue_script('gavern-shortcodes-elements', gavern_file_uri('js/shortcodes.elements.js'), array('jquery', 'gavern-scripts'), false, true);
	}
	// template
	if(get_option($tpl->name . "_shortcodes3_state", 'Y') == 'Y') {
		wp_enqueue_style('gavern-shortcodes-template', gavern_file_uri('css/shortcodes.template.css'), array('gavern-extensions'));
		wp_enqueue_script('gavern-shortcodes-template', gavern_file_uri('js/shortcodes.template.js'), array('jquery', 'gavern-scripts'), false, true);	
	}
}

/**
 *
 * Function used to create font CSS rules
 *
 * @return HTML output
 *
 **/
function gk_head_fonts() {
	global $tpl;

	$output = "<style type=\"text/css\">\n";

	for($i = 0; $i < count($tpl->fonts); $i++) {
		$selectors = get_option($tpl->name . '_fonts_selectors_' . $tpl->fonts[$i]['short_name'], '');
		$type = get_option($tpl->name . '_fonts_type_' . $tpl->fonts[$i]['short_name'], 'normal');
		$normal = get_option($tpl->name . '_fonts_normal_' . $tpl->fonts[$i]['short_name'], '');
		$squirrel = get_option($tpl->name . '_fonts_squirrel_' . $tpl->fonts[$i]['short_name'], '');
		$google = get_option($tpl->name . '_fonts_google_' . $tpl->fonts[$i]['short_name'], '');
		$edgefonts = get_option($tpl->name . '_fonts_edgefonts_' . $tpl->fonts[$i]['short_name'], '');
		
		if(trim($selectors) != '') {
			$font_family = "";
			
			if($type == 'normal') {
				$normal = str_replace(
				                    array(
				                        "Times New Roman",
				                        "Trebuchet MS",
				                        "Arial Black",
				                        "Palatino Linotype",
				                        "Book Antiqua",
				                        "Lucida Sans Unicode",
				                        "Lucida Grande",
				                        "MS Serif",
				                        "New York",
				                        "Comic Sans MS",
				                        "Courier New",
				                        "Lucida Console",
				                    ),
				                    array(
				                        "'Times New Roman'",
				                        "'Trebuchet MS'",
				                        "'Arial Black'",
				                        "'Palatino Linotype'",
				                        "'Book Antiqua'",
				                        "'Lucida Sans Unicode'",
				                        "'Lucida Grande'",
				                        "'MS Serif'",
				                        "'New York'",
				                        "'Comic Sans MS'",
				                        "'Courier New'",
				                        "'Lucida Console'",
				                    ),
				                    $normal
				                );
			
				$font_family = str_replace('\&#039;', "'", $normal);
			} else if($type == 'squirrel') {				
				echo '<link href="' . gavern_file_uri('fonts/' . $squirrel . '/stylesheet.css') . '" rel="stylesheet" type="text/css" />';
				$font_family = "'" . $squirrel . "'";
			} else if($type == 'google'){
				$fname = array();
				preg_match('@family=(.+)$@is', $google, $fname);
				if(!count($fname)) {
					preg_match('@family=(.+):.+@is', $google, $fname);
				} 
				
				if(!count($fname)) {
					preg_match('@family(.+)\|.+@is', $google, $fname);
				}
				
				$font_family = "'" . str_replace('+', ' ', preg_replace('@:.+@', '', preg_replace('@&.+@', '', $fname[1]))) . "'";
				// We are providing the protocol to avoid duplicated downloads on IE7/8
				$google = ($tpl->isSSL) ? str_replace('http://', 'https://', $google) : $google;
				
				echo '<link href="'.$google.'" rel="stylesheet" type="text/css" />';
			} else {
				$fname = array();
				preg_match('@use.edgefonts.net/(.+)(\.js|:(.+)\.js)$@is', $edgefonts, $fname);
				
				$font_family = $fname[1];
				// We are providing the protocol to avoid duplicated downloads on IE7/8
				$edgefonts = ($tpl->isSSL) ? str_replace('http://', 'https://', $edgefonts) : $edgefonts;
				
				echo '<script src="'.$edgefonts.'"></script>';
			}
			
			$output .= str_replace(array('\\', '&quot;', '&apos;', '&gt;'), array('', '"', '\'', '>'), $selectors) . " { font-family: " . $font_family . "; }\n\n";
		}
	}
	
	$output .= "</style>\n";
	
	echo $output;
}

/**
 *
 * Function used to create links to stylesheets and script files for specific pages
 *
 * @return HTML output
 *
 **/
function gk_head_style_pages() {
	// get access to the template object
	global $tpl;
	// scripts for the contact page
	if( is_page_template('contact.php') ){ 		
		wp_enqueue_script('gavern-contact-validate', gavern_file_uri('js/jquery.validate.min.js'), array('jquery', 'gavern-scripts'), false, true);
		wp_enqueue_script('gavern-contact-main', gavern_file_uri('js/contact.js'), array('jquery', 'gavern-scripts'), false, true);
	}
}

/**
 *
 * Function used to create conditional string
 *
 * @param mode - mode of the condition - exclude, all, include
 * @param input - input data separated by commas, look into example inside the function
 * @param users - the value of the user access
 *
 * @return HTML output
 *
 **/
function gk_condition($mode, $input, $users) {
	// Example input:
	// homepage,page:12,post:10,category:test,tag:test
	
	$output = ' (';
	if($mode == 'all') {
		$output = '';
	} else if($mode == 'exclude') {
		$output = ' !(';
	}
	
	if($mode != 'all') {
		$input = preg_replace('@[^a-zA-Z0-9\-_,;\:\.\s]@mis', '', $input); 
		$input = substr($input, 1);
		$input = explode(',', $input);

		for($i = 0; $i < count($input); $i++) {
			if($i > 0) {
				$output .= '||'; 
			}

			if(stripos($input[$i], 'homepage') !== FALSE) {
			    $output .= ' is_home() ';
			} else if(stripos($input[$i], 'page:') !== FALSE) {
			    $output .= ' is_page(\'' . substr($input[$i], 5) . '\') ';
			} else if(stripos($input[$i], 'post:') !== FALSE) {
			    $output .= ' is_single(\'' . substr($input[$i], 5) . '\') ';
			} else if(stripos($input[$i], 'category:') !== FALSE) {
			    $output .= ' (is_category(\'' . substr($input[$i], 9) . '\') || (in_category(\'' . substr($input[$i], 9) . '\') && is_single())) ';
			} else if(stripos($input[$i], 'tag:') !== FALSE) {
			    $output .= ' (is_tag(\'' . substr($input[$i], 4) . '\') || (has_tag(\'' . substr($input[$i], 4) . '\') && is_single())) ';
			} else if(stripos($input[$i], 'archive') !== FALSE) {
			    $output .= ' is_archive() ';
			} else if(stripos($input[$i], 'author:') !== FALSE) {
			    $output .= ' (is_author(\'' . substr($input[$i], 7) . '\')) ';
		    } else if(stripos($input[$i], 'template:') !== FALSE) {
		        if(substr($input[$i], 9) != '') {
		       		$output .= ' (is_page_template(\'' . substr($input[$i], 9) . '.php\') && is_singular()) ';
		       	} else {
		       		$output .= ' (is_page_template() && is_singular()) ';
		       	}
	        } else if(stripos($input[$i], 'format:') !== FALSE) {
	        	if(substr($input[$i], 7 != '')) {
	            	$output .= ' (has_term( \'post_format\', \'post-format-' . substr($input[$i], 7) . '\') && is_single()) ';
	            } else {
	            	$output .= ' (has_term( \'post_format\') && is_single()) ';
	            }
			} else if(stripos($input[$i], 'taxonomy:') !== FALSE) {
			    if(substr($input[$i], 9) != '') {
			    	$taxonomy = substr($input[$i], 9);
			    	$taxonomy = explode(';', $taxonomy);
			    	// check amount of taxonomies
			    	
			    	if(count($taxonomy) == 1) {
			    	     $output .= ' (is_tax(\'' . $taxonomy[0] . '\'))';
			    	} else if(count($taxonomy) == 2) {
			    	     $output .= ' (has_term(\'' . $taxonomy[1] . '\', \'' . $taxonomy[0] . '\')) ';
			    	}
			   	}
			} else if(stripos($input[$i], 'posttype:') !== FALSE) {
			    if(substr($input[$i], 9) != '') {
			    	$type = substr($input[$i], 9);
			    	// check for post types
			    	if($type != '') {
			   			$output .= ' (get_post_type() == \'' . $type . '\' && is_single()) ';
			   		}
			   	}
			} else if(stripos($input[$i], 'search') !== FALSE) {
			    $output .= ' is_search() ';
			} else if(stripos($input[$i], 'page404') !== FALSE) {
			    $output .= ' is_404() ';
			}
		}

		$output .= ')';
	}
	
	if($users != 'all') {
		if($users == 'guests') {
			$output .= (($output == '') ? '' : ' && ') . ' !is_user_logged_in()';
		} else if($users == 'registered') {
			$output .= (($output == '') ? '' : ' && ') . ' is_user_logged_in()';
		} else if($users == 'administrator') {
			$output .= (($output == '') ? '' : ' && ') . ' current_user_can(\'manage_options\')';
		}
	}
	
	if($output == '') {
		$output = ' TRUE';
	}
	
	return $output;
}

/**
 *
 * Function used to check if given sidebar is active
 *
 * @param index - index of the sidebar
 *
 * @return bool/int
 * 
 **/
function gk_is_active_sidebar( $index ) {
	// get access to the template object
	global $tpl;
	// get access to registered widgets
	global $wp_registered_widgets;
	// sidebar flag
	$sidebar_flag = false;
	//
	if($GLOBALS['pagenow'] !== 'wp-signup.php' && $GLOBALS['pagenow'] !== 'wp-activate.php') {
		// generate sidebar index
		$index = ( is_int($index) ) ? "sidebar-$index" : sanitize_title($index);
		// getting the widgets
		$sidebars_widgets = wp_get_sidebars_widgets();
		// get the widget showing rules
		$options_type = get_option($tpl->name . '_widget_rules_type');
		$options = get_option($tpl->name . '_widget_rules');
		$users = get_option($tpl->name . '_widget_users');
		// if some widget exists
		if ( !empty($sidebars_widgets[$index]) ) {
			$widget_counter = 0;
			foreach ( (array) $sidebars_widgets[$index] as $id ) {
				// if widget doesn't exists - skip this iteration
				if ( !isset($wp_registered_widgets[$id]) ) continue;
				// if the widget rules are enabled
				if(get_option($tpl->name . '_widget_rules_state') == 'Y') {
					// check the widget rules
					$conditional_result = false;
					// create conditional function based on rules
					if( isset($options[$id]) && $options[$id] != '' ) {
						// create function
						$conditional_function = create_function('', 'return '.gk_condition($options_type[$id], $options[$id], $users[$id]).';');
						// generate the result of function
						$conditional_result = $conditional_function();
					}
					// if condition for widget isn't set or is TRUE
					if( !isset($options[$id]) || $options[$id] == '' || $conditional_result === TRUE ) {
						// return TRUE, because at lease one widget exists in the specific sidebar
						$sidebar_flag = true;
						$widget_counter++;
					}
					// set the state of the widget
					$wp_registered_widgets[$id]['gkstate'] = $conditional_result;
				} else {
					$widget_counter++;
					$sidebar_flag = true;
					$wp_registered_widgets[$id]['gkstate'] = true;
				}
			}
			// change num 
			foreach ( (array) $sidebars_widgets[$index] as $id ) {
				// if widget doesn't exists - skip this iteration
				if ( !isset($wp_registered_widgets[$id]) ) continue;
				// save the class
				$wp_registered_widgets[$id]['gkcount'] = $widget_counter;
			}
		}
	}
	// if there is no widgets in the sidebar
	return $sidebar_flag;
}

/**
 *
 * Function used to generate the template sidebars
 *
 * @param index - index of the sidebar
 *
 * @return bool
 *
 **/
function gk_dynamic_sidebar($index) {
	// get access to the template object
	global $tpl;
	// get access to registered sidebars and widgets
	global $wp_registered_sidebars;
	global $wp_registered_widgets;
	// prepare index of the sidebar
	$index = sanitize_title($index);
	// get the widget showing rules
	$options_type = get_option($tpl->name . '_widget_rules_type');
	$options = get_option($tpl->name . '_widget_rules');
	$styles = get_option($tpl->name . '_widget_style');
	$styles_css = get_option($tpl->name . '_widget_style_css');
	$responsive = get_option($tpl->name . '_widget_responsive');
	// find sidebar with specific name
	foreach ( (array) $wp_registered_sidebars as $key => $value ) {
		if ( sanitize_title($value['name']) == $index ) {
			$index = $key;
			break;
		}
	}
	// get sidebars widgets list
	$sidebars_widgets = wp_get_sidebars_widgets();
	// if the list is empty - finish the function
	if ( empty( $sidebars_widgets ) ) {
		return false;
	}
	// if specified sidebar doesn't exist - finish the function
	if ( empty($wp_registered_sidebars[$index]) || 
		!array_key_exists($index, $sidebars_widgets) || 
		!is_array($sidebars_widgets[$index]) || 
		empty($sidebars_widgets[$index]) ) {
		return false;
	}
	// if the sidebar exists - get it
	$sidebar = $wp_registered_sidebars[$index];
	// widget counter
	$counter = 0;
	// run hook
	do_action('gavernwp_before_sidebar');
	// iterate through specified sidebar widget
	foreach ( (array) $sidebars_widgets[$index] as $id ) {
		// if widget doesn't exists - skip this iteration
		if ( !isset($wp_registered_widgets[$id]) ) continue;
		// if condition for widget isn't set or is TRUE
		if( !isset($options[$id]) || $options[$id] == '' || $wp_registered_widgets[$id]['gkstate'] == TRUE ) {
			$counter++;
			// get the widget params and merge with sidebar data, widget ID and name
			$params = array_merge(
				array( 
					array_merge( 
						$sidebar, 
						array(
							'widget_id' => $id, 
							'widget_name' => $wp_registered_widgets[$id]['name']
						) 
					) 
				),
				
				(array) $wp_registered_widgets[$id]['params']
			);
			// Substitute HTML id and class attributes into before_widget
			$classname_ = '';
			foreach ( (array) $wp_registered_widgets[$id]['classname'] as $cn ) {
				if ( is_string($cn) ) $classname_ .= '_' . $cn;
				elseif ( is_object($cn) ) $classname_ .= '_' . get_class($cn);
			}
			// only for the widget areas where the amount of widgets is bigger than 1			
			if(isset($tpl->widgets[$index]) && $tpl->widgets[$index] > 1) {
				$widget_class = '';
				$widget_amount = $wp_registered_widgets[$id]['gkcount'];
				// set the col* classes
				$widget_class = ' col' . $tpl->widgets[$index];
				// set the nth* classes
				if($counter % $tpl->widgets[$index] == 0) {
					$widget_class .= ' nth' . $tpl->widgets[$index];
				} else {
					$widget_class .= ' nth' . $counter % $tpl->widgets[$index];
				}
				// set the last classes
				$last_amount = $widget_amount % $tpl->widgets[$index];
				if(
					$last_amount > 0 && 
					$counter > $widget_amount - $last_amount
				) {
					$widget_class .= ' last' . $last_amount; 
				}
				//
				$classname_ .= $widget_class;
			}
			// trim the class name
			$classname_ = ltrim($classname_, '_');
			// define the code before widget
			if( (isset($styles[$id]) && $styles[$id] != '') || (isset($responsive[$id]) && $responsive[$id] != '' && $responsive[$id] != 'all')) {
				$css_class = '';
				
				if($styles[$id] == 'gkcustom') {
					$css_class = $styles_css[$id];
				} else {
					$css_class = $styles[$id];
				}
			
				$params[0]['before_widget'] = sprintf($params[0]['before_widget'], $id, ' ' . $css_class . ' ' . $responsive[$id] . ' ' . $classname_);
			} else {
				$params[0]['before_widget'] = sprintf($params[0]['before_widget'], $id, ' ' . $classname_);
			}
			// apply params
			$params = apply_filters( 'dynamic_sidebar_params', $params );
			// get the widget callback function
			$callback = $wp_registered_widgets[$id]['callback'];
			// generate the widget
			do_action( 'dynamic_sidebar', $wp_registered_widgets[$id] );
			// use the widget callback function if exists
			if ( is_callable($callback) ) {
				call_user_func_array($callback, $params);
			}
		}
	}
	// run hook
	do_action('gavernwp_after_sidebar');
}

/**
 *
 * Code used to implement thickbox in the page
 *
 * @return null
 * 
 **/
function gk_thickbox_load() {
	//
	global $tpl;
	//
	if(get_option($tpl->name . '_thickbox_state') == 'Y') : 
	?>
	<script type="text/javascript">
		var thickboxL10n = {
			"next":"<?php _e('Next >', GKTPLNAME); ?>",
			"prev":"<?php _e('< Prev', GKTPLNAME); ?>",
			"image":"<?php _e('Image', GKTPLNAME); ?>",
			"of":"<?php _e('of', GKTPLNAME); ?>",
			"close":"<?php _e('Close', GKTPLNAME); ?>",
			"noiframes":"<?php _e('This feature requires inline frames. You have iframes disabled or your browser does not support them.', GKTPLNAME); ?>",
			"loadingAnimation":"<?php echo home_url(); ?>/wp-includes/js/thickbox/loadingAnimation.gif",
			"closeImage":"<?php echo home_url(); ?>/wp-includes/js/thickbox/tb-close.png"
		};
	</script>

	<?php
		wp_enqueue_style('gavern-thickbox', home_url() . '/wp-includes/js/thickbox/thickbox.css', array('gavern-extensions'));
		wp_enqueue_script('gavern-thickbox', home_url() . '/wp-includes/js/thickbox/thickbox.js', array('jquery', 'gavern-scripts'), false, true);
	endif;
}

// EOF