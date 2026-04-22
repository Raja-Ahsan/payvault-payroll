@props([
    'id' => '',
    'pdf' => false,
    'fields' => [],
])
@if ($pdf)
    <span class="f941x-pdf-val text-break">{{ e($fields[$id] ?? '') }}</span>
@else
    <input type="text" {{ $attributes->merge(['class' => 'form-control form-control-sm']) }} id="{{ $id }}" value="" autocomplete="off">
@endif
