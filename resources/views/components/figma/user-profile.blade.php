@props([
    'name' => '',
    'location' => '',
    'avatar' => null,
    'followers' => 0,
    'following' => 0,
    'coverImage' => null
])

<div {{ $attributes->merge(['class' => 'figma-card figma-user-profile']) }}>
    @if($coverImage)
    <div class="figma-user-cover">
        <div class="w-full h-full bg-gray-200 flex items-center justify-center">🖼️ Cover</div>
    </div>
    @endif
    
    <div class="figma-avatar">
        @if($avatar)
            <div class="w-full h-full bg-gray-300 flex items-center justify-center rounded-full text-2xl">👤</div>
        @else
            <div class="w-full h-full flex items-center justify-center bg-primary-light text-primary rounded-full">
                {{ substr($name, 0, 1) }}
            </div>
        @endif
    </div>
    
    <h3 class="figma-card-title text-center mt-4">{{ $name }}</h3>
    
    @if($location)
    <div class="flex items-center justify-center gap-1 mt-1">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
        </svg>
        <span class="figma-card-body">{{ $location }}</span>
    </div>
    @endif
    
    <div class="figma-stats">
        <div class="figma-stat-item">
            <span class="figma-stat-value">{{ $following }}</span>
            <span class="figma-stat-label">Following</span>
        </div>
        
        <div class="figma-stat-item">
            <span class="figma-stat-value">{{ $followers }}</span>
            <span class="figma-stat-label">Followers</span>
        </div>
    </div>
    
    {{ $slot }}
</div>