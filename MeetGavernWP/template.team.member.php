<?php

/*
Template Name: Team Member
*/

global $gk_tpl;

gk_load('header');
gk_load('before');

?>

<section id="gk-mainbody" class="team-member">
	<?php while ( have_posts() ) : the_post(); ?>
		<?php get_template_part( 'layouts/content.post.featured' ); ?>
		
		<h1><?php echo preg_replace('/\((.*?)\)/i', '<small>${1}</small>', get_the_title()); ?></h1>
		
		<div class="content">				
			<?php the_content(); ?>
		</div>
	<?php endwhile; ?>
</section>

<?php

gk_load('after');
gk_load('footer');

// EOF