<?php

/**
 * 
 * GK Comments Widget class
 *
 **/

class GK_Comments_Widget extends WP_Widget {
	/**
	 *
	 * Constructor
	 *
	 * @return void
	 *
	 **/
	function GK_Comments_Widget() {
		$this->WP_Widget(
			'widget_gk_comments', 
			__( 'GK Comments', GKTPLNAME ), 
			array( 
				'classname' => 'widget_gk_comments', 
				'description' => __( 'Use this widget to show recent comments with avatars', GKTPLNAME) 
			)
		);
		
		$this->alt_option_name = 'widget_gk_comments';

		add_action( 'comment_post', array(&$this, 'refresh_cache' ) );
		add_action( 'comment_unapproved_to_approved', array(&$this, 'refresh_cache' ) );
		add_action( 'comment_approved_to_unapproved', array(&$this, 'refresh_cache' ) );
		add_action( 'trashed_comment', array(&$this, 'refresh_cache' ));
	}

	/**
	 *
	 * Outputs the HTML code of this widget.
	 *
	 * @param array An array of standard parameters for widgets in this theme
	 * @param array An array of settings for this widget instance
	 * @return void
	 *
	 **/
	function widget($args, $instance) {
		$cache = get_transient(md5($this->id));
		
		// the part with the title and widget wrappers cannot be cached! 
		// in order to avoid problems with the calculating columns
		//
		extract($args, EXTR_SKIP);
		
		$title = apply_filters('widget_title', empty($instance['title']) ? __( 'Latest Comments', GKTPLNAME ) : $instance['title'], $instance, $this->id_base);
		
		echo $before_widget;
		echo $before_title;
		echo $title;
		echo $after_title;
		
		if($cache) {
			echo $cache;
			echo $after_widget;
			return;
		}

		ob_start();
		//
		$avatar_size = empty($instance['avatar_size']) ? 64 : $instance['avatar_size'];
		$word_count = empty($instance['word_count']) ? 20 : $instance['word_count'];
		$number = empty($instance['number']) ? 5 : $instance['number'];

		$comments_args = array(
			'status' => 'approve',
			'order' => 'DESC',
			'number' => $number
		);
		$comments = get_comments($comments_args);
		//
		if (count($comments)) {			
			if(count($comments) > 0) {				
				echo '<ol>';
				
				for($i = 0; $i < count($comments); $i++) {
					echo '<li'.(user_can($comments[$i]->user_id, 'delete_users') ? ' class="gk-admin-comment"' : '').'>';
						echo '<p><a href="'.get_comment_link($comments[$i]->comment_ID).'">' . $this->comment_text($comments[$i]->comment_content, $word_count) . '</a></p>';
						
						echo get_avatar($comments[$i]->comment_author_email, $avatar_size);
						echo '<strong>' . $comments[$i]->comment_author . '</strong>';
						echo '<small>' . $comments[$i]->comment_date_gmt . '</small>';				
					echo '</li>';
				}
				
				echo '</ol>';
			}
		}
		// save the cache results
		$cache_output = ob_get_flush();
		set_transient(md5($this->id) , $cache_output, 3 * 60 * 60);
		// 
		echo $after_widget;
	}

	/**
	 *
	 * Used in the back-end to update the module options
	 *
	 * @param array new instance of the widget settings
	 * @param array old instance of the widget settings
	 * @return updated instance of the widget settings
	 *
	 **/
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['avatar_size'] = strip_tags( $new_instance['avatar_size'] );
		$instance['word_count'] = strip_tags( $new_instance['word_count'] );
		$instance['number'] = strip_tags( $new_instance['number'] );
		$this->refresh_cache();

		$alloptions = wp_cache_get('alloptions', 'options');
		if(isset($alloptions['widget_gk_comments'])) {
			delete_option( 'widget_gk_comments' );
		}

		return $instance;
	}

	/**
	 *
	 * Refreshes the widget cache data
	 *
	 * @return void
	 *
	 **/

	function refresh_cache() {
	    $ids = array_keys(get_option('widget_widget_gk_comments'));
	    for($i = 0; $i < count($ids); $i++) {
	        if(is_numeric($ids[$i])) {
	            delete_transient(md5('widget_gk_comments-' . $ids[$i]));
	        }
	    }
	}
	
	/**
	 *
	 * Limits the comment text to specified words amount
	 *
	 * @param string input text
	 * @param int amount of words
	 * @return string the cutted text
	 *
	 **/
	
	function comment_text($input, $amount = 20) {
		$output = '';
		$input = strip_tags($input);
		$input = explode(' ', $input);
		
		for($i = 0; $i < $amount; $i++) {
			if(isset($input[$i])) {
				$output .= $input[$i] . ' ';
			}
		}
	
		if(count($input) > $amount) {
			$output .= '&hellip;';
		}
	
		return $output;
	}

	/**
	 *
	 * Outputs the HTML code of the widget in the back-end
	 *
	 * @param array instance of the widget settings
	 * @return void - HTML output
	 *
	 **/
	function form($instance) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$avatar_size = isset($instance['avatar_size']) ? esc_attr($instance['avatar_size']) : 64;
		$word_count = isset($instance['word_count']) ? esc_attr($instance['word_count']) : 10;
		$number = isset($instance['number']) ? esc_attr($instance['number']) : 5;
	?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', GKTPLNAME ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'avatar_size' ) ); ?>"><?php _e( 'Avatar size:', GKTPLNAME ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'avatar_size' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'avatar_size' ) ); ?>" type="text" value="<?php echo esc_attr( $avatar_size ); ?>" />
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'word_count' ) ); ?>"><?php _e( 'Word count:', GKTPLNAME ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'word_count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'word_count' ) ); ?>" type="text" value="<?php echo esc_attr( $word_count ); ?>" />
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php _e( 'Number of comments:', GKTPLNAME ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>" />
		</p>
	<?php
	}
}

// EOF