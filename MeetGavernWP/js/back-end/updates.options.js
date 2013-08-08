/**
 *
 * -------------------------------------------
 * Script for the updates engine
 * -------------------------------------------
 *
 **/
(function () {
    "use strict";

    jQuery(document).ready(function () {
        var update_url = 'https://www.gavick.com/updates/json/tmpl,component/query,product/product,' + $GK_TEMPLATE_UPDATE_NAME;
        var update_div = jQuery('#gkTemplateUpdates');
        update_div.html('<div id="gkUpdateDiv"><span id="gkLoader"></span>Loading update data from GavicPro Update service...</div>');

        jQuery.getScript(update_url, function () {
            var content = '';

            var templateVersion = $GK_TEMPLATE_UPDATE_VERSION.split('.');
          
            jQuery.map(templateVersion, function (version) {
                return parseInt(version, 10);
            });

            jQuery($GK_UPDATE).each(function (i, el) {
                var updateVersion = el.version.split('.');
                jQuery.map(updateVersion, function (version) {
                    return parseInt(version, 10);
                });
                var isNewer = false;

                if (updateVersion[0] > templateVersion[0]) {
                    isNewer = true;
                } else if (updateVersion[0] >= templateVersion[0] && updateVersion[1] > templateVersion[1]) {
                    isNewer = true;
                } else if (updateVersion.length > 2) {
                    if (templateVersion.length > 2) {
                        if (updateVersion[0] >= templateVersion[0] && updateVersion[1] >= templateVersion[1] && updateVersion[2] > templateVersion[2]) {
                            isNewer = true;
                        }
                    } else {
						if (updateVersion[0] >= templateVersion[0] && updateVersion[1] >= templateVersion[1] && updateVersion[2] > 0) {
						    isNewer = true;
						}
                    }
                }
                //
                if (isNewer) {
                    content += '<li><span class="gkUpdateVersion"><strong>Version:</strong> ' + el.version + ' </span><span class="gkUpdateData"><strong>Date:</strong> ' + el.date + ' </span><span class="gkUpdateLink"><a href="' + el.link + '" target="_blank">Download</a></span></li>';
                }
            });

            update_div.html('<ul class="gk_updates">' + content + '</ul>');

            if (update_div.html() === '<ul class="gk_updates"></ul>') {
                update_div.html('<p>Your template is up to date</p>');
            }
        });
    });
})();