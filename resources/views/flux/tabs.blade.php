@props([
    'variant' => 'default',
    'size' => 'md',
    'wire:model' => null,
])

@php
$classes = collect([
    'flex',
    match($variant) {
        'segmented' => 'bg-gray-100 dark:bg-gray-800 p-1 rounded-lg',
        'pills' => 'space-x-1',
        default => 'border-b border-gray-200 dark:border-gray-700',
    },
    match($size) {
        'sm' => 'text-sm',
        'lg' => 'text-lg',
        default => 'text-base',
    }
])->filter()->implode(' ');
@endphp

<div {{ $attributes->class($classes) }} role="tablist">
    {{ $slot }}
</div>