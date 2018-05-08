/**
 * Page-specific Javascript file.  Should generally be included as a separate asset bundle in your page template.
 * example: {{ assets.js('js/pages/sign-in-or-register') | raw }}
 *
 * This script depends on widgets/fleets.js, uf-table.js, moment.js, handlebars-helpers.js
 *
 * Target page: /fleets
 */

$(document).ready(function() {

    $("#widget-fleets").ufTable({
        dataUrl: site.uri.public + "/api/fleets",
        useLoadingTransition: site.uf_table.use_loading_transition
    });

    // Bind creation button
    bindFleetCreationButton($("#widget-fleets"));

    // Bind table fleets
    $("#widget-fleets").on("pagerComplete.ufTable", function () {
        bindGroupButtons($(this));
    });
});
