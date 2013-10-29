<?php

// disable direct access to the file	
defined('GAVERN_WP') or die('Access denied');	

/**
 *
 * GavernWP layout fragments
 *
 * Functions used to create GavernWP-specific functions 
 *
 **/
 
/**
 *
 * Template for menus
 *
 * @param menuname - name of the menu
 * @param fullname - full name of the menu - ID
 * @param params - array of the other params (optional)
 *
 * @return HTML output
 *
 **/ 
 
function gavern_menu($menuname, $fullname, $params = null) {			
	global $tpl;
	
	if(gk_show_menu($menuname)) {
		if($params !== null) {
			extract($params);
		}
	
		wp_nav_menu(array(
		      'theme_location'  => $menuname,
			  'container'       => isset($container) ? $container : false, 
			  'container_class' => 'menu-{menu slug}-container', 
			  'container_id'    => $fullname,
			  'menu_class'      => 'menu ' . $tpl->menu[$menuname]['style'], 
			  'menu_id'         => str_replace('menu', '-menu', $menuname),
			  'echo'            => isset($echo) ? $echo : true,
			  'fallback_cb'     => isset($fallback_cb) ? $fallback_cb: 'wp_page_menu',
			  'before'          => isset($before) ? $before : '',
			  'after'           => isset($after) ? $after : '',
			  'link_before'     => isset($link_before) ? $link_before : '',
			  'link_after'      => isset($link_after) ? $link_after : '',
			  'items_wrap'      => isset($items_wrap) ? $items_wrap : '<ul id="%1$s" class="%2$s">%3$s</ul>',
			  'depth'           => $tpl->menu[$menuname]['depth'],
			  'walker'			=> isset($walker) ? $walker : ''
		));
	}
}
 
/**
 *
 * Template for comments and pingbacks.
 *
 * @param comment - the comment to render
 * @param args - additional arguments
 * @param depth - the depth of the comment
 *
 * @return null
 *
 **/
function gavern_comment_template( $comment, $args, $depth ) {
	global $tpl;
	
	$GLOBALS['comment'] = $comment;

	do_action('gavernwp_before_comment');

	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="pingback">
		<p>
			<?php _e( 'Pingback:', GKTPLNAME ); ?> 
			<?php comment_author_link(); ?>
			<?php edit_comment_link( __( 'Edit', GKTPLNAME ), '<span class="edit-link">', '</span>' ); ?>
		</p>
		<?php break; ?>
	<?php default : ?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>">	
			<aside>
				<?php echo get_avatar( $comment, ($comment->comment_parent == '0') ? 40 : 32); ?>
			</aside>
					
			<section class="content">				
				<?php if ( $comment->comment_approved == '0' ) : ?>
				<em class="awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', GKTPLNAME ); ?></em>
				<?php endif; ?>
				
				<?php comment_text(); ?>
				
				<footer>
					<?php
						/* translators: 1: comment author, 2: date and time */
						printf( 
							__( '%1$s on %2$s', GKTPLNAME ),
							sprintf( 
								'<span class="author">%s</span>', 
								get_comment_author_link() 
							),
							sprintf( 
								'<a href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>',
								esc_url( get_comment_link( $comment->comment_ID ) ),
								get_comment_time(DATE_W3C),
								sprintf( __( '%1$s at %2$s', GKTPLNAME ), 
								get_comment_date(), 
								get_comment_time() )
							)
						);
					?>
					
					<?php edit_comment_link( __( 'Edit', GKTPLNAME ), '<span class="edit-link">', '</span>' ); ?>
					
					<span class="reply">
						<?php comment_reply_link( 
							array_merge( 
								$args,
								array( 
									'reply_text' => __( 'Reply', GKTPLNAME ), 
									'depth' => $depth, 
									'max_depth' => $args['max_depth'] 
								) 
							) 
						); ?>
					</span>
				</footer>
			</section>
		</article>

	<?php
			break;
	endswitch;
	
	do_action('gavernwp_after_comment');
}

/**
 *
 * Function used to generate post fields
 *
 * @return null
 *
 **/
