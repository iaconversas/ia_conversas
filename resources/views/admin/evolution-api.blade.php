<x-layouts.app>
    <flux:heading size="xl">Configurações da Evolution API</flux:heading>
    <flux:subheading>Gerencie as configurações de conexão com a Evolution API e n8n</flux:subheading>
    
    <div class="mt-6 space-y-6">
        <div class="flex items-center justify-between">
            <div></div>
            
            <div class="flex items-center space-x-2">
                    @if($isConfigured)
                        <flux:badge color="green" size="sm">
                            <flux:icon.check-circle class="size-4" />
                            Configurado
                        </flux:badge>
                    @else
                        <flux:badge color="red" size="sm">
                            <flux:icon.exclamation-triangle class="size-4" />
                            Não Configurado
                        </flux:badge>
                    @endif
                </div>
            </div>
        </div>

        @if(session('success'))
            <flux:banner variant="success">
                {{ session('success') }}
            </flux:banner>
        @endif

        @if($errors->any())
            <flux:banner variant="danger">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </flux:banner>
        @endif

        <!-- Status das Configurações -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <flux:card>
                <div class="flex items-center space-x-3">
                    <div class="p-2 rounded-lg {{ !empty($evolutionSettings['api_url']) ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                        <flux:icon.globe-alt class="size-6" />
                    </div>
                    <div>
                        <flux:heading size="sm">URL da Evolution API</flux:heading>
                        <flux:subheading class="text-xs">
                            {{ !empty($evolutionSettings['api_url']) ? 'Configurado' : 'Não configurado' }}
                        </flux:subheading>
                    </div>
                </div>
                @if(!empty($evolutionSettings['api_url']))
                    <div class="mt-3 p-2 bg-gray-50 dark:bg-gray-800 rounded text-sm font-mono">
                        {{ $evolutionSettings['api_url'] }}
                    </div>
                @endif
            </flux:card>

            <flux:card>
                <div class="flex items-center space-x-3">
                    <div class="p-2 rounded-lg {{ !empty($evolutionSettings['api_key']) ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                        <flux:icon.key class="size-6" />
                    </div>
                    <div>
                        <flux:heading size="sm">API Key</flux:heading>
                        <flux:subheading class="text-xs">
                            {{ !empty($evolutionSettings['api_key']) ? 'Configurado' : 'Não configurado' }}
                        </flux:subheading>
                    </div>
                </div>
                @if(!empty($evolutionSettings['api_key']))
                    <div class="mt-3 p-2 bg-gray-50 dark:bg-gray-800 rounded text-sm font-mono">
                        {{ str_repeat('*', strlen($evolutionSettings['api_key']) - 8) . substr($evolutionSettings['api_key'], -8) }}
                    </div>
                @endif
            </flux:card>

            <flux:card>
                <div class="flex items-center space-x-3">
                    <div class="p-2 rounded-lg {{ !empty($evolutionSettings['n8n_url']) ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                        <flux:icon.link class="size-6" />
                    </div>
                    <div>
                        <flux:heading size="sm">URL do n8n</flux:heading>
                        <flux:subheading class="text-xs">
                            {{ !empty($evolutionSettings['n8n_url']) ? 'Configurado' : 'Não configurado' }}
                        </flux:subheading>
                    </div>
                </div>
                @if(!empty($evolutionSettings['n8n_url']))
                    <div class="mt-3 p-2 bg-gray-50 dark:bg-gray-800 rounded text-sm font-mono">
                        {{ $evolutionSettings['n8n_url'] }}
                    </div>
                @endif
            </flux:card>


        </div>

        <!-- Formulário de Configuração -->
        <flux:card class="border-l-4 border-l-blue-500">
            <flux:card.header>
                <flux:heading size="lg">Atualizar Configurações</flux:heading>
                <flux:subheading>Configure ou atualize as informações de conexão</flux:subheading>
            </flux:card.header>

            <form method="POST" action="{{ route('admin.evolution-settings.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <flux:field>
                        <flux:label for="evolution_api_url">URL da Evolution API *</flux:label>
                        <flux:input 
                            id="evolution_api_url" 
                            name="evolution_api_url" 
                            type="url"
                            placeholder="https://evolution.seudominio.com"
                            value="{{ old('evolution_api_url', $evolutionSettings['api_url']) }}"
                            required
                        />
                        <flux:description>URL base da sua instância Evolution API</flux:description>
                        @error('evolution_api_url')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </flux:field>

                    <flux:field>
                        <flux:label for="n8n_webhook_url">URL do n8n *</flux:label>
                        <flux:input 
                            id="n8n_webhook_url" 
                            name="n8n_webhook_url" 
                            type="url"
                            placeholder="https://n8n.seudominio.com"
                            value="{{ old('n8n_webhook_url', $evolutionSettings['n8n_url']) }}"
                            required
                        />
                        <flux:description>URL base da sua instância n8n</flux:description>
                        @error('n8n_webhook_url')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </flux:field>
                </div>

                <flux:field>
                        <flux:label for="evolution_api_key">API Key da Evolution *</flux:label>
                        <flux:input 
                            id="evolution_api_key" 
                            name="evolution_api_key" 
                            type="password"
                            placeholder="Digite sua API Key"
                            value="{{ old('evolution_api_key', $evolutionSettings['api_key']) }}"
                            required
                        />
                        <flux:description>Chave de API para autenticação com a Evolution API</flux:description>
                        @error('evolution_api_key')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </flux:field>



                <div class="flex items-center justify-between pt-4 border-t">
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        <flux:icon.information-circle class="size-4 inline" />
                        Todas as configurações são obrigatórias para o funcionamento correto
                    </div>
                    
                    <flux:button type="submit" variant="primary">
                        <flux:icon.check class="size-4" />
                        Salvar Configurações
                    </flux:button>
                </div>
            </form>
        </flux:card>

        <!-- Teste de Conexão -->
        @if($isConfigured)
            <flux:card class="border-l-4 border-l-green-500">
                <flux:card.header>
                    <flux:heading size="lg">Teste de Conexão</flux:heading>
                    <flux:subheading>Verifique se as configurações estão funcionando</flux:subheading>
                </flux:card.header>

                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <flux:heading size="sm">Status da Evolution API</flux:heading>
                            <flux:subheading class="text-xs">Verificar conectividade com a API</flux:subheading>
                        </div>
                        <flux:button variant="outline" size="sm" onclick="testEvolutionConnection()">
                            <flux:icon.signal class="size-4" />
                            Testar Conexão
                        </flux:button>
                    </div>

                    <div id="connectionTest" class="hidden">
                        <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <div id="testResult" class="text-sm"></div>
                        </div>
                    </div>
                </div>
            </flux:card>
        @endif

        <!-- Instruções -->
        <flux:card class="border-l-4 border-l-yellow-500">
            <flux:card.header>
                <flux:heading size="lg">Instruções de Configuração</flux:heading>
            </flux:card.header>

            <div class="space-y-4 text-sm">
                <div>
                    <flux:heading size="sm" class="mb-2">1. URL da Evolution API</flux:heading>
                    <p class="text-gray-600 dark:text-gray-400 mb-2">Insira a URL completa da sua instância Evolution API, incluindo o protocolo (https://).</p>
                    <div class="p-2 bg-gray-50 dark:bg-gray-800 rounded font-mono text-xs">
                        Exemplo: https://evolution.seudominio.com
                    </div>
                </div>

                <div>
                    <flux:heading size="sm" class="mb-2">2. API Key</flux:heading>
                    <p class="text-gray-600 dark:text-gray-400 mb-2">Chave de autenticação gerada na sua instância Evolution API.</p>
                    <div class="p-2 bg-gray-50 dark:bg-gray-800 rounded font-mono text-xs">
                        Exemplo: 5863c643c8bf6d84e8da8bb564ea13fc
                    </div>
                </div>

                <div>
                    <flux:heading size="sm" class="mb-2">3. URL do n8n</flux:heading>
                    <p class="text-gray-600 dark:text-gray-400 mb-2">URL base da sua instância n8n para webhooks e automações.</p>
                    <div class="p-2 bg-gray-50 dark:bg-gray-800 rounded font-mono text-xs">
                        Exemplo: https://n8n.seudominio.com
                    </div>
                </div>
            </div>
        </flux:card>
    </div>

    <script>
        async function testEvolutionConnection() {
            const testContainer = document.getElementById('connectionTest');
            const testResult = document.getElementById('testResult');
            
            testContainer.classList.remove('hidden');
            testResult.innerHTML = '<div class="flex items-center"><svg class="animate-spin h-4 w-4 mr-2" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Testando conexão...</div>';
            
            try {
                const response = await fetch('{{ $evolutionSettings["api_url"] }}/instance/fetchInstances', {
                    headers: {
                        'apikey': '{{ $evolutionSettings["api_key"] }}',
                        'Content-Type': 'application/json'
                    }
                });
                
                if (response.ok) {
                    testResult.innerHTML = '<div class="flex items-center text-green-600"><svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>Conexão bem-sucedida!</div>';
                } else {
                    testResult.innerHTML = '<div class="flex items-center text-red-600"><svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>Erro na conexão: ' + response.status + '</div>';
                }
            } catch (error) {
                testResult.innerHTML = '<div class="flex items-center text-red-600"><svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>Erro de conexão: ' + error.message + '</div>';
            }
        }
    </script>
</x-layouts.app>