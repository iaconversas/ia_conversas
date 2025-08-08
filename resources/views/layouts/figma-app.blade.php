<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
</head>
<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <x-figma.sidebar class="hidden md:flex">
            <x-figma.sidebar-item href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" icon="home">
                Dashboard
            </x-figma.sidebar-item>
            
            <x-figma.sidebar-item href="#" icon="chart-bar">
                Analytics
            </x-figma.sidebar-item>
            
            <x-figma.sidebar-item href="#" icon="credit-card">
                Payments
            </x-figma.sidebar-item>
            
            <x-figma.sidebar-item href="#" icon="user">
                Profile
            </x-figma.sidebar-item>
            
            <x-figma.sidebar-item href="#" icon="cog">
                Settings
            </x-figma.sidebar-item>
        </x-figma.sidebar>

        <!-- Main Content -->
        <main class="flex-1 p-6 md:p-8 overflow-y-auto">
            {{ $slot }}
        </main>
    </div>
</body>
</html>