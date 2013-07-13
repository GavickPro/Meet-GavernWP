/**
 *
 * -------------------------------------------
 * Script for the interactive elements shortcodes
 * -------------------------------------------
 *
 **/
jQuery(window).load(function () {
    jQuery('.gk-toggle').each(function (i, el) {
        jQuery(el).children('h3').click(function (e) {
            var parent = jQuery(e.target).parent();

            if (parent.hasClass('closed')) {
                parent.removeClass('closed');
                parent.addClass('opened');
            } else {
                parent.addClass('closed');
                parent.removeClass('opened');
            }
        });
    });
});