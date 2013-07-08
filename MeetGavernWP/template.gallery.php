<?php
/*
Template Name: Gallery Page
*/

global $tpl;

gk_load(
	'header', 
	array(
		'css' => gavern_file_uri('css/templates/gallery.css'),
		'js' => gavern_file_uri('js/templates/gallery.js')
	)
);
gk_load('before');

?>

<section id="gk-mainbody">
	<?php the_post(); ?>
	
	<h1 class="page-title"><?php the_title(); ?></h1>
	
	<article>
		<section class="intro">
			<?php the_content(); ?>
		</section>
	
		<section class="content">
			<?php
				// Load images
				$images = get_children(
					array(
						'numberposts' => -1, // Load all posts
						'orderby' => 'menu_order', // Images will be loaded in the order set in the page media manager
						'order'=> 'ASC', // Use ascending order
						'post_mime_type' => 'image', // Loads only images
						'post_parent' => $post->ID, // Loads only images associated with the specific page
						'post_status' => null, // No status
						'post_type' => 'attachment' // Type of the posts to load - attachments
					)
				);
			?>
			
			<?php if($images): ?>
			<section id="gallery">
				<?php 
					$firstFlag = true;
					$counter = 0;
					foreach($images as $image) : 
				?>
				<figure<?php if($firstFlag) echo ' class="active"'; ?>>
					<img src="<?php echo $image->guid; ?>" alt="<?php echo $image->post_title; ?>" title="<?php echo $image->post_title; ?>" />
					
					<?php if($image->post_title != '' || $image->post_content != '' || $image->post_excerpt != '') : ?>
					<figcaption>
						<h3><?php echo $image->post_title; // get the attachment title ?></h3>
						<p><?php echo $image->post_content; // get the attachment description ?></p>
						<small><?php echo $image->post_excerpt; // get the attachment caption ?></small>
					</figcaption>
					<?php endif; ?>
				</figure>
				<?php 
					$firstFlag = false;
					$counter++;
					endforeach;
				?>
				
				<ol>
				<?php for($i = 0; $i < $counter; $i++) : ?>
					<li<?php if($i == 0) echo ' class="active"'; ?>><?php echo $i; ?></li>
				<?php endfor; ?>
				</ol>
			</section>
		  	<?php endif; ?>
		</section>
	</article>
</section>

<?php

gk_load('after');
gk_load('footer');

// EOF