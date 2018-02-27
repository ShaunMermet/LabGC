/**
 * Users widget.  Sets up dropdowns, modals, etc for a table of users.
 */

/**
 * Set up the form in a modal after being successfully attached to the body.
 */
function attachDroneForm() {
    $("body").on('renderSuccess.ufModal', function (data) {
        var modal = $(this).ufModal('getModal');
        var form = modal.find('.js-form');

        // Set up any widgets inside the modal
        form.find(".js-select2").select2({
            width: '100%'
        });

        // Set up the form for submission
        form.ufForm({
            validators: page.validators
        }).on("submitSuccess.ufForm", function() {
            // Reload page on success
            window.location.reload();
        });
    });
}

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
        gmapCenterOnMarker($(this).data('drone_slug'));
    });
    el.find('.js-drone-details').click(function() {
        gmapDroneDetails($(this).data('drone_id'));
    });
}

function bindDroneCreationButton(el) {
    // Link create button
    el.find('.js-drone-create').click(function() {
        $("body").ufModal({
            sourceUrl: site.uri.public + "/modals/drones/create",
            msgTarget: $("#alerts-page")
        });

        attachDroneForm();
    });
};
