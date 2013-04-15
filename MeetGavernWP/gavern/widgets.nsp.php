<?php

/**
 * 
 * GK News Show Pro Widget class
 *
 **/

class GK_NSP_Widget extends WP_Widget {
	// variable used to store the object configuration
	private $wdgt_config;
	// variable uset to store the object query results
	private $wdgt_results;
	
	/**
	 *
	 * Constructor
	 *
	 * @return void
	 *
	 **/
	function GK_NSP_Widget() {
		$this->WP_Widget(
			'widget_gk_nsp', 
			__('GK News Show Pro', GKTPLNAME), 
			array( 
				'classname' => 'widget_gk_nsp', 
				'description' => __( 'Use this widget to show recent items with images and additional elements like title, date etc.', GKTPLNAME) 
			),
			array(
				'width' => 640, 
				'height' => 350
			)
		);
		
		$this->alt_option_name = 'widget_gk_nsp';
		//
		add_action('edit_post', array(&$this, 'refresh_cache'));
		add_action('delete_post', array(&$this, 'refresh_cache'));
		add_action('trashed_post', array(&$this, 'refresh_cache'));
		add_action('save_post', array(&$this, 'refresh_cache'));
		//
		add_action('wp_enqueue_scripts', array('GK_NSP_Widget', 'add_scripts'));
	}
	
