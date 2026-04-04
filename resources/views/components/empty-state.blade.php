<div class="text-center py-5">
    <span class="text-white secondry-font fs-32 d-block mb-2">
        {{ $message }}
    </span>

    @if(!empty($subMessage))
        <small class="text-white">
            {{ $subMessage }}
        </small>
    @endif
</div>
