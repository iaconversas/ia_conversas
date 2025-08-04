<x-layouts.app title="Dashboard Cliente">
    <flux:header>
        <flux:heading size="xl">Dashboard Cliente</flux:heading>
        <flux:subheading>Bem-vindo, {{ auth()->user()->name }}!</flux:subheading>
    </flux:header>

    <div class="space-y-6">
        <!-- Estatísticas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <div class="flex items-center">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Total de Usuários</p>
                        <p class="text-2xl font-semibold text-neutral-900 dark:text-neutral-100">{{ $stats['total_users'] }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <div class="flex items-center">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-100 text-green-600 dark:bg-green-900/20 dark:text-green-400">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Minhas Roles</p>
                        <p class="text-sm text-neutral-900 dark:text-neutral-100">
                            @foreach($stats['my_roles'] as $role)
                                <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400 mr-1">
                                    {{ ucfirst($role) }}
                                </span>
                            @endforeach
                        </p>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <div class="flex items-center">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-purple-100 text-purple-600 dark:bg-purple-900/20 dark:text-purple-400">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Permissões</p>
                        <p class="text-2xl font-semibold text-neutral-900 dark:text-neutral-100">{{ count($stats['my_permissions']) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ferramentas WhatsApp -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <a href="{{ route('client.evolution-manager') }}" class="group rounded-xl border-l-4 border-l-orange-500 border border-neutral-200 bg-white p-6 shadow-sm transition-all hover:shadow-md hover:border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:hover:border-neutral-600">
                <div class="flex items-center">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-orange-100 text-orange-600 transition-colors group-hover:bg-orange-200 dark:bg-orange-900/20 dark:text-orange-400 dark:group-hover:bg-orange-900/30">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">Evolution Manager</h3>
                        <p class="text-neutral-600 dark:text-neutral-400">Gerenciar instâncias WhatsApp</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('client.disparo-inteligente') }}" class="group rounded-xl border-l-4 border-l-blue-500 border border-neutral-200 bg-white p-6 shadow-sm transition-all hover:shadow-md hover:border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:hover:border-neutral-600">
                <div class="flex items-center">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100 text-blue-600 transition-colors group-hover:bg-blue-200 dark:bg-blue-900/20 dark:text-blue-400 dark:group-hover:bg-blue-900/30">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">Disparo Inteligente</h3>
                        <p class="text-neutral-600 dark:text-neutral-400">Sistema de envio em massa</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('client.lead-hunter') }}" class="group rounded-xl border-l-4 border-l-green-500 border border-neutral-200 bg-white p-6 shadow-sm transition-all hover:shadow-md hover:border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:hover:border-neutral-600">
                <div class="flex items-center">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-100 text-green-600 transition-colors group-hover:bg-green-200 dark:bg-green-900/20 dark:text-green-400 dark:group-hover:bg-green-900/30">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">Lead Hunter</h3>
                        <p class="text-neutral-600 dark:text-neutral-400">Captura de leads</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('client.sim-warming') }}" class="group rounded-xl border-l-4 border-l-red-500 border border-neutral-200 bg-white p-6 shadow-sm transition-all hover:shadow-md hover:border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:hover:border-neutral-600">
                <div class="flex items-center">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-red-100 text-red-600 transition-colors group-hover:bg-red-200 dark:bg-red-900/20 dark:text-red-400 dark:group-hover:bg-red-900/30">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">SIM Warming</h3>
                        <p class="text-neutral-600 dark:text-neutral-400">Aquecimento de chips</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('client.gerenciar-arquivos') }}" class="group rounded-xl border-l-4 border-l-purple-500 border border-neutral-200 bg-white p-6 shadow-sm transition-all hover:shadow-md hover:border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:hover:border-neutral-600">
                <div class="flex items-center">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-purple-100 text-purple-600 transition-colors group-hover:bg-purple-200 dark:bg-purple-900/20 dark:text-purple-400 dark:group-hover:bg-purple-900/30">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">Gerenciar Arquivos</h3>
                        <p class="text-neutral-600 dark:text-neutral-400">Upload de mídia</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('client.profile') }}" class="group rounded-xl border-l-4 border-l-gray-500 border border-neutral-200 bg-white p-6 shadow-sm transition-all hover:shadow-md hover:border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:hover:border-neutral-600">
                <div class="flex items-center">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gray-100 text-gray-600 transition-colors group-hover:bg-gray-200 dark:bg-gray-900/20 dark:text-gray-400 dark:group-hover:bg-gray-900/30">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">Meu Perfil</h3>
                        <p class="text-neutral-600 dark:text-neutral-400">Gerenciar informações pessoais</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Minhas Permissões -->
        <div class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <div class="border-b border-neutral-200 px-6 py-4 dark:border-neutral-700">
                <h2 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">Minhas Permissões</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($stats['my_permissions'] as $permission)
                        <div class="flex items-center rounded-lg bg-neutral-50 p-3 dark:bg-neutral-800">
                            <div class="mr-3 flex h-8 w-8 items-center justify-center rounded-full bg-green-100 text-green-600 dark:bg-green-900/20 dark:text-green-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-neutral-900 dark:text-neutral-100">{{ $permission }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Informações do Usuário -->
        <div class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <div class="border-b border-neutral-200 px-6 py-4 dark:border-neutral-700">
                <h2 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">Informações do Usuário</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-neutral-500 dark:text-neutral-400 mb-2">Nome</h3>
                        <p class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">{{ $user->name }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-neutral-500 dark:text-neutral-400 mb-2">Email</h3>
                        <p class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">{{ $user->email }}</p>
                    </div>
                </div>
            </div>
        </div>
                    <div>
                        <h3 class="text-sm font-medium text-neutral-500 dark:text-neutral-400 mb-2">Tipo de Usuário</h3>
                        <span class="inline-flex rounded-full px-3 py-1 text-sm font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400">
                            Cliente
                        </span>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-neutral-500 dark:text-neutral-400 mb-2">Membro desde</h3>
                        <p class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">{{ $user->created_at->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-neutral-500 dark:text-neutral-400 mb-2">Último acesso</h3>
                        <p class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">{{ now()->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>