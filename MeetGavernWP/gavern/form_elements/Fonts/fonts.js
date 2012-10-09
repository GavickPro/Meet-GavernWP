/*
 JavaScript code used in the Fonts element
 */
jQuery(document).ready(function() {
	// showing proper selectors
	jQuery('select[data-type=type]').each(function(i, el) {
		var value = jQuery(el).find(':selected').val();
		var family = jQuery(el).attr('data-family');
		
		gkFontsSelector(family, value);

		jQuery(el).change(function() {
			var value = jQuery(el).find(':selected').val();
			var family = jQuery(el).attr('data-family');
			gkFontsSelector(family, value);
		});
		
		jQuery(el).blur(function() {
			var value = jQuery(el).find(':selected').val();
			var family = jQuery(el).attr('data-family');
			gkFontsSelector(family, value);
		});
	});
});

function gkFontsSelector(family, value) {
	jQuery('select[data-family='+family+']').filter('select[data-type=normal]').parent().css('display', (value == 'normal') ? 'block' : 'none');
	jQuery('select[data-family='+family+']').filter('select[data-type=squirrel]').parent().css('display', (value == 'squirrel') ? 'block' : 'none');
	jQuery('input[data-family='+family+']').filter('input[data-type=google]').parent().css('display', (value == 'google') ? 'block' : 'none');
	jQuery('input[data-family='+family+']').filter('input[data-type=edgefonts]').parent().css('display', (value == 'edgefonts') ? 'block' : 'none');
}