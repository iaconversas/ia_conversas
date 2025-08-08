@props([
    'href' => null,
    'type' => 'button',
])

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => 'figma-button']) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => 'figma-button']) }}>
        {{ $slot }}
    </button>
@endif