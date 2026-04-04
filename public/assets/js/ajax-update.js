function ajaxUpdate(formSelector, successRedirect = null) {
    $(document).on('submit', formSelector, function (e) {
        e.preventDefault();
        let form = $(this);
        let formData = new FormData(this);
        formData.append('_method', 'PUT'); // Spoof PUT method for Laravel

        // Check for Dropzone instances and append files
        let hasDropzone = (typeof window.Dropzone !== 'undefined' && Dropzone.instances && Dropzone.instances.length > 0);

        if (hasDropzone) {
            Dropzone.instances.forEach(function (dz) {
                dz.getQueuedFiles().forEach(function (file) {
                    formData.append(dz.options.paramName, file);
                });
            });
        }

        let existingImages = $("#galleryPreview .image-preview-wrapper").length;
        let newImages = hasDropzone ? Dropzone.instances[0].files.length : 0;

        let totalImages = existingImages + newImages;

        let maxImages = 5;
        if (totalImages > maxImages) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'You can only upload up to 5 images!',
            });
            return false;
        }

        // Proceed with AJAX request to update
        $.ajax({
            url: form.attr('action'),
            type: 'POST', // Always POST when using FormData with _method spoofing
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {
                form.find('button[type="submit"]').prop('disabled', true).text('Updating...');
            },
            success: function (response) {
                form.find('button[type="submit"]').prop('disabled', false).text('Update');
                Swal.fire({
                    icon: 'success',
                    title: 'Updated!',
                    text: response.message || 'Updated successfully!',
                    showConfirmButton: false,
                    timer: 1500,
                });

                if (response.data) {
                    updateCategoryRow(response.data);
                }

                if (successRedirect) {
                    setTimeout(() => {
                        window.location.href = successRedirect;
                    }, 1600);
                }
                if (window.location.pathname === '/admin/products/categories') {
                    location.reload(); // Only reload the products page
                }
                $('#crudModal').modal('hide');
            },
            error: function (xhr) {
                form.find('button[type="submit"]').prop('disabled', false).text('Update');
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
