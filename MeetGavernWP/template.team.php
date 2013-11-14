<?php

/*
Template Name: Team
*/

global $gk_tpl;
global $post;

gk_load('header');
gk_load('before');

$subpages = get_pages( array( 'child_of' => $post->ID, 'sort_column' => 'post_date', 'sort_order' => 'desc' ) );

?>

<section id="gk-mainbody" class="team">
	<?php while ( have_posts() ) : the_post(); ?>
		<header>
			<h1 class="page-title"><?php the_title(); ?></h1>
		</header>
		
		<div class="content">
			<?php the_content(); ?>
			
			<div class="gk-team-members">
				<?php if(count($subpages) > 0) : ?> 
					<?php foreach( $subpages as $page ) :	?>
						<div class="gk-team-member">
							<a href="<?php echo get_page_link( $page->ID ); ?>">
								<?php echo get_the_post_thumbnail($page->ID, 'medium'); ?>
							</a>
							
							<h2>
								<a href="<?php echo get_page_link( $page->ID ); ?>">
									<?php echo preg_replace('/\((.*?)\)/i', '<small>${1}</small>', $page->post_title); ?>
								</a>
							</h2>
						</div>
					<?php endforeach; ?>
				<?php else : ?>
					<p><?php _e('There is no team members', GKTPLNAME); ?></p>
				<?php endif; ?>
			</div>
		</div>
		
	<?php endwhile; ?>
</section>

<?php

gk_load('after');
gk_load('footer');

// EOF