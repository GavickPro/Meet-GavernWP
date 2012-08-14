/**
 *
 * -------------------------------------------
 * Script for the template options
 * -------------------------------------------
 *
 **/

// ID of the upload field 
var uploadID = '';
// common functions and objects
function isNumber(n) {
	return !isNaN(parseFloat(n)) && isFinite(n);
}
//
gkValidation = [];
gkValidationResults = [];
gkValidationResultsTabs = [];
gkVisibility = {};
gkVisibilityDependicies = {};
//
jQuery(document).ready(function() {
	// tabs
	jQuery('#gkTabs li').each(function(i,el){
		var item = jQuery(el);
		item.click(function() {
			jQuery('#gkTabs li').removeClass('active');
			jQuery('#gkTabsContent > div').removeClass('active');
			
			item.addClass('active');
			jQuery(jQuery('#gkTabsContent > div')[i]).addClass('active');
		});
	});
	// initialize Media uploaders
	gkMediaInit();
	// initialize validation
	gkValidateInit();
	// initialize visualisation
	gkVisibilityInit();
	// add mini tips
	var fields = jQuery('#gkTabsContent').find('.gkInput');
	
	fields.each(function(i, field) {
		var field = jQuery(field);
		field.prev('label').miniTip();
	});	
	// saving the settings
	jQuery('.gkSave').each(function(i, button) {
		jQuery(button).click(function(event) {
			event.preventDefault();
			
			if(gkValidate()) {
				// save the settings
				var data = {
					action: 'template_save',
					security: $gk_ajax_nonce
				};
				
				var fields = jQuery('#gkTabsContent').find('.gkInput');
				
				fields.each(function(i, field) {
					var field = jQuery(field);
					if(field.hasClass('gkSwitcher') || field.hasClass('gkSelect')) {
						data[field.attr('id')] = field.find('option:selected').val();
					} else {
						data[field.attr('id')] = field.val();
					}
				});			
				// make an effect ;)
				jQuery('#gkTabsContent').find('.active').find('.gkAjaxLoading').css('opacity', 1);
				jQuery(event.target).html(jQuery(event.target).attr('data-loading'));
				// make a request
				jQuery.post(ajaxurl, data, function(response) {
					jQuery(event.target).html(response);
					jQuery('#gkTabsContent').find('.active').find('.gkAjaxLoading').css('opacity', 0);
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
});
// function to init the validation rules
function gkValidateInit() {
	jQuery('#gkTabsContent > div').each(function(i, tab) {
		gkValidation[i] = [];
		gkValidationResults[i] = [];
		gkValidationResultsTabs[i] = true;
		
		var fields = jQuery(tab).find('.gkInput');
		
		fields.each(function(j, field) {
			var data = {
				'type': 'text',
				'format': '',
				'required': ''
			};
			var field = jQuery(field);
			
			if(field.hasClass('gkSwitcher') || field.hasClass('gkSelect')) {
				data.type = 'select';	
				field.blur(function() {
					gkValidateField(field, 'select');
					gkVisibilityField(field, 'select');
				});
				field.change(function() {
					gkValidateField(field, 'select');
					gkVisibilityField(field, 'select');
				});
			} else {
				field.blur(function() {
					gkValidateField(field, 'text');
					gkVisibilityField(field, 'text');
				});
			}
			data.format = (field.attr('data-format') != '') ? new RegExp(field.attr('data-format')) : '';
			data.required = field.attr('data-required');
			gkValidation[i][j] = data;
			gkValidationResults[i][j] = [];
		});	
	});
}
// function to validate
function gkValidate() {
	// validate
	jQuery(gkValidation).each(function(i, fields) {
		var allFields = jQuery(jQuery('#gkTabsContent > div')[i]).find('.gkInput');
		gkValidationResultsTabs[i] = true;
		
		jQuery(fields).each(function(j, field) {
			var value = field.type == 'select' ? jQuery(allFields[j]).find('option:selected').val() : jQuery(allFields[j]).val();
			var data = gkValidation[i][j];
			gkValidationResults[i][j] = [];
			
			if(data.required == 'true' && jQuery(allFields[j]).get('data-visible') == 'true' && !value) {
				gkValidationResults[i][j].push('required');
				gkValidationResultsTabs[i] = false;
			}
			
			if(data.format != '' && jQuery(allFields[j]).attr('data-visible') == 'true' && !value.match(data.format)) {
				gkValidationResults[i][j].push('format');
				gkValidationResultsTabs[i] = false;
			}
		});
	});
	// change elements basic on the results
	var result = true;
	
	jQuery(gkValidationResultsTabs).each(function(i, tabCorrect) {
		if(tabCorrect) {
			jQuery(jQuery('#gkTabs li')[i]).removeClass('wrong');			
		} else {
			jQuery(jQuery('#gkTabs li')[i]).addClass('wrong');
			result = false;
		}
	});
	// validate all fields
	var fields = jQuery('#gkTabsContent').find('.gkInput');
	
	fields.each(function(i, field) {
		var field = jQuery(field);	
		gkValidateField(field, (field.hasClass('gkSwitcher') || field.hasClass('gkSelect')) ? 'select' : 'text');
	});
	
	// return the result
	return result;
}
// function to validate one field
function gkValidateField(field, type) {
	var value = (type == 'select') ? field.find('option:selected').val() : field.val();
	var format = (field.attr('data-format') != '') ? new RegExp(field.attr('data-format')) : '';
	var required = field.attr('data-required');
	var visibility = field.attr('data-visible');
	
	field.removeClass('wrong-format');
	field.removeClass('wrong-required');
	
	if(required == 'true' && visibility == 'true' && !value) {
		field.addClass('wrong-required');
	}
	
	if(format != '' && visibility == 'true' && !value.match(format)) {
		field.addClass('wrong-format');
	}
	// check the tabs
	jQuery('#gkTabsContent > div').each(function(i, tab) {
		var wrongFormat = jQuery(tab).find('.wrong-format');
		var wrongRequired = jQuery(tab).find('.wrong-required');
		
		if(wrongFormat.length == 0 && wrongRequired.length == 0) {
			if(jQuery(jQuery('#gkTabs li')[i]).hasClass('wrong')) {
				jQuery(jQuery('#gkTabs li')[i]).removeClass('wrong');
			}
		} else {
			if(!jQuery(jQuery('#gkTabs li')[i]).hasClass('wrong')) {
				jQuery(jQuery('#gkTabs li')[i]).addClass('wrong');
			}
		}
	});
}
//
function gkVisibilityInit() {
	var allFields = jQuery('#gkTabsContent').find('.gkInput');
	//
	allFields.each(function(i, field) {
		var visibility = jQuery(field).attr('data-visibility');
		
		if(visibility != '') {
			var tempVisibilityRules = visibility.split(',');
			
			for(var j = 0; j < tempVisibilityRules.length; j++) {
				tempVisibilityRules[j] = tempVisibilityRules[j].split('=');
				
				tempVisibilityRules[j] = {
										"field": tempVisibilityRules[j][0],
										"value": tempVisibilityRules[j][1]
									};
									
				var visible = jQuery(field).attr('id');
									
				if(typeof gkVisibilityDependicies[visible] !== "object") {
					gkVisibilityDependicies[visible] = [tempVisibilityRules[j]];
				} else {
					gkVisibilityDependicies[visible].push(tempVisibilityRules[j]);
				}
			}
			
			var visibilityRules = jQuery(field).attr('data-visibility').split(',');
			
			for(var j = 0; j < visibilityRules.length; j++) {
				visibilityRules[j] = visibilityRules[j].split('=');
				var usedField = visibilityRules[j][0];	
				var tempField = jQuery('*[data-name='+usedField+']');
				var type = (tempField.hasClass('gkSwitcher') || tempField.hasClass('gkSelect')) ? 'select' : 'text';
				
				visibilityRules[j] = {
										"type": type,
										"visible": jQuery(field).attr('id')
									};
									
				if(typeof gkVisibility[usedField] !== "object") {
					gkVisibility[usedField] = [visibilityRules[j]];
				} else {
					gkVisibility[usedField].push(visibilityRules[j]);
				}
			}
		}
	});
	//
	allFields.each(function(i, field) {
		gkVisibilityField(jQuery(field));
	});
}
//
function gkVisibilityField(field) {
	//
	if(gkVisibility[field.attr('data-name')]) {
		//
		var dependencies = gkVisibility[field.attr('data-name')];
		//
		for(var i = 0; i < dependencies.length; i++) {
			var dependsFrom = gkVisibilityDependicies[dependencies[i].visible];
			var flag = 'true';
			
			for(var j = 0; j < dependsFrom.length; j++) {
				var type = gkVisibility[dependsFrom[j].field].type;
				var field = jQuery('*[data-name='+dependsFrom[j].field+']');
				var value = (type == 'select') ? field.find('option:selected').val() : field.val();
				
				if(value != dependsFrom[j].value) {
					flag = 'false';
				}
			}
			
			jQuery('#' + dependencies[i].visible).parent('p').attr('data-visible', flag);
		}
	}
}
//
function gkMediaInit() {
	// image uploaders
	jQuery('.gkMediaInput').each(
		function(i, el) {
			var btnid = jQuery(el).attr('id') + '_button'; 
		
			jQuery('#'+btnid).click(function() {
				uploadID = jQuery(this).prev('input');
				formfield = jQuery(this).prev('input').attr('name');
				tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
				
				return false;
			});
		}
	);
}
//
window.send_to_editor = function(html) {
	imgurl = jQuery('img', html).attr('src');
	uploadID.val(imgurl);
	tb_remove();
}