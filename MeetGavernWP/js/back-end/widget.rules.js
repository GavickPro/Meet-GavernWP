/**
 *
 * -------------------------------------------
 * Script for the Widget Rules
 * -------------------------------------------
 *
 **/
 
 
function gk_widget_control_init(id, inner) {
	// check if the widget isn't a new widget
	var allForms = jQuery('.gk_widget_rules_form');
	var newest = null;
	var flag = 0;
	
	if(!inner) {
		for(var i = 0; i < allForms.length; i++) {
			if('#' + jQuery(allForms[i]).attr('id') == id) {
				newest = jQuery(allForms[i]);
				flag += 1;
			}
		}
	
		if(flag > 1) {
			newest.attr('id', newest.attr('id') + '-' + Math.floor((Math.random() * 10000 + 1)));
			newest.attr('data-state', 'uninitialized');
			gk_widget_control_init('#' + newest.attr('id'), true);
			return;
		}
	}
	// if it is a new widget
	if(inner) {
		var mouseUpEvent = function() {
			setTimeout(function() {
				gk_widget_control_init_events(id, inner);
				jQuery(document).unbind('mouseup', mouseUpEvent);
			}, 250);
		};
		
		jQuery(document).bind('mouseup', mouseUpEvent);
	} else {
		gk_widget_control_init_events(id, inner);
	}
}
// function to init form event
function gk_widget_control_init_events(id, inner) {
	var form = jQuery(id);
	
	if(inner) {
		form.parent().find('select:last-child').css('opacity', '0.5');
		
		setTimeout(function() {
			var btn = form.parent().parent().find('*[name="savewidget"]');
			btn.click();
		}, 1000);
	}
	
	if(form.attr('data-state') !== 'initialized') {		
		form.attr('data-state', 'initialized');
		var firstSelect = form.parent().find('.gk_widget_rules_select');
		var select = form.children('.gk_widget_rules_form_select');
		var page = form.find('.gk_widget_rules_form_input_page').parent();
		var post = form.find('.gk_widget_rules_form_input_post').parent();
		var category = form.find('.gk_widget_rules_form_input_category').parent();
		var tag = form.find('.gk_widget_rules_form_input_tag').parent();
		var author = form.find('.gk_widget_rules_form_input_author').parent();
		var btn = form.find('.gk_widget_rules_btn');
		// hide unnecesary form
		if(firstSelect.children('option:selected').val() == 'all') {
			form.css('display', 'none');
		}
		// change event
		firstSelect.change(function() {
			var value = firstSelect.children('option:selected').val();
			
			if(value == 'all') {
				form.css('display', 'none');
			} else {
				form.css('display', 'block');
			}
		});
		// refresh the list
		gk_widget_control_refresh(form);
		// add onChange event to the selectbox
		select.change(function() {
			var value = select.children('option:selected').val()
			
			if(value == 'homepage' || value == 'page404' || value == 'search' || value == 'archive') {
				page.css('display', 'none');
				post.css('display', 'none');
				category.css('display', 'none');
				tag.css('display', 'none');
				author.css('display', 'none');
			} else if(value == 'page:') {
				page.css('display', 'block');
				post.css('display', 'none');
				category.css('display', 'none');
				tag.css('display', 'none');
				author.css('display', 'none');
			} else if(value == 'post:') {
				page.css('display', 'none');
				post.css('display', 'block');
				category.css('display', 'none');
				tag.css('display', 'none');
				author.css('display', 'none');
			} else if(value == 'category:') {
				page.css('display', 'none');
				post.css('display', 'none');
				category.css('display', 'block');
				tag.css('display', 'none');
				author.css('display', 'none');
			} else if(value == 'tag:') {
				page.css('display', 'none');
				post.css('display', 'none');
				category.css('display', 'none');
				tag.css('display', 'block');
				author.css('display', 'none');
			} else if(value == 'author:') {
				page.css('display', 'none');
				post.css('display', 'none');
				category.css('display', 'none');
				tag.css('display', 'none');
				author.css('display', 'block');
			}
		});
		// add the onClick event to the button
		btn.click(function(event) {
			event.preventDefault();
			
			var output = form.find('.gk_widget_rules_output');
			var value = select.children('option:selected').val()
			
			if(value == 'homepage') {
				output.val(output.val() + ',homepage');
			} else if(value == 'search') {
				output.val(output.val() + ',search');
			} else if(value == 'archive') {
				output.val(output.val() + ',archive');
			} else if(value == 'page404') {
				output.val(output.val() + ',page404');
			} else if(value == 'page:') {
				output.val(output.val() + ',page:' + form.find('.gk_widget_rules_form_input_page').val());
			} else if(value == 'post:') {
				output.val(output.val() + ',post:' + form.find('.gk_widget_rules_form_input_post').val());
			} else if(value == 'category:') {
				output.val(output.val() + ',category:' + form.find('.gk_widget_rules_form_input_category').val());
			} else if(value == 'tag:') {
				output.val(output.val() + ',tag:' + form.find('.gk_widget_rules_form_input_tag').val());
			} else if(value == 'author:') {
				output.val(output.val() + ',author:' + form.find('.gk_widget_rules_form_input_author').val());
			}
			
			gk_widget_control_refresh(form);
		});
		// event to remove the page tags
		form.find('.gk_widget_rules_pages div').click(function(event) {
			if(event.target.nodeName.toLowerCase() == 'strong') {
				var output = form.find('.gk_widget_rules_output');
				var parent = jQuery(event.target).parent();
				parent.find('strong').remove();
				var text = parent.text();
				output.val(output.val().replace("," + text, ""));
				gk_widget_control_refresh(form);	
			}
		});
	}
}

// function to refresh the list of pages
function gk_widget_control_refresh(form) {
	var output = form.find('.gk_widget_rules_output');
	if(output.length > 0) {
		var list = form.find('.gk_widget_rules_pages div');
		list.html('');
		var pages = output.val().split(',');
		var pages_exist = false;
		
		for(var i = 0; i < pages.length; i++) {
			if(pages[i] != '') {
				pages_exist = true;
				var type = 'homepage';
				
				if(pages[i].substr(0,5) == 'page:') type = 'page';
				else if(pages[i].substr(0,5) == 'post:') type = 'post';
				else if(pages[i].substr(0,9) == 'category:') type = 'category';
				else if(pages[i].substr(0,4) == 'tag:') type = 'tag';
				else if(pages[i].substr(0,7) == 'archive') type = 'archive';
				else if(pages[i].substr(0,7) == 'author:') type = 'author';
				else if(pages[i].substr(0,7) == 'page404') type = 'page404';
				else if(pages[i].substr(0,6) == 'search') type = 'search';
				
				list.html(list.html() + "<span class="+type+">"+pages[i]+"<strong>&times;</strong></span>");
			}
		}
		
		form.find('.gk_widget_rules_nopages').css('display', pages_exist ? 'none' : 'block');
	}
}