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
								get_comment_time( 'c' ),
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
	// get the post custom fields
	if ($keys = get_post_custom_keys()) {
		// variable for the list items
		$output = '';
		// generate the list
		foreach ((array) $keys as $key) {
			// trim the key name
			$key_trimmed = trim($key);
			// skip the protected meta data and "gavern-" values
			if(is_protected_meta($key_trimmed, 'post') || stripos($key_trimmed, 'gavern-') !== FALSE) {
				continue;
			}
			// map the values
			$values = array_map('trim', get_post_custom_values($key));
			// extract the value
			$value = implode($values,', ');
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
 	?>
 	<aside class="meta">
	 	<dl>
	 		<dt class="date">
	 			<?php _e('Post date:', GKTPLNAME); ?>
	 		</dt>
	 		
	 		<dd>
	 			<a href="<?php echo esc_url(get_permalink()); ?>" title="<?php echo esc_attr(get_the_time()); ?>" rel="bookmark">
	 				<time class="entry-date" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
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
	 		
	 		<?php if(!(is_tag() || is_archive() || is_home() || is_search())) : ?>
		 		<?php if(!is_page()) : ?>
		 		<dt class="category">
		 			<?php _e('Category:', GKTPLNAME); ?>
		 		</dt>
		 		<dd>
		 			<?php echo get_the_category_list( __(', ', GKTPLNAME )); ?>
		 		</dd>
		 		<?php endif; ?>
		 		<dt class="author">
		 			<?php _e('Author:', GKTPLNAME); ?>
		 		</dt>
		 		<dd>
		 			<a class="url fn n" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" title="<?php echo esc_attr(sprintf(__('View all posts by %s', GKTPLNAME), get_the_author())); ?>" rel="author"><?php echo get_the_author(); ?></a>
		 		</dd>
		 		<?php if ( comments_open() && ! post_password_required() ) : ?>
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
		 		<?php if($tag_list != ''): ?>
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
		<nav class="pagenav"<?php if($id != '') : ?> id="<?php echo $id; ?>"<?php endif; ?>>
			<div class="nav-prev"><?php next_posts_link( __( '&larr; Older posts', GKTPLNAME ) ); ?></div>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts &rarr;', GKTPLNAME ) ); ?></div>
		</nav><!-- #nav-above -->
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
			
			$fb_like_output = '<div class="fb-like" data-href="'.get_current_page_url().'" '.$fb_like_attributes.'></div>';
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
			
			$gplus_output = '<g:plusone '.$gplus_attributes.' callback="'.get_current_page_url().'"></g:plusone>';
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
			  
			$twitter_output = '<a href="http://twitter.com/share" class="twitter-share-button" data-text="'.$title.'" data-url="'.get_current_page_url().'" '.$tweet_btn_attributes.'>'.__('Tweet', GKTPLNAME).'</a>';
		}
		// Pinterest
		if(get_option($tpl->name . '_pinterest_btn', 'Y') == 'Y') {
		      // configure Pinterest buttons               
		      $pinterest_btn_attributes = get_option($tpl->name . '_pinterest_btn_style', 'horizontal');
		      $pinterest_output = '<a href="http://pinterest.com/pin/create/button/?url='.get_current_page_url().'&amp;media='.get_post_meta($postID, 'gavern_opengraph_image', true).'&amp;description='.$title.'" class="pin-it-button" count-layout="'.$pinterest_btn_attributes.'"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="'.__('Pin it', GKTPLNAME).'" /></a>';
		}
		
		return '<section id="gk-social-api">' . $fb_like_output . $gplus_output . $twitter_output . $pinterest_output . '</section>';
	}
}

/**
 *
 * Function to generate the author info block
 *
 * @return null
 *
 **/
 
function gk_author($author_page = false) {
    global $tpl;

    if(
        get_the_author_meta( 'description' ) && 
        (
        	$author_page ||
        	get_option($tpl->name . '_template_show_author_info') == 'Y'
        )
    ): 
    ?>
    <section class="author-info">
        <aside class="author-avatar">
            <?php echo get_avatar( get_the_author_meta( 'user_email' ), 64 ); ?>
        </aside>
        <div class="author-desc">
            <h2>
                <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
                    <?php printf( __( 'Author: %s %s', GKTPLNAME ), get_the_author_meta('first_name', get_the_author_meta( 'ID' )), get_the_author_meta('last_name', get_the_author_meta( 'ID' )) ); ?> 
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
            ?>
            <p class="author-google">
            	<a href="<?php echo esc_url($google_profile); ?>" rel="me"><?php _e('Google Profile', GKTPLNAME); ?></a>
            </p>
            <?php endif; ?>
        </div>
    </section>
    <?php 
    endif;
} 
// EOF