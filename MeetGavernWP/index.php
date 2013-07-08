<?php

/**
 * GavernWP main template file
 *
 * This file is loaded only when user openes the site using the template
 *
 * @package WordPress
 * @subpackage GavernWP
 * @since GavernWP 1.0
 **/

global $tpl;

gk_load('header');
gk_load('before');

?>		
		
	<?php if(get_option($tpl->name . '_template_homepage_mainbody', 'N') == 'N') : ?>
		<?php do_action('gavernwp_before_mainbody'); ?>
		
		<?php if ( have_posts() ) : ?>		
			<section id="gk-mainbody">
				<?php do_action('gavernwp_before_loop'); ?>
			
				<?php while ( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'content', get_post_format() ); ?>
				<?php endwhile; ?>
				
				<?php gk_content_nav(); ?>
				
				<?php do_action('gavernwp_after_loop'); ?>
			</section>
		<?php else : ?>
			<section id="gk-mainbody">
				<article id="post-0" class="post no-results not-found">
					<header class="entry-header">
						<h1 class="entry-title"><?php _e( 'Nothing Found', GKTPLNAME ); ?></h1>
					</header>
		
					<div class="entry-content">
						<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', GKTPLNAME ); ?></p>
						<?php get_search_form(); ?>
					</div>
				</article>
			</section>
		<?php endif; ?>
		
		<?php do_action('gavernwp_after_mainbody'); ?>
	<?php else: ?>
		<?php if(gk_is_active_sidebar('mainbody')) : ?>
		<section id="gk-mainbody">
			<?php gk_dynamic_sidebar('mainbody'); ?>
		</section>
		<?php endif; ?>
	<?php endif; ?>
<?php

gk_load('after');
gk_load('footer');

/* EOF */