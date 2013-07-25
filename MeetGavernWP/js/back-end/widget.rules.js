/**
 *
 * -------------------------------------------
 * Script for the Widget Rules
 * -------------------------------------------
 *
 **/
"use strict";

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
            var days = options.expires,
                t = options.expires = new Date();            
            t.setDate(t.getDate() + days);        
        }
                
        value = String(value);

        return (document.cookie = [encodeURIComponent(key), '=', options.raw ? value : encodeURIComponent(value), options.expires ? '; expires=' + options.expires.toUTCString() : '', options.path ? '; path=' + options.path : '', options.domain ? '; domain=' + options.domain : '', options.secure ? '; secure' : ''        
        ].join(''));    
    }
    // key and possibly options given, get cookie...   
    options = value || {};    
    var result, decode = options.raw ? function (s) {
            return s;
        } : decodeURIComponent;    
    return (result = new RegExp('(?:^|; )' + encodeURIComponent(key) + '=([^;]*)').exec(document.cookie)) ? decode(result[1]) : null;
};

// remove active state from any widget rules wrapper on the start to avoid
// the situation when some wrapper is opened earlier due saved data in the cookie file.
jQuery(window).on('beforeunload', function () {
    jQuery.cookie('gk_last_opened_widget_rules_wrap', 0, {
        expires: 365,
        path: '/'
    });
});

// added event to open the widget rules wrapper (uses id from the back-end wihout the random ID at end).
jQuery(document).click(function (e) {
    if (jQuery(e.target).hasClass('gk_widget_rules_btn')) {
        var wrap = jQuery(e.target).next('.gk_widget_rules_wrapper');

        if (wrap.hasClass('active')) {
            wrap.removeClass('active');
            jQuery.cookie('gk_last_opened_widget_rules_wrap', 0, {
                expires: 365,
                path: '/'
            });
        } else {
            wrap.addClass('active');
            jQuery.cookie('gk_last_opened_widget_rules_wrap', wrap.attr('data-id'), {
                expires: 365,
                path: '/'
            });
        }
    }
});

