<?php
	
// disable direct access to the file	
defined('GAVERN_WP') or die('Access denied');	

/**
 *
 * Shortcodes
 *
 * Groups of shortcodes
 *
 * - typography
 * - page interactive elements
 * - template specific shortcodes
 *
 **/

/**
 *
 * Typography shortcodes
 *
 * CSS loaded from shortcodes.typography.css
 * JS loaded from shortcodes.typography.js
 *
 **/

if(get_option($tpl->name . '_shortcodes1_state', 'Y') == 'Y') {
	
	/**
	 *
	 * Warnings
	 *
	 **/
	if(!function_exists('gavern_ts_warning')) {
		// Usage:
		// [warning]text[/warning]
		function gavern_ts_warning($atts, $content) {   
			// return the url
		    return '<p class="gk-warning">'.$content.'</p>';  
		} 
		// add the shortcode to system
		add_shortcode('warning', 'gavern_ts_warning');
	} 
	
	/**
	 *
	 * Info
	 *
	 **/
	if(!function_exists('gavern_ts_info')) {
		// Usage:
		// [info]text[/info]
		function gavern_ts_info($atts, $content) {   
			// return the url
		    return '<p class="gk-info">'.$content.'</p>';  
		} 
		// add the shortcode to system
		add_shortcode('info', 'gavern_ts_info');
	} 
		
	/**
	 *
	 * Notices
	 *
	 **/
	if(!function_exists('gavern_ts_notice')) {
		// Usage:
		// [notice]text[/notice]
		function gavern_ts_notice($atts, $content) {   
			// return the url
		    return '<p class="gk-notice">'.$content.'</p>';  
		} 
		// add the shortcode to system
		add_shortcode('notice', 'gavern_ts_notice');
	} 
	
	/**
	 *
	 * Errors
	 *
	 **/
	if(!function_exists('gavern_ts_error')) {
		// Usage:
		// [error]text[/error]
		function gavern_ts_error($atts, $content) {   
			// return the url
		    return '<p class="gk-error">'.$content.'</p>';  
		} 
		// add the shortcode to system
		add_shortcode('error', 'gavern_ts_error');
	} 
		
	/**
	 *
	 * Highlights
	 *
	 **/
	if(!function_exists('gavern_ts_label')) {
		// Usage:
		// [label]text[/label]
		// [label style="2"]text[/label]
		function gavern_ts_label($atts, $content) {   
			// get the optional style value
			extract(shortcode_atts( array('style' => '1'), $atts));
			// return the url
		    return '<strong class="gk-label" data-style="style' . $style . '">'.$content.'</strong>';  
		} 
		// add the shortcode to system
		add_shortcode('label', 'gavern_ts_label');
	}
	
	/**
	 *
	 * Badges
	 *
	 **/
	if(!function_exists('gavern_ts_badge')) {
		// Usage:
		// [badge]text[/badge]
		// [badge style="2"]text[/badge]
		function gavern_ts_badge($atts, $content) {   
			// get the optional style value
			extract(shortcode_atts( array('style' => '1'), $atts));
			// return the url
		    return '<strong class="gk-badge" data-style="style' . $style . '">'.$content.'</strong>';  
		} 
		// add the shortcode to system
		add_shortcode('badge', 'gavern_ts_badge');
	}
	
	/**
	 *
	 * Code listing
	 *
	 **/
	if(!function_exists('gavern_ts_code')) {
		// Usage:
		// [code]text[/code]
		// [code style="2"]text[/code]
		function gavern_ts_code($atts, $content) {   
			// get the optional style value
			extract(shortcode_atts( array('style' => '1'), $atts));
			// return the element
		    return '<pre class="gk-code" data-style="style' . $style . '"><code>'.str_replace(array('<p>','</p>'), '', $content).'</code></pre>';  
		} 
		// add the shortcode to system
		add_shortcode('code', 'gavern_ts_code');
	} 

	/**
	 *
	 * Text blocks
	 *
	 **/
	if(!function_exists('gavern_ts_text_block')) {
		// Usage:
		// [textblock]text[/textblock]
		// [textblock style="2"]text[/textblock]
		function gavern_ts_text_block($atts, $content) {   
			// get the optional style value
			extract(shortcode_atts( array('style' => '1'), $atts));
			// return the url
		    return '<div class="gk-textblock" data-style="style' . $style . '">'.$content.'</div>';  
		} 
		// add the shortcode to system
		add_shortcode('textblock', 'gavern_ts_text_block');
	}  
	
	/**
	 *
	 * Bubbles
	 *
	 **/
	if(!function_exists('gavern_ts_quote')) {
		// Usage:
		// [quote author="John Doe"]text[/quote]
		// [quote style="2" author="John Doe"]text[/quote]
		function gavern_ts_quote($atts, $content) {   
			// get the optional style value
			extract(shortcode_atts( array('style' => '1', 'author' => ''), $atts));
			// return the url
		    return '<blockquote class="gk-quote" data-style="style' . $style . '"><p>'.$content.'</p><cite>'.$author.'</cite></blockquote>';  
		} 
		// add the shortcode to system
		add_shortcode('quote', 'gavern_ts_quote');
	}
	
	/**
	 *
	 * Dropcaps
	 *
	 **/
	if(!function_exists('gavern_ts_dropcap')) {
		// Usage:
		// [dropcap]text[/dropcap]
		// [dropcap style="2"]text[/dropcap]
		function gavern_ts_dropcap($atts, $content) {   
			// get the optional style value
			extract(shortcode_atts( array('style' => '1'), $atts));
			// return the url
		    return '<p class="gk-dropcap" data-style="style' . $style . '">'.$content.'</p>';  
		} 
		// add the shortcode to system
		add_shortcode('dropcap', 'gavern_ts_dropcap');
	}
	
	/**
	 *
	 * Ordered lists
	 *
	 **/
	if(!function_exists('gavern_ts_olist')) {
		// Usage:
		// [olist]
		// item1
		// item2
		// [/olist]
		//
		// [olist style="2"]
		// item1
		// item2
		// [/olist]
		function gavern_ts_olist($atts, $content) {   
			// get the optional style value
			extract(shortcode_atts( array('style' => '1'), $atts));
			// generate output
		    $output = "<ol data-style=\"style$style\">\n";
		    // get the lines
		    $lines = preg_split( '/\r\n|\r|\n/', $content );
		    // generate the list items
		    if(count($lines)) {
		    	foreach($lines as $line) {
		    		$line = str_replace(array('<br />', '<br/>', '<br>'), '', $line);
		    		if(trim($line) != '') {
		    			$output .= "<li>" . $line . "</li>\n";
		    		}
		    	}
		    }
		    // close the list
		    $output .="\n</ol>";
		    return $output;  
		} 
		// add the shortcode to system
		add_shortcode('olist', 'gavern_ts_olist');
	}
	
	/**
	 *
	 * Unordered lists
	 *
	 **/
	if(!function_exists('gavern_ts_ulist')) {
		// Usage:
		// [ulist]
		// item1
		// item2
		// [/ulist]
		//
		// [ulist style="2"]
		// item1
		// item2
		// [/ulist]
		function gavern_ts_ulist($atts, $content) {   
			// get the optional style value
			extract(shortcode_atts( array('style' => '1'), $atts));
			// generate output
		    $output = "<ul data-style=\"style$style\">\n";
		    // get the lines
		    $lines = preg_split( '/\r\n|\r|\n/', $content );
		    // generate the list items
		    if(count($lines)) {
		    	foreach($lines as $line) {
		    		$line = str_replace(array('<br />', '<br/>', '<br>'), '', $line);
		    		if(trim($line) != '') {
		    			$output .= "<li>" . $line . "</li>\n";
		    		}
		    	}
		    }
		    // close the list
		    $output .="\n</ul>";
		    return $output;  
		} 
		// add the shortcode to system
		add_shortcode('ulist', 'gavern_ts_ulist');
	}
	
	/**
	 *
	 * Number blocks
	 *
	 **/
	
	if(!function_exists('gavern_ts_numblock')) {
		// Usage:
		// [numblock num="01"]Lorem ipsum[/numblock]
		//
		// [numblock num="01" style="2"]Lorem ipsum[/numblock]
		function gavern_ts_numblock($atts, $content) {   
			// get the optional style value
			extract(shortcode_atts( array('num' => '01', 'style' => '1'), $atts));
			// return output
		    return "<p class=\"gk-numblock\" data-style=\"style$style\"><span>" . $num . "</span>" . $content . "</p>";  
		} 
		// add the shortcode to system
		add_shortcode('numblock', 'gavern_ts_numblock');
	}
	
	/**
	 *
	 * Columns
	 *
	 **/
	if(!function_exists('gavern_ts_columns')) {
		// Usage:
		// [columns]
		// [column]Content for the first column[/column]
		// [column]Content for the second column[/column]
		// [column]Content for the third column[/column]
		// [/columns]
		function gavern_ts_columns($atts, $content) {   
		    // get the columns		    
		    preg_match_all( '@\[column\](.*?)\[/column\]@mis', $content, $columns);
		    $output = '';
		    
		    if(isset($columns[1])) {
			    // generate output
			    $output = "<div class=\"gk-columns\" data-column-count=\"" . count($columns[1]) . "\">\n";
			    // generate the list items
			    if(count($columns[1])) {
			    	foreach($columns[1] as $column) {
			    		$output .= "<div>" . $column . "</div>\n";
			    	}
			    }
			    // close the list
			    $output .="\n</div>"; 
		    }
		    return $output; 
		} 
		// add the shortcode to system
		add_shortcode('columns', 'gavern_ts_columns');
	}
	
	/**
	 *
	 * Buttons
	 *
	 **/
	if(!function_exists('gavern_ts_button')) {
		// Usage:
		// [button]text[/button]
		// [button style="2"]text[/button]
		function gavern_ts_button($atts, $content) {   
			// get the optional style value
			extract(shortcode_atts( array('style' => '1'), $atts));
			// return the url
		    return '<button class="gk-button" data-style="style' . $style . '">'.$content.'</button>';  
		} 
		// add the shortcode to system
		add_shortcode('button', 'gavern_ts_button');
	}
	
	/**
	 *
	 * Legends
	 *
	 **/
	if(!function_exists('gavern_ts_legend')) {
		// Usage:
		// [legend title="Title"]text[/legend]
		// [legend title="Title" style="2"]text[/legend]
		function gavern_ts_legend($atts, $content) {   
			// get the optional style value
			extract(shortcode_atts( array('title' => '', 'style' => '1'), $atts));
			// return the url
		    return '<div class="gk-legend" data-style="style' . $style . '"><strong>' . $title . '</strong>'.$content.'</div>';  
		} 
		// add the shortcode to system
		add_shortcode('legend', 'gavern_ts_legend');
	}
	
	/**
	 *
	 * Floated blocks
	 *
	 **/
	if(!function_exists('gavern_ts_float')) {
		// Usage:
		// [floated]text[/floated]
		// [floated align="left"]text[/floated]
		function gavern_ts_float($atts, $content) {   
			// get the optional style value
			extract(shortcode_atts( array('align' => 'left'), $atts));
			// return the url
		    return '<div class="gk-floated" data-align="' . $align . '">' . $content . '</div>';  
		} 
		// add the shortcode to system
		add_shortcode('floated', 'gavern_ts_float');
	}

	/**
	 *
	 * RSS
	 *
	 **/
	 if(!function_exists('gavern_ts_rss')) {
	 	// Usage:
	 	// [rss href="url"]text[/rss]
	 	function gavern_ts_rss($atts, $content) {   
	 		// get the optional style value
	 		extract(shortcode_atts( array('href' => ''), $atts));
	 		// return the url
	 	    return '<a class="gk-rss" href="' . $href . '"><i class="icon-bullhorn"></i>' . $content . '</a>';  
	 	} 
	 	// add the shortcode to system
	 	add_shortcode('rss', 'gavern_ts_rss');
	 }
	
	/**
	 *
	 * Raw text
	 *
	 **/
	if(!function_exists('gavern_ts_raw')) {
		// Usage:
		// [raw]text[/raw]
		// [raw style="1"]text[/raw]
		function gavern_ts_raw($atts, $content) {   
			// get the optional style value
			extract(shortcode_atts( array('style' => '1'), $atts));
			// return the url
			return '<pre class="gk-raw" data-style="style' . $style . '">'.$content.'</pre>';    
		} 
		// add the shortcode to system
		add_shortcode('raw', 'gavern_ts_raw');
	}
	
	/**
	 *
	 * [pageurl]
	 *
	 **/
	if(!function_exists('gavern_ts_pageurl')) {
		// Usage:
		// [pageurl]
		function gavern_ts_pageurl( $atts ){
			// return the url
			return home_url();
		}
		add_shortcode( 'pageurl', 'gavern_ts_pageurl' );
	}
}
 
