<?php 
	
	/**
	 *
	 * Template footer
	 *
	 **/
	
	// create an access to the template main object
	global $tpl;
	
	// disable direct access to the file	
	defined('GAVERN_WP') or die('Access denied');
	
?>

	<footer id="gk-footer" class="gk-page">
		<?php 			
			if(gk_show_menu('footermenu')) {
				wp_nav_menu(array(
				      'theme_location'  => 'footermenu',
					  'container'       => 'menu', 
					  'container_class' => 'menu-{menu slug}-container', 
					  'container_id'    => 'gkFooterMenu',
					  'menu_class'      => 'menu ' . $tpl->menu['footermenu']['style'], 
					  'menu_id'         => 'footer-menu',
					  'echo'            => true,
					  'fallback_cb'     => 'wp_page_menu',
					  'before'          => '',
					  'after'           => '',
					  'link_before'     => '',
					  'link_after'      => '',
					  'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
					  'depth'           => $tpl->menu['footermenu']['depth']
				));
			}
		?>
		
		<?php if(get_option($tpl->name . '_template_footer_content', '') != '') : ?>
		<div class="gk-copyrights">
			<?php echo str_replace('\\', '', htmlspecialchars_decode(get_option($tpl->name . '_template_footer_content', ''))); ?>
			
			<a href="#top" id="gk-top-link"><?php _e('Back to top', GKTPLNAME); ?></a>
		</div>
		<?php endif; ?>
		
		<?php if(get_option($tpl->name . '_styleswitcher_state', 'Y') == 'Y') : ?>
		<div id="gk-style-area">
			<?php for($i = 0; $i < count($tpl->styles); $i++) : ?>
			<div class="gk-style-switcher-<?php echo $tpl->styles[$i]; ?>">
				<?php foreach($tpl->style_colors[$tpl->styles[$i]] as $stylename => $link) : ?> 
				<a href="#<?php echo $link; ?>"><?php echo $stylename; ?></a>
				<?php endforeach; ?>
			</div>
			<?php endfor; ?>
		</div>
		<?php endif; ?>
		
		<?php if(get_option($tpl->name . '_template_footer_logo', 'Y') == 'Y') : ?>
		<img src="<?php echo get_template_directory_uri(); ?>/images/gavernwp.png" class="gk-framework-logo" alt="GavernWP" />
		<?php endif; ?>
		
		<p class="gk-disclaimer">Copyright &copy; 2012. Designed by <a href="http://www.gavick.com">GavickPro</a> - High quality free WordPress Themes</p>
		<p class="gk-disclaimer">Icons from <a href="http://glyphicons.com">Glyphicons Free</a>, licensed under <a href="http://creativecommons.org/licenses/by/3.0/">CC BY 3.0</a></p>
	</footer>
	
	<?php if(gk_is_active_sidebar('social')) : ?>
	<div id="gk-social-icons" class="<?php echo get_option($tpl->name . '_social_icons_position', 'right'); ?>">
		<?php gk_dynamic_sidebar('social'); ?>
	</div>
	<?php endif; ?>
	
	<?php gk_load('social'); ?>
	<?php wp_footer(); ?>
</body>
</html>
