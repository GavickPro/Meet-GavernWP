<?php

/*
Template Name: Contact Form
*/

global $tpl;

// flag used to detect if the page is validated
$validated = true;
// flag to detect if e-mail was sent
$messageSent = false;
// variable to store the errors, empty string means no error 
$errors = array(
	"name" => '',
	"email" => '',
	"message" => ''
);
// variable for the input fields output
$output = array(
	"name" => '',
	"email" => '',
	"message" => ''
);
// if the form was sent
if(isset($_POST['message-send'])) {
	// check the name
	if(trim($_POST['contact-name']) === '') {
		$validated = false;
		$errors['name'] = __('please enter your name', GKTPLNAME);
	} else {
		$output['name'] = trim($_POST['contact-name']);
	}
	// check the e-mail
	if(trim($_POST['email']) === '' || !eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", trim($_POST['email']))) {
		$validated = false;
		$errors['email'] = __('please enter correct email address.', GKTPLNAME);
	} else {
		$output['email'] = trim($_POST['email']);
	}
	// check the message content
	if(trim($_POST['comment-text']) === '') {
		$validated = false;
		$errors['message'] = __('please enter a text of the message.', GKTPLNAME);
	} else {
		$output['message'] = stripslashes(trim($_POST['comment-text']));
	}
	// if the all fields was correct
	if($validated) {
		// send an e-mail
		$email = get_option('tz_email');
		if (!isset($email) || ($email == '') ){
			$email = get_option('admin_email');
		}
		$subject = 'From ' . $output['name'];
		$body = "Name: ".$output['name']." \n\nE-mail: ".$output['email']." \n\nMessage: ".$output['message'];
		$headers = 'From: '.$output['name'].' <'.$output['email'].'>' . "\r\n" . 'Reply-To: ' . $output['email'];

		mail($email, $subject, $body, $headers);
		
		if(isset($_POST['send_copy'])) {
			mail($output['email'], $subject, $body, $headers);
		}
		
		$messageSent = true;
	}

} 

gk_load('header');
gk_load('before');
$show_title = get_post_meta($post->ID, 'gavern-meta-show-title', true);
$show_name = get_post_meta($post->ID, 'gavern-meta-show-contact-name', true);
$show_email = get_post_meta($post->ID, 'gavern-meta-show-contact-email', true);
$show_message = get_post_meta($post->ID, 'gavern-meta-show-contact-message', true);
$show_send_copy = get_post_meta($post->ID, 'gavern-meta-show-contact-send-copy', true);
?>

<section id="gk-mainbody" class="contactpage">
	<?php the_post(); ?>
	
	<?php if ( empty( $show_title) ||  $show_title == 'Y') : ?>
		<h2 class="page-title"><?php the_title(); ?></h2>
	<?php endif; ?>
	
	<article>
		<section class="intro">
			<?php the_content(); ?>
		</section>
	
		<?php if($messageSent == true) : ?>
		<p class="gk-thanks"><?php _e('Your message was sent to us successfully.', GKTPLNAME); ?></p>
		<?php else : ?>
		
			<?php if(!$validated) : ?>
			<p class="gk-error"><?php _e('Sorry, an error occured.', GKTPLNAME); ?></p>
			<?php endif; ?>
		
			<form action="<?php the_permalink(); ?>" id="gk-contact" method="post">
				<dl>
					<?php if ( empty( $show_name) ||  $show_name == 'Y') : ?>
						<dt>
							<label for="contact-name"><?php _e('Name:', GKTPLNAME); ?></label>
							<?php if($errors['name'] != '') : ?>
							<span class="error"><?php echo $errors['name'];?></span>
							<?php endif; ?>
						</dt>
						<dd>	
							<input type="text" name="contact-name" id="contact-name" value="<?php echo $output['message'];?>" />
						</dd>
					<?php endif; ?>
					
					<?php if ( empty( $show_email) ||  $show_email == 'Y') : ?>
						<dt>
							<label for="email"><?php _e('Email:', GKTPLNAME); ?></label>
							<?php if($errors['email'] != '') : ?>
							<span class="error"><?php echo $errors['email'];?></span>
							<?php endif; ?>
						</dt>
						<dd>	
							<input type="text" name="email" id="email" value="<?php echo $output['email'];?>" />
						</dd>
					<?php endif; ?>
					
					<?php if ( empty( $show_message) ||  $show_message == 'Y') : ?>
						<dt>
							<label for="comment-text"><?php _e('Message:', GKTPLNAME); ?></label>
							<?php if($errors['message'] != '') : ?>
							<span class="error"><?php echo $errors['message'];?></span>
							<?php endif; ?>
						</dt>
						<dd>
							<textarea name="comment-text" id="comment-text" rows="6" cols="30"><?php echo $output['message']; ?></textarea>
						</dd>
					<?php endif; ?>
				</dl>
				<?php if ( empty( $show_send_copy) ||  $show_send_copy == 'Y') : ?>
				<p>
					<label>
						<input type="checkbox" name="send_copy" /> 
						<?php _e('Send copy of the message to yourself', GKTPLNAME); ?>
					</label>
				</p>
				<?php endif; ?>
				<p>
					<input type="submit" value="<?php _e('Send message', GKTPLNAME); ?>" />
				</p>
				<input type="hidden" name="message-send" id="message-send" value="true" />
			</form>
		<?php endif; ?>
	</article>
</section>

<?php

gk_load('after');
gk_load('footer');

// EOF