/**
 *
 * Interactive elements shortcodes
 *
 * CSS loaded from shortcodes.elements.css
 * JS loaded from shortcodes.elements.js
 *
 **/ 
 
if(get_option($tpl->name . '_shortcodes2_state', 'Y') == 'Y') {  	  
	// toggled content
	if(!function_exists('gavern_ies_toggled')) {
		// Usage
		// [toggle header="Header text"]Toggled text[/toggle]
		// [toggle header="Header text" open="true"]Toggled text[/toggle]
		function gavern_ies_toggled($atts, $content) {   
			// get the optional style value
			extract(shortcode_atts( array('header' => '', 'open' => 'false'), $atts));
			// return the code
			return '<div class="gk-toggle '.(($open == 'true') ? 'opened' : 'closed').'"><h3>'.$header.'</h3><div>'.$content.'</div></div>';    
		} 
		// add the shortcode to system
		add_shortcode('toggle', 'gavern_ies_toggled');
	}
	// tooltips
	if(!function_exists('gavern_ies_tooltips')) {
		// Usage:
		// [tip label="Label"]Text of the tooltip[/tip]
		// [tip label="Label" style="1"]Text of the tooltip[/tip]
		// [tip label="Label" style="1" href="http://gavick.com"]Text of the tooltip[/tip]
		function gavern_ies_tooltips($atts, $content) {   
			// get the optional style value
			extract(shortcode_atts( array('href' => '', 'label' => '', 'style' => '1'), $atts));
			// return the code
			if($href != '') {
				return '<a href="'.$href.'" class="gk-tooltip" data-style="style' . $style . '">'.$label.'<dfn>'.$content.'</dfn></a>';
			} else {
				return '<span class="gk-tooltip" data-style="style' . $style . '">'.$label.'<dfn>'.$content.'</dfn></span>';    
			}
		} 
		// add the shortcode to system
		add_shortcode('tip', 'gavern_ies_tooltips');
	} 
	// google docs viewer
	if(!function_exists('gavern_ies_pdflink')) {
		// Usage
		// [pdf url="URL"]Text[/pdf]
		function gavern_ies_pdf($atts, $content) {   
			// get the params
			extract(shortcode_atts( array('url' => ''), $atts)); 
			// return the url
		    return '<a class="gk-pdf" href="http://docs.google.com/viewer?url=' . $url . '"><i class="icon-file"></i>'.$content.'</a>';  
		} 
		// add the shortcode to system
		add_shortcode('pdf', 'gavern_ies_pdf');
	} 
	// private notes
	if(!function_exists('gavern_ies_note')) {
		// Usage:
		// [note]Your private note[/note]
		function gavern_ies_note( $atts, $content = null ) {
			if(current_user_can( 'publish_posts')) {
				return '<div class="gk-note">'.$content.'</div>';
			} else {
				return '';
			}
		}
		
		add_shortcode( 'note', 'gavern_ies_note' );
	}
	// obfuscate an email address
	if(!function_exists('gavern_ies_mail_obfuscate')) {
		// Usage
		// [mail]mail.to@obfuscate.com[mail]
		function gavern_ies_mail_obfuscate( $atts , $content = null ) {
		    $encoded_email = '';
		    
		    for ($i = 0; $i < strlen($content); $i++) {
		    	$encoded_email .= "&#" . ord($content[$i]) . ';';
		    }
		    
		    return '<a href="mailto:'.$encoded_email.'">'.$encoded_email.'</a>';
		}
		
		add_shortcode('mail', 'gavern_ies_mail_obfuscate');
	}
	// content for registered users
	if(!function_exists('gavern_ies_members_content')) {
		// Usage:
		// [members_content]Content for the members only[/members_content]
		function gavern_ies_members_content( $atts, $content = null ) {
			if (is_user_logged_in() && !is_null( $content ) && !is_feed()) {
				return '<div class="gk-members">' . $content . '</div>';
			} else {
				return '';
			}
		}
	
		add_shortcode( 'members_content', 'gavern_ies_members_content' );
	}
	// related posts
	if(!function_exists('gavern_ies_related_posts')) {
		// Usage
		// [related]
		// [related limit="5"]
		function gavern_ies_related_posts($atts) {
			// extract the attributes
			extract(shortcode_atts(array("limit" => "5"), $atts));
			//
			global $tpl, $wpdb, $post, $table_prefix;
			// if the post ID is defined
			if ($post->ID) {
		 		// Get tags
				$tags = wp_get_post_tags($post->ID);
				$tags_array = array();
				
				foreach ($tags as $tag) {
					$tags_array[] = $tag->term_id;
				}
				$tags_array = implode(',', $tags_array);
				$related = false;
				
				if(strlen($tags_array) > 0) {
					// Do the query
					$query = "SELECT 
									posts.post_title AS title,
									posts.ID AS ID, 
									count(relations.object_id) as count
								FROM 
									".($wpdb->term_taxonomy)." AS taxonomy, 
									".($wpdb->term_relationships)." AS relations, 
									".($wpdb->posts)." AS posts 
								WHERE 
									taxonomy.taxonomy ='post_tag' AND 
									taxonomy.term_taxonomy_id = relations.term_taxonomy_id AND
									relations.object_id  = posts.ID AND 
									taxonomy.term_id IN (".$tags_array.") AND 
									posts.ID != ".($post->ID)." AND 
									posts.post_status = 'publish' AND 
									posts.post_date_gmt < NOW()
			 					GROUP BY 
			 						relations.object_id
								ORDER BY 
									count DESC, 
									posts.post_date_gmt DESC
								LIMIT ".$limit."
					;";
					//
					$related = $wpdb->get_results($query);
				}
		 		$output = '<ol class="gk-related">';
		 		if ( $related ) {
					foreach($related as $result) {
						$output .= '<li><a title="'.wptexturize($result->title).'" href="'.get_permalink($result->ID).'">'.wptexturize($result->title).'</a></li>';
					}
				} else {
					$output .= '<li>'.__('No related posts found', GKTPLNAME).'</li>';
				}
				//
				return $output . '</ol>';
			} else {
				return '';
			}
		}
		
		add_shortcode('related', 'gavern_ies_related_posts');
	}  
 }
 
