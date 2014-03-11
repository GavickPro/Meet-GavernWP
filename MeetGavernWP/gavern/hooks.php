<?php

// disable direct access to the file	
defined('GAVERN_WP') or die('Access denied');	

/**
 *
 * Full hooks reference:
 * 
 * Hooks connected with the document:
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
 * gavernwp_ga_code
 *
 * Hooks connected with the content:
 *
 * gavernwp_before_mainbody
 * gavernwp_after_mainbody
 * gavernwp_before_loop
 * gavernwp_after_loop
 * gavernwp_before_nav
 * gavernwp_after_nav
 * gavernwp_before_post_content
 * gavernwp_after_post_content
 * gavernwp_before_column
 * gavernwp_after_column
 * gavernwp_before_sidebar
 * gavernwp_after_sidebar
 *
 * Hooks connected with comments:
 * 
 * gavernwp_before_comments_count
 * gavernwp_after_comments_count
 * gavernwp_before_comments_list
 * gavernwp_after_comments_list
 * gavernwp_before_comment
 * gavernwp_after_comment
 * gavernwp_before_comments_form
 * gavernwp_after_comments_form
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
	global $tpl;
	// generate the <html> language attributes
	language_attributes();
	// generate the prefix attribute
	if(get_option($tpl->name . '_opengraph_use_opengraph') == 'Y') {
		echo ' prefix="og: http://ogp.me/ns#"';
	}
	// generate the cache manifest attribute
	if(trim(get_option($tpl->name . '_cache_manifest', '')) != '') {
		echo ' manifest="'.trim(get_option($tpl->name . '_cache_manifest', '')).'"';
	}
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
	
	// only for IE
	if(preg_match('/MSIE/i',$_SERVER['HTTP_USER_AGENT'])) {
		echo '<meta http-equiv="X-UA-Compatible" content="IE=Edge" />' . "\n";
	}
	echo '<meta charset="'.get_bloginfo('charset').'" />' . "\n";
	echo '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />' . "\n";
	
	// generates Gavern SEO metatags
	gk_metatags();
	// generates Gavern Open Graph metatags
	gk_opengraph_metatags();
	// generates Twitter Cards metatags
	gk_twitter_metatags();
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
	echo '<script src="'.gavern_file_uri('js/html5shiv.js').'"></script>' . "\n";
	echo '<script src="'.gavern_file_uri('js/respond.js').'"></script>' . "\n";
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
 	// generate the mobile attribute
 	if($tpl->browser->get("mobile") == true) {
 		echo ' data-mobile="true"';
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

/**
 *
 * Function used to generate the Google Analytics before the closing <body> tag
 *
 **/

function gavernwp_ga_code_hook() {
	global $tpl;
	// check if the Tracking ID is specified
	if(get_option($tpl->name . '_ga_ua_id', '') != '') {
		?>
		<script type="text/javascript">
		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', '<?php echo get_option($tpl->name . '_ga_ua_id', ''); ?>']);
		  _gaq.push(['_trackPageview']);
		
		  (function() {
		    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();
		</script>
		<?php
	}
}
  
add_action('gavernwp_ga_code', 'gavernwp_ga_code_hook');
 
/**
 * 
 * 
 * 
 * 
 * WP Core actions 
 *
 *
 *
 *
 **/

/**
 *
 * Function used to generate the custom RSS feed link
 *
 **/

function gavernwp_custom_rss_feed_url( $output, $feed ) {
    global $tpl;
    // get the new RSS URL
    $feed_link = get_option($tpl->name . '_custom_rss_feed', '');
    // check the URL
    if(trim($feed_link) !== '') {
	    if (strpos($output, 'comments')) {
	        return $output;
	    }
	
	    return esc_url($feed_link);
    } else {
    	return $output;
    }
}

add_action( 'feed_link', 'gavernwp_custom_rss_feed_url', 10, 2 ); 
 
// EOF