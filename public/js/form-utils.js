function setupAjaxForm(formSelector) {
    $(formSelector).submit(function(event) {
        event.preventDefault();
        const form = $(this);
        const formData = form.serialize();
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Show the loading overlay
        showOverlay();

        $.ajax({
            url: form.data('action'),
            type: form.data('action-method'),
            data: formData,
            headers: {'X-CSRF-TOKEN': csrfToken},
            success: function(response) {
                // Hide the loading overlay
                hideOverlay();

                console.log(response);
                switch (response.status) {
                    case 200:
                        if (response.isCaution === true){
                            toastr.warning(response.message, response.title);
                        }else {
                            toastr.success(response.message, response.title || "Success");
                        }
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
                const reloadTarget = $(`${response.reloadTarget}`);
                if (reloadTarget) {
                    reloadTarget.each(function (){
                        if ($.fn.dataTable.isDataTable($(this))) {
                            $(this).DataTable().ajax.reload();
                        }
                    })
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

$.fn.serializeObject = function() {
    // Find disabled inputs, and remove the "disabled" attribute
    const disabled = this.find(':input:disabled').removeAttr('disabled');
    const obj = {};
    const arr = this.serializeArray();
    arr.forEach(function(item) {
        if (obj[item.name]) {
            if (typeof(obj[item.name]) === "string") {
                obj[item.name] = [obj[item.name]];
            }
            obj[item.name].push(item.value);
        } else {
            obj[item.name] = item.value;
        }
    });
    disabled.attr('disabled','disabled');
    return obj;
};