/**
 * Fleets widget.  Sets up dropdowns, modals, etc for a table of fleets.
 */

/**
 * Set up the form in a modal after being successfully attached to the body.
 */
function attachFleetForm() {
    $("body").on('renderSuccess.ufModal', function (data) {
        var modal = $(this).ufModal('getModal');
        var form = modal.find('.js-form');

        /**
         * Set up modal widgets
         */
        // Set up any widgets inside the modal
        form.find(".js-select2").select2({
            width: '100%'
        });

        // Auto-generate slug
        form.find('input[name=name]').on('input change', function() {
            var manualSlug = form.find('#form-fleet-slug-override').prop('checked');
            if (!manualSlug) {
                var slug = getSlug($(this).val());
                form.find('input[name=slug]').val(slug);
            }
        });

        form.find('#form-fleet-slug-override').on('change', function() {
            if ($(this).prop('checked')) {
                form.find('input[name=slug]').prop('readonly', false);
            } else {
                form.find('input[name=slug]').prop('readonly', true);
                form.find('input[name=name]').trigger('change');
            }
        });

        // Set icon when changed
        form.find('input[name=icon]').on('input change', function() {
            $(this).prev(".icon-preview").find("i").removeClass().addClass($(this).val());
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
 * Link fleet action buttons, for example in a table or on a specific fleets's page.
 */
function bindFleetButtons(el) {
    /**
     * Link row buttons after table is loaded.
     */

    /**
     * Buttons that launch a modal dialog
     */
    // Edit fleet details button
    el.find('.js-fleet-edit').click(function() {
        $("body").ufModal({
            sourceUrl: site.uri.public + "/modals/fleets/edit",
            ajaxParams: {
                slug: $(this).data('slug')
            },
            msgTarget: $("#alerts-page")
        });

        attachFleetForm();
    });

    // Delete fleet button
    el.find('.js-fleet-delete').click(function() {
        $("body").ufModal({
            sourceUrl: site.uri.public + "/modals/fleets/confirm-delete",
            ajaxParams: {
                slug: $(this).data('slug')
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

function bindFleetCreationButton(el) {
    // Link create button
    el.find('.js-fleet-create').click(function() {
        $("body").ufModal({
            sourceUrl: site.uri.public + "/modals/fleets/create",
            msgTarget: $("#alerts-page")
        });

        attachGroupForm();
    });
};
