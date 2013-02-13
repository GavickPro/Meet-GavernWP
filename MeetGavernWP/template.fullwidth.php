<?php

/*
Template Name: Full width page
*/
 
global $tpl;

$fullwidth = true;

gk_load('header');
gk_load('before', null, array('sidebar' => false));

?>

<div id="gk-mainbody">
	<?php while ( have_posts() ) : the_post(); ?>
		<?php gk_content_nav(); ?>
		
		<?php get_template_part( 'content', 'single' ); ?>
	
		<?php if(get_option($tpl->name . '_pages_show_comments_on_pages', 'Y') == 'Y') : ?>
		<?php comments_template( '', true ); ?>
		<?php endif; ?>
	<?php endwhile; ?>
</div>

<?php

gk_load('after', null, array('sidebar' => false));
gk_load('footer');

// EOF