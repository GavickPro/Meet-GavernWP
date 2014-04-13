/**
 *
 * -------------------------------------------
 * Script for the shortcode button
 * -------------------------------------------
 *
 **/
 
(function() {
    "use strict";
    
    var icon_url = '" style="background-image: url(\'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAEhJREFUeNpi/P//PwM1ARMDlQELjNG26z66UxmhNFFeqHJTZKSJCwe/gSxYwowBR1gOvAspimWY+tFYHo3lYRnLjIO+xAYIMABySA8v3JaU8wAAAABJRU5ErkJggg==\');';

    tinymce.PluginManager.add( 'gavern_shortcode_button', function( editor, url ) {
        // generate the menu structure
        var menu = generateItems($GAVERNWP_SHORTCODES, editor);
        // Add a button that opens a window
        editor.addButton( 'gavern_shortcode_button', {
            type: 'menubutton',
            title: 'Insert GavernWP Shortcode',
            icon: icon_url,
           	menu: menu
        } );
    } );

    function generateItems(items, editor) {
        var menu_items = [];
        
        for(var i = 0; i < items.length; i++) {
            var item = {
                text: items[i].title,
                value: items[i].code
            };

            if(item.code !== '') {
                item.onclick = function(e) {
                    e.stopPropagation();
                    editor.insertContent(this.value());
                };
            }

            if(items[i].submenu) {
                item.menu = generateItems(items[i].submenu, editor);
            }

            menu_items.push(item);
        }

        return menu_items;
    }
} )();