function gk_widget_control_init(id, inner) {
    // check if the widget isn't a new widget
    var allForms = jQuery('.gk_widget_rules_form');
    var newest = null;
    var flag = 0;

    if (!inner) {
        for (var i = 0; i < allForms.length; i++) {
            if ('#' + jQuery(allForms[i]).attr('id') === id) {
                newest = jQuery(allForms[i]);
                flag += 1;
            }
        }

        if (flag > 1) {
            newest.attr('id', newest.attr('id') + '-' + Math.floor((Math.random() * 10000 + 1)));
            newest.attr('data-state', 'uninitialized');
            gk_widget_control_init('#' + newest.attr('id'), true);
            return;
        }
    }
    // if it is a new widget
    if (inner) {
        var mouseUpEvent = function () {
            setTimeout(function () {
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

    if (inner) {
        form.parent().find('select:last-child').css('opacity', '0.5');

        setTimeout(function () {
            var btn = form.parent().parent().parent().find('*[name="savewidget"]');
            btn.click();
        }, 1000);
    }

    if (form.attr('data-state') !== 'initialized') {
        form.attr('data-state', 'initialized');
        var firstSelect = form.parent().find('.gk_widget_rules_select');
        var select = form.children('.gk_widget_rules_form_select');
        var btn = form.find('.gk_widget_rules_btn');
        var form_inputs = {};
        var form_inputs_names = ['page', 'post', 'category', 'tag', 'template', 'taxonomy', 'taxonomy_term', 'posttype', 'author'];
        jQuery.each(form_inputs_names, function (i, el) {
            form_inputs[el] = form.find('.gk_widget_rules_form_input_' + el).parent();
        });
        // hide unnecesary form
        if (firstSelect.children('option:selected').val() === 'all') {
            form.css('display', 'none');
        }
        // change event
        firstSelect.change(function () {
            var value = firstSelect.children('option:selected').val();

            if (value === 'all') {
                form.css('display', 'none');
            } else {
                form.css('display', 'block');
            }
        });
        // refresh the list
        gk_widget_control_refresh(form);
        // add onChange event to the selectbox
        select.change(function () {
            var value = select.children('option:selected').val();

            if (value === 'homepage' || value === 'page404' || value === 'search' || value === 'archive') {
                jQuery.each(form_inputs, function (i, el) {
                    el.css('display', 'none');
                });
            } else {
                jQuery.each(form_inputs_names, function (i, el) {
                    if (value.replace(':', '') !== el && value.replace(':', '') !== el + '_term') {
                        form_inputs[el].css('display', 'none');
                    } else {
                        form_inputs[el].css('display', 'block');
                    }
                });
            }
        });
        // add the onClick event to the button
        btn.click(function (event) {
            event.preventDefault();

            var output = form.find('.gk_widget_rules_output');
            var value = select.children('option:selected').val();

            if (
                value === 'homepage' ||
                value === 'search' ||
                value === 'archive' ||
                value === 'page404'
            ) {
                output.val(output.val() + ',' + value);
            } else if (
                value === 'page:' ||
                value === 'post:' ||
                value === 'format:' ||
                value === 'template:' ||
                value === 'category:' ||
                value === 'tag:' ||
                value === 'author:'
            ) {
                output.val(output.val() + ',' + value + form.find('.gk_widget_rules_form_input_' + value.replace(':', '')).val());
            } else if (value === 'taxonomy:') {
                var tax = form.find('.gk_widget_rules_form_input_taxonomy').val();
                var term = form.find('.gk_widget_rules_form_input_taxonomy_term').val();
                output.val(output.val() + ',taxonomy:' + tax + ((term !== '') ? ';' + term : ''));
            } else if (value === 'posttype:') {
                var type = form.find('.gk_widget_rules_form_input_posttype').val();
                //
                if (type !== '') {
                    output.val(output.val() + ',posttype:' + type);
                }
            }

            gk_widget_control_refresh(form);
        });
        // event to remove the page tags
        form.find('.gk_widget_rules_pages div').click(function (event) {
            if (event.target.nodeName.toLowerCase() === 'strong') {
                var output = form.find('.gk_widget_rules_output');
                var parent = jQuery(event.target).parent();
                parent.find('strong').remove();
                var text = parent.text();
                //
                if (text === 'All pages') {
                    text = 'page:';
                } else if (text === 'All posts pages') {
                    text = 'post:';
                } else if (text === 'All category pages') {
                    text = 'category:';
                } else if (text === 'All tag pages') {
                    text = 'tag:';
                } else if (text === 'All author pages') {
                    text = 'author:';
                } else if (text === 'All taxonomy pages') {
                    text = 'taxonomy:';
                } else if (text === 'All post format pages') {
                    text = 'format:';
                } else if (text === 'All page template pages') {
                    text = 'template:';
                }
                //
                if (text.indexOf(':') === text.length - 1) {
                    var startlen = output.val().length;
                    output.val(output.val().replace("," + text + ",", ""));
                    // if previous regexp didn't changed the value
                    if (startlen === output.val().length) {
                        var regex = new RegExp(',' + text + '$', 'gmi');
                        output.val(output.val().replace(regex, ""));
                    }
                } else {
                    output.val(output.val().replace("," + text, ""));
                }
                //
                gk_widget_control_refresh(form);
            }
        });
        // event to display the custom CSS class field 
        var selectStyles = jQuery(document).find('.gk_widget_rules_select_styles');
        selectStyles.each(function (i, select) {
            select = jQuery(select);

            if (!select.hasClass('initialized')) {
                select.change(function () {
                    var value = select.children('option:selected').val();
                    var field = select.parent().parent().next('p');

                    if (value !== 'gkcustom') {
                        if (!field.hasClass('gk-unvisible')) {
                            field.addClass('gk-unvisible');
                        }
                    } else {
                        if (field.hasClass('gk-unvisible')) {
                            field.removeClass('gk-unvisible');
                        }
                    }
                });

                select.addClass('initialized');
            }
        });
    }
}

// function to refresh the list of pages

function gk_widget_control_refresh(form) {
    var output = form.find('.gk_widget_rules_output');
    if (output.length > 0) {
        var list = form.find('.gk_widget_rules_pages div');
        list.html('');
        var pages = output.val().split(',');
        var pages_exist = false;

        for (var i = 0; i < pages.length; i++) {
            if (pages[i] !== '') {
                pages_exist = true;
                var type = 'homepage';
                var types = ['page:', 'post:', 'category:', 'tag:', 'archive', 'author:', 'template:', 'taxonomy:', 'posttype:', 'page404', 'search'];

                jQuery.each(types, function (j, el) {
                    if (pages[i].substr(0, el.length) === el) {
                        type = el.replace(':', '');
                    }
                });

                var out = pages[i];

                if (out === 'page:') {
                    out = 'All pages';
                } else if (out === 'post:') {
                    out = 'All posts pages';
                } else if (out === 'category:') {
                    out = 'All category pages';
                } else if (out === 'tag:') {
                    out = 'All tag pages';
                } else if (out === 'author:') {
                    out = 'All author pages';
                } else if (out === 'taxonomy:') {
                    out = 'All taxonomy pages';
                } else if (out === 'format:') {
                    out = 'All post format pages';
                } else if (out === 'template:') {
                    out = 'All page template pages';
                }

                list.html(list.html() + "<span class=" + type + ">" + out + "<strong>&times;</strong></span>");
            }
        }

        form.find('.gk_widget_rules_nopages').css('display', pages_exist ? 'none' : 'block');
    }
}