	static function add_scripts() {
		wp_register_script( 'gk-nsp', gavern_file_uri('js/widgets/nsp.js'), array('jquery'));
		wp_enqueue_script('gk-nsp');
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
		
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		
		$ops = array('data_source_type', 'data_source', 'orderby', 'order', 'offset', 'article_pages', 'article_cols', 'article_rows', 'links_pages', 'links_rows', 'article_pagination', 'links_pagination', 'article_title_state', 'article_title_len', 'article_title_len_type', 'article_title_order', 'article_text_state', 'article_text_len', 'article_text_len_type', 'article_text_order', 'article_image_state', 'article_image_w', 'article_image_h', 'article_image_pos', 'article_image_order', 'article_info_state', 'article_info_format', 'article_info_date_format', 'article_info_order', 'article_readmore_state', 'article_readmore_order', 'links_title_state', 'links_title_len', 'links_title_len_type', 'links_text_state', 'links_text_len', 'links_text_len_type', 'article_block_padding', 'image_block_padding', 'cache_time', 'autoanim', 'autoanim_interval', 'autoanim_hover');
		
		foreach($ops as $option) {
			$config[$option] =  empty($instance[$option]) ? null : $instance[$option];
		}
		
		echo $before_widget;
		
		if($title != '') {
			echo $before_title;
			echo $title;
			echo $after_title;
		}
		
		if($cache) {
			echo $cache;
			echo $after_widget;
			return;
		}
		// start cache buffering
		ob_start();
		// get the posts data
		// let's save the global $post variable
		global $post;
		$tmp_post = $post;
		//
		// other options for the query
		//
		// total amount of the posts
		$amount_of_posts = ($config['article_pages'] * $config['article_cols'] * $config['article_rows']) + ($config['links_pages'] * $config['links_rows']);
		// resutls array
		$results = array();
		// data source
		if($config['data_source_type'] == 'latest') {
			$results = get_posts(array(
				'posts_per_page' => $amount_of_posts,
				'offset' => $config['offset'], 
				'orderby' => $config['orderby'],
				'order' => $config['order']
			));
		} else if($config['data_source_type'] == 'category') {
			$results = get_posts(array(
				'category_name' => $config['data_source'],
				'posts_per_page' => $amount_of_posts,
				'offset' => $config['offset'], 
				'orderby' => $config['orderby'],
				'order' => $config['order']
			));
		} else if($config['data_source_type'] == 'tag') {
			$results = get_posts(array(
				'tag' => $config['data_source'],
				'posts_per_page' => $amount_of_posts,
				'offset' => $config['offset'], 
				'orderby' => $config['orderby'],
				'order' => $config['order']
			));
		} else if($config['data_source_type'] == 'post') {
			$post_slugs = explode(',', $config['data_source']);
			foreach($post_slugs as $slug) {
				$res = get_posts(array('name' => $slug));
				array_push($results, $res[0]);
			}
		} else if($config['data_source_type'] == 'custom') {
			$post_type = explode(',', $config['data_source']);
			array_push($results, get_posts(array('post_type' => $post_type, 'numberposts' => $amount_of_posts)));
		}
		// restore the global $post variable
		$post = $tmp_post;
		// parse the data into a widget code
		// prepare widhet classes
		$wdgt_class = 'gk-nsp';
		
		if($config['article_image_state'] == 'on' && $config['article_image_w'] > 160) {
			$wdgt_class .= ' mobile_layout';
		}
		
		if($config['article_cols'] > 1) {
			$wdgt_class .= ' horizontal';
		}
		
		if($config['article_rows'] > 1 && $config['article_cols'] == 1) {
			$wdgt_class .= ' vertical';
		}
		
		// generate the widget wrapper
		echo '<div class="'.$wdgt_class.'" data-cols="'.$config['article_cols'].'" data-rows="'.$config['article_rows'].'" data-links="'.$config['links_rows'].'" data-autoanim="'.$config['autoanim'].'" data-autoanimint="'.$config['autoanim_interval'].'" data-autoanimhover="'.$config['autoanim_hover'].'">';
		// generate the articles
		$amount_of_articles = $config['article_pages'] * $config['article_cols'] * $config['article_rows'];
		$amount_of_articles = $amount_of_articles > count($results) ? count($results) : $amount_of_articles; 
		$amount_of_art_pages = ($amount_of_articles >= count($results)) ? ceil(count($results) / ($config['article_cols'] * $config['article_rows'])) : $config['article_pages'];
		// iterate
		$this->wdgt_config = $config;
		$this->wdgt_results = $results;
		
		// wrap articles
		echo '<div class="gk-nsp-arts">';
		echo '<div class="gk-nsp-arts-scroll gk-nsp-pages-'.$amount_of_art_pages.'">';
		//
		$i = 0;
		//
		for($p = 0; $p < $amount_of_art_pages; $p++) {
			echo '<div class="gk-nsp-arts-page gk-nsp-cols-'.$amount_of_art_pages.'">';
			
			for($r = 0; $r < $config['article_cols'] * $config['article_rows']; $r++) {
				if(isset($results[$i]) || (is_array($results[0]) && isset($results[0][$i]))) {
					$art_title = '';
					$art_text = '';
					$art_image = '';
					$art_info = '';
					$art_readmore = '';
					
					if($config['article_title_state'] == 'on') {
						$art_title = $this->generate_art_title($i);
					}
					
					if($config['article_text_state'] == 'on') {
						$art_text = $this->generate_art_text($i);
					}
					
					if($config['article_image_state'] == 'on') {
						$art_image = $this->generate_art_image($i);
					}
					
					if($config['article_info_state'] == 'on') {
						$art_info = $this->generate_art_info($i);
					}
					
					if($config['article_readmore_state'] == 'on') {
						$art_readmore = $this->generate_art_readmore($i);
					}
					//
					$art_output = '';
					//
					for($j = 1; $j <= 5; $j++) {
						// open the content wrap if necessary
						if(
							$this->wdgt_config['article_image_pos'] == 'left' && 
							$this->wdgt_config['article_image_order'] == 1 && 
							$j == 2
						) {
							$art_output .= '<div class="gk-nsp-content-wrap">';
						}
						// generate the article elements
						if($config['article_title_order'] == $j) $art_output .= $art_title;
						if($config['article_text_order'] == $j) $art_output .= $art_text;
						if($config['article_image_order'] == $j) $art_output .= $art_image;
						if($config['article_info_order'] == $j) $art_output .= $art_info;
						if($config['article_readmore_order'] == $j) $art_output .= $art_readmore;
					}
					// close the content wrap
					if(
						$this->wdgt_config['article_image_pos'] == 'left' && 
						$this->wdgt_config['article_image_order'] == 1
					) {
						$art_output .= '</div>';
					}
					// the final output
					$style = '';
					
					if($this->wdgt_config['article_block_padding'] != '' && $this->wdgt_config['article_block_padding'] != '0') {
						$style = ' style="padding: '.$this->wdgt_config['article_block_padding'].';"';
					}
					
					echo '<div class="gk-nsp-art gk-nsp-cols-'.$config['article_cols'].'" '.$style.'>' . $art_output . '</div>';
				}
				//
				$i++;
			}
			echo '</div>';
		}
		//
		echo '</div>';
		//
		if($amount_of_art_pages > 1) {
			echo '<div class="gk-nsp-arts-nav">';
			
			if($config['article_pagination'] != 'arrows') {
				echo '<ul class="gk-nsp-pagination">';
				
				for($i = 1; $i <= $amount_of_art_pages; $i++) {
					echo '<li>' . $i . '</li>';
				}
				
				echo '</ul>';
			}
			
			if($config['article_pagination'] != 'pagination') {
				echo '<div class="gk-nsp-prev">&laquo;</div>';
				echo '<div class="gk-nsp-next">&raquo;</div>';
			}
			
			echo '</div>';
		}
		//
		echo '</div>';
		// generate the links
		if($amount_of_articles <= count($results)) {
			// calculate amount of links
			$amount_of_links = count($results) - $amount_of_articles;
			$start = $amount_of_articles;
			// generate the links
			if($amount_of_links > 0) {
				echo '<div class="gk-nsp-links">';
				echo '<div class="gk-nsp-links-scroll gk-nsp-pages-'.ceil($amount_of_links / $this->wdgt_config['links_rows']).'">';
				
				for($i = 0; $i < ceil($amount_of_links / $this->wdgt_config['links_rows']); $i++) {
					echo '<ul class="gk-nsp-list gk-nsp-cols-'.$config['links_pages'].'">';
					for($j = 0; $j < $this->wdgt_config['links_rows']; $j++) {
						echo '<li>';
						if($this->wdgt_config['links_title_state'] == 'on') echo $this->generate_link_title($start);
						if($this->wdgt_config['links_text_state'] == 'on') echo $this->generate_link_text($start);
						echo '</li>';
						
						$start++;
					}
					echo '</ul>';
				}
				//
				echo '</div>';
				//
				echo '</div>';
			}
		}
		//
		if($this->wdgt_config['links_rows'] > 0 && ceil($amount_of_links / $this->wdgt_config['links_rows']) > 1) {
			echo '<div class="gk-nsp-links-nav">';
			
			if($config['links_pagination'] != 'arrows') {
				echo '<ul class="gk-nsp-pagination">';
				
				for($i = 1; $i <= ceil($amount_of_links / $this->wdgt_config['links_rows']); $i++) {
					echo '<li>' . $i . '</li>';
				}
				
				echo '</ul>';
			}
			
			if($config['links_pagination'] != 'pagination') {
				echo '<div class="gk-nsp-prev">&laquo;</div>';
				echo '<div class="gk-nsp-next">&raquo;</div>';
			}
			
			echo '</div>';
		}
		// closing the widget wrapper
		echo '</div>';
		// save the cache results
		$cache_output = ob_get_flush();
		$cache_time = ($this->wdgt_config['cache_time'] == '' || !is_numeric($this->wdgt_config['cache_time'])) ? 60 : (int) $this->wdgt_config['cache_time'];
		set_transient(md5($this->id) , $cache_output, $cache_time * 60);
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
		$ops = array('data_source_type', 'data_source', 'orderby', 'order', 'offset', 'article_pages', 'article_cols', 'article_rows', 'links_pages', 'links_rows', 'article_pagination', 'links_pagination', 'article_title_state', 'article_title_len', 'article_title_len_type', 'article_title_order', 'article_text_state', 'article_text_len', 'article_text_len_type', 'article_text_order', 'article_image_state', 'article_image_w', 'article_image_h', 'article_image_pos', 'article_image_order', 'article_info_state', 'article_info_format', 'article_info_date_format', 'article_info_order', 'article_readmore_state', 'article_readmore_order', 'links_title_state', 'links_title_len', 'links_title_len_type', 'links_text_state', 'links_text_len', 'links_text_len_type', 'article_block_padding', 'image_block_padding', 'cache_time', 'autoanim', 'autoanim_interval', 'autoanim_hover');
		
		foreach($ops as $option) {
			$instance[$option] = strip_tags( $new_instance[$option] );	
		}
		
		$this->refresh_cache();

		$alloptions = wp_cache_get('alloptions', 'options');
		if(isset($alloptions['widget_gk_nsp'])) {
			delete_option( 'widget_gk_nsp' );
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
		if(is_array(get_option('widget_widget_gk_nsp'))) {
		    $ids = array_keys(get_option('widget_widget_gk_nsp'));
		    for($i = 0; $i < count($ids); $i++) {
		        if(is_numeric($ids[$i])) {
		            delete_transient(md5('widget_gk_nsp-' . $ids[$i]));
		        }
		    }
	    } else {
	    	delete_transient(md5('widget_gk_nsp-' . $this->id));
	    }
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
		
		// data source
		$data_source_type = isset($instance['data_source_type']) ? esc_attr($instance['data_source_type']) : 'latest';
		$data_source = isset($instance['data_source']) ? esc_attr($instance['data_source']) : '';
		$orderby = isset($instance['orderby']) ? esc_attr($instance['orderby']) : 'ID';
		$order = isset($instance['order']) ? esc_attr($instance['order']) : 'DESC';
		$offset = isset($instance['offset']) ? esc_attr($instance['offset']) : '0';
		
		// articles amount
		$article_pages = isset($instance['article_pages']) ? esc_attr($instance['article_pages']) : '1';
		$article_cols = isset($instance['article_cols']) ? esc_attr($instance['article_cols']) : '1';
		$article_rows = isset($instance['article_rows']) ? esc_attr($instance['article_rows']) : '1';
		
		// links amount
		$links_pages = isset($instance['links_pages']) ? esc_attr($instance['links_pages']) : '0';
		$links_rows = isset($instance['links_rows']) ? esc_attr($instance['links_rows']) : '0';
		
		// paginations
		$article_pagination = isset($instance['article_pagination']) ? esc_attr($instance['article_pagination']) : 'pagination';
		$links_pagination = isset($instance['links_pagination']) ? esc_attr($instance['links_pagination']) : 'pagination';
		
		// article title format
		$article_title_state = isset($instance['article_title_state']) ? esc_attr($instance['article_title_state']) : 'on';
		$article_title_len = isset($instance['article_title_len']) ? esc_attr($instance['article_title_len']) : '10';
		$article_title_len_type = isset($instance['article_title_len_type']) ? esc_attr($instance['article_title_len_type']) : 'words';
		$article_title_order = isset($instance['article_title_order']) ? esc_attr($instance['article_title_order']) : '1';
		
		// article text format
		$article_text_state = isset($instance['article_text_state']) ? esc_attr($instance['article_text_state']) : 'on';
		$article_text_len = isset($instance['article_text_len']) ? esc_attr($instance['article_text_len']) : '20';
		$article_text_len_type = isset($instance['article_text_len_type']) ? esc_attr($instance['article_text_len_type']) : 'words';
		$article_text_order = isset($instance['article_text_order']) ? esc_attr($instance['article_text_order']) : '2';
		
		// article text format
		$article_image_state = isset($instance['article_image_state']) ? esc_attr($instance['article_image_state']) : 'on';
		$article_image_w = isset($instance['article_image_w']) ? esc_attr($instance['article_image_w']) : '160';
		$article_image_h = isset($instance['article_image_h']) ? esc_attr($instance['article_image_h']) : '120';
		$article_image_pos = isset($instance['article_image_pos']) ? esc_attr($instance['article_image_pos']) : 'top';
		$article_image_order = isset($instance['article_image_order']) ? esc_attr($instance['article_image_order']) : '3';
		
		// article info format
		$article_info_state = isset($instance['article_info_state']) ? esc_attr($instance['article_info_state']) : 'on';
		$article_info_format = isset($instance['article_info_format']) ? esc_attr($instance['article_info_format']) : '%DATE %CATEGORY %AUTHOR %COMMENTS';
		$article_info_date_format = isset($instance['article_info_date_format']) ? esc_attr($instance['article_info_date_format']) : 'd M Y';
		$article_info_order = isset($instance['article_info_order']) ? esc_attr($instance['article_info_order']) : '4';
		
		// article readmore format
		$article_readmore_state = isset($instance['article_readmore_state']) ? esc_attr($instance['article_readmore_state']) : 'on';
		$article_readmore_order = isset($instance['article_readmore_order']) ? esc_attr($instance['article_readmore_order']) : '5';
		
		// links title format
		$links_title_state = isset($instance['links_title_state']) ? esc_attr($instance['links_title_state']) : 'on';
		$links_title_len = isset($instance['links_title_len']) ? esc_attr($instance['links_title_len']) : '10';
		$links_title_len_type = isset($instance['links_title_len_type']) ? esc_attr($instance['links_title_len_type']) : 'words';
		
		// links text format
		$links_text_state = isset($instance['links_text_state']) ? esc_attr($instance['links_text_state']) : 'on';
		$links_text_len = isset($instance['links_text_len']) ? esc_attr($instance['links_text_len']) : '20';
		$links_text_len_type = isset($instance['links_text_len_type']) ? esc_attr($instance['links_text_len_type']) : 'words';
		// paddings
		$article_block_padding = isset($instance['article_block_padding']) ? esc_attr($instance['article_block_padding']) : '0';
		$image_block_padding = isset($instance['image_block_padding']) ? esc_attr($instance['image_block_padding']) : '0';
		
		// cache time
		$cache_time = isset($instance['cache_time']) ? esc_attr($instance['cache_time']) : '60';
		
		// Autoanimation
		$autoanim = isset($instance['autoanim']) ? esc_attr($instance['autoanim']) : 'off';
		$autoanim_interval = isset($instance['autoanim_interval']) ? esc_attr($instance['autoanim_interval']) : '5000';
		$autoanim_hover = isset($instance['autoanim_hover']) ? esc_attr($instance['autoanim_hover']) : 'on';
		
	?>	
		<div class="gk-nsp-col">
			<h3><?php _e('Basic settings', GKTPLNAME); ?></h3>
			
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', GKTPLNAME ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			
			<h3><?php _e('Data source settings', GKTPLNAME); ?></h3>
			
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'data_source_type' ) ); ?>"><?php _e( 'Data source:', GKTPLNAME ); ?></label>
				
