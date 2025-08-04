@props([
    'default' => 'qr-code',
])

<div {{ $attributes->class('') }} x-data="{ activeTab: '{{ $default }}' }">
    {{ $slot }}
</div>