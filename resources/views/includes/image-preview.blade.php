{{--
    Reusable avatar-style image picker (round preview + camera FAB top-right).

    @param string $name       Input name attribute (required)
    @param string|null $label Visible label above control (default: "Image")
    @param string|null $src   Current image URL for edit mode; omit or null for empty state
    @param string|null $accept Accept attribute (default: png, jpeg, jpg, webp)
    @param string|null $uid   Optional unique DOM id prefix (auto-generated if omitted)
--}}
@php
    $name = $name ?? 'image';
    $label = $label ?? 'Image';
    $src = $src ?? null;
    $accept = $accept ?? 'image/png,image/jpeg,image/jpg,image/webp';
    $uid = $uid ?? 'imgp_' . preg_replace('/\W+/', '_', $name) . '_' . \Illuminate\Support\Str::random(6);
    $hasSrc = filled($src);
@endphp

<div class="image-preview-upload md:col-span-2">
    <label class="block text-sm font-medium text-gray-700 mb-2">{{ $label }}</label>
    <p class="text-xs text-gray-500 mb-3">Click the photo or camera to choose an image (PNG, JPG, WEBP).</p>

    <input type="file"
        id="{{ $uid }}_input"
        name="{{ $name }}"
        accept="{{ $accept }}"
        class="sr-only"
        aria-label="{{ $label }}">

    <label for="{{ $uid }}_input" class="relative inline-block cursor-pointer select-none group">
        <div
            class="relative rounded-full overflow-hidden bg-gray-100 ring-2 ring-gray-200 shadow-inner transition group-hover:ring-green-400 group-focus-within:ring-2 group-focus-within:ring-green-600"
            style="width: 110px; height: 110px; max-width: 110px; max-height: 110px;">
            <img id="{{ $uid }}_preview"
                src="{{ $hasSrc ? $src : '' }}"
                alt=""
                class="h-full w-full object-cover {{ $hasSrc ? '' : 'hidden' }}"
                style="max-width: 110px; max-height: 110px;">
            <div id="{{ $uid }}_placeholder"
                class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 {{ $hasSrc ? 'hidden' : '' }}">
                <i class="fas fa-image text-4xl mb-1 opacity-60"></i>
                <span class="text-xs font-medium text-gray-500">Add image</span>
            </div>
        </div>
        {{-- Camera FAB: top-right, pointer-events-none so clicks go to the <label for> --}}
        <span
            class="absolute -top-0.5 -right-0.5 z-10 flex h-8 w-8 items-center justify-center rounded-full btn-gradient text-white shadow-lg ring-4 ring-white transition hover:opacity-95 focus-within:ring-green-200 pointer-events-none"
            aria-hidden="true">
            <i class="fas fa-camera text-sm"></i>
        </span>
    </label>
</div>

<script>
(function () {
    var uid = @json($uid);
    var input = document.getElementById(uid + '_input');
    var img = document.getElementById(uid + '_preview');
    var ph = document.getElementById(uid + '_placeholder');
    if (!input || !img) return;

    input.addEventListener('change', function (e) {
        var file = e.target.files && e.target.files[0];
        if (!file || !file.type.match(/^image\//)) return;
        var reader = new FileReader();
        reader.onload = function () {
            img.src = reader.result;
            img.classList.remove('hidden');
            if (ph) ph.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    });
})();
</script>
