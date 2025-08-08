@props([
    'icon' => null,
    'active' => false,
    'href' => '#'
])

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'figma-sidebar-item ' . ($active ? 'active' : '')]) }} wire:navigate>
    @if($icon)
    <div class="w-5 h-5">
        <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-full h-full" />
    </div>
    @endif
    
    <span>{{ $slot }}</span>
</a>