function gk_post_fields() {
	global $tpl;
	// check if the custom fields are enabled
	if(get_option($tpl->name . '_custom_fields_state', 'Y') == 'Y') {
		// get the post custom fields
		if ($keys = get_post_custom_keys()) {
			// get the hidden fields array
			$hiddenfields = explode(',', get_option($tpl->name . '_hidden_post_fields', ''));
			// variable for the list items
			$output = '';
			// generate the list
			foreach ((array) $keys as $key) {
				// trim the key name
				$key_trimmed = trim($key);
				// skip the protected meta data and "gavern-" or "gavern_" values
				if(
					is_protected_meta($key_trimmed, 'post') || 
					stripos($key_trimmed, 'gavern-') !== FALSE ||
					stripos($key_trimmed, 'gavern_') !== FALSE ||
					in_array($key_trimmed, $hiddenfields)
					) {
					continue;
				}
				// map the values
				$values = array_map('trim', get_post_custom_values($key));
				// extract the value
				$value = implode($values,', ');
				
				//custom post fileds label mapping
				$mapping = preg_split('/\r\n|\r|\n/', get_option($tpl->name . '_post_fileds_label_mapping'));
				foreach($mapping as $item) {
				     //
				     if(strpos($item, '=') === false) continue;
				     
				     $item = explode('=', $item);
				     
				     if($key != $item[0]) continue;
				     
				     if($item[1] != '' && $item[0] == $key) {
				      		$key = $item[1];
				      }
				  } 
				
				// generate the item
				$output .= apply_filters('the_meta_key', '<dt>'.$key.':</dt>'."\n".'<dd>'.$value.'</dd>'."\n", $key, $value);
			}
			// output the list
			if($output !== '') {
				echo '<dl class="post-fields">' . "\n";
				echo $output;
				echo '</dl>' . "\n";
			}
		}
	}
}

/**
 *
 * Function used to generate post meta data
 *
 * @param attachment - for the attachment size the script generate additional informations
 *
 * @return null
 *
 **/
function gk_post_meta($attachment = false) {
 	global $tpl;
 	$tag_list = get_the_tag_list( '', __( ', ', GKTPLNAME ) );
 	$params = get_post_custom();
 	$params_aside = isset($params['gavern-post-params-aside']) ? $params['gavern-post-params-aside'][0] : false;
 	
 	$param_aside = true;
 	$param_date = true;
 	$param_author = true;
 	$param_category = true;
 	$param_tags = true;
 	$param_comments = true;
 	
 	if($params_aside) {
 		$params_aside = unserialize(unserialize($params_aside));
 		$param_aside = $params_aside['aside'] == 'Y';
 		$param_date = $params_aside['date'] == 'Y';
 		$param_author = $params_aside['author'] == 'Y';
 		$param_category = $params_aside['category'] == 'Y';
 		$param_tags = $params_aside['tags'] == 'Y';
 		$param_comments = $params_aside['comments'] == 'Y';
 	}
 	
 	?>
 	
 	<?php if($param_aside) : ?>
 	<aside class="meta">
	 	<dl>
	 		<?php if($param_date) : ?>
	 		<dt class="date">
	 			<?php _e('Post date:', GKTPLNAME); ?>
	 		</dt>
	 		
	 		<dd>
	 			<a href="<?php echo esc_url(get_permalink()); ?>" title="<?php echo esc_attr(get_the_time()); ?>" rel="bookmark">
	 				<time class="entry-date" datetime="<?php echo esc_attr(get_the_date(DATE_W3C)); ?>">
	 					<?php echo esc_html(get_the_date('d')); ?>	
	 					<span><?php echo esc_html(get_the_date('M')); ?></span>
	 				</time>
	 			</a>
	 		</dd>
		 		
	 		<?php if(get_post_format() != '') : ?>
	 		<dd class="format gk-format-<?php echo get_post_format(); ?>">
	 			<?php echo get_post_format(); ?>
	 		</dd>
	 		<?php endif; ?>
	 		
	 		<?php endif; ?>
	 		
	 		<?php if(!(is_tag() || is_archive() || is_home() || is_search())) : ?>
		 		<?php if(get_post_type() != 'page' && $param_category) : ?>
		 		<dt class="category">
		 			<?php _e('Category:', GKTPLNAME); ?>
		 		</dt>
		 		<dd>
		 			<?php echo get_the_category_list( __(', ', GKTPLNAME )); ?>
		 		</dd>
		 		<?php endif; ?>
		 		
		 		<?php if($param_author) : ?>
		 		<dt class="author">
		 			<?php _e('Author:', GKTPLNAME); ?>
		 		</dt>
		 		<dd>
		 			<a class="url fn n" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" title="<?php echo esc_attr(sprintf(__('View all posts by %s', GKTPLNAME), get_the_author())); ?>" rel="author"><?php echo get_the_author(); ?></a>
		 		</dd>
		 		<?php endif; ?>
		 		
		 		<?php if ( comments_open() && ! post_password_required() && $param_comments) : ?>
		 		<dt class="comments">
		 			<?php _e('Comments:', GKTPLNAME); ?>
		 		</dt>
		 		<dd>
		 			<?php 
		 				comments_popup_link(
		 					'<span class="leave-reply">' . __( 'Leave a reply', GKTPLNAME ) . '</span>', 
		 					__( '<b>1</b> Reply', GKTPLNAME ), 
		 					__( '<b>%</b> Replies', GKTPLNAME )
		 				);
		 			?>
		 		</dd>
		 		<?php endif; ?> 	
		 			
		 		<?php if($tag_list != '' && $param_tags) : ?>
		 		<dt class="tags">
		 			<?php _e('Tags:', GKTPLNAME); ?>
		 		</dt>
		 		<dd>
		 			<?php echo $tag_list; ?>
		 		</dd>
		 		<?php endif; ?>
		 		<?php if($attachment && wp_attachment_is_image()) : ?>
		 		<dt class="size">
		 			<?php _e('Attachment size:', GKTPLNAME); ?>
		 		</dt>
		 		<dd>	
		 			<?php
		 				$metadata = wp_get_attachment_metadata();
		 				printf( __( 'Full size is %s pixels', GKTPLNAME),
		 					sprintf( '<a href="%1$s" title="%2$s">%3$s &times; %4$s</a>',
		 						wp_get_attachment_url(),
		 						esc_attr( __('Link to full-size image', GKTPLNAME) ),
		 						$metadata['width'],
		 						$metadata['height']
		 					)
		 				);
		 			?> 
		 		</dd>	
		 		<?php endif; ?>
		 		<dd class="bookmark">
		 			<a href="<?php echo esc_url(get_permalink()); ?>" title="<?php printf(__('Permalink to %1$s', GKTPLNAME), the_title_attribute('echo=0')); ?>" rel="bookmark"><?php _e('permalink', GKTPLNAME); ?></a>
		 		</dd>
		 		
		 		<?php echo edit_post_link(__( 'Edit', GKTPLNAME ), '<dd class="edit">', '</dd>'); ?>
	 		<?php endif; ?>
 		</dl>
 	</aside>
 	<?php endif; ?>
 	
 	<?php
}

