<x-layouts.app title="Dashboard Cliente">
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl p-6 text-white">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold mb-2">Bem-vindo, {{ auth()->user()->name }}! 👋</h1>
                <p class="text-blue-100">Gerencie suas campanhas e acompanhe seus resultados</p>
            </div>
            <div class="bg-white/20 rounded-lg px-4 py-2">
                <span class="text-sm font-medium">Cliente</span>
            </div>
        </div>
    </div>

    <div class="mt-6 space-y-6">
        <!-- Estatísticas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Instâncias do WhatsApp -->
            <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <div class="flex items-center">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-100 text-green-600 dark:bg-green-900/20 dark:text-green-400">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Instâncias do WhatsApp</p>
                        <p class="text-2xl font-semibold text-neutral-900 dark:text-neutral-100">{{ $stats['active_instances'] }}</p>
                        <p class="text-xs text-neutral-500 dark:text-neutral-400">Conexões ativas do WhatsApp</p>
                    </div>
                </div>
            </div>

            <!-- Despedidas Realizadas -->
            <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <div class="flex items-center">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Despedidas Realizadas</p>
                        <p class="text-2xl font-semibold text-neutral-900 dark:text-neutral-100">{{ $stats['total_dispatches'] }}</p>
                        <p class="text-xs text-neutral-500 dark:text-neutral-400">Mensagens enviadas pelo sistema</p>
                    </div>
                </div>
            </div>

            <!-- Leads Encontrados -->
            <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <div class="flex items-center">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-purple-100 text-purple-600 dark:bg-purple-900/20 dark:text-purple-400">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Leads Encontrados</p>
                        <p class="text-2xl font-semibold text-neutral-900 dark:text-neutral-100">{{ $stats['leads_found'] }}</p>
                        <p class="text-xs text-neutral-500 dark:text-neutral-400">Leads capturados pelo Lead Hunter</p>
                    </div>
                </div>
            </div>

            <!-- Desempenho -->
            <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <div class="flex items-center">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-orange-100 text-orange-600 dark:bg-orange-900/20 dark:text-orange-400">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Desempenho</p>
                        <p class="text-2xl font-semibold text-neutral-900 dark:text-neutral-100">{{ $stats['performance_rate'] }}</p>
                        <p class="text-xs text-neutral-500 dark:text-neutral-400">Taxa de entrega das mensagens</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acesso Rápido -->
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-neutral-900 dark:text-neutral-100 mb-4">Acesso Rápido</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <a href="{{ route('client.evolution-manager') }}" class="group rounded-xl border border-neutral-200 bg-white p-6 shadow-sm transition-all hover:shadow-md hover:border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:hover:border-neutral-600">
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



            <a href="{{ route('client.lead-hunter') }}" class="group rounded-xl border border-neutral-200 bg-white p-6 shadow-sm transition-all hover:shadow-md hover:border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:hover:border-neutral-600">
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

            <a href="{{ route('client.gerenciar-arquivos') }}" class="group rounded-xl border border-neutral-200 bg-white p-6 shadow-sm transition-all hover:shadow-md hover:border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:hover:border-neutral-600">
                <div class="flex items-center">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-purple-100 text-purple-600 transition-colors group-hover:bg-purple-200 dark:bg-purple-900/20 dark:text-purple-400 dark:group-hover:bg-purple-900/30">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">Gerenciar Arquivos</h3>
                        <p class="text-neutral-600 dark:text-neutral-400">Upload e organização de arquivos</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('client.disparo-inteligente') }}" class="group rounded-xl border border-neutral-200 bg-white p-6 shadow-sm transition-all hover:shadow-md hover:border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:hover:border-neutral-600">
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

            <a href="{{ route('client.profile') }}" class="group rounded-xl border border-neutral-200 bg-white p-6 shadow-sm transition-all hover:shadow-md hover:border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:hover:border-neutral-600">
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