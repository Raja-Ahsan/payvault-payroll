// pre loader
$(".loader-wrapper").fadeOut("slow", function () {
    $(this).remove();
});

(function () {
    const themeToggleBtn = document.querySelector(".toggleThemeBtn");
    if (!themeToggleBtn) {
        return;
    }

    function syncThemeToggleButton() {
        const isDark = document.body.classList.contains("dark-only");
        themeToggleBtn.setAttribute(
            "aria-label",
            isDark ? "Switch to light mode" : "Switch to dark mode",
        );
        themeToggleBtn.setAttribute(
            "title",
            isDark ? "Light mode" : "Dark mode",
        );
    }

    themeToggleBtn.addEventListener("click", function () {
        const body = document.querySelector("body");
        if (body.classList.contains("dark-only")) {
            body.classList.remove("dark-only");
            localStorage.setItem("theme", "light");
        } else {
            body.classList.add("dark-only");
            localStorage.setItem("theme", "dark");
        }
        syncThemeToggleButton();
    });

    document.addEventListener("DOMContentLoaded", syncThemeToggleButton);
})();

// single image upload
$("#image").on("change", function () {
    const file = this.files[0];

    if (file) {
        const reader = new FileReader();

        reader.onload = function (e) {
            $("#imagePreview").attr("src", e.target.result).fadeIn();
        };

        reader.readAsDataURL(file);
    } else {
        $("#imagePreview").hide().attr("src", "");
    }
});

// multiple image upload
const max_images = 5;

$("#gallery_images").on("change", function () {
    const files = this.files;
    $("#galleryPreview").html("");

    if (files.length > max_images) {
        Swal.fire({
            icon: "error",
            title: "Too many images",
            text: `You can upload maximum ${max_images} images only`,
        });

        this.value = "";
        return;
    }

    Array.from(files).forEach((file) => {
        if (!file.type.startsWith("image/")) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            $("#galleryPreview").append(`
                    <img src="${e.target.result}"
                         style="width:80px;height:80px;object-fit:cover;
                         border-radius:6px;border:1px solid #ddd;">
                `);
        };
        reader.readAsDataURL(file);
    });
});

// sm functions
function formatEIN(el) {
    let value = el.value.replace(/\D/g, "");

    // max 9 digits
    if (value.length > 9) {
        value = value.slice(0, 9);
    }

    // dash after 2 digits
    if (value.length > 2) {
        value = value.slice(0, 2) + "-" + value.slice(2);
    }

    el.value = value;
}