				<select id="<?php echo esc_attr( $this->get_field_id('data_source_type')); ?>" name="<?php echo esc_attr( $this->get_field_name('data_source_type')); ?>">
					<option value="latest"<?php echo (esc_attr($data_source_type) == 'latest') ? ' selected="selected"' : ''; ?>>
						<?php _e('Latest posts', GKTPLNAME); ?>
					</option>
					<option value="category"<?php echo (esc_attr($data_source_type) == 'category') ? ' selected="selected"' : ''; ?>>
						<?php _e('Categories slugs', GKTPLNAME); ?>
					</option>
					<option value="tag"<?php echo (esc_attr($data_source_type) == 'tag') ? ' selected="selected"' : ''; ?>>
						<?php _e('Tags', GKTPLNAME); ?>
					</option>
					<option value="post"<?php echo (esc_attr($data_source_type) == 'post') ? ' selected="selected"' : ''; ?>>
						<?php _e('Posts slugs', GKTPLNAME); ?>
					</option>
					<option value="custom"<?php echo (esc_attr($data_source_type) == 'custom') ? ' selected="selected"' : ''; ?>>
						<?php _e('Custom post types', GKTPLNAME); ?>
					</option>
				</select>
				
				<textarea id="<?php echo esc_attr( $this->get_field_id('data_source')); ?>" name="<?php echo esc_attr( $this->get_field_name('data_source')); ?>"><?php echo esc_attr($data_source); ?></textarea>
			</p>
			
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"><?php _e( 'Order by:', GKTPLNAME ); ?></label>
				
				<select id="<?php echo esc_attr( $this->get_field_id('orderby')); ?>" name="<?php echo esc_attr( $this->get_field_name('orderby')); ?>">
					<option value="ID"<?php echo (esc_attr($orderby) == 'ID') ? ' selected="selected"' : ''; ?>>
						<?php _e('ID', GKTPLNAME); ?>
					</option>
					
					<option value="date"<?php echo (esc_attr($orderby) == 'date') ? ' selected="selected"' : ''; ?>>
						<?php _e('Date', GKTPLNAME); ?>
					</option>
					
					<option value="title"<?php echo (esc_attr($orderby) == 'title') ? ' selected="selected"' : ''; ?>>
						<?php _e('Title', GKTPLNAME); ?>
					</option>
					
					<option value="modified"<?php echo (esc_attr($orderby) == 'modified') ? ' selected="selected"' : ''; ?>>
						<?php _e('Modified', GKTPLNAME); ?>
					</option>
					
					<option value="rand"<?php echo (esc_attr($orderby) == 'rand') ? ' selected="selected"' : ''; ?>>
						<?php _e('Random', GKTPLNAME); ?>
					</option>
				</select>
				
