<?php

/**
 *
 * Search page
 *
 **/

global $tpl;

gk_load('header');
gk_load('before');

?>

<section id="gk-mainbody" class="search-page">
	<?php if ( have_posts() ) : ?>
		<h1 class="page-title">
			<?php printf( __( 'Search Results for: %s', GKTPLNAME ), '<em>' . get_search_query() . '</em>' ); ?>
		</h1>
	
		<?php 
			get_search_form(); 
			$founded = false;
		?>
		
		<?php gk_content_nav(); ?>
		
		<?php while ( have_posts() ) : the_post(); ?>
			<?php
			
				get_template_part( 'content', get_post_format() );
				$founded = true;
			?>
		<?php endwhile; ?>
		
		<?php gk_content_nav(); ?>
	
		<?php if(!$founded) : ?>
		<h2>
			<?php _e( 'Nothing Found', GKTPLNAME ); ?>
		</h2>
		
		<section class="intro">
			<?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', GKTPLNAME ); ?>
		</section>
		<?php endif; ?>
	
	<?php else : ?>				
		<h1 class="page-title">
			<?php _e( 'Nothing Found', GKTPLNAME ); ?>
		</h1>
		
		<?php get_search_form(); ?>
		
		<section class="intro">
			<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', GKTPLNAME ); ?></p>
		</section>
	<?php endif; ?>
</section>

<?php

gk_load('after');
gk_load('footer');

// EOF