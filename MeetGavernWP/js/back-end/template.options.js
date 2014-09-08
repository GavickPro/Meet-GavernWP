/**
 *
 * -------------------------------------------
 * Script for the template options
 * -------------------------------------------
 *
 **/
(function () {
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
        var result, decode = options.raw ? function (s) {
                return s;
            } : decodeURIComponent;
        return (result = new RegExp('(?:^|; )' + encodeURIComponent(key) + '=([^;]*)').exec(document.cookie)) ? decode(result[1]) : null;
    };

    // ID of the upload field 
    var uploadID = '';
    // common functions and objects
    //
    var gkValidation = [];
    var gkValidationResults = [];
    var gkValidationResultsTabs = [];
    var gkVisibility = {};
    var gkVisibilityDependicies = {};
    //
    jQuery(document).ready(function () {
        // tabs
        jQuery('#gkTabs li').each(function (i, el) {
            var item = jQuery(el);
            item.click(function () {
                jQuery('#gkTabs li').removeClass('active');
                jQuery('#gkTabsContent > div').removeClass('active');

                item.addClass('active');
                jQuery(jQuery('#gkTabsContent > div')[i]).addClass('active');
                // save the cookie with the active tab
                jQuery.cookie(jQuery('#gkMainWrap').attr('data-theme') + '_active_tab', i, {
                    expires: 365,
                    path: '/'
                });
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

        fields.each(function (i, field) {
            field = jQuery(field);
            field.prev('label').miniTip();
        });
        // saving the settings
        jQuery('.gkSave').each(function (i, button) {
            jQuery(button).click(function (event) {
                event.preventDefault();

                if (gkValidate()) {
                    // save the settings
                    var data = {
                        action: 'template_save',
                        security: $gk_ajax_nonce
                    };

                    var fields = jQuery('#gkTabsContent').find('.gkInput');

                    fields.each(function (i, field) {
                        field = jQuery(field);
                        if (field.hasClass('gkSwitcher') || field.hasClass('gkSelect')) {
                            data[field.attr('id')] = field.find('option:selected').val();
                        } else {
                            data[field.attr('id')] = field.val();
                        }
                    });
                    // make an effect ;)
                    jQuery('#gkTabsContent').find('.active').find('.gkAjaxLoading').css('opacity', 1);
                    jQuery(event.target).html(jQuery(event.target).attr('data-loading'));
                    // make a request
                    jQuery.post(ajaxurl, data, function (response) {
                        if (response === '0') {
                            jQuery(event.target).html('You\'re not logged in. Settings wasn\'t saved');
                        } else {
                            jQuery(event.target).html(response);
                        }
                        jQuery('#gkTabsContent').find('.active').find('.gkAjaxLoading').css('opacity', 0);
                        setTimeout(function () {
                            jQuery(event.target).html(jQuery(event.target).attr('data-loaded'));
                        }, 2500);
                    });
                } else {
                    jQuery(event.target).html(jQuery(event.target).attr('data-wrong'));
                    setTimeout(function () {
                        jQuery(event.target).html(jQuery(event.target).attr('data-loaded'));
                    }, 2500);
                }
            });
        });

        if (jQuery('#gk-load-settings').length) {
            jQuery('#gk-load-settings').click(function () {
                window.location.href += '&task=load_widgets';
            });

            jQuery('#gk-cancel-settings').click(function () {
                window.location.href += '&task=notload_widgets';
            });
        }
    });
    // function to init the validation rules

    function gkValidateInit() {
        jQuery('#gkTabsContent > div').each(function (i, tab) {
            gkValidation[i] = [];
            gkValidationResults[i] = [];
            gkValidationResultsTabs[i] = true;

            var fields = jQuery(tab).find('.gkInput');

            fields.each(function (j, field) {
                var data = {
                    'type': 'text',
                    'format': '',
                    'required': ''
                };
                field = jQuery(field);

                if (field.hasClass('gkSwitcher') || field.hasClass('gkSelect')) {
                    data.type = 'select';
                    field.blur(function () {
                        gkValidateField(field, 'select');
                        gkVisibilityField(field, 'select');
                    });
                    field.change(function () {
                        gkValidateField(field, 'select');
                        gkVisibilityField(field, 'select');
                    });
                } else {
                    field.blur(function () {
                        gkValidateField(field, 'text');
                        gkVisibilityField(field, 'text');
                    });
                }
                data.format = (field.attr('data-format') !== '') ? new RegExp(field.attr('data-format')) : '';
                data.required = field.attr('data-required');
                gkValidation[i][j] = data;
                gkValidationResults[i][j] = [];
            });
        });
    }
    // function to validate

    function gkValidate() {
        // validate
        jQuery(gkValidation).each(function (i, fields) {
            var allFields = jQuery(jQuery('#gkTabsContent > div')[i]).find('.gkInput');
            gkValidationResultsTabs[i] = true;

            jQuery(fields).each(function (j, field) {
                var value = field.type === 'select' ? jQuery(allFields[j]).find('option:selected').val() : jQuery(allFields[j]).val();
                var data = gkValidation[i][j];
                gkValidationResults[i][j] = [];

                if (data.required === 'true' && jQuery(allFields[j]).get('data-visible') === 'true' && !value) {
                    gkValidationResults[i][j].push('required');
                    gkValidationResultsTabs[i] = false;
                }

                if (data.format !== '' && jQuery(allFields[j]).attr('data-visible') === 'true' && !value.match(data.format)) {
                    gkValidationResults[i][j].push('format');
                    gkValidationResultsTabs[i] = false;
                }
            });
        });
        // change elements basic on the results
        var result = true;

        jQuery(gkValidationResultsTabs).each(function (i, tabCorrect) {
            if (tabCorrect) {
                jQuery(jQuery('#gkTabs li')[i]).removeClass('wrong');
            } else {
                jQuery(jQuery('#gkTabs li')[i]).addClass('wrong');
                result = false;
            }
        });
        // validate all fields
        var fields = jQuery('#gkTabsContent').find('.gkInput');

        fields.each(function (i, field) {
            field = jQuery(field);
            gkValidateField(field, (field.hasClass('gkSwitcher') || field.hasClass('gkSelect')) ? 'select' : 'text');
        });

        // return the result
        return result;
    }
    // function to validate one field

    function gkValidateField(field, type) {
        var value = (type === 'select') ? field.find('option:selected').val() : field.val();
        var format = (field.attr('data-format') !== '') ? new RegExp(field.attr('data-format')) : '';
        var required = field.attr('data-required');
        var visibility = field.attr('data-visible');

        field.removeClass('wrong-format');
        field.removeClass('wrong-required');

        if (required === 'true' && visibility === 'true' && !value) {
            field.addClass('wrong-required');
        }

        if (format !== '' && visibility === 'true' && !value.match(format)) {
            field.addClass('wrong-format');
        }
        // check the tabs
        jQuery('#gkTabsContent > div').each(function (i, tab) {
            var wrongFormat = jQuery(tab).find('.wrong-format');
            var wrongRequired = jQuery(tab).find('.wrong-required');

            if (wrongFormat.length === 0 && wrongRequired.length === 0) {
                if (jQuery(jQuery('#gkTabs li')[i]).hasClass('wrong')) {
                    jQuery(jQuery('#gkTabs li')[i]).removeClass('wrong');
                }
            } else {
                if (!jQuery(jQuery('#gkTabs li')[i]).hasClass('wrong')) {
                    jQuery(jQuery('#gkTabs li')[i]).addClass('wrong');
                }
            }
        });
    }
    //

    function gkVisibilityInit() {
        var allFields = jQuery('#gkTabsContent').find('.gkInput');
        //
        allFields.each(function (i, field) {
            var visibility = jQuery(field).attr('data-visibility');

            if (visibility !== '') {
                var tempVisibilityRules = visibility.split(',');

                for (var j = 0; j < tempVisibilityRules.length; j++) {
                    tempVisibilityRules[j] = tempVisibilityRules[j].split('=');

                    tempVisibilityRules[j] = {
                        "field": tempVisibilityRules[j][0],
                        "value": tempVisibilityRules[j][1]
                    };

                    var visible = jQuery(field).attr('id');

                    if (typeof gkVisibilityDependicies[visible] !== "object") {
                        gkVisibilityDependicies[visible] = [tempVisibilityRules[j]];
                    } else {
                        gkVisibilityDependicies[visible].push(tempVisibilityRules[j]);
                    }
                }

                var visibilityRules = jQuery(field).attr('data-visibility').split(',');

                for (var k = 0; k < visibilityRules.length; k++) {
                    visibilityRules[k] = visibilityRules[k].split('=');
                    var usedField = visibilityRules[k][0];
                    var tempField = jQuery('*[data-name="' + usedField + '"]');
                    var type = (tempField.hasClass('gkSwitcher') || tempField.hasClass('gkSelect')) ? 'select' : 'text';

                    visibilityRules[k] = {
                        "type": type,
                        "visible": jQuery(field).attr('id')
                    };

                    if (typeof gkVisibility[usedField] !== "object") {
                        gkVisibility[usedField] = [visibilityRules[k]];
                    } else {
                        gkVisibility[usedField].push(visibilityRules[k]);
                    }
                }
            }
        });
        //
        allFields.each(function (i, field) {
            gkVisibilityField(jQuery(field));
        });
    }
    //
    function gkVisibilityField(field) {
        //
        if (gkVisibility[field.attr('data-name')]) {
            //
            var dependencies = gkVisibility[field.attr('data-name')];
            //
            for (var i = 0; i < dependencies.length; i++) {
                var dependsFrom = gkVisibilityDependicies[dependencies[i].visible];
                var flag = 'true';

                for (var j = 0; j < dependsFrom.length; j++) {
                    var type = gkVisibility[dependsFrom[j].field].type;
                    field = jQuery('*[data-name="' + dependsFrom[j].field + '"]');
                    var value = (type === 'select') ? field.find('option:selected').val() : field.val();

                    if (value !== dependsFrom[j].value) {
                        flag = 'false';
                    }
                }

                jQuery('#' + dependencies[i].visible).parent('p').attr('data-visible', flag);
            }
        }
    }
    //
    function gkMediaInit() {
        //
        jQuery('.gkMediaInput').each(
            function (i, el) {
                el = jQuery(el);
                var btnid = el.attr('id') + '_button';
                var file_frame;

                jQuery('#' + btnid).click(function (event) {
                    event.preventDefault();
                    // If the media frame already exists, reopen it.
                    if ( file_frame ) {
                        file_frame.open();
                        return;
                    }
                    // Create the media frame.
                    file_frame = wp.media.frames.file_frame = wp.media({
                        title: 'Choose Image',
                        multiple: false,
                        library: {
                            type: 'image'
                        },
                        button: {
                            text: 'Use This Image'
                        }
                    });
                    // When an image is selected, run a callback.
                    file_frame.on( 'select', function() {
                        // We set multiple to false so only get one image from the uploader
                        var attachment = file_frame.state().get('selection').first().toJSON();
                        el.val(attachment.url);
                        el.parent().find('.gkMediaPreview').html('<img src="' + el.val() + '" alt="Preview" />');   
                    });
                    // Finally, open the modal
                    file_frame.open();
                });

                el.change(function () {
                    if (el.val() === '') {
                        var span = el.parent().find('.gkMediaPreview');
                        span.html(span.attr('data-text'));
                    } else {
                        el.parent().find('.gkMediaPreview').html('<img src="' + el.val() + '" alt="Preview" />');
                    }
                });

                el.blur(function () {
                    if (el.val() === '') {
                        var span = el.parent().find('.gkMediaPreview');
                        span.html(span.attr('data-text'));
                    } else {
                        el.parent().find('.gkMediaPreview').html('<img src="' + el.val() + '" alt="Preview" />');
                    }
                });
            }
        );
    }
    //
})();