/**
 *
 * Template specific shortcodes
 *
 * CSS loaded from shortcodes.template.css
 * JS loaded from shortcodes.template.js
 *
 **/ 
  
if(get_option($tpl->name . '_shortcodes3_state', 'Y') == 'Y') {  
	// use for the function names prefix gavern_tss_ prefix
	
	/**
	 *
	 * Big header
	 *
	 **/
	if(!function_exists('gavern_tss_big_header')) {
		// Usage:
		// [bigheader]Hello, <span>everyone!</span>[/bigheader]
		function gavern_tss_big_header($atts, $content) {   
			// return the code
			return '<h1 class="gk-big-header">'.$content.'</h1>';    
		} 
		// add the shortcode to system
		add_shortcode('bigheader', 'gavern_tss_big_header');
	}
		
	/**
	 *
	 * Medium header
	 *
	 **/
	if(!function_exists('gavern_tss_medium_header')) {
		// Usage:
		// [mediumheader]Archive page template[/mediumheader]
		function gavern_tss_medium_header($atts, $content) {   
			// return the code
			return '<h2 class="gk-medium-header">'.$content.'</h2>';    
		} 
		// add the shortcode to system
		add_shortcode('mediumheader', 'gavern_tss_medium_header');
	}
	
	/**
	 *
	 * Small header
	 *
	 **/
	if(!function_exists('gavern_tss_small_header')) {
		// Usage:
		// [smallheader]I’m new and really free Wordpress 3.4 template based on the brand-new GavernWP Framework[/smallheader]
		function gavern_tss_small_header($atts, $content) {   
			// return the code
			return '<h2 class="gk-small-header">'.$content.'</h2>';    
		} 
		// add the shortcode to system
		add_shortcode('smallheader', 'gavern_tss_small_header');
	}
	
	/**
	 *
	 * Blue button
	 *
	 **/
	if(!function_exists('gavern_tss_blue_btn')) {
		// Usage:
		// [bluebtn url="#"]View details[/bluebtn]
		function gavern_tss_blue_btn($atts, $content) {   
			// extract the attributes
			extract(shortcode_atts(array("url" => "#"), $atts));
			// return the code
			return '<a href="'.$url.'" class="gk-blue-button">'.$content.'</a>';    
		} 
		// add the shortcode to system
		add_shortcode('bluebtn', 'gavern_tss_blue_btn');
	}
	
	/**
	 *
	 * Yellow button
	 *
	 **/
	if(!function_exists('gavern_tss_yellow_btn')) {
		// Usage:
		// [yellowbtn url="#"]Download[/yellowbtn]
		function gavern_tss_yellow_btn($atts, $content) {   
			// extract the attributes
			extract(shortcode_atts(array("url" => "#"), $atts));
			// return the code
			return '<a href="'.$url.'" class="gk-yellow-button">'.$content.'</a>';    
		} 
		// add the shortcode to system
		add_shortcode('yellowbtn', 'gavern_tss_yellow_btn');
	}
}  
  
/*EOF*/