/**
 * Page-specific Javascript file.  Should generally be included as a separate asset bundle in your page template.
 * example: {{ assets.js('js/pages/sign-in-or-register') | raw }}
 *
 * This script depends on widgets/users.js, uf-table.js, moment.js, handlebars-helpers.js
 *
 * Target page: /users
 */

$(document).ready(function() {
    // Set up table of mountpoints
    $("#widget-mountpoints").ufTable({
        dataUrl: site.uri.public + "/api/mountpoints/bydroneid/" + $('#widget-mountpoints').data('droneid')
    });

    // Bind creation button
    bindMountpointCreationButton($("#widget-mountpoints"));

    // Bind table buttons
    $("#widget-mountpoints").on("pagerComplete.ufTable", function () {
        bindMountpointsButtons($(this));
    });
});

