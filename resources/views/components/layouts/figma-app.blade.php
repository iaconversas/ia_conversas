<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
        <!-- Figma Fonts -->
        @include('partials.figma-fonts')
    </head>
    <body class="min-h-screen bg-gray-50 dark:bg-zinc-800">
        <div class="flex min-h-screen">
            <!-- Figma Sidebar -->
            <x-figma.sidebar class="hidden lg:flex lg:w-64 h-screen sticky top-0">
                <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse mb-6" wire:navigate>
                    <x-app-logo />
                </a>
                
                <x-figma.sidebar-item 
                    icon="home" 
                    :href="route('dashboard')" 
                    :active="request()->routeIs('dashboard')"
                >
                    {{ __('Dashboard') }}
                </x-figma.sidebar-item>
                
                @if(auth()->user()->hasRole('admin'))
                    <x-figma.sidebar-item 
                        icon="users" 
                        :href="route('admin.users')" 
                        :active="request()->routeIs('admin.users')"
                    >
                        {{ __('Users') }}
                    </x-figma.sidebar-item>
                    
                    <x-figma.sidebar-item 
                        icon="cog-6-tooth" 
                        :href="route('admin.settings')" 
                        :active="request()->routeIs('admin.settings')"
                    >
                        {{ __('Settings') }}
                    </x-figma.sidebar-item>
                    
                    <x-figma.sidebar-item 
                        icon="cloud" 
                        :href="route('admin.evolution-api')" 
                        :active="request()->routeIs('admin.evolution-api')"
                    >
                        {{ __('Evolution API') }}
                    </x-figma.sidebar-item>
                @endif
                
                @if(auth()->user()->hasRole('client'))
                    <x-figma.sidebar-item 
                        icon="device-phone-mobile" 
                        :href="route('client.evolution-manager')" 
                        :active="request()->routeIs('client.evolution-manager')"
                    >
                        {{ __('Evolution Manager') }}
                    </x-figma.sidebar-item>
                    

                    
                    <x-figma.sidebar-item 
                        icon="magnifying-glass" 
                        :href="route('client.lead-hunter')" 
                        :active="request()->routeIs('client.lead-hunter')"
                    >
                        {{ __('Lead Hunter') }}
                    </x-figma.sidebar-item>
                    
                    <x-figma.sidebar-item 
                        icon="folder" 
                        :href="route('client.gerenciar-arquivos')" 
                        :active="request()->routeIs('client.gerenciar-arquivos')"
                    >
                        {{ __('Gerenciar Arquivos') }}
                    </x-figma.sidebar-item>
                    
                    <x-figma.sidebar-item 
                        icon="paper-airplane" 
                        :href="route('client.disparo-inteligente')" 
                        :active="request()->routeIs('client.disparo-inteligente')"
                    >
                        {{ __('Disparo Inteligente') }}
                    </x-figma.sidebar-item>
                    
                    <x-figma.sidebar-item 
                        icon="user" 
                        :href="route('client.profile')" 
                        :active="request()->routeIs('client.profile')"
                    >
                        {{ __('My Profile') }}
                    </x-figma.sidebar-item>
                @endif
                
                <div class="mt-auto">
                    <!-- Theme Toggle -->
                    <div class="px-3 py-2">
                        <x-theme-toggle />
                    </div>
                    
                    <!-- User Profile -->
                    <div class="mt-4 px-3 py-2">
                        <div class="flex items-center gap-2">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>
                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </x-figma.sidebar>
            
            <!-- Main Content -->
            <div class="flex-1 p-6">
                @if($title ?? false)
                    <h1 class="text-2xl font-bold mb-6">{{ $title }}</h1>
                @endif
                
                {{ $slot }}
            </div>
        </div>
    </body>
</html>