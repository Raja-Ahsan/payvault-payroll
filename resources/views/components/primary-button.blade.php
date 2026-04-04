<button {{ $attributes->merge(['type' => 'submit', 'class' => 'primary-button style-inter']) }}>
    {{ $slot }}
</button>
