function ajaxDelete(buttonSelector, itemSelector, dataTableId = null) {
    $(document).on('click', buttonSelector, function(e) {
        e.preventDefault();

        let form = $(this).closest('form');
        let formData = form.serialize();

        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            customClass: {
                popup: 'custom-popup',
                confirmButton: 'custom-confirm-btn',
                cancelButton: 'custom-cancel-btn'
            },
            
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: form.attr('action'),
                    type: 'DELETE',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        form.closest(itemSelector).remove();
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.message || 'Record deleted successfully.',
                            showConfirmButton: false,
                            timer: 1500
                        });

                        if (dataTableId && typeof $(dataTableId).DataTable === 'function') {
                            $(dataTableId).DataTable().ajax.reload(null, false);
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Unable to delete this record.',
                        });
                    }
                });
            }
        });
    });
}
