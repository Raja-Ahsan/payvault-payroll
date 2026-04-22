$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
})
function ajaxCreate(successRedirect = null) {
    $(document).on('submit', 'form.ajax-form', function (e) {
        e.preventDefault();

        let form = $(this);
        let submitBtn = form.find('button[type="submit"]');
        let btnOriginalText = submitBtn.length ? submitBtn.text() : 'Save';
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
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            beforeSend: function () {
                submitBtn.prop('disabled', true).text('Saving...');
            },
            success: function (response) {
                submitBtn.prop('disabled', false).text(btnOriginalText);
                const methodOverride = form.find('input[name="_method"]').val();
                if (methodOverride !== 'PUT' && methodOverride !== 'PATCH') {
                    form[0].reset();
                }

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
                submitBtn.prop('disabled', false).text(btnOriginalText);

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
                            let input = form.find(`[name="${key}"]`);
                            if (!input.length) {
                                const dot = String(key).match(/^(.+)\.(\d+)$/);
                                if (dot) {
                                    input = form.find(`[name="${dot[1]}[${dot[2]}]"]`);
                                }
                            }
                            if (!input.length) {
                                const br = String(key).match(/^(.+)\.(\d+)$/);
                                if (br) {
                                    const named = form.find(`[name="${br[1]}[]"]`);
                                    if (named.length) {
                                        const idx = parseInt(br[2], 10);
                                        input = named.eq(idx);
                                    }
                                }
                            }
                            if (input.length) {
                                input.addClass('is-invalid');
                                input.after(`<div class="invalid-feedback d-block">${messages[0]}</div>`);
                            } else {
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
