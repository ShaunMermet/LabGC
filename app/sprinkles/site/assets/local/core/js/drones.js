/**
 * Page-specific Javascript file.  Should generally be included as a separate asset bundle in your page template.
 * example: {{ assets.js('js/pages/sign-in-or-register') | raw }}
 *
 * This script depends on widgets/users.js, uf-table.js, moment.js, handlebars-helpers.js
 *
 * Target page: /users
 */

$(document).ready(function() {
    // Set up table of drones
    $("#widget-drones").ufTable({
        dataUrl: site.uri.public + "/api/drones/my"
    });

    $("#drone1").hover(
        function(){
            console.log("drone1 tab hover");    
        }
    );



    // Bind creation button
    bindDroneCreationButton($("#widget-drones"));

    // Bind table buttons
    $("#widget-drones").on("pagerComplete.ufTable", function () {
        bindDroneButtons($(this));
    });
});

