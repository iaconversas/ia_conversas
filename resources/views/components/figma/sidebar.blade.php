<div {{ $attributes->merge(['class' => 'figma-sidebar']) }}>
    <div class="flex items-center gap-3 px-4 py-2">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-2" wire:navigate>
            <x-app-logo />
        </a>
    </div>
    
    <div class="flex flex-col gap-2">
        {{ $slot }}
    </div>
</div>