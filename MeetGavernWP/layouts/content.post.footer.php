<?php

/**
 *
 * The template fragment to show post footer
 *
 **/

// disable direct access to the file	
defined('GAVERN_WP') or die('Access denied');

global $tpl; 

?>

<?php do_action('gavernwp_after_post_content'); ?>

<?php if(is_singular()) : ?>
	<?php 
		// variable for the social API HTML output
		$social_api_output = gk_social_api(get_the_title(), get_the_ID()); 
	?>
		
	<?php if($social_api_output != '' || gk_author(false, true)): ?>
	<footer>
		<?php echo $social_api_output; ?>
		<?php gk_author(); ?>
	</footer>
	<?php endif; ?>
<?php endif; ?>