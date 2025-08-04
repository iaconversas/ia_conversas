@props([
    'variant' => 'info',
    'dismissible' => false,
    'icon' => null,
])

@php
$variants = [
    'info' => 'bg-blue-50 border-blue-200 text-blue-800 dark:bg-blue-900/20 dark:border-blue-800 dark:text-blue-200',
    'success' => 'bg-green-50 border-green-200 text-green-800 dark:bg-green-900/20 dark:border-green-800 dark:text-green-200',
    'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-800 dark:bg-yellow-900/20 dark:border-yellow-800 dark:text-yellow-200',
    'danger' => 'bg-red-50 border-red-200 text-red-800 dark:bg-red-900/20 dark:border-red-800 dark:text-red-200',
];

$iconMap = [
    'info' => 'information-circle',
    'success' => 'check-circle',
    'warning' => 'exclamation-triangle',
    'danger' => 'exclamation-circle',
];

$defaultIcon = $icon ?? $iconMap[$variant] ?? 'information-circle';

$classes = collect([
    'flex items-start p-4 border rounded-lg',
    $variants[$variant] ?? $variants['info'],
])->filter()->implode(' ');
@endphp

<div {{ $attributes->class($classes) }} @if($dismissible) x-data="{ show: true }" x-show="show" @endif>
    <div class="flex">
        @if($defaultIcon)
            <flux:icon name="{{ $defaultIcon }}" variant="outline" class="size-5 mr-3 mt-0.5 flex-shrink-0" />
        @endif
        
        <div class="flex-1">
            {{ $slot }}
        </div>
        
        @if($dismissible)
            <button 
                @click="show = false"
                class="ml-3 flex-shrink-0 opacity-70 hover:opacity-100 transition-opacity"
            >
                <flux:icon name="x-mark" variant="outline" class="size-4" />
            </button>
        @endif
    </div>
</div>