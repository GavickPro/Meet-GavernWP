<?php

/**
 *
 * The template fragment to show post featured image
 *
 **/

// disable direct access to the file	
defined('GAVERN_WP') or die('Access denied');

global $tpl; 

$params = get_post_custom();
$params_image = isset($params['gavern-post-params-image']) ? esc_attr( $params['gavern-post-params-image'][0] ) : 'Y';

?>

<?php if((is_single() || is_page()) && $params_image == 'Y') : ?>
	<?php 
		// if there is a Featured Video
		if(get_post_meta(get_the_ID(), "_gavern-featured-video", true) != '') : 
	?>
	
	<?php echo get_post_meta(get_the_ID(), "_gavern-featured-video", true); ?>
	
	<?php elseif(has_post_thumbnail()) : ?>
	<figure class="featured-image">
		<?php the_post_thumbnail(); ?>
		
		<?php if(is_single() || is_page()) : ?>
			<?php echo gk_post_thumbnail_caption(); ?>
		<?php endif; ?>
	</figure>
	<?php endif; ?>
<?php elseif(!(is_single() || is_page())) : ?>
	<?php 
		// if there is a Featured Video
		if(get_post_meta(get_the_ID(), "_gavern-featured-video", true) != '') : 
	?>
	
	<?php echo get_post_meta(get_the_ID(), "_gavern-featured-video", true); ?>
	
	<?php elseif(has_post_thumbnail()) : ?>
	<figure class="featured-image">
		<a href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail(); ?>
		</a>
		
		<?php if(is_single() || is_page()) : ?>
			<?php echo gk_post_thumbnail_caption(); ?>
		<?php endif; ?>
	</figure>
	<?php endif; ?>
<?php endif; ?>