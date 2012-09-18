<?php

/*
Template Name: Tag cloud
*/

global $tpl;

gk_load('header');
gk_load('before');
$show_title = get_post_meta($post->ID, 'gavern-meta-show-title', true);
?>

<section id="gk-mainbody" class="tagcloud">
	<?php while ( have_posts() ) : the_post(); ?>
		<?php if ( empty( $show_title) ||  $show_title == 'Y') : ?>
		<header>
			<h1 class="page-title"><?php the_title(); ?></h1>
		</header>
		<?php endif; ?>
		
		<section class="content">
			<?php the_content(); ?>
			
			<div class="tag-cloud">
				<?php wp_tag_cloud('number=0'); ?>
			</div>
		</section>
		
	<?php endwhile; ?>
</section>

<?php

gk_load('after');
gk_load('footer');

// EOF