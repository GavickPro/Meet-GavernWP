/**
 *
 * -------------------------------------------
 * Script for the Gavern metaboxes
 * -------------------------------------------
 *
 **/
(function () {
    "use strict";
    // Uploading files
    var file_frame;
    jQuery('.gavern_opengraph_upload_image_button').live('click', function( event ){
        event.preventDefault();
        var preview = jQuery(this).siblings('.gavern_opengraph_preview_image');
        var formfield = jQuery(this).siblings('.gavern_opengraph_upload_image');
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
            formfield.val(attachment.id);
            preview.attr('src', attachment.url);
            // Do something with attachment.id and/or attachment.url here
        });

        // Finally, open the modal
        file_frame.open();

    });

    // clear image to the default
    jQuery(document).ready(function () {
        jQuery('.gavern_opengraph_clear_image').click(function (event) {
            event.preventDefault();
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