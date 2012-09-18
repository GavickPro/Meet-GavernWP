<?php

/*
Template Name: Contact Form
*/

global $tpl;

//
require_once('gavern/classes/class.recaptchalib.php');
// flag used to detect if the page is validated
$validated = true;
// flag to detect if e-mail was sent
$messageSent = false;
// variable to store the errors, empty string means no error 
$errors = array(
	"name" => '',
	"email" => '',
	"message" => '',
	"recaptcha" => ''
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
	// reCAPTCHA validation
	if(
		get_option($tpl->name . '_recaptcha_state', 'N') == 'Y' && 
		get_option($tpl->name . '_recaptcha_public_key', '') != '' &&
		get_option($tpl->name . '_recaptcha_private_key', '') != ''
	) {
		$privatekey = get_option($tpl->name . '_recaptcha_private_key', '');
		$resp = recaptcha_check_answer ($privatekey,
		                            $_SERVER["REMOTE_ADDR"],
		                            $_POST["recaptcha_challenge_field"],
		                            $_POST["recaptcha_response_field"]);
		
		if (!$resp->is_valid) {
			// What happens when the CAPTCHA was entered incorrectly
			$validated = false;
			$errors['recaptcha'] = __("The reCAPTCHA wasn't entered correctly. Go back and try it again.", GKTPLNAME);
		}
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

?>

<section id="gk-mainbody" class="contactpage">
	<?php the_post(); ?>
	
	<h2 class="page-title"><?php the_title(); ?></h2>
	
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
					<dt>
						<label for="contact-name"><?php _e('Name:', GKTPLNAME); ?></label>
						<?php if($errors['name'] != '') : ?>
						<span class="error"><?php echo $errors['name'];?></span>
						<?php endif; ?>
					</dt>
					<dd>	
						<input type="text" name="contact-name" id="contact-name" value="<?php echo $output['message'];?>" />
					</dd>
		
					<dt>
						<label for="email"><?php _e('Email:', GKTPLNAME); ?></label>
						<?php if($errors['email'] != '') : ?>
						<span class="error"><?php echo $errors['email'];?></span>
						<?php endif; ?>
					</dt>
					<dd>	
						<input type="text" name="email" id="email" value="<?php echo $output['email'];?>" />
					</dd>
		
					<dt>
						<label for="comment-text"><?php _e('Message:', GKTPLNAME); ?></label>
						<?php if($errors['message'] != '') : ?>
						<span class="error"><?php echo $errors['message'];?></span>
						<?php endif; ?>
					</dt>
					<dd>
						<textarea name="comment-text" id="comment-text" rows="6" cols="30"><?php echo $output['message']; ?></textarea>
					</dd>
				</dl>
				<p>
					<label>
						<input type="checkbox" name="send_copy" /> 
						<?php _e('Send copy of the message to yourself', GKTPLNAME); ?>
					</label>
				</p>
				
				<?php 
					if(
						get_option($tpl->name . '_recaptcha_state', 'N') == 'Y' && 
						get_option($tpl->name . '_recaptcha_public_key', '') != '' &&
						get_option($tpl->name . '_recaptcha_private_key', '') != ''
					) : ?>
				<p>
					<script type="text/javascript">var RecaptchaOptions = { theme : 'clean' };</script>
					<?php if($errors['recaptcha'] != '') : ?>
					<span class="error"><?php echo $errors['recaptcha'];?></span>
					<?php endif; ?>
					<?php 
						$publickey = get_option($tpl->name . '_recaptcha_public_key', ''); // you got this from the signup page
						echo recaptcha_get_html($publickey); 
					?>				
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