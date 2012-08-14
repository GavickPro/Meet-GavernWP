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

<?php if(has_post_thumbnail()) : ?>
<figure class="featured-image">
	<?php the_post_thumbnail(); ?>
</figure>
<?php endif; ?>