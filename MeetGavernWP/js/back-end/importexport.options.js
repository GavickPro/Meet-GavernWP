/**
 *
 * -------------------------------------------
 * Script for the import/export options
 * -------------------------------------------
 *
 **/

jQuery(document).ready(function() {	
	//
	// saving the options
	//
	jQuery('#importexport_save').click(function(event) {
		event.preventDefault();
		//
		// Import-export part
		//		
		if(jQuery('#importexport_import').val() != '') {
			// save the settings
			var data = {
				action: 'importexport_save',
				security: $gk_ajax_nonce,
				importexport_import: jQuery('#importexport_import').val() 
			};
					
			// make an effect ;)
			jQuery('.gkWrap').find('.gkAjaxLoading').css('opacity', 1);
			jQuery(event.target).html(jQuery(event.target).attr('data-loading'));
			// make a request
			jQuery.post(ajaxurl, data, function(response) {
				jQuery(event.target).html(response);
				jQuery('.gkWrap').find('.gkAjaxLoading').css('opacity', 0);
				jQuery('#importexport_import').val('');
				setTimeout(function() { 
					jQuery(event.target).html(jQuery(event.target).attr('data-loaded')); 
				}, 2500);
			});
		} else {
			jQuery(event.target).html(jQuery(event.target).attr('data-wrong'));
			setTimeout(function() { 
				jQuery(event.target).html(jQuery(event.target).attr('data-loaded')); 
			}, 2500);
		}
	});
});