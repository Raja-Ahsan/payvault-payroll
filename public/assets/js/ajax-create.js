$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
})
function ajaxCreate(successRedirect = null) {
    $(document).on('submit', 'form.ajax-form', function (e) {
        e.preventDefault();

        let form = $(this);
        let formData = new FormData(this);
        
        // Check for Dropzone instances and append files
        if (typeof Dropzone !== 'undefined' && Dropzone.instances.length > 0) {
            Dropzone.instances.forEach(function (dz) {
                // Only process if the dropzone is attached to this form or a child of it
                // Or simply process all active dropzones if that's the desired generic behavior.
                // Given the user context, we'll append all queued files from all instances.
                dz.getQueuedFiles().forEach(function (file) {
                    formData.append(dz.options.paramName, file);
                });
            });
        }
       
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {
                form.find('button[type="submit"]').prop('disabled', true).text('Saving...');
            },
            success: function (response) {
                form.find('button[type="submit"]').prop('disabled', false).text('Save');
                form[0].reset();

                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message || 'Created successfully!',
                    showConfirmButton: false,
                    timer: 1500
                });

                if (successRedirect) {
                    setTimeout(() => {
                        window.location.href = successRedirect;
                    }, 1600);
                } else if (response.redirect) {
                    setTimeout(() => {
                        window.location.href = response.redirect;
                    }, 1600);
                } else if (typeof $('#dataTable').DataTable === 'function') {
                    $('#dataTable').DataTable().ajax.reload(null, false);
                }
            },
            error: function (xhr) {
                form.find('button[type="submit"]').prop('disabled', false).text('Create');

                if (xhr.status === 422) {
                    const response = xhr.responseJSON;
                    form.find('.invalid-feedback').remove();
                    form.find('.is-invalid').removeClass('is-invalid');

                    if (response.success === false && response.message) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message
                        });
                    }

                    let globalErrors = [];
                    if (response.errors) {
                        $.each(response.errors, function (key, messages) {
                            const input = form.find(`[name="${key}"]`);
                            if (input.length) {
                                input.addClass('is-invalid');
                                input.after(`<div class="invalid-feedback d-block">${messages[0]}</div>`);
                            } else {
                                // If input not found (e.g. dropzone images), collect error
                                globalErrors.push(messages[0]);
                            }
                        });
                    }

                    if (globalErrors.length > 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            html: globalErrors.join('<br>')
                        });
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Something went wrong!',
                    });
                }
            }
        });
    });
}
