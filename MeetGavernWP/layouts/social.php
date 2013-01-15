<?php

global $tpl;
// check if the social api is enabled on the specific page
$social_api_mode = get_option($tpl->name . '_social_api_exclude_include', 'exclude');
$social_api_articles = explode(',', get_option($tpl->name . '_social_api_articles', ''));
$social_api_pages = explode(',', get_option($tpl->name . '_social_api_pages', ''));
$social_api_categories = explode(',', get_option($tpl->name . '_social_api_categories', ''));
//
$is_excluded = false;
//
if($social_api_mode == 'include' || $social_api_mode == 'exclude') {
	//
	$is_excluded = 
		($social_api_pages != FALSE ? is_page($social_api_pages) : FALSE) || 
		($social_api_articles != FALSE ? is_single($social_api_articles) : FALSE) || 
		($social_api_categories != FALSE ? in_category($social_api_categories) : FALSE);
	//
	if($social_api_mode == 'exclude') {
		$is_excluded = !$is_excluded;
	}
}
//
if($social_api_mode != 'none' && $is_excluded && is_singular()) :

?>

	<?php if(get_option($tpl->name . '_fb_like', 'Y') == 'Y') : ?>
	<div id="fb-root"></div>
	<script type="text/javascript">
	//<![CDATA[     
	      window.fbAsyncInit = function() {
	        FB.init({
	          appId      : '<?php echo get_option($tpl->name . '_fb_api_id', ''); ?>', // App ID
	          status     : true, // check login status
	          cookie     : true, // enable cookies to allow the server to access the session
	          xfbml      : true  // parse XFBML
	        });
	      };
	    
	      // Load the SDK Asynchronously
	      (function(d){
	         var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
	         if (d.getElementById(id)) {return;}
	         js = d.createElement('script'); js.id = id; js.async = true;
	         js.src = "//connect.facebook.net/<?php echo get_option($tpl->name . '_fb_lang', 'en_US'); ?>/all.js";
	         ref.parentNode.insertBefore(js, ref);
	       }(document));
	    //]]>
	</script>
	<?php endif; ?>
	
	<?php if(get_option($tpl->name . '_tweet_btn', 'Y') == 'Y') : ?>
	<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
	<?php endif; ?>
	
	<?php if(get_option($tpl->name . '_google_plus', 'Y') == 'Y') : ?>
	<script type="text/javascript" src="https://apis.google.com/js/plusone.js">
	  {lang: '<?php echo get_option($tpl->name . "_google_plus_lang", "en-GB"); ?>'}
	</script>
	<?php endif; ?>
	
	<?php if(get_option($tpl->name . '_pinterest_btn', 'Y') == 'Y') : ?>
	<script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script>
	<?php endif; ?>

<?php endif; ?>