/**
 *
 * -------------------------------------------
 * Script for the shortcode button
 * -------------------------------------------
 *
 **/

( 
    function(){        
        var icon_url = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAEhJREFUeNpi/P//PwM1ARMDlQELjNG26z66UxmhNFFeqHJTZKSJCwe/gSxYwowBR1gOvAspimWY+tFYHo3lYRnLjIO+xAYIMABySA8v3JaU8wAAAABJRU5ErkJggg==';
    
        tinymce.create(
            "tinymce.plugins.GavernWPShortcodes",
            {
                init: function(editor, pluginUrl) {
                	// initial code if necessary
                },   
                createControl:function(controlName, controlManager){
                    if(controlName == "gavern_shortcode_button"){
                        controlName = controlManager.createMenuButton( "gavern_shortcode_button", {
                            title:"Insert Gavern Shortcode",
                            image:icon_url,
                            icons:false
                        });
                            
                        var $this = this;
                        
                        controlName.onRenderMenu.add(function(menu, button){
                            for(var i = 0; i < $GAVERNWP_SHORTCODES.length; i++) {
                            	//
                            	if($GAVERNWP_SHORTCODES[i]['submenu'] != null) {
                            		menu = button.addMenu({
                            			title: $GAVERNWP_SHORTCODES[i]['title'],
                            			onclick : function() {
                            				if($GAVERNWP_SHORTCODES[num]['code'] !== '') {
                            			    	tinyMCE.activeEditor.execCommand(
                            			    		"mceInsertContent", 
                            			    		false, 
                            			    		$GAVERNWP_SHORTCODES[num]['code'] + ' '
                            			    	);
                            				}
                            			}
                            		});
                            		
                            		for(var j = 0; j < $GAVERNWP_SHORTCODES[i]['submenu'].length; j++) {
                            			$this.addSubmenu(
                            				menu, 
                            				$GAVERNWP_SHORTCODES[i]['submenu'][j]['title'], 
                            				$GAVERNWP_SHORTCODES[i]['submenu'][j]['code'] + ' '
                            			);
                            		}
                            	} else {
                            		var num = i;
		                            button.add({
		                            	title : $GAVERNWP_SHORTCODES[i]['title'], 
		                            	onclick : function() {
		                                	if($GAVERNWP_SHORTCODES[num]['code'] !== '') {
			                                	tinyMCE.activeEditor.execCommand(
			                                		"mceInsertContent", 
			                                		false, 
			                                		$GAVERNWP_SHORTCODES[num]['code'] + ' '
			                                	);
		                                	}
		                                }
		                            });
	                            }
                            }
						});
						 
                        return controlName;
                    }
                    
                    return null
                },
                
                addSubmenu:function(menu, label, code){
                	menu.add({
	        			title: label,
	        			onclick:function(){
	        				tinyMCE.activeEditor.execCommand("mceInsertContent", false, code);
	        			}
                	})
                },
                
                getInfo:function() { 
                	return {
	        			longname:"GavernWP Shortcodes",
	        			author:"GavickPro",
	        			authorurl:"http://www.gavick.com",
	        			infourl:"http://www.gavick.com",
	        			version:"1.0"
                	} 
                }
            }
        );
        
        tinymce.PluginManager.add( "GavernWPShortcodes", tinymce.plugins.GavernWPShortcodes );
    }
)();