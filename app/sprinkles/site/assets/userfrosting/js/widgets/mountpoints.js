/**
 * Drones widget.  Sets up dropdowns, modals, etc for a table of drones.
 */

/**
 * Set up the form in a modal after being successfully attached to the body.
 */
function attachMountpointForm() {
    $("body").on('renderSuccess.ufModal', function (data) {
        var modal = $(this).ufModal('getModal');
        var form = modal.find('.js-form');

        // Set up any widgets inside the modal
        form.find(".js-select2").select2({
            width: '100%'
        });

        // Auto-generate slug
        /*form.find('input[name=name]').on('input change', function() {
            var manualSlug = form.find('#form-drone-slug-override').prop('checked');
            if (!manualSlug) {
                var slug = getSlug($(this).val());
                form.find('input[name=slug]').val(slug);
            }
        });

        form.find('#form-drone-slug-override').on('change', function() {
            if ($(this).prop('checked')) {
                form.find('input[name=slug]').prop('readonly', false);
            } else {
                form.find('input[name=slug]').prop('readonly', true);
                form.find('input[name=name]').trigger('change');
            }
        });*/

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
 function bindMountpointsButtons(el) {
    
    /**
     * Buttons that launch a modal dialog
     */

    

    /**
     * Direct action buttons
     */
    // Edit general user details button
    el.find('.js-stream-edit').click(function() {
        $("body").ufModal({
            sourceUrl: site.uri.public + "/modals/mountpoints/edit",
            ajaxParams: {
                id: $(this).data('id')
            },
            msgTarget: $("#alerts-page")
        });

        attachMountpointForm();
    });
    // Delete stream 
    el.find('.js-stream-delete').click(function() {
        $("body").ufModal({
            sourceUrl: site.uri.public + "/modals/mountpoints/confirm-delete",
            ajaxParams: {
                id: $(this).data('id')
            },
            msgTarget: $("#alerts-page")
        });

        $("body").on('renderSuccess.ufModal', function (data) {
            var modal = $(this).ufModal('getModal');
            var form = modal.find('.js-form');

            form.ufForm()
            .on("submitSuccess.ufForm", function() {
                // Reload page on success
                window.location.reload();
            });
        });
    });
}

function bindMountpointCreationButton(el) {
    // Link create button
    el.find('.js-stream-create').click(function() {
        $("body").ufModal({
            sourceUrl: site.uri.public + "/modals/mountpoints/create",
            ajaxParams: {
                drone_id:  $(this).data('droneid')
            },
            msgTarget: $("#alerts-page")
        });

        attachMountpointForm();
    });
};
