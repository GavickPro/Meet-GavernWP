<?php
/*
Template Name: Archive Page
*/

global $tpl;

gk_load('header');
gk_load('before');
$show_title = get_post_meta($post->ID, 'gavern-meta-show-title', true);
?>

<section id="gk-mainbody" class="archivepage">
	<?php the_post(); ?>
	
	<?php if ( empty( $show_title) ||  $show_title == 'Y') : ?>
		<h2 class="page-title"><?php the_title(); ?></h2>
	<?php endif; ?>
	
	<article>
		<section class="intro">
			<?php the_content(); ?>
		</section>
		
		<?php
			$posts_to_show = 10; //Max number of articles to display
			$debut = 0; //The first article to be displayed
		?>
		<div class="widget box first">
			<h2><?php _e('Lastest posts', GKTPLNAME); ?></h2>
			<ul>
				<?php
					$myposts = get_posts('numberposts='.$posts_to_show.'&offset='.$debut);
					foreach($myposts as $post) :
				?>
				<li><small><?php the_time('d/m/y') ?>:</small> <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
	
		<div class="widget box">
			<h2><?php _e('Categories', GKTPLNAME); ?></h2>
			<ul>
				<?php 
					wp_list_categories(array(
						'orderby' => 'name',
						'show_count' => 1
					)); 
				?>
			</ul>
		</div>
		
		<div class="widget box last">
			<h2><?php _e('Monthly Archives', GKTPLNAME); ?></h2>
			<ul>
				<?php wp_get_archives('type=monthly&show_post_count=1') ?>
			</ul>
		</div>
		
	</article>
</section>

<?php

gk_load('after');
gk_load('footer');

// EOF