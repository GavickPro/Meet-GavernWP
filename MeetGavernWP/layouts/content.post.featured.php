<?php

/**
 *
 * The template fragment to show post featured image
 *
 **/

// disable direct access to the file	
defined('GAVERN_WP') or die('Access denied');

global $tpl; 

?>

<?php 
	// if there is a Featured Video
	if(get_post_meta(get_the_ID(), "_gavern-featured-video", true) != '') : 
?>

<?php echo get_post_meta(get_the_ID(), "_gavern-featured-video", true); ?>

<?php elseif(has_post_thumbnail()) : ?>
<figure class="featured-image">
	<?php the_post_thumbnail(); ?>
	
	<?php if(is_single()) : ?>
		<?php echo gk_post_thumbnail_caption(); ?>
	<?php endif; ?>
</figure>
<?php endif; ?>