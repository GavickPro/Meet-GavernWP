<?php

/**
 *
 * The template for displaying content in the single.php template
 *
 **/
 
global $tpl;
 
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(is_page_template('template.fullwidth.php') ? ' page-fullwidth' : null); ?>>
	<header>
		<?php include('layouts/content.post.header.php'); ?>
	</header>

	<?php include('layouts/content.post.featured.php'); ?>

	<section class="content">
		<?php the_content(); ?>
		
		<?php gk_post_fields(); ?>
		<?php gk_post_links(); ?>
	</section>

	<?php include('layouts/content.post.footer.php'); ?>
</article>
