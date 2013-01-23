jQuery(window).load(function(){
	jQuery(document).find('.gk-tabs').each(function(i, el) {
		el = jQuery(el);
		var animation_speed = el.attr('data-speed');
		var animation_interval = el.attr('data-interval');
		var autoanim = el.attr('data-autoanim');
		var eventActivator =  el.attr('data-event');
		var active_tab = 0;
		
		var tabs = el.find('.gk-tabs-item');
		var items = el.find('.gk-tabs-nav li');
		var tabs_wrapper = jQuery(el.find('.gk-tabs-container')[0]);
		var current_tab = active_tab;
		var previous_tab = null;
		var amount = tabs.length;
		var timer = false;
		var blank = false;
		var falsy_click = false;
		var tabs_h = [];
		//
		jQuery(tabs).css('opacity', 0);
		jQuery(tabs[active_tab]).css({
			'opacity': '1',
			'position': 'relative',
			'z-index': 2
		});
		
		jQuery(tabs).each(function(i, item) {
			tabs_h[i] = jQuery(item).outerHeight();
		});
		
		// add events to tabs
		items.each(function(i, item){
			item = jQuery(item);
			item.bind(eventActivator, function(){
				if(i != current_tab) {
					previous_tab = current_tab;
					current_tab = i;
					
					if(typeof gk_tab_event_trigger != 'undefined') {
						gk_tab_event_trigger(current_tab, previous_tab, el.parent().parent().attr('id'));
					}
					
					tabs_wrapper.css('height', tabs_wrapper.outerHeight() + 'px');
					
					var previous_tab_animation = { 'opacity': 0 };
					var current_tab_animation = { 'opacity': 1 };
					//
					jQuery(tabs[previous_tab]).animate(previous_tab_animation, animation_speed / 2, function() {
						jQuery(tabs[previous_tab]).css({
							'position': 'absolute',
							'top': '0',
							'z-index': '1'
						});	
						
						jQuery(tabs[current_tab]).css({
							'position': 'relative',
							'z-index': '2'
						});
						
						jQuery(tabs[previous_tab]).removeClass('active');
						jQuery(tabs[current_tab]).addClass('active');
						
						tabs_wrapper.animate({ 
							"height": tabs_h[i]
						}, 
						animation_speed / 2, 
						function() { 
							tabs_wrapper.css('height', 'auto'); 
						});
						//
						setTimeout(function(){
							// anim
							jQuery(tabs[current_tab]).animate(current_tab_animation, animation_speed);
						}, animation_speed / 2);
					});
					// common operations for both types of animation
					if(!falsy_click) blank = true;
					else falsy_click = false;
					jQuery(items[previous_tab]).removeClass('active');
					jQuery(items[current_tab]).addClass('active');
				}
			});
		});
		//
		if(autoanim == 'enabled') {
			setInterval(function(){
				if(!blank) {
					falsy_click = true;
					if(current_tab < amount - 1) jQuery(items[current_tab + 1]).trigger(eventActivator);	
					else jQuery(items[0]).trigger(eventActivator);
				} else {
					blank = false;
				}
			}, animation_interval);
		}
	});
});