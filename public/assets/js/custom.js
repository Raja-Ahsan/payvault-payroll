// pre loader
$(".loader-wrapper").fadeOut("slow", function() {
    $(this).remove();
});

// single image upload
$('#image').on('change', function() {
    const file = this.files[0];

    if (file) {
        const reader = new FileReader();

        reader.onload = function(e) {
            $('#imagePreview')
                .attr('src', e.target.result)
                .fadeIn();
        };

        reader.readAsDataURL(file);
    } else {
        $('#imagePreview').hide().attr('src', '');
    }
});


// multiple image upload
const max_images = 5;

    $('#gallery_images').on('change', function () {
        const files = this.files;
        $('#galleryPreview').html('');

        if (files.length > max_images) {
            Swal.fire({
                icon: 'error',
                title: 'Too many images',
                text: `You can upload maximum ${max_images} images only`
            });

            this.value = '';
            return;
        }

        Array.from(files).forEach(file => {
            if (!file.type.startsWith('image/')) return;

            const reader = new FileReader();
            reader.onload = function (e) {
                $('#galleryPreview').append(`
                    <img src="${e.target.result}"
                         style="width:80px;height:80px;object-fit:cover;
                         border-radius:6px;border:1px solid #ddd;">
                `);
            };
            reader.readAsDataURL(file);
        });
    });