				<select id="<?php echo esc_attr( $this->get_field_id('order')); ?>" name="<?php echo esc_attr( $this->get_field_name('order')); ?>">
					<option value="ASC"<?php echo (esc_attr($order) == 'ASC') ? ' selected="selected"' : ''; ?>>
						<?php _e('ASC', GKTPLNAME); ?>
					</option>
					<option value="DESC"<?php echo (esc_attr($order) == 'DESC') ? ' selected="selected"' : ''; ?>>
						<?php _e('DESC', GKTPLNAME); ?>
					</option>
				</select>
			</p>
			
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'offset' ) ); ?>"><?php _e( 'Offset:', GKTPLNAME ); ?></label>
				<input id="<?php echo esc_attr( $this->get_field_id( 'offset' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'offset' ) ); ?>" type="text" value="<?php echo esc_attr( $offset ); ?>" class="short" />
			</p>
			
			<p>
				<h3><?php _e('Articles amount', GKTPLNAME); ?></h3>
				<label for="<?php echo esc_attr( $this->get_field_id( 'article_pages' ) ); ?>"><?php _e( 'pages:', GKTPLNAME ); ?></label>
				<input id="<?php echo esc_attr( $this->get_field_id( 'article_pages' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'article_pages' ) ); ?>" type="text" value="<?php echo esc_attr( $article_pages ); ?>" class="short" />

				<label for="<?php echo esc_attr( $this->get_field_id( 'article_cols' ) ); ?>"><?php _e( 'columns:', GKTPLNAME ); ?></label>
				<input id="<?php echo esc_attr( $this->get_field_id( 'article_cols' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'article_cols' ) ); ?>" type="text" value="<?php echo esc_attr( $article_cols ); ?>" class="short" />

				<label for="<?php echo esc_attr( $this->get_field_id( 'article_rows' ) ); ?>"><?php _e( 'rows:', GKTPLNAME ); ?></label>
				<input id="<?php echo esc_attr( $this->get_field_id( 'article_rows' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'article_rows' ) ); ?>" type="text" value="<?php echo esc_attr( $article_rows ); ?>" class="short" />
			</p>
			
			<p>
				<h3><?php _e('Links amount', GKTPLNAME); ?></h3>
				<label for="<?php echo esc_attr( $this->get_field_id( 'links_pages' ) ); ?>"><?php _e( 'pages:', GKTPLNAME ); ?></label>
				<input id="<?php echo esc_attr( $this->get_field_id( 'links_pages' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'links_pages' ) ); ?>" type="text" value="<?php echo esc_attr( $links_pages ); ?>" class="short" />

				<label for="<?php echo esc_attr( $this->get_field_id( 'links_rows' ) ); ?>"><?php _e( 'rows:', GKTPLNAME ); ?></label>
				<input id="<?php echo esc_attr( $this->get_field_id( 'links_rows' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'links_rows' ) ); ?>" type="text" value="<?php echo esc_attr( $links_rows ); ?>" class="short" />
			</p>
			
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'article_pagination' ) ); ?>"><?php _e( 'Article pagination:', GKTPLNAME ); ?></label>
				
				<select id="<?php echo esc_attr( $this->get_field_id('article_pagination')); ?>" name="<?php echo esc_attr( $this->get_field_name('article_pagination')); ?>">
					<option value="pagination"<?php echo (esc_attr($article_pagination) == 'pagination') ? ' selected="selected"' : ''; ?>>
						<?php _e('Pagination', GKTPLNAME); ?>
					</option>
					<option value="arrows"<?php echo (esc_attr($article_pagination) == 'arrows') ? ' selected="selected"' : ''; ?>>
						<?php _e('Arrows', GKTPLNAME); ?>
					</option>
					<option value="pagination_with_arrows"<?php echo (esc_attr($article_pagination) == 'pagination_with_arrows') ? ' selected="selected"' : ''; ?>>
						<?php _e('Both', GKTPLNAME); ?>
					</option>
				</select>
			</p>
			
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'links_pagination' ) ); ?>"><?php _e( 'Links pagination:', GKTPLNAME ); ?></label>
				
				<select id="<?php echo esc_attr( $this->get_field_id('links_pagination')); ?>" name="<?php echo esc_attr( $this->get_field_name('links_pagination')); ?>">
					<option value="pagination"<?php echo (esc_attr($links_pagination) == 'pagination') ? ' selected="selected"' : ''; ?>>
						<?php _e('Pagination', GKTPLNAME); ?>
					</option>
					<option value="arrows"<?php echo (esc_attr($links_pagination) == 'arrows') ? ' selected="selected"' : ''; ?>>
						<?php _e('Arrows', GKTPLNAME); ?>
					</option>
					<option value="pagination_with_arrows"<?php echo (esc_attr($links_pagination) == 'pagination_with_arrows') ? ' selected="selected"' : ''; ?>>
						<?php _e('Both', GKTPLNAME); ?>
					</option>
				</select>
			</p>
			
			<h3><?php _e('Autoanimation settings', GKTPLNAME); ?></h3>
			
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'autoanim' ) ); ?>"><?php _e( 'Auto-animation:', GKTPLNAME ); ?></label>
				<select id="<?php echo esc_attr( $this->get_field_id('autoanim')); ?>" name="<?php echo esc_attr( $this->get_field_name('autoanim')); ?>">
					<option value="on"<?php echo (esc_attr($autoanim) == 'on') ? ' selected="selected"' : ''; ?>>
						<?php _e('On', GKTPLNAME); ?>
					</option>
					<option value="off"<?php echo (esc_attr($autoanim) == 'off') ? ' selected="selected"' : ''; ?>>
						<?php _e('Off', GKTPLNAME); ?>
					</option>
				</select>			
			</p>
			
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'autoanim_interval' ) ); ?>"><?php _e( 'Interval (ms):', GKTPLNAME ); ?></label>
				<input class="medium" id="<?php echo esc_attr( $this->get_field_id( 'autoanim_interval' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'autoanim_interval' ) ); ?>" type="text" value="<?php echo esc_attr( $autoanim_interval ); ?>" />
			</p>
			
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'autoanim_hover' ) ); ?>"><?php _e( 'Auto-animation stops on hover:', GKTPLNAME ); ?></label>
				<select id="<?php echo esc_attr( $this->get_field_id('autoanim_hover')); ?>" name="<?php echo esc_attr( $this->get_field_name('autoanim_hover')); ?>">
					<option value="on"<?php echo (esc_attr($autoanim_hover) == 'on') ? ' selected="selected"' : ''; ?>>
						<?php _e('On', GKTPLNAME); ?>
					</option>
					<option value="off"<?php echo (esc_attr($autoanim_hover) == 'off') ? ' selected="selected"' : ''; ?>>
						<?php _e('Off', GKTPLNAME); ?>
					</option>
				</select>			
			</p>
		</div>
		
		<div class="gk-nsp-col">
			<h3><?php _e('Article format', GKTPLNAME); ?></h3>	
			
			<p>				
				<label for="<?php echo esc_attr( $this->get_field_id( 'article_block_padding' ) ); ?>"><?php _e( 'Padding:', GKTPLNAME ); ?></label>
				
				<input id="<?php echo esc_attr( $this->get_field_id( 'article_block_padding' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'article_block_padding' ) ); ?>" type="text" value="<?php echo esc_attr( $article_block_padding ); ?>" />
			</p>
						
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'article_title_state' ) ); ?>"><?php _e('Title', GKTPLNAME); ?></label>
				
				<select id="<?php echo esc_attr( $this->get_field_id('article_title_state')); ?>" name="<?php echo esc_attr( $this->get_field_name('article_title_state')); ?>">
					<option value="on"<?php echo (esc_attr($article_title_state) == 'on') ? ' selected="selected"' : ''; ?>>
						<?php _e('On', GKTPLNAME); ?>
					</option>
					<option value="off"<?php echo (esc_attr($article_title_state) == 'off') ? ' selected="selected"' : ''; ?>>
						<?php _e('Off', GKTPLNAME); ?>
					</option>
				</select>
				
				<label for="<?php echo esc_attr( $this->get_field_id( 'article_title_len' ) ); ?>"><?php _e( 'length:', GKTPLNAME ); ?></label>
				
				<input id="<?php echo esc_attr( $this->get_field_id( 'article_title_len' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'article_title_len' ) ); ?>" type="text" value="<?php echo esc_attr( $article_title_len ); ?>" class="short" />
				
				<select id="<?php echo esc_attr( $this->get_field_id('article_title_len_type')); ?>" name="<?php echo esc_attr( $this->get_field_name('article_title_len_type')); ?>">
					<option value="chars"<?php echo (esc_attr($article_title_len_type) == 'chars') ? ' selected="selected"' : ''; ?>>
						<?php _e('Chars', GKTPLNAME); ?>
					</option>
					<option value="words"<?php echo (esc_attr($article_title_len_type) == 'words') ? ' selected="selected"' : ''; ?>>
						<?php _e('Words', GKTPLNAME); ?>
					</option>
				</select>
				
				<span class="gk-right">
					<label for="<?php echo esc_attr( $this->get_field_id( 'article_title_order' ) ); ?>"><?php _e( 'order:', GKTPLNAME ); ?></label>
					
					<select id="<?php echo esc_attr( $this->get_field_id('article_title_order')); ?>" name="<?php echo esc_attr( $this->get_field_name('article_title_order')); ?>" class="gk-order">
						<option value="1"<?php echo (esc_attr($article_title_order) == '1') ? ' selected="selected"' : ''; ?>>1</option>
						<option value="2"<?php echo (esc_attr($article_title_order) == '2') ? ' selected="selected"' : ''; ?>>2</option>
						<option value="3"<?php echo (esc_attr($article_title_order) == '3') ? ' selected="selected"' : ''; ?>>3</option>
						<option value="4"<?php echo (esc_attr($article_title_order) == '4') ? ' selected="selected"' : ''; ?>>4</option>
						<option value="5"<?php echo (esc_attr($article_title_order) == '5') ? ' selected="selected"' : ''; ?>>5</option>
					</select>
				</span>
			</p>
						
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'article_text_state' ) ); ?>"><?php _e('Text', GKTPLNAME); ?></label>
				
				<select id="<?php echo esc_attr( $this->get_field_id('article_text_state')); ?>" name="<?php echo esc_attr( $this->get_field_name('article_text_state')); ?>">
					<option value="on"<?php echo (esc_attr($article_text_state) == 'on') ? ' selected="selected"' : ''; ?>>
						<?php _e('On', GKTPLNAME); ?>
					</option>
					<option value="off"<?php echo (esc_attr($article_text_state) == 'off') ? ' selected="selected"' : ''; ?>>
						<?php _e('Off', GKTPLNAME); ?>
					</option>
				</select>
				
				<label for="<?php echo esc_attr( $this->get_field_id( 'article_text_len' ) ); ?>"><?php _e( 'length:', GKTPLNAME ); ?></label>
				
				<input id="<?php echo esc_attr( $this->get_field_id( 'article_text_len' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'article_text_len' ) ); ?>" type="text" value="<?php echo esc_attr( $article_text_len ); ?>" class="short" />
				
				<select id="<?php echo esc_attr( $this->get_field_id('article_text_len_type')); ?>" name="<?php echo esc_attr( $this->get_field_name('article_text_len_type')); ?>">
					<option value="chars"<?php echo (esc_attr($article_text_len_type) == 'chars') ? ' selected="selected"' : ''; ?>>
						<?php _e('Chars', GKTPLNAME); ?>
					</option>
					<option value="words"<?php echo (esc_attr($article_text_len_type) == 'words') ? ' selected="selected"' : ''; ?>>
						<?php _e('Words', GKTPLNAME); ?>
					</option>
				</select>
				
				<span class="gk-right">
					<label for="<?php echo esc_attr( $this->get_field_id( 'article_text_order' ) ); ?>"><?php _e( 'order:', GKTPLNAME ); ?></label>
					
					<select id="<?php echo esc_attr( $this->get_field_id('article_text_order')); ?>" name="<?php echo esc_attr( $this->get_field_name('article_text_order')); ?>" class="gk-order">
						<option value="1"<?php echo (esc_attr($article_text_order) == '1') ? ' selected="selected"' : ''; ?>>1</option>
						<option value="2"<?php echo (esc_attr($article_text_order) == '2') ? ' selected="selected"' : ''; ?>>2</option>
						<option value="3"<?php echo (esc_attr($article_text_order) == '3') ? ' selected="selected"' : ''; ?>>3</option>
						<option value="4"<?php echo (esc_attr($article_text_order) == '4') ? ' selected="selected"' : ''; ?>>4</option>
						<option value="5"<?php echo (esc_attr($article_text_order) == '5') ? ' selected="selected"' : ''; ?>>5</option>
					</select>
				</span>
			</p>	
			
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'article_image_state' ) ); ?>"><?php _e('Image', GKTPLNAME); ?></label>
				
				<select id="<?php echo esc_attr( $this->get_field_id('article_image_state')); ?>" name="<?php echo esc_attr( $this->get_field_name('article_image_state')); ?>">
					<option value="on"<?php echo (esc_attr($article_image_state) == 'on') ? ' selected="selected"' : ''; ?>>
						<?php _e('On', GKTPLNAME); ?>
					</option>
					<option value="off"<?php echo (esc_attr($article_image_state) == 'off') ? ' selected="selected"' : ''; ?>>
						<?php _e('Off', GKTPLNAME); ?>
					</option>
				</select>
				
				<label for="<?php echo esc_attr( $this->get_field_id( 'article_image_w' ) ); ?>"><?php _e( 'size:', GKTPLNAME ); ?></label>
				
				<input id="<?php echo esc_attr( $this->get_field_id( 'article_image_w' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'article_image_w' ) ); ?>" type="text" value="<?php echo esc_attr( $article_image_w ); ?>" class="short" />
				&times;
				<input id="<?php echo esc_attr( $this->get_field_id( 'article_image_h' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'article_image_h' ) ); ?>" type="text" value="<?php echo esc_attr( $article_image_h ); ?>" class="short" />
				
				<span class="gk-right">
					<label for="<?php echo esc_attr( $this->get_field_id( 'article_image_order' ) ); ?>"><?php _e( 'order:', GKTPLNAME ); ?></label>
					
					<select id="<?php echo esc_attr( $this->get_field_id('article_image_order')); ?>" name="<?php echo esc_attr( $this->get_field_name('article_image_order')); ?>" class="gk-order">
						<option value="1"<?php echo (esc_attr($article_image_order) == '1') ? ' selected="selected"' : ''; ?>>1</option>
						<option value="2"<?php echo (esc_attr($article_image_order) == '2') ? ' selected="selected"' : ''; ?>>2</option>
						<option value="3"<?php echo (esc_attr($article_image_order) == '3') ? ' selected="selected"' : ''; ?>>3</option>
						<option value="4"<?php echo (esc_attr($article_image_order) == '4') ? ' selected="selected"' : ''; ?>>4</option>
						<option value="5"<?php echo (esc_attr($article_image_order) == '5') ? ' selected="selected"' : ''; ?>>5</option>
					</select>
				</span>
			</p>	
				
			<p class="gk-indent">				
				<label for="<?php echo esc_attr( $this->get_field_id( 'image_block_padding' ) ); ?>"><?php _e( 'Margin:', GKTPLNAME ); ?></label>
				
				<input id="<?php echo esc_attr( $this->get_field_id( 'image_block_padding' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image_block_padding' ) ); ?>" type="text" value="<?php echo esc_attr( $image_block_padding ); ?>" class="long" />
			</p>
			
			<p class="gk-indent">
				<label for="<?php echo esc_attr( $this->get_field_id( 'article_image_pos' ) ); ?>"><?php _e( 'Position:', GKTPLNAME ); ?></label>
				
				<select id="<?php echo esc_attr( $this->get_field_id('article_image_pos')); ?>" name="<?php echo esc_attr( $this->get_field_name('article_image_pos')); ?>">
					<option value="top"<?php echo (esc_attr($article_image_pos) == 'top') ? ' selected="selected"' : ''; ?>><?php _e('Top', GKTPLNAME); ?></option>
					<option value="left"<?php echo (esc_attr($article_image_pos) == 'left') ? ' selected="selected"' : ''; ?>><?php _e('Left', GKTPLNAME); ?></option>
				</select>
			</p>	
						
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'article_info_state' ) ); ?>"><?php _e('Info block', GKTPLNAME); ?></label>
				
				<select id="<?php echo esc_attr( $this->get_field_id('article_info_state')); ?>" name="<?php echo esc_attr( $this->get_field_name('article_info_state')); ?>">
					<option value="on"<?php echo (esc_attr($article_info_state) == 'on') ? ' selected="selected"' : ''; ?>>
						<?php _e('On', GKTPLNAME); ?>
					</option>
					<option value="off"<?php echo (esc_attr($article_info_state) == 'off') ? ' selected="selected"' : ''; ?>>
						<?php _e('Off', GKTPLNAME); ?>
					</option>
				</select>
				
				<span class="gk-right">
					<label for="<?php echo esc_attr( $this->get_field_id( 'article_info_order' ) ); ?>"><?php _e( 'order:', GKTPLNAME ); ?></label>
					
					<select id="<?php echo esc_attr( $this->get_field_id('article_info_order')); ?>" name="<?php echo esc_attr( $this->get_field_name('article_info_order')); ?>" class="gk-order">
						<option value="1"<?php echo (esc_attr($article_info_order) == '1') ? ' selected="selected"' : ''; ?>>1</option>
						<option value="2"<?php echo (esc_attr($article_info_order) == '2') ? ' selected="selected"' : ''; ?>>2</option>
						<option value="3"<?php echo (esc_attr($article_info_order) == '3') ? ' selected="selected"' : ''; ?>>3</option>
						<option value="4"<?php echo (esc_attr($article_info_order) == '4') ? ' selected="selected"' : ''; ?>>4</option>
						<option value="5"<?php echo (esc_attr($article_info_order) == '5') ? ' selected="selected"' : ''; ?>>5</option>
					</select>
				</span>
			</p>
			
			<p class="gk-indent">	
				<small>You can use in the Format option following tags:<br />%DATE, %CATEGORY, %AUTHOR, %COMMENTS</small>
			</p>
			
			<p class="gk-indent">
				<label for="<?php echo esc_attr( $this->get_field_id( 'article_info_format' ) ); ?>"><?php _e( 'Format:', GKTPLNAME ); ?></label>
				
				<input id="<?php echo esc_attr( $this->get_field_id( 'article_info_format' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'article_info_format' ) ); ?>" type="text" value="<?php echo esc_attr( $article_info_format ); ?>" />
			</p>
			
			<p class="gk-indent">	
				<label for="<?php echo esc_attr( $this->get_field_id( 'article_info_date_format' ) ); ?>"><?php _e( 'Date format:', GKTPLNAME ); ?></label>
				
				<input id="<?php echo esc_attr( $this->get_field_id( 'article_info_date_format' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'article_info_date_format' ) ); ?>" type="text" value="<?php echo esc_attr( $article_info_date_format ); ?>" class="medium" />
			</p>
						
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'article_readmore_state' ) ); ?>"><?php _e('Read more', GKTPLNAME); ?></label>
			
				<select id="<?php echo esc_attr( $this->get_field_id('article_readmore_state')); ?>" name="<?php echo esc_attr( $this->get_field_name('article_readmore_state')); ?>">
					<option value="on"<?php echo (esc_attr($article_readmore_state) == 'on') ? ' selected="selected"' : ''; ?>>
						<?php _e('On', GKTPLNAME); ?>
					</option>
					<option value="off"<?php echo (esc_attr($article_readmore_state) == 'off') ? ' selected="selected"' : ''; ?>>
						<?php _e('Off', GKTPLNAME); ?>
					</option>
				</select>
				
				<span class="gk-right">
					<label for="<?php echo esc_attr( $this->get_field_id( 'article_readmore_order' ) ); ?>"><?php _e( 'order:', GKTPLNAME ); ?></label>
					
					<select id="<?php echo esc_attr( $this->get_field_id('article_readmore_order')); ?>" name="<?php echo esc_attr( $this->get_field_name('article_readmore_order')); ?>" class="gk-order">
						<option value="1"<?php echo (esc_attr($article_readmore_order) == '1') ? ' selected="selected"' : ''; ?>>1</option>
						<option value="2"<?php echo (esc_attr($article_readmore_order) == '2') ? ' selected="selected"' : ''; ?>>2</option>
						<option value="3"<?php echo (esc_attr($article_readmore_order) == '3') ? ' selected="selected"' : ''; ?>>3</option>
						<option value="4"<?php echo (esc_attr($article_readmore_order) == '4') ? ' selected="selected"' : ''; ?>>4</option>
						<option value="5"<?php echo (esc_attr($article_readmore_order) == '5') ? ' selected="selected"' : ''; ?>>5</option>
					</select>
				</span>
			</p>
			
			<h3><?php _e('Link format', GKTPLNAME); ?></h3>
						
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'link_title_state' ) ); ?>"><?php _e('Title', GKTPLNAME); ?></label>
				
				<select id="<?php echo esc_attr( $this->get_field_id('links_title_state')); ?>" name="<?php echo esc_attr( $this->get_field_name('links_title_state')); ?>">
					<option value="on"<?php echo (esc_attr($links_title_state) == 'on') ? ' selected="selected"' : ''; ?>>
						<?php _e('On', GKTPLNAME); ?>
					</option>
					<option value="off"<?php echo (esc_attr($links_title_state) == 'off') ? ' selected="selected"' : ''; ?>>
						<?php _e('Off', GKTPLNAME); ?>
					</option>
				</select>
				
				<label for="<?php echo esc_attr( $this->get_field_id( 'links_title_len' ) ); ?>"><?php _e( 'length:', GKTPLNAME ); ?></label>
				
				<input id="<?php echo esc_attr( $this->get_field_id( 'links_title_len' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'links_title_len' ) ); ?>" type="text" value="<?php echo esc_attr( $links_title_len ); ?>" class="short" />
				
				<select id="<?php echo esc_attr( $this->get_field_id('links_title_len_type')); ?>" name="<?php echo esc_attr( $this->get_field_name('links_title_len_type')); ?>">
					<option value="chars"<?php echo (esc_attr($links_title_len_type) == 'chars') ? ' selected="selected"' : ''; ?>>
						<?php _e('Chars', GKTPLNAME); ?>
					</option>
					<option value="words"<?php echo (esc_attr($links_title_len_type) == 'words') ? ' selected="selected"' : ''; ?>>
						<?php _e('Words', GKTPLNAME); ?>
					</option>
				</select>
			</p>
			
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'link_text_state' ) ); ?>"><?php _e('Text', GKTPLNAME); ?></label>
			
				<select id="<?php echo esc_attr( $this->get_field_id('links_text_state')); ?>" name="<?php echo esc_attr( $this->get_field_name('links_text_state')); ?>">
					<option value="on"<?php echo (esc_attr($links_text_state) == 'on') ? ' selected="selected"' : ''; ?>>
						<?php _e('On', GKTPLNAME); ?>
					</option>
					<option value="off"<?php echo (esc_attr($links_text_state) == 'off') ? ' selected="selected"' : ''; ?>>
						<?php _e('Off', GKTPLNAME); ?>
					</option>
				</select>
				
				<label for="<?php echo esc_attr( $this->get_field_id( 'links_text_len' ) ); ?>"><?php _e( 'length:', GKTPLNAME ); ?></label>
				
				<input id="<?php echo esc_attr( $this->get_field_id( 'links_text_len' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'links_text_len' ) ); ?>" type="text" value="<?php echo esc_attr( $links_text_len ); ?>" class="short" />
				
				<select id="<?php echo esc_attr( $this->get_field_id('links_text_len_type')); ?>" name="<?php echo esc_attr( $this->get_field_name('links_text_len_type')); ?>">
					<option value="chars"<?php echo (esc_attr($links_text_len_type) == 'chars') ? ' selected="selected"' : ''; ?>>
						<?php _e('Chars', GKTPLNAME); ?>
					</option>
					<option value="words"<?php echo (esc_attr($links_text_len_type) == 'words') ? ' selected="selected"' : ''; ?>>
						<?php _e('Words', GKTPLNAME); ?>
					</option>
				</select>
			</p>
			
			<h3><?php _e('Cache settings', GKTPLNAME); ?></h3>
			
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'cache_time' ) ); ?>"><?php _e( 'Cache time (min):', GKTPLNAME ); ?></label>
				<input class="medium" id="<?php echo esc_attr( $this->get_field_id( 'cache_time' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'cache_time' ) ); ?>" type="text" value="<?php echo esc_attr( $cache_time ); ?>" />
			</p>
		</div>
		
		<hr class="clear" />
		
	<?php
	}
	
	/**
	 *
	 * Functions used to generate the article elements
	 *
	 **/
	 
	 function generate_art_title($i) {
	 	$art_title = '';
	 	$art_ID = '';
	 	$art_url = '';
	 	
	 	if($this->wdgt_config['data_source_type'] == 'custom') {
	 		$art_title = $this->wdgt_results[0][$i]->post_title;
	 		$art_ID = $this->wdgt_results[0][$i]->ID;
	 	} else {
	 		$art_title = $this->wdgt_results[$i]->post_title;
	 		$art_ID = $this->wdgt_results[$i]->ID;
	 	}
	 	
	 	$art_url = get_permalink($art_ID);
	 	$art_title_short = $this->cut_text('article_title', $art_title);
	 	
	 	$output = '<h3 class="gk-nsp-header"><a href="'.$art_url.'" title="'.str_replace('"','', $art_title).'">'.$art_title_short.'</a></h3>';
	 	
	 	return $output;
	 }
	 
	 function generate_art_text($i) {
	 	$art_text = '';
	 	
	 	if($this->wdgt_config['data_source_type'] == 'custom') {
	 		$art_text = $this->wdgt_results[0][$i]->post_content;
	 	} else {
	 		$art_text = $this->wdgt_results[$i]->post_content;
	 	}
	 	
	 	$art_text = $this->cut_text('article_text', $art_text);
	 	$art_text = preg_replace('@\[.+?\]@mis', '', $art_text);
	 	
	 	$output = '<p class="gk-nsp-text">'.$art_text.'</p>';
	 	
	 	return $output;
	 }
	 
	 function generate_art_image($i) {
	 	$art_ID = '';
	 	
	 	if($this->wdgt_config['data_source_type'] == 'custom') {
	 		$art_ID = $this->wdgt_results[0][$i]->ID;
	 	} else {
	 		$art_ID = $this->wdgt_results[$i]->ID;
	 	}
	 	
	 	$art_url = get_permalink($art_ID);
	 
	 	$image = wp_get_attachment_image_src( get_post_thumbnail_id( $art_ID ), 'single-post-thumbnail' );
	 	$image_path = $image[0];
	 	$upload_dir = wp_upload_dir();
	 	$image_path = str_replace($upload_dir['baseurl'] . DIRECTORY_SEPARATOR, '', $image_path);
	 	
	 	if($image_path != '') {
	 		$img_editor = wp_get_image_editor( $upload_dir['basedir'] . DIRECTORY_SEPARATOR . $image_path);
	 		
	 		if(!is_wp_error($img_editor)) {
		 		$img_editor->resize($this->wdgt_config['article_image_w'], $this->wdgt_config['article_image_h'], true);
		 		$img_filename = $img_editor->generate_filename( $this->id, dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cache_nsp');
		 		$img_editor->save($img_filename);
		 		
		 	    $new_path = $img_filename;	
		 		
		 		if(is_string($new_path)) {
			 		$new_path_pos = stripos($new_path, '/gavern/cache_nsp');
		 			$new_path = substr($new_path, $new_path_pos);
		 			$new_path = get_template_directory_uri() . $new_path;
		 		
		 			$style = '';
		 			
		 			if($this->wdgt_config['image_block_padding'] != '' && $this->wdgt_config['image_block_padding'] != '0') {
		 				$style = ' style="margin: '.$this->wdgt_config['image_block_padding'].';"';
		 			}
		 		
		 			if($this->wdgt_config['article_image_pos'] == 'left' && $this->wdgt_config['article_image_order'] == 1) {
		 				return '<div class="gk-nsp-image-wrap"><a href="'.$art_url.'" class="gk-image-link"><img src="'.$new_path.'" alt="" class="gk-nsp-image" '.$style.' /></a></div>';
		 			} else {
		 				return '<a href="'.$art_url.'" class="gk-responsive gk-image-link"><img src="'.$new_path.'" alt="" class="gk-nsp-image gk-responsive" '.$style.' /></a>';
		 			}
	 			} else {
	 				return __('An error occured during creating the thumbnail.', GKTPLNAME);
	 			}
 			} else {
 				return __('An error occured during creating the thumbnail.', GKTPLNAME);
 			}
	 	} else {
	 		return '';
	 	} 
	 }
	 
	 function generate_art_info($i) {
	 	// replacements for the possible tags
	 	$category = '';
	 	$author = '';
	 	$date = '';
	 	$comments = '';
	 	
	 	// check if there is a category in format
	 	if(stripos($this->wdgt_config['article_info_format'], '%CATEGORY') !== FALSE) {
	 	
	 		if($this->wdgt_config['data_source_type'] == 'custom') {
	 			$art_ID = $this->wdgt_results[0][$i]->ID;
	 		} else {
	 			$art_ID = $this->wdgt_results[$i]->ID;
	 		}
	 		
	 		$categories = get_the_category($art_ID);
	 		
	 		if(count($categories) > 0) {
	 			foreach($categories as $cat) { 			
	 				$category .= ' <a href="'.get_category_link( $cat->term_id ).'" class="gk-nsp-category">'.$cat->name.'</a> ';
	 			}
	 		}
	 	}
	 	// check if there is a author in format
	 	if(stripos($this->wdgt_config['article_info_format'], '%AUTHOR') !== FALSE) {	 		
	 		if($this->wdgt_config['data_source_type'] == 'custom') {
	 			$author_ID = $this->wdgt_results[0][$i]->post_author;
	 		} else {
	 			$author_ID = $this->wdgt_results[$i]->post_author;
	 		}
	 		
	 		$username = get_the_author_meta('display_name', $author_ID);
	 		$author = '<a href="'.get_author_posts_url($author_ID).'" class="gk-nsp-author">'.$username.'</a>';
	 	}
	 	// check if there is a date in format
	 	if(stripos($this->wdgt_config['article_info_format'], '%DATE') !== FALSE) {
	 		// post_date
	 		if($this->wdgt_config['data_source_type'] == 'custom') {
	 			$art_ID = $this->wdgt_results[0][$i]->ID;
	 		} else {
	 			$art_ID = $this->wdgt_results[$i]->ID;
	 		}
	 		
	 		$date = '<span class="gk-nsp-date">' . get_the_time($this->wdgt_config['article_info_date_format'], $art_ID) . '</span>';
	 	}
	 	// check if there is a comments in format
	 	if(stripos($this->wdgt_config['article_info_format'], '%COMMENTS') !== FALSE) {
	 		// comment_count
	 		// post_date
	 		if($this->wdgt_config['data_source_type'] == 'custom') {
	 			$comment_count = $this->wdgt_results[0][$i]->comment_count;
	 			$art_ID = $this->wdgt_results[0][$i]->ID;
	 		} else {
	 			$comment_count = $this->wdgt_results[$i]->comment_count;
	 			$art_ID = $this->wdgt_results[$i]->ID;
	 		}
	 		
	 		$comment_phrase = '';
	 		
	 		if($comment_count == 0) {
	 			$comment_phrase = __('No comments', GKTPLNAME);
	 		} else if($comment_count >= 1) {
	 			$comment_phrase = __('Comments ', GKTPLNAME) . '(' . $comment_count . ')';
	 		}
	 		
	 		$comments = '<a href="'.get_permalink($art_ID).'#comments">'.$comment_phrase.'</a>';
	 	}
	 	// replace them all!
	 	$output = str_replace(
	 		array('%CATEGORY', '%AUTHOR', '%DATE', '%COMMENTS'),
	 		array($category, $author, $date, $comments),
	 		$this->wdgt_config['article_info_format']
	 	);
	 
	 	return '<p class="gk-nsp-info">' . $output . '</p>';
	 }
	 
	 function generate_art_readmore($i) {
	 	$art_ID = '';
	 	$art_url = '';
	 	
	 	if($this->wdgt_config['data_source_type'] == 'custom') {
	 		$art_ID = $this->wdgt_results[0][$i]->ID;
	 	} else {
	 		$art_ID = $this->wdgt_results[$i]->ID;
	 	}
	 	
	 	$art_url = get_permalink($art_ID);
	 	
	 	$output = '<a href="'.$art_url.'" class="readon btn" title="'.__('Read more', GKTPLNAME).'">'.__('Read more', GKTPLNAME).'</a>';
	 	
	 	return $output;
	 }
	 
	 /**
	  *
	  * Functions used to generate the links elements
	  *
	  **/
	  
	  function generate_link_title($i) {
	  	$art_title = '';
	  	$art_ID = '';
	  	$art_url = '';
	  	
	  	if($this->wdgt_config['data_source_type'] == 'custom') {
	  		$art_title = $this->wdgt_results[0][$i]->post_title;
	  		$art_ID = $this->wdgt_results[0][$i]->ID;
	  	} else {
	  		$art_title = $this->wdgt_results[$i]->post_title;
	  		$art_ID = $this->wdgt_results[$i]->ID;
	  	}
	  	
	  	$art_url = get_permalink($art_ID);
	  	$art_title_short = $this->cut_text('links_title', $art_title);
	  	
	  	$output = '<h4 class="gk-nsp-link-header"><a href="'.$art_url.'" title="'.str_replace('"','', $art_title).'">'.$art_title_short.'</a></h4>';
	  	
	  	return $output;
	  }
	  
	  function generate_link_text($i) {
	  	$art_text = '';
	  	
	  	if($this->wdgt_config['data_source_type'] == 'custom') {
	  		$art_text = $this->wdgt_results[0][$i]->post_content;
	  	} else {
	  		$art_text = $this->wdgt_results[$i]->post_content;
	  	}
	  	
	  	$art_text = $this->cut_text('links_text', $art_text);
	  	$art_text = preg_replace('@\[.+?\]@mis', '', $art_text);
	  	
	  	$output = '<p class="gk-nsp-link-text">'.$art_text.'</p>';
	  	
	  	return $output;
	  }
	 
	 /**
	  *
	  * Helper functions
	  *
	  **/
	 
	 function cut_text($type, $text, $at_end = '&hellip;') {
	 	$text = strip_tags($text);
	 	$len_type = $this->wdgt_config[$type . '_len_type'];
	 	$len = $this->wdgt_config[$type . '_len'];
	 	$cutter = array();
	 	
	 	if($len_type == 'words' && $len > 0){
	 		$temp = explode(' ',$text);
	 		
	 		if(count($temp) > $len){
	 			for($i=0; $i<$len; $i++) $cutted[$i] = $temp[$i];
	 			$cutted = implode(' ', $cutted);
	 			$text = $cutted.$at_end;
	 		}
	 	} elseif($len_type == 'words' && $len == 0) {
	 		return '';
	 	} else {
	 		if(function_exists('mb_strlen')) {
	 			if(mb_strlen($text) > $len){
	 				$text = mb_substr($text, 0, $len) . $at_end;
	 			}
	 		} else {
	 			if(strlen($text) > $len){
	 				$text = substr($text, 0, $len) . $at_end;
	 			}
	 		}
	 	}
	 	// replace unnecessary entities at end of the cutted text
	 	$toReplace = array('&&', '&a&', '&am&', '&amp&', '&q&', '&qu&', '&quo&', '&quot&', '&ap&', '&apo&', '&apos&');
	 	$text = str_replace($toReplace, '&', $text);
	 	//
	 	return $text;
	 }
}

// EOF