function setupAjaxForm(formSelector) {
    $(formSelector).submit(function(event) {
        event.preventDefault();
        const form = $(this);
        const formData = form.serialize();
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: form.data('action'),
            type: form.data('action-method'),
            data: formData,
            headers: {'X-CSRF-TOKEN': csrfToken},
            success: function(response) {
                console.log(response);
                switch (response.status) {
                    case 200:
                        toastr.success(response.message, response.title || "Success");
                        break;
                    case 422:
                        toastr.error(response.message, response.title || "Validation Error");
                        break;
                    default:
                        toastr.error(response.message || "Unknown error occurred", response.title || "Error");
                }

                // Reset requested element (mostly input form)
                if (response.resetTarget) {
                    $(response.resetTarget).trigger('reset');
                }
                // Reload requested element (mostly data table)
                if (response.reloadTarget && $.fn.dataTable.isDataTable(response.reloadTarget)) {
                    $(response.reloadTarget).DataTable().ajax.reload();
                }
                //Hide requested element (mostly confirm modal)
                if (response.hideTarget) {
                    $(response.hideTarget).modal('hide');
                }
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                toastr.error("A server error occurred. Please try again.", "Error");
            }
        });
    });
}
