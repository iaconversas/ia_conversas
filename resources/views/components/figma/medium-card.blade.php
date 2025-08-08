@props([
    'title' => '',
    'body' => '',
    'value' => '',
    'icon' => null,
    'buttonText' => null,
    'buttonUrl' => '#',
    'type' => 'default' // default, earnings, security, safety, user, transactions, calendar
])

<div {{ $attributes->merge(['class' => 'figma-card ' . ($type ? 'figma-card-' . $type : '')]) }}>
    @if($icon)
    <div class="figma-icon-container">
        <div class="w-6 h-6 flex items-center justify-center text-lg">📄</div>
    </div>
    @endif
    
    <div class="space-y-2">
        <h3 class="figma-card-title">{{ $title }}</h3>
        
        @if($body)
        <p class="figma-card-body">{{ $body }}</p>
        @endif
    </div>
    
    @if($value)
    <p class="figma-card-value mt-2">{{ $value }}</p>
    @endif
    
    @if($buttonText)
    <div class="mt-auto pt-4">
        <a href="{{ $buttonUrl }}" class="figma-button block text-center">
            {{ $buttonText }}
        </a>
    </div>
    @endif
    
    {{ $slot }}
</div>