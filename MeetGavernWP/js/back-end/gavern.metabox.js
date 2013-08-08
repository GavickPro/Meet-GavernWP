/**
 *
 * -------------------------------------------
 * Script for the Gavern metaboxes
 * -------------------------------------------
 *
 **/
(function () {
    "use strict";
    // Open Graph metatags
    jQuery(function (jQuery) {
        jQuery('.gavern_opengraph_upload_image_button').click(function () {
            var formfield = jQuery(this).siblings('.gavern_opengraph_upload_image');
            var preview = jQuery(this).siblings('.gavern_opengraph_preview_image');
            tb_show('', 'media-upload.php?type=image&TB_iframe=true');
            window.send_to_editor = function (html) {
                var imgurl = jQuery('img', html).attr('src');
                var classes = jQuery('img', html).attr('class');
                var id = classes.replace(/(.*?)wp-image-/, '');
                formfield.val(id);
                preview.attr('src', imgurl);
                tb_remove();
            };
            return false;
        });

        jQuery('.gavern_opengraph_clear_image').click(function () {
            var defaultImage = jQuery(this).parent().siblings('.gavern_opengraph_default_image').text();
            jQuery(this).parent().siblings('.gavern_opengraph_upload_image').val('');
            jQuery(this).parent().siblings('.gavern_opengraph_preview_image').attr('src', defaultImage);
            return false;
        });
    });

    // Page additional params
    jQuery(document).ready(function () {
        var templateSelect = jQuery('#page_template');
        var template = templateSelect.find('option:selected').val();

        jQuery(document).find('p[data-template]').removeClass('active');
        jQuery(document).find('p[data-template="' + template + '"]').addClass('active');

        templateSelect.change(function () {
            var template = templateSelect.find('option:selected').val();
            jQuery(document).find('p[data-template]').removeClass('active');
            jQuery(document).find('p[data-template="' + template + '"]').addClass('active');
        });

        templateSelect.blur(function () {
            var template = templateSelect.find('option:selected').val();
            jQuery(document).find('p[data-template]').removeClass('active');
            jQuery(document).find('p[data-template="' + template + '"]').addClass('active');
        });
    });
}());