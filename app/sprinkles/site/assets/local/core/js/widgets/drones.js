/**
 * Users widget.  Sets up dropdowns, modals, etc for a table of users.
 */



/**
 * Link user action buttons, for example in a table or on a specific user's page.
 */
 function bindDroneButtons(el) {
    
    /**
     * Buttons that launch a modal dialog
     */

    

    /**
     * Direct action buttons
     */

    //el.find('.js-user-disable').click(function () {
    //    var btn = $(this);
    //    updateUser(btn.data('user_name'), 'flag_enabled', '0');
    //});

    el.find('.js-drone-locate').click(function() {
        console.log('locate clicked');
        console.log($(this).data('drone_slug'));
        gmapCenterOnMarker($(this).data('drone_slug'));
    });
}

function bindDroneCreationButton(el) {
    // Link create button
//    el.find('.js-user-create').click(function() {
//        $("body").ufModal({
//            sourceUrl: site.uri.public + "/modals/users/create",
//            msgTarget: $("#alerts-page")
//        });

//        attachUserForm();
//    });
};
