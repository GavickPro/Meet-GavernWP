<?php

/**
 * 
 * GK Tabs Widget class
 *
 **/

class GK_Tabs_Widget extends WP_Widget {
	/**
	 *
	 * Constructor
	 *
	 * @return void
	 *
	 **/
	function GK_Tabs_Widget() {
		$this->WP_Widget(
			'widget_gk_tabs', 
			__( 'GK Tabs', GKTPLNAME ), 
			array( 
				'classname' => 'widget_gk_tabs', 
				'description' => __( 'Use this widget to show tabs created form the selected sidebar', GKTPLNAME) 
			)
		);
		
		$this->alt_option_name = 'widget_gk_tabs';
		//
		add_action('wp_enqueue_scripts', array('GK_Tabs_Widget', 'add_scripts'));
	}
	
	static function add_scripts() {
		wp_register_script( 'gk-tabs', gavern_file_uri('js/widgets/tabs.js'), array('jquery'), false, true);
		wp_enqueue_script('gk-tabs');
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
		global $wp_registered_widgets;
		global $wp_registered_sidebars;
		
		if(!isset($args['widget_id'])) {
			$args['widget_id'] = null;
		}

		//
		extract($args, EXTR_SKIP);
		//
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		$sidebar = empty($instance['sidebar']) ? '' : $instance['sidebar'];
		$event = empty($instance['event']) ? '' : $instance['event'];
		$autoanim = empty($instance['autoanim']) ? '' : $instance['autoanim'];
		$anim_speed = empty($instance['anim_speed']) ? '' : $instance['anim_speed'];
		$anim_interval = empty($instance['anim_interval']) ? '' : $instance['anim_interval'];
		
		//
		if ($sidebar !== '') {
			echo $before_widget;
			if($title != '') {
				echo $before_title;
				echo $title;
				echo $after_title;
			}
			// generating wrapper with params in the data-* attributes
			echo '<div class="gk-tabs" data-event="'.$event.'" data-autoanim="'.$autoanim.'" data-speed="'.$anim_speed.'" data-interval="'.$anim_interval.'">';
			echo '<div class="gk-tabs-wrap">';
			
			// creating the tabs
			$sidebars = wp_get_sidebars_widgets();
			$widget_code = array();
			
			foreach($sidebars[$sidebar] as $widget) {
				if(isset($wp_registered_widgets[$widget])) {
					$selected_sidebar = $wp_registered_sidebars[$sidebar];
					// get the widget params and merge with sidebar data, widget ID and name
					$params = array_merge(
						array( 
							array_merge( 
								$selected_sidebar, 
								array(
									'widget_id' => $widget, 
									'widget_name' => $wp_registered_widgets[$widget]['name']
								) 
							) 
						),
						
						(array) $wp_registered_widgets[$widget]['params']
					);
					
					// apply params
					$params = apply_filters( 'dynamic_sidebar_params', $params );
					// modify params
					$params[0]['before_widget'] = '<div id="'.$widget.'" class="box '.$wp_registered_widgets[$widget]['classname'].'"><div>';
					$params[0]['after_widget'] = '</div></div>';
					$params[0]['before_title'] = '{TABS_TITLE}';
					$params[0]['after_title'] = '{TABS_TITLE}';
					// get the widget callback function
					$callback = $wp_registered_widgets[$widget]['callback'];
					// generate
					ob_start();
					do_action('dynamic_sidebar', $wp_registered_widgets[$widget]);
					// use the widget callback function if exists
					if ( is_callable($callback) ) {
						call_user_func_array($callback, $params);
					}
					// get the widget code
					array_push($widget_code, ob_get_contents());
					ob_end_clean();
				}
			}
			// get the tabs data
			$tabs = array();
			$tabs_content = array();
			
			foreach($widget_code as $code) {
				$title_match = array();
				preg_match_all('@{TABS_TITLE}(.*?){TABS_TITLE}@mis', $code, $title_match);
				
				if(count($title_match) > 1 && isset($title_match[1][0])) {
					array_push($tabs, $title_match[1][0]);
				} else {
					array_push($tabs, _e('No title specified!', GKTPLNAME));
				}
				
				array_push($tabs_content, preg_replace('@{TABS_TITLE}(.*?){TABS_TITLE}@mis', '', $code));
			}
			// generate the tabs content
			echo '<ol class="gk-tabs-nav">';
			for($i = 0; $i < count($tabs); $i++) {
				echo '<li'.(($i == 0) ? ' class="active"' : '').'>' . apply_filters('gk_tabs_tab', $tabs[$i]) . '</li>';
			}
			echo '</ol>';
			
			echo '<div class="gk-tabs-container">';
			for($i = 0; $i < count($tabs_content); $i++) {
				echo '<div class="gk-tabs-item'.(($i == 0) ? ' active' : '').'">' . apply_filters('gk_tabs_content', $tabs_content[$i]) . '</div>';
			}
			echo '</div>';
			// close the tabs wrapper
			echo '</div>';
			echo '</div>';
			// 
			echo $after_widget;
		}
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
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['sidebar'] = strip_tags($new_instance['sidebar']);
		$instance['event'] = strip_tags($new_instance['event']);
		$instance['autoanim'] = strip_tags($new_instance['autoanim']);
		$instance['anim_speed'] = strip_tags($new_instance['anim_speed']);
		$instance['anim_interval'] = strip_tags($new_instance['anim_interval']);

		$this->refresh_cache();

		$alloptions = wp_cache_get('alloptions', 'options');
		if(isset($alloptions['widget_gk_tabs'])) {
			delete_option( 'widget_gk_tabs' );
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
		wp_cache_delete( 'widget_gk_tabs', 'widget' );
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
		global $wp_registered_sidebars;
		
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$selected_sidebar = isset($instance['sidebar']) ? esc_attr($instance['sidebar']) : '';
		$event = isset($instance['event']) ? esc_attr($instance['event']) : '';
		$autoanim = isset($instance['autoanim']) ? esc_attr($instance['autoanim']) : '';
		$anim_speed = isset($instance['anim_speed']) ? esc_attr($instance['anim_speed']) : '';
		$anim_interval = isset($instance['anim_interval']) ? esc_attr($instance['anim_interval']) : '';
	
	?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', GKTPLNAME ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'sidebar' ) ); ?>"><?php _e( 'Tabs source:', GKTPLNAME ); ?></label>
			
			<select id="<?php echo esc_attr( $this->get_field_id( 'sidebar' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'sidebar' ) ); ?>">
				<?php foreach(array_keys($wp_registered_sidebars) as $sidebar) : ?>
				<option value="<?php echo $sidebar; ?>"<?php selected($sidebar, $selected_sidebar); ?>>
					<?php echo $wp_registered_sidebars[$sidebar]["name"]; ?>
				</option>
				<?php endforeach; ?>
			</select>
		</p>
		
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('event')); ?>"><?php _e('Tabs activator event:', GKTPLNAME); ?></label>
			
			<select id="<?php echo esc_attr($this->get_field_id('event')); ?>" name="<?php echo esc_attr( $this->get_field_name('event')); ?>">
				<option value="click"<?php selected($event, 'click'); ?>>
					<?php _e('Click', GKTPLNAME); ?>
				</option>
				<option value="hover"<?php selected($event, 'hover'); ?>>
					<?php _e('Hover', GKTPLNAME); ?>
				</option>
			</select>
		</p>
		
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('autoanim')); ?>"><?php _e('Auto animation:', GKTPLNAME); ?></label>
			
			<select id="<?php echo esc_attr( $this->get_field_id('autoanim')); ?>" name="<?php echo esc_attr( $this->get_field_name('autoanim')); ?>">
				<option value="enabled"<?php selected($autoanim, 'enabled'); ?>>
					<?php _e('Enabled', GKTPLNAME); ?>
				</option>
				<option value="disabled"<?php selected($autoanim, 'disabled'); ?>>
					<?php _e('Disabled', GKTPLNAME); ?>
				</option>
			</select>
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'anim_speed' ) ); ?>"><?php _e( 'Animation speed (ms):', GKTPLNAME ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'anim_speed' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'anim_speed' ) ); ?>" type="text" value="<?php echo esc_attr( $anim_speed ); ?>" />
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'anim_interval' ) ); ?>"><?php _e( 'Animation interval (ms):', GKTPLNAME ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'anim_interval' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'anim_interval' ) ); ?>" type="text" value="<?php echo esc_attr( $anim_interval ); ?>" />
		</p>
		
		
	<?php
	}
}

// EOF