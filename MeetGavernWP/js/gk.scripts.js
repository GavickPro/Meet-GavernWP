/**
 * jQuery Cookie plugin
 *
 * Copyright (c) 2010 Klaus Hartl (stilbuero.de)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 */
jQuery.cookie = function (key, value, options) {

    // key and at least value given, set cookie...
    if (arguments.length > 1 && String(value) !== "[object Object]") {
        options = jQuery.extend({}, options);

        if (value === null || value === undefined) {
            options.expires = -1;
        }

        if (typeof options.expires === 'number') {
            var days = options.expires, t = options.expires = new Date();
            t.setDate(t.getDate() + days);
        }

        value = String(value);

        return (document.cookie = [
            encodeURIComponent(key), '=',
            options.raw ? value : encodeURIComponent(value),
            options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
            options.path ? '; path=' + options.path : '',
            options.domain ? '; domain=' + options.domain : '',
            options.secure ? '; secure' : ''
        ].join(''));
    }

    // key and possibly options given, get cookie...
    options = value || {};
    var result, decode = options.raw ? function (s) { return s; } : decodeURIComponent;
    return (result = new RegExp('(?:^|; )' + encodeURIComponent(key) + '=([^;]*)').exec(document.cookie)) ? decode(result[1]) : null;
};

/**
 *
 * Template scripts
 *
 **/

// onDOMLoadedContent event
jQuery(document).ready(function() {	
	// Back to Top Scroll
    jQuery('#gk-top-link').click(function () {
        jQuery('body,html').animate({
            scrollTop: 0
        }, 800);
        return false;
    });
	// Thickbox use
	jQuery(document).ready(function(){
		if(typeof tb_init != "undefined") {
			tb_init('div.wp-caption a');//pass where to apply thickbox
		}
	});
	// style area
	if(jQuery('#gk-style-area')){
		jQuery('#gk-style-area div').each(function(i){
			jQuery(this).find('a').each(function(index) {
				jQuery(this).click(function(e){
	            	e.stopPropagation();
	            	e.preventDefault();
					changeStyle(jQuery(this).attr('href').replace('#', ''));
				});
			});
		});
	}
	// font-size switcher
	if(jQuery('#gk-font-size') && jQuery('#gk-mainbody')) {
		var current_fs = 100;
		jQuery('#gk-mainbody').css('font-size', current_fs+"%");
		
		jQuery('#gk-increment').click(function(e){ 
			e.stopPropagation();
			e.preventDefault(); 
			
			if(current_fs < 150) { 
				jQuery('#gk-mainbody').animate({ 'font-size': (current_fs + 10) + "%"}, 200); 
				current_fs += 10; 
			} 
		});
		
		jQuery('#gk-reset').click(function(e){ 
			e.stopPropagation(); 
			e.preventDefault(); 
			
			jQuery('#gk-mainbody').animate({ 'font-size': "100%"}, 200); 
			current_fs = 100; 
		});
		
		jQuery('#gk-decrement').click(function(e){ 
			e.stopPropagation(); 
			e.preventDefault(); 
			
			if(current_fs > 70) { 
				jQuery('#gk-mainbody').animate({ 'font-size': (current_fs - 10) + "%"}, 200); 
				current_fs -= 10; 
			} 
		});
	}
	
	// Function to change styles
	function changeStyle(style){
		var file = $GK_TMPL_URL+'/css/'+style;
		jQuery('head').append('<link rel="stylesheet" href="'+file+'" type="text/css" />');
		jQuery.cookie($GK_TMPL_NAME+'_style', style, { expires: 365, path: '/' });
	}
});