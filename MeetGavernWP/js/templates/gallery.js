/**
 *
 * -------------------------------------------
 * Script for the template.gallery.php page style
 * -------------------------------------------
 *
 **/
(function() {
	"use strict";
	var gkGalleryState = 0; // number of the current slide
	var gkGalleryAnimationState = 'play'; // play|stop
	var gkGalleryTimer = false;
	
	// main onLoad event used to initialize the gallery
	jQuery(window).load(function() {
		if(jQuery('#gallery')) {
			gkGalleryTimer = setTimeout(function() {
				gkGalleryAutoanimation('next', null);
			}, 5000);
			// pagination
			jQuery('#gallery').children('ol').find('li').each(function(i, btn) {
				jQuery(btn).click(function() {
					if(i !== gkGalleryState) {
						gkGalleryAnimationState = 'stop'; 
						gkGalleryAutoanimation('next', i);
					}		
				});
			});
		}
	});
	// gallery animation function
	var gkGalleryAnimate = function(imgPrev, imgNext) {
		imgPrev.animate({
			opacity: 0
		}, 500, function() {
			imgPrev.attr('class', ' ');
		});
		
		imgNext.animate({
			opacity: 1
		}, 500, function(){
			imgNext.attr('class', 'active');
			
			gkGalleryTimer = setTimeout(function() {
				gkGalleryAutoanimation('next', null);
			}, 5000);
		});
	}; 
	// gallery autoanimation function
	var gkGalleryAutoanimation = function(dir, nextSlide) {
		var i = gkGalleryState;
		var imgs = jQuery('#gallery figure');
		var next = nextSlide;
		
		if(nextSlide === null) {
			next = (dir === 'next') ? ((i < imgs.length - 1) ? i+1 : 0) : ((i === 0) ? imgs.length - 1 : i - 1); // dir: next|prev
		}
		
		if(gkGalleryAnimationState === 'stop') {
			clearTimeout(gkGalleryTimer);
			gkGalleryAnimationState = 'play';
		}
		
		gkGalleryAnimate(jQuery(imgs[i]), jQuery(imgs[next]));
		gkGalleryState = next;
		jQuery('#gallery').children('ol').find('li').attr('class', '');
		jQuery(jQuery('#gallery').children('ol').find('li')[next]).attr('class', 'active');
	};
})();