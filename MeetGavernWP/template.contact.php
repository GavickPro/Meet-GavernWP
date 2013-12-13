<?php

/*
Template Name: Contact Form
*/

global $tpl;

// check if reCAPTCHA isn't loaded earlier by other plugin
if(!function_exists('_recaptcha_qsencode')) {
	include_once('gavern/classes/class.recaptchalib.php');
}
// get the params
$params = get_post_custom();
$params_templates = isset($params['gavern-post-params-templates']) ? $params['gavern-post-params-templates'][0] : false;

if($params_templates) {
	$params_templates = unserialize(unserialize($params_templates));
	$params_contact = $params_templates['contact'];
	
	if($params_contact != '' && count($params_contact) > 0) {
		$params_contact = explode(',', $params_contact); // [0] - name, [1] - e-mail, [2] - send copy   
	}
}

$params_name = true;
$params_email = true;
$params_copy = true;

if(count($params_contact) == 3) {
	$params_name = $params_contact[0] == 'Y';
	$params_email = $params_contact[1] == 'Y';
	$params_copy = $params_contact[2] == 'Y';
}

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
	if($params_name) {
		if(trim($_POST['contact-name']) === '') {
			$validated = false;
			$errors['name'] = __('please enter your name', GKTPLNAME);
		} else {
			$output['name'] = trim($_POST['contact-name']);
		}
	}
	// check the e-mail
	if($params_email) {
		if(trim($_POST['email']) === '' || !eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", trim($_POST['email']))) {
			$validated = false;
			$errors['email'] = __('please enter correct email address.', GKTPLNAME);
		} else {
			$output['email'] = trim($_POST['email']);
		}
	}
	// check the message content
	if(trim($_POST['comment-text']) === '') {
		$validated = false;
		$errors['message'] = __('please enter a text of the message.', GKTPLNAME);
	} else {
		$output['message'] = stripslashes(trim(htmlspecialchars($_POST['comment-text'])));
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
		$email = get_option($tpl->name . '_contact_template_email', '');
		// if the user specified blank e-mail or not specified it
		if(trim($email) == '') {
			$email = get_option('admin_email');
		}
		// e-mail structure
		if($params_name) {
			$subject = __('From ', GKTPLNAME) . $output['name'];
		} else if(!$params_name && $params_email) {
			$subject = __('From ', GKTPLNAME) . $output['email'];
		} else {
			$subject = __('From ', GKTPLNAME) . get_bloginfo('name');
		}
		
		$body = "<html>";
		$body .= "<body>";
		$body .= "<h1 style=\"font-size: 24px; border-bottom: 4px solid #EEE; margin: 10px 0; padding: 10px 0; font-weight: normal; font-style: italic;\">".__('Message from', GKTPLNAME)." <strong>".get_bloginfo('name')."</strong></h1>";
		
		if($params_name) {
			$body .= "<div>";
			$body .= "<h2 style=\"font-size: 16px; font-weight: normal; border-bottom: 1px solid #EEE; padding: 5px 0; margin: 10px 0;\">".__('Name:', GKTPLNAME)."</h2>";
			$body .= "<p>".$output['name']."</p>";
			$body .= "</div>";
		}
		
		if($params_email) {
			$body .= "<div>";
			$body .= "<h2 style=\"font-size: 16px; font-weight: normal; border-bottom: 1px solid #EEE; padding: 5px 0; margin: 10px 0;\">".__('E-mail:', GKTPLNAME)."</h2>";
			$body .= "<p>".$output['email']."</p>";
			$body .= "</div>";
		}
		
		$body .= "<div>";
		$body .= "<h2 style=\"font-size: 16px; font-weight: normal; border-bottom: 1px solid #EEE; padding: 5px 0; margin: 10px 0;\">".__('Message:', GKTPLNAME)."</h2>";
		$body .= $output['message'];
		$body .= "</div>";
		$body .= "</body>";
		$body .= "</html>";
		
		if($params_name && $params_email) {
			$headers[] = 'From: '.$output['name'].' <'.$output['email'].'>';
			$headers[] = 'Reply-To: ' . $output['email'];
			$headers[] = 'Content-type: text/html';
		} else if($params_name && !$params_email) {
			$headers[] = 'From: '.$output['name'];
			$headers[] = 'Content-type: text/html';
		} else if(!$params_name && $params_email) {
			$headers[] = 'From: '.$output['email'].' <'.$output['email'].'>';
			$headers[] = 'Reply-To: ' . $output['email'];
			$headers[] = 'Content-type: text/html';
		} else {
			$headers[] = 'Content-type: text/html';
		}

		wp_mail($email, $subject, $body, $headers);
		
		if($params_copy && $params_email && isset($_POST['send_copy'])) {
			wp_mail($output['email'], $subject, $body, $headers);
		}
		
		$messageSent = true;
	}

} 

gk_load('header');
gk_load('before');

?>

<section id="gk-mainbody" class="contactpage">
	<?php the_post(); ?>
	
	<h1 class="page-title"><?php the_title(); ?></h1>
	
	<article>
		<section class="intro">
			<?php the_content(); ?>
		</section>
	
		<?php if($messageSent == true) : ?>
		<p class="gk-contact-thanks"><?php _e('Your message was sent to us successfully.', GKTPLNAME); ?></p>
		<p><a href="<?php echo home_url(); ?>"><?php _e('Back to the homepage', GKTPLNAME); ?></a></p>
		<?php else : ?>
		
			<?php if(!$validated) : ?>
			<p class="gk-contact-error"><?php _e('Sorry, an error occured.', GKTPLNAME); ?></p>
			<?php endif; ?>
		
			<form action="<?php the_permalink(); ?>" id="gk-contact" method="post">
				<dl>
					<?php if($params_name) : ?>
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
		
					<?php if($params_email) : ?>
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
				
				<?php if($params_copy && $params_email) : ?>
				<p>
					<label>
						<input type="checkbox" name="send_copy" /> 
						<?php _e('Send copy of the message to yourself', GKTPLNAME); ?>
					</label>
				</p>
				<?php endif; ?>
				
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