/**
 *
 * Function to generate the post pagination
 *
 * @return null
 *
 **/
function gk_post_links() {
	global $tpl;
	
	wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', GKTPLNAME ) . '</span>', 'after' => '</div>' ) );
}

/**
 *
 * Function to generate the post navigation
 *
 * @param id - id of the NAV element
 *
 * @return null
 *
 **/
function gk_content_nav($id = '') {
	global $tpl;
	global $wp_query;

	if($wp_query->max_num_pages > 1) : ?>
		<?php do_action('gavernwp_before_nav'); ?>
		<nav class="pagenav"<?php if($id != '') : ?> id="<?php echo $id; ?>"<?php endif; ?>>
			<?php if(get_next_posts_link() != '') : ?>
			<div class="nav-prev nav-btn"><?php next_posts_link( __( '&larr; Older posts', GKTPLNAME ) ); ?></div>
			<?php endif; ?>
			
			<?php if(get_previous_posts_link() != '') : ?>
			<div class="nav-next nav-btn"><?php previous_posts_link( __( 'Newer posts &rarr;', GKTPLNAME ) ); ?></div>
			<?php endif; ?>
		</nav><!-- #nav-above -->
		<?php do_action('gavernwp_after_nav'); ?>
	<?php endif;
}

/**
 *
 * Function to generate the post Social API elements
 *
 * @param title - title of the post
 * @param postID - ID of the post
 *
 * @return string - HTML output
 *
 **/
 
function gk_social_api($title, $postID) {
	global $tpl;
	// check if the social api is enabled on the specific page
	$social_api_mode = get_option($tpl->name . '_social_api_exclude_include', 'exclude');
	$social_api_articles = explode(',', get_option($tpl->name . '_social_api_articles', ''));
	$social_api_pages = explode(',', get_option($tpl->name . '_social_api_pages', ''));
	$social_api_categories = explode(',', get_option($tpl->name . '_social_api_categories', ''));
	//
	$is_excluded = false;
	//
	if($social_api_mode == 'include' || $social_api_mode == 'exclude') {
		//
		$is_excluded = 
			($social_api_pages != FALSE ? is_page($social_api_pages) : FALSE) || 
			($social_api_articles != FALSE ? is_single($social_api_articles) : FALSE) || 
			($social_api_categories != FALSE ? in_category($social_api_categories) : FALSE);
		//
		if($social_api_mode == 'exclude') {
			$is_excluded = !$is_excluded;
		}
	}
	//
	if($social_api_mode != 'none' && $is_excluded) {
		// variables for output
		$fb_like_output = '';
		$gplus_output = '';
		$twitter_output = '';
		$pinterest_output = '';
		// FB like
		if(get_option($tpl->name . '_fb_like', 'Y') == 'Y') {
			// configure FB like
			$fb_like_attributes = ''; 
			// configure FB like
			if(get_option($tpl->name . '_fb_like_send', 'Y') == 'Y') { $fb_like_attributes .= ' data-send="true"'; }
			$fb_like_attributes .= ' data-layout="'.get_option($tpl->name . '_fb_like_layout', 'standard').'"';
			$fb_like_attributes .= ' data-show-faces="'.get_option($tpl->name . '_fb_like_show_faces', 'true').'"';
			$fb_like_attributes .= ' data-width="'.get_option($tpl->name . '_fb_like_width', '500').'"';
			$fb_like_attributes .= ' data-action="'.get_option($tpl->name . '_fb_like_action', 'like').'"';
			$fb_like_attributes .= ' data-font="'.get_option($tpl->name . '_fb_like_font', 'arial').'"';
			$fb_like_attributes .= ' data-colorscheme="'.get_option($tpl->name . '_fb_like_colorscheme', 'light').'"';
			
			$fb_like_output = '<div class="fb-like" data-href="'.get_permalink($postID).'" '.$fb_like_attributes.'></div>';
		}
		// G+
		if(get_option($tpl->name . '_google_plus', 'Y') == 'Y') {
			// configure +1 button
			$gplus_attributes = '';    		
			// configure +1 button attributes
			$gplus_attributes .= ' annotation="'.get_option($tpl->name . '_google_plus_count', 'none').'"'; 
			$gplus_attributes .= ' width="'.get_option($tpl->name . '_google_plus_width', '300').'"'; 
			$gplus_attributes .= ' expandTo="top"'; 
				
			if(get_option($tpl->name . '_google_plus_size', 'medium') != 'standard') { 
				$gplus_attributes .= ' size="'.get_option($tpl->name . '_google_plus_size', 'medium').'"'; 
			}
			
			$gplus_output = '<g:plusone '.$gplus_attributes.' callback="'.get_permalink($postID).'"></g:plusone>';
		}
		// Twitter
		if(get_option($tpl->name . '_tweet_btn', 'Y') == 'Y') {
			// configure Twitter buttons    		  
			$tweet_btn_attributes = '';
			$tweet_btn_attributes .= ' data-count="'.get_option($tpl->name . '_tweet_btn_data_count', 'vertical').'"';
			if(get_option($tpl->name . '_tweet_btn_data_via', '') != '') {
				$tweet_btn_attributes .= ' data-via="'.get_option($tpl->name . '_tweet_btn_data_via', '').'"'; 
			}
			$tweet_btn_attributes .= ' data-lang="'.get_option($tpl->name . '_tweet_btn_data_lang', 'en').'"';
			  
			$twitter_output = '<a href="http://twitter.com/share" class="twitter-share-button" data-text="'.strip_tags($title).'" data-url="'.get_permalink($postID).'" '.$tweet_btn_attributes.'>'.__('Tweet', GKTPLNAME).'</a>';
		}
		// Pinterest
		if(get_option($tpl->name . '_pinterest_btn', 'Y') == 'Y') {
		     $pinit_title = gk_post_thumbnail_caption(true);
		      
		     if($pinit_title == '') {
		     	$pinit_title = false;
		     }
		      
		     $image = get_post_meta($postID, 'gavern_opengraph_image', true);
		      
		     if($image == '') {
		      	$image = wp_get_attachment_image_src( get_post_thumbnail_id( $postID ), 'single-post-thumbnail' );
		      	$image = $image[0];
		      	
		      	if($image == '' && get_option($tpl->name . '_og_default_image', '') != '') {
		      		$image = get_option($tpl->name . '_og_default_image', '');
		      	}
		     } 
		     // configure Pinterest buttons               
		     $pinterest_btn_attributes = get_option($tpl->name . '_pinterest_btn_style', 'horizontal');
		     $pinterest_output = '<a href="http://pinterest.com/pin/create/button/?url='.get_permalink($postID).'&amp;media='.$image.'&amp;description='.(($pinit_title == false) ? urlencode(strip_tags($title)) : $pinit_title).'" class="pin-it-button" count-layout="'.$pinterest_btn_attributes.'"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="'.__('Pin it', GKTPLNAME).'" alt="'.__('Pin it', GKTPLNAME).'" /></a>';
		}
		
		$output = '<section id="gk-social-api">' . apply_filters('gavern_social_api_fb', $fb_like_output) . apply_filters('gavern_social_api_gplus', $gplus_output) . apply_filters('gavern_social_api_twitter', $twitter_output) . apply_filters('gavern_social_api_pinterest', $pinterest_output) . '</section>';
		
		return apply_filters('gavern_social_api', $output);
	}
}

/**
 *
 * Function to generate the author info block
 *
 * @return null
 *
 **/
 
function gk_author($author_page = false, $return_value = false) {
    global $tpl;

	// check if the author info is enabled on the specific page
	$authorinfo_mode = get_option($tpl->name . '_authorinfo_exclude_include', 'exclude');
	$authorinfo_articles = explode(',', get_option($tpl->name . '_authorinfo_articles', ''));
	$authorinfo_pages = explode(',', get_option($tpl->name . '_authorinfo_pages', ''));
	$authorinfo_categories = explode(',', get_option($tpl->name . '_authorinfo_categories', ''));
	//
	$is_excluded = false;
	//
	if($authorinfo_mode == 'include' || $authorinfo_mode == 'exclude') {
		//
		$is_excluded = 
			($authorinfo_pages != FALSE ? is_page($authorinfo_pages) : FALSE) || 
			($authorinfo_articles != FALSE ? is_single($authorinfo_articles) : FALSE) || 
			($authorinfo_categories != FALSE ? in_category($authorinfo_categories) : FALSE);
		//
		if($authorinfo_mode == 'exclude') {
			$is_excluded = !$is_excluded;
		}
	}
	//
	if($authorinfo_mode != 'none' && $is_excluded) :
		if(
			(is_page() && get_option($tpl->name . '_template_show_author_info_on_pages') == 'Y') ||
			!is_page()
		) :
		    if(
		        get_the_author_meta( 'description' ) && 
		        (
		        	$author_page ||
		        	get_option($tpl->name . '_template_show_author_info') == 'Y'
		        )
		    ): 
		    ?>
		    <?php if($return_value == true) : ?>
		    	<?php return true; ?>
		    <?php else : ?>
			    <section class="author-info">
			        <aside class="author-avatar">
			            <?php echo get_avatar( get_the_author_meta( 'user_email' ), 64 ); ?>
			        </aside>
			        <div class="author-desc">
			            <h2>
			                <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
			                    <?php printf( __( 'Author: %s ', GKTPLNAME ), get_the_author_meta('display_name', get_the_author_meta( 'ID' )) ); ?> 
			                </a>
			            </h2>
			            <p>
			                <?php the_author_meta( 'description' ); ?>
			            </p>
			
			            <?php 
			                $www = get_the_author_meta('user_url', get_the_author_meta( 'ID' ));
			                if($www != '') : 
			            ?>
			            <p class="author-www">
			                <?php _e('Website: ', GKTPLNAME); ?><a href="<?php echo $www; ?>"><?php echo $www; ?></a>
			            </p>
			            <?php endif; ?>
			            
			            <?php
			            	$google_profile = get_the_author_meta( 'google_profile' );
			            	if ($google_profile != '') :
			            		if(stripos($google_profile, '?') === FALSE && stripos($google_profile, 'rel=author') === FALSE) {
			            			$google_profile .= '?rel=author'; 
			            		}
			            ?>
			            <p class="author-google">
			            	<a href="<?php echo esc_url($google_profile); ?>" rel="me"><?php _e('Google Profile', GKTPLNAME); ?></a>
			            </p>
			            <?php endif; ?>
			        </div>
			    </section>
		    	<?php 
		    	endif;
		    endif;
		endif;
	endif;
	
	if($return_value == true) {
		return false;
	}
} 

/**
 *
 * Function to generate the featured image caption
 *
 * @param raw - if you need to get raw text without HTML tags
 * 
 * @return HTML output or raw text (depending from params)
 *
 **/

function gk_post_thumbnail_caption($raw = false) {
	global $post;
	// get the post thumbnail ID
	$thumbnail_id = get_post_thumbnail_id($post->ID);
	// get the thumbnail description
	$thumbnail_img = get_posts(array('p' => $thumbnail_id, 'post_type' => 'attachment'));
	// return the thumbnail caption
	if ($thumbnail_img && isset($thumbnail_img[0])) {
		if($thumbnail_img[0]->post_excerpt != '') {
			if($raw) {
				return apply_filters('gavern_thumbnail_caption', strip_tags($thumbnail_img[0]->post_excerpt));
			} else {
				return apply_filters('gavern_thumbnail_caption', '<figcaption>'.$thumbnail_img[0]->post_excerpt.'</figcaption>');
			}
		}
	} else {
		return false;
	}
}

// EOF