<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">
        <!-- Welcome Section -->
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
        
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl p-6 border border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium text-gray-600">Instâncias do WhatsApp</h3>
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-bold text-gray-900 mb-1">2</div>
                <div class="text-sm text-green-600">✓ Conexões ativas do WhatsApp</div>
            </div>
            
            <div class="bg-white rounded-xl p-6 border border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium text-gray-600">Despedidas Realizadas</h3>
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-bold text-gray-900 mb-1">0</div>
                <div class="text-sm text-gray-500">Mensagens enviadas pelo sistema</div>
            </div>
            
            <div class="bg-white rounded-xl p-6 border border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium text-gray-600">Leads Encontrados</h3>
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 9a2 2 0 114 0 2 2 0 01-4 0z"/>
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a4 4 0 00-4 4 2 2 0 11-4 0 6 6 0 1112 0 2 2 0 11-4 0 4 4 0 00-4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-bold text-gray-900 mb-1">0</div>
                <div class="text-sm text-gray-500">Leads capturados pelo Lead Hunter</div>
            </div>
            
            <div class="bg-white rounded-xl p-6 border border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium text-gray-600">Desempenho</h3>
                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-bold text-gray-900 mb-1">98,5%</div>
                <div class="text-sm text-orange-600">Taxa de entrega das mensagens</div>
            </div>
        </div>
        </div>
        
        <!-- Main Modules -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Disparo Inteligente -->
            <div class="bg-white rounded-xl p-6 border border-gray-200">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Disparo Inteligente</h3>
                </div>
                <p class="text-gray-600 mb-6">Envie mensagens personalizadas em massa</p>
                <a href="{{ route('disparo.index') }}" class="w-full bg-black text-white py-3 px-4 rounded-lg font-medium hover:bg-gray-800 transition-colors duration-200 flex items-center justify-center">
                    Acessar
                </a>
            </div>
            
            <!-- Caçador de Leads -->
            <div class="bg-white rounded-xl p-6 border border-gray-200">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 9a2 2 0 114 0 2 2 0 01-4 0z"/>
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a4 4 0 00-4 4 2 2 0 11-4 0 6 6 0 1112 0 2 2 0 11-4 0 4 4 0 00-4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Caçador de Leads</h3>
                </div>
                <p class="text-gray-600 mb-6">Encontre e capture leads</p>
                <button class="w-full bg-black text-white py-3 px-4 rounded-lg font-medium hover:bg-gray-800 transition-colors duration-200">
                    Acessar
                </button>
            </div>
            
            <!-- Gerente de Evolução -->
            <div class="bg-white rounded-xl p-6 border border-gray-200">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Gerente de Evolução</h3>
                </div>
                <p class="text-gray-600 mb-6">Gerencie suas instâncias do WhatsApp</p>
                <button class="w-full bg-black text-white py-3 px-4 rounded-lg font-medium hover:bg-gray-800 transition-colors duration-200">
                    Acessar
                </button>
            </div>
        </div>
    </div>
</x-layouts.app>
