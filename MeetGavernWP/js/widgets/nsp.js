//
// GK NSP Widget
//

jQuery(window).load(function(){
	jQuery(document).find('.gk-nsp').each(function(i, widget) {	
		if(!jQuery(widget).hasClass('activated')) {	
			new GK_NSP(widget);
		}
	});
});

var GK_NSP = function(widget) {
	// init class fields
	this.init_fields(widget);
	// init the interface
	this.init_interface();
}

GK_NSP.prototype = {
	// class fields
	animation: true,
	arts: null,
	arts_block_width: 0,
	arts_current: 0,
	arts_pages: null,
	arts_per_page: null,
	config: null,
	hover: false,
	links: null,
	links_block_width: 0,
	links_pages: null,
	links_pages_amount: null,
	links_current: 0,
	modInterface: null,
	module: null,
	//
	init_fields: function(module) {
		// the most important class field ;)
		this.module = jQuery(module);
		this.module.addClass('activated');
		// rest of the fields
		this.config = [];
		
		this.config["animation_speed"] = 400;
		this.config['autoanim'] = this.module.attr('data-autoanim') == 'on' ? true : false;
		this.config["autoanim_interval"] = this.module.attr('data-autoanimint') != '' ? this.module.attr('data-autoanimint') : 5000;
		this.config['autoanim_hover'] = this.module.attr('data-autoanimhover') == 'on' ? true : false;
		this.config['news_column'] = this.module.attr('data-cols');
		this.config['news_rows'] = this.module.attr('data-rows');
		this.config['links_amount'] = this.module.attr('data-links');
		
		this.arts = this.module.find('.gk-nsp-art');
		this.arts_pages = this.module.find('.gk-nsp-arts-page');
		this.arts_per_page = this.config['news_column'] * this.config['news_rows'];
		this.links = (this.module.find('.gk-nsp-links-scroll')) ? this.module.find('.gk-nsp-links-scroll li') : [];
		this.links_pages = this.module.find('.gk-nsp-list');
		this.links_pages_amount = Math.ceil(Math.ceil(this.links.length / this.config['links_amount']));
		this.modInterface = { 
			top: this.module.find('.gk-nsp-arts-nav'), 
			bottom: this.module.find('.gk-nsp-links-nav')
		};
		this.pages_amount = Math.ceil(this.arts.length / this.arts_per_page);
	},
	init_interface: function() {
		var $this = this;
		// arts
		if(this.arts.length > 0){
			this.arts_block_width = 100;
		}
		// events
		this.module.mouseenter(function() {
			if(!$this.module.hasClass('onhover')) $this.module.addClass('onhover');
		});
		//
		this.module.mouseleave(function() {
			if($this.module.hasClass('onhover')) $this.module.removeClass('onhover');
		});
		// links
		if(this.links.length > 0){
			this.links_block_width = 100;
		}
		// top interface
		this.nsp_art_list(0, 'top');
		this.nsp_art_list(0, 'bottom');
		//
		if(this.modInterface.top && this.modInterface.top.find('.gk-nsp-pagination')){
			this.modInterface.top.find('.gk-nsp-pagination li').each(function(i, item){
				jQuery(item).click(function(){
					$this.arts_anim(i);
				});	
			});
		}
		//
		if(this.modInterface.top && this.modInterface.top.find('.gk-nsp-prev')){
			this.modInterface.top.find('.gk-nsp-prev').click(function(){
				$this.arts_anim('prev');
			});

			this.modInterface.top.find('.gk-nsp-next').click(function(){
				$this.arts_anim('next');
			});
		}
		// bottom interface
		if(this.modInterface.bottom && this.modInterface.bottom.find('.gk-nsp-pagination')){
			this.modInterface.bottom.find('.gk-nsp-pagination li').each(function(i, item){
				jQuery(item).click(function(){
					$this.lists_anim(i);
				});	
			});
		}
		//
		if(this.modInterface.bottom && this.modInterface.bottom.find('.gk-nsp-prev')){
			this.modInterface.bottom.find('.gk-nsp-prev').click(function(){
				$this.lists_anim('prev');
			});

			this.modInterface.bottom.find('.gk-nsp-next').click(function(){
				$this.lists_anim('next');
			});
		}
		
		if(this.config['autoanim']) {
			setTimeout(function() {
				$this.autoanim();
			}, this.config['autoanim_interval']);
			
			if(this.config['autoanim_hover']) {
				this.module.mouseenter(function() {
					$this.hover = true;
				});
				
				this.module.mouseleave(function() {
					$this.hover = false;
				});
			}
		}
	},
	//
	nsp_art_list: function(i, pos){
		var num  = (i !== null) ? i : (pos == 'top') ? this.arts_current : this.links_current;

		if(this.modInterface[pos] && this.modInterface[pos].find('.gk-nsp-pagination')){
			var pagination = this.modInterface[pos].find('.gk-nsp-pagination');
			pagination.find('li').attr('class', '');
			jQuery(pagination.find('li')[num]).attr('class', 'active');
		}
	},
	//
	arts_anim: function(dir) {
		var $this = this;
		jQuery(this.arts_pages[this.arts_current]).removeClass('active');

		if(dir == 'next') {
			this.arts_current = (this.arts_current == this.pages_amount - 1) ? 0 : this.arts_current + 1;
		} else if(dir == 'prev') {
			this.arts_current = (this.arts_current == 0) ? this.pages_amount - 1 : this.arts_current - 1;
		} else {
			this.arts_current = dir;
		}
		//		
		jQuery($this.module.find('.gk-nsp-arts-scroll')).animate({
			'margin-left': (-1 * this.arts_current * this.arts_block_width) + "%"
		}, $this.config['animation_speed']);

		setTimeout(function() {
			jQuery($this.arts_pages[$this.arts_current]).addClass('active');
		}, this.config['animation_speed'] * 0.5);

		this.nsp_art_list(this.arts_current, 'top');
		this.animation = false;
		setTimeout(function() {
			$this.animation = true;
		}, this.config['animation_interval'] * 0.8);
	},
	//
	lists_anim: function(dir) {
		var $this = this;

		for(var x = 0; x < 1; x++) {
			var item = this.links_pages[this.links_current * 1 + x];
			if(item) jQuery(item).removeClass('active');
		}

		if(dir == 'next') {
			this.links_current = (this.links_current == this.links_pages_amount - 1) ? 0 : this.links_current + 1;
		} else if(dir == 'prev') {
			this.links_current = (this.links_current == 0) ? this.links_pages_amount - 1 : this.links_current - 1;
		} else {
			$this.links_current = dir;
		}

		setTimeout(function() {
			for(var x = 0; x < 1; x++) {
				var item = $this.links_pages[$this.links_current * 1 + x]; 
				if(item) jQuery(item).addClass('active');
			}
		}, this.config['animation_speed'] * 0.5);
		//
		jQuery($this.module.find('.gk-nsp-links-scroll')).animate({
			'margin-left': (-1 * this.links_current * this.links_block_width) + "%"
		}, $this.config['animation_speed']);

		this.nsp_art_list(null, 'bottom');
	},
	//
	autoanim: function() {
		var $this = this;
		
		if(!this.hover) {
			this.arts_anim('next');
		}
		
		setTimeout(function() {
			$this.autoanim();
		}, this.config['autoanim_interval']);
	}
};