<?php

/**
 *
 * Page
 *
 **/

global $tpl;

gk_load('header');
gk_load('before');

?>

<section id="gk-mainbody">
	<?php the_post(); ?>
	
	<?php get_template_part( 'content', 'page' ); ?>
	
	<?php comments_template( '', true ); ?>
</section>

<?php

gk_load('after');
gk_load('footer');

// EOF