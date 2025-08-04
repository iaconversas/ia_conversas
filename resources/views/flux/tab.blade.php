@props([
    'name' => null,
    'icon' => null,
    'action' => false,
])

@php
$isActive = $name ? "activeTab === '{$name}'" : 'false';
$classes = collect([
    'px-4 py-2 font-medium transition-colors cursor-pointer',
    'hover:text-gray-700 dark:hover:text-gray-300',
    'focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2',
])->filter()->implode(' ');

$activeClasses = match($attributes->get('variant', 'default')) {
    'segmented' => 'bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm rounded-md',
    'pills' => 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded-full',
    default => 'border-b-2 border-blue-500 text-blue-600 dark:text-blue-400',
};

$inactiveClasses = match($attributes->get('variant', 'default')) {
    'segmented' => 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300',
    'pills' => 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 rounded-full',
    default => 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 border-b-2 border-transparent',
};
@endphp

<button 
    {{ $attributes->class($classes) }}
    :class="{{ $isActive }} ? '{{ $activeClasses }}' : '{{ $inactiveClasses }}'"
    @if($name && !$action) @click="activeTab = '{{ $name }}'" @endif
    role="tab"
    :aria-selected="{{ $isActive }}"
    @if($name) aria-controls="panel-{{ $name }}" @endif
>
    @if($icon)
        <flux:icon name="{{ $icon }}" class="size-4 mr-2" />
    @endif
    {{ $slot }}
</button>