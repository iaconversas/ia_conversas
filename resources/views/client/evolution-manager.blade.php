<x-layouts.app>
    <flux:heading size="xl">Evolution Manager</flux:heading>
    <flux:subheading>Gerenciamento de Instâncias WhatsApp</flux:subheading>

    <div class="mt-6">
        <flux:tab.group>
            <flux:tabs variant="segmented" wire:model="activeTab">
                <flux:tab name="qr-code">Gerar QR Code</flux:tab>
                <flux:tab name="webhook">Atualizar Webhook</flux:tab>
                <flux:tab name="manage">Gerenciar Instâncias</flux:tab>
            </flux:tabs>

            <flux:tab.panel name="qr-code">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" style="margin-bottom: 20px;">
                    <!-- Configuração da Instância -->
                    <flux:card style="margin-bottom: 20px; margin-top: 20px;">
                        <flux:card.header>
                            <flux:heading size="lg">Configuração da Instância</flux:heading>
                            <flux:subheading>Crie uma nova instância WhatsApp</flux:subheading>
                        </flux:card.header>

                        <div class="space-y-4">
                            <flux:field>
                                <flux:label>Nome da Instância</flux:label>
                                <flux:input 
                                    id="instanceName" 
                                    placeholder="Nome da instância Evolution API"
                                    class="w-full"
                                />
                            </flux:field>

                            <flux:button 
                                variant="primary" 
                                size="sm"
                                onclick="generateQRCode()"
                                id="generateBtn"
                                class="w-full"
                            >
                                <flux:icon.qr-code class="size-4" />
                                Gerar QR Code
                            </flux:button>

                            <div id="loadingState" class="hidden">
                                <div class="flex items-center justify-center p-6">
                                    <flux:icon.arrow-path class="size-6 animate-spin text-orange-500" />
                                    <span class="ml-2">Gerando QR Code...</span>
                                </div>
                            </div>

                            <div id="errorState" class="hidden">
                                <flux:banner variant="danger">
                                    <span id="errorMessage"></span>
                                </flux:banner>
                            </div>

                            <div id="successState" class="hidden">
                                <flux:banner variant="success" id="successMessage">
                                    Instância conectada com sucesso!
                                </flux:banner>
                            </div>
                        </div>
                    </flux:card>

                    <!-- QR Code WhatsApp -->
                    <flux:card style="margin-bottom: 20px;">
                        <flux:card.header>
                            <flux:heading size="lg">QR Code WhatsApp</flux:heading>
                            <flux:subheading>Escaneie o código para conectar</flux:subheading>
                        </flux:card.header>

                        <div class="flex flex-col items-center justify-center min-h-[300px] space-y-4">
                            <div id="qrCodeContainer" class="hidden">
                                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 text-center border">
                                    <div id="qrCodeDisplay" class="mb-4"></div>
                                    <div id="qrTimer" class="text-sm text-gray-600 dark:text-gray-400"></div>
                                </div>
                            </div>
                            
                            <div id="qrPlaceholder" class="text-center text-gray-500 dark:text-gray-400">
                                <div class="w-32 h-32 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                    <flux:icon.qr-code class="size-16" style="color: #155DFC" />
                                </div>
                                <p>Gere um QR Code para começar</p>
                            </div>
                        </div>
                    </flux:card>
                </div>
            </flux:tab.panel>

            <flux:tab.panel name="webhook">
                <div class="space-y-6" style="margin-bottom: 20px;">
                    <flux:card style="margin-bottom: 20px;">
                        <flux:card.header>
                            <flux:heading size="lg">Atualizar Webhook</flux:heading>
                            <flux:subheading>Configure webhooks para suas instâncias</flux:subheading>
                        </flux:card.header>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <flux:heading size="sm">Instâncias Disponíveis</flux:heading>
                                <flux:button 
                                    variant="ghost" 
                                    size="sm"
                                    onclick="loadInstances()"
                                >
                                    <flux:icon.arrow-path class="size-4" />
                                    Atualizar Lista
                                </flux:button>
                            </div>

                            <div class="flex items-center space-x-4">
                                <flux:checkbox id="connectedOnly" />
                                <flux:label for="connectedOnly">Mostrar apenas instâncias conectadas</flux:label>
                            </div>

                            <div class="flex items-center space-x-2 mb-3">
                                <flux:button 
                                    variant="outline" 
                                    size="sm"
                                    onclick="selectConnectedInstances()"
                                >
                                    Marcar Todas Conectadas
                                </flux:button>
                                <flux:button 
                                    variant="ghost" 
                                    size="sm"
                                    onclick="clearAllInstances()"
                                >
                                    Desmarcar Todas
                                </flux:button>
                            </div>

                            <div id="instancesList" class="space-y-2 max-h-60 overflow-y-auto">
                                <!-- Instâncias serão carregadas aqui -->
                            </div>

                            <flux:field>
                                <flux:label>URL do Webhook</flux:label>
                                <flux:input 
                                    id="webhookUrl" 
                                    placeholder="https://seu-webhook.com/endpoint"
                                    class="w-full"
                                />
                            </flux:field>

                            <flux:button 
                                variant="primary" 
                                size="sm"
                                onclick="updateWebhooks()"
                                id="updateWebhookBtn"
                            >
                                <flux:icon.link class="size-4" />
                                Atualizar Webhooks
                            </flux:button>

                            <div id="webhookResults" class="hidden space-y-2">
                                <!-- Resultados serão exibidos aqui -->
                            </div>
                        </div>
                    </flux:card>
                </div>
            </flux:tab.panel>

            <flux:tab.panel name="manage">
                <div class="space-y-6" style="margin-bottom: 20px;">
                    <flux:card style="margin-bottom: 20px;">
                        <flux:card.header>
                            <div class="flex items-center justify-between">
                                <div>
                                    <flux:heading size="lg">Gerenciar Instâncias</flux:heading>
                                    <flux:subheading>Visualize e gerencie suas instâncias</flux:subheading>
                                </div>
                                <flux:button 
                                    variant="ghost" 
                                    size="sm"
                                    onclick="refreshInstances()"
                                >
                                    <flux:icon.arrow-path class="size-4" />
                                    Atualizar
                                </flux:button>
                            </div>
                        </flux:card.header>

                        <div id="instancesTable">
                            <!-- Tabela de instâncias será carregada aqui -->
                        </div>
                    </flux:card>
                </div>
            </flux:tab.panel>



        </flux:tab.group>
    </div>

    <!-- Modal de Confirmação -->
    <div id="confirmModal" class="hidden fixed inset-0 flex items-center justify-center z-50" style="background-color: rgba(0, 0, 0, 0.25);">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full mx-4">
            <flux:heading size="lg" class="mb-4">Confirmar Ação</flux:heading>
            <p id="confirmMessage" class="text-gray-600 dark:text-gray-400 mb-6"></p>
            <div class="flex space-x-3">
                <flux:button variant="danger" size="sm" onclick="confirmAction()">
                    Confirmar
                </flux:button>
                <flux:button variant="ghost" size="sm" onclick="closeModal()">
                    Cancelar
                </flux:button>
            </div>
        </div>
    </div>

    <script>
        // Configurações do backend
        if (typeof config === 'undefined') {
            var config = @json($config);
        }
        
        let activeTab = 'qr-code';
        let qrTimer = null;
        let pendingAction = null;

        // Gerar QR Code
        async function generateQRCode() {
            const instanceName = document.getElementById('instanceName').value;
            if (!instanceName) {
                showError('Por favor, digite um nome para a instância');
                return;
            }

            // Verificar se as configurações estão disponíveis
            if (!config || !config.n8n_url) {
                showError('Configurações do n8n não encontradas. Verifique as configurações do sistema.');
                return;
            }

            console.log('Iniciando geração de QR Code para instância:', instanceName);
            console.log('URL do n8n:', config.n8n_url);
            showLoading();
            
            try {
                const response = await fetch(config.n8n_url + '/webhook/criar-instancia-evolution', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ instanceName })
                });

                console.log('Generate QR - Response Status:', response.status, response.statusText);
                
                // Verificar se a resposta é válida
                if (!response.ok && response.status >= 500) {
                    throw new Error(`Erro do servidor: ${response.status} ${response.statusText}`);
                }

                // Verificar o Content-Type da resposta
                const contentType = response.headers.get('content-type');
                console.log('Generate QR - Content-Type:', contentType);
                let data;
                
                if (contentType && contentType.includes('application/json')) {
                    const text = await response.text();
                    console.log('Update QR - JSON Response text:', text.substring(0, 200));
                    
                    // Verificar se a resposta não está vazia
                    if (!text || text.trim() === '') {
                        console.log('Resposta JSON vazia do servidor ao atualizar QR Code - Instância já conectada');
                        clearInterval(qrTimer);
                        showSuccess('Instância já está conectada!');
                        return;
                    }
                    
                    try {
                        data = JSON.parse(text);
                        debugResponse(response, data);
                    } catch (e) {
                        console.error('Erro ao fazer parse JSON da resposta:', e);
                        console.error('Texto da resposta JSON:', text);
                        return;
                    }
                } else if (contentType && contentType.includes('image/')) {
                    // Se a resposta é uma imagem, converter para base64
                    console.log('Resposta é uma imagem, convertendo para base64...');
                    const blob = await response.blob();
                    const reader = new FileReader();
                    reader.onload = function() {
                        const base64 = reader.result;
                        console.log('Base64 gerado:', base64.substring(0, 50) + '...');
                        displayQRCode({ qrcode: base64 });
                        startQRTimer();
                    };
                    reader.readAsDataURL(blob);
                    return;
                } else {
                    // Tentar como texto primeiro
                    const text = await response.text();
                    console.log('Resposta como texto:', text.substring(0, 200));
                    
                    // Verificar se a resposta não está vazia
                    if (!text || text.trim() === '') {
                        throw new Error('Resposta vazia do servidor');
                    }
                    
                    try {
                        data = JSON.parse(text);
                        debugResponse(response, data);
                    } catch (e) {
                        // Se não conseguir fazer parse como JSON, tratar como erro
                        console.error('Erro ao fazer parse JSON:', e);
                        console.error('Texto da resposta:', text);
                        throw new Error('Resposta inválida do servidor. Status: ' + response.status + ', Texto: ' + text.substring(0, 100));
                    }
                }
                
                if (response.ok) {
                    // Verificar se a instância já está conectada
                    if (data.connected) {
                        showSuccess('Instância já está conectada!');
                    } else {
                        displayQRCode(data);
                        startQRTimer();
                    }
                } else {
                    showError(data.message || 'Erro ao gerar QR Code');
                }
            } catch (error) {
                showError('Erro de conexão: ' + error.message);
            }
        }

        function displayQRCode(data) {
            hideLoading();
            const container = document.getElementById('qrCodeContainer');
            const display = document.getElementById('qrCodeDisplay');
            const placeholder = document.getElementById('qrPlaceholder');
            
            // Suporte a diferentes formatos de resposta
            if (data.qrcode) {
                if (data.qrcode.startsWith('data:image')) {
                    // Já é um data URL completo
                    display.innerHTML = `<div class="bg-white p-4 rounded inline-block"><div class="text-center p-4">📱 QR Code disponível</div></div>`;
                } else if (data.qrcode.startsWith('/9j/') || data.qrcode.startsWith('iVBORw0KGgo')) {
                    // É base64 puro (JPEG ou PNG)
                    const mimeType = data.qrcode.startsWith('/9j/') ? 'jpeg' : 'png';
                    display.innerHTML = `<div class="bg-white p-4 rounded inline-block"><div class="text-center p-4">📱 QR Code disponível</div></div>`;
                } else {
                    // Assumir que é base64 PNG por padrão
                    display.innerHTML = `<div class="bg-white p-4 rounded inline-block"><div class="text-center p-4">📱 QR Code disponível</div></div>`;
                }
            } else if (data.base64) {
                // Campo base64 separado
                display.innerHTML = `<div class="bg-white p-4 rounded inline-block"><div class="text-center p-4">📱 QR Code disponível</div></div>`;
            } else if (data.image) {
                // Campo image
                display.innerHTML = `<div class="bg-white p-4 rounded inline-block"><div class="text-center p-4">📱 QR Code disponível</div></div>`;
            } else {
                display.innerHTML = '<p class="text-gray-500">QR Code gerado com sucesso</p>';
            }
            
            container.classList.remove('hidden');
            placeholder.classList.add('hidden');
        }

        function startQRTimer() {
            let seconds = 30;
            const timerElement = document.getElementById('qrTimer');
            
            qrTimer = setInterval(async () => {
                timerElement.textContent = `Atualizando em ${seconds}s`;
                seconds--;
                
                if (seconds < 0) {
                    await updateQRCode();
                    seconds = 30;
                }
            }, 1000);
        }

        async function updateQRCode() {
            try {
                const response = await fetch(config.n8n_url + '/webhook/atualiza-qrcode', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ 
                        instanceName: document.getElementById('instanceName').value 
                    })
                });

                console.log('Update QR - Response Status:', response.status, response.statusText);
                
                // Verificar se a resposta é válida
                if (!response.ok && response.status >= 500) {
                    console.error(`Erro do servidor ao atualizar QR: ${response.status} ${response.statusText}`);
                    return;
                }

                // Verificar o Content-Type da resposta
                const contentType = response.headers.get('content-type');
                let data;
                
                if (contentType && contentType.includes('application/json')) {
                    const text = await response.text();
                    console.log('Update QR - JSON Response text:', text.substring(0, 200));
                    
                    // Verificar se a resposta não está vazia
                    if (!text || text.trim() === '') {
                        console.error('Resposta JSON vazia do servidor ao atualizar QR Code');
                        return;
                    }
                    
                    try {
                        data = JSON.parse(text);
                    } catch (e) {
                        console.error('Erro ao fazer parse JSON da resposta:', e);
                        console.error('Texto da resposta JSON:', text);
                        return;
                    }
                } else if (contentType && contentType.includes('image/')) {
                    // Se a resposta é uma imagem, converter para base64
                    const blob = await response.blob();
                    const reader = new FileReader();
                    reader.onload = function() {
                        const base64 = reader.result;
                        displayQRCode({ qrcode: base64 });
                    };
                    reader.readAsDataURL(blob);
                    return;
                } else {
                    // Tentar como texto primeiro
                    const text = await response.text();
                    console.log('Update QR - Resposta como texto:', text.substring(0, 200));
                    
                    // Verificar se a resposta não está vazia
                    if (!text || text.trim() === '') {
                        console.error('Resposta vazia do servidor ao atualizar QR Code');
                        return;
                    }
                    
                    try {
                        data = JSON.parse(text);
                    } catch (e) {
                        console.error('Erro ao fazer parse da resposta:', e);
                        console.error('Texto da resposta:', text);
                        return;
                    }
                }
                
                if (data.connected) {
                    clearInterval(qrTimer);
                    showSuccess('Instância já está conectada!');
                } else if (data.qrcode) {
                    displayQRCode(data);
                }
            } catch (error) {
                console.error('Erro ao atualizar QR Code:', error);
            }
        }

        // Carregar instâncias
        async function loadInstances() {
            try {
                console.log('Carregando instâncias...');
                console.log('Evolution URL:', config.evolution_url);
                console.log('API Key:', config.has_evolution_api_key ? 'Configurada' : 'Não configurada');
                
                const response = await fetch('/client/api/instances', {
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                console.log('Load Instances - Response Status:', response.status, response.statusText);
                
                if (!response.ok) {
                    throw new Error(`Erro HTTP: ${response.status} ${response.statusText}`);
                }
                
                const text = await response.text();
                console.log('Load Instances - Response text:', text.substring(0, 500));
                
                if (!text || text.trim() === '') {
                    console.error('Resposta vazia ao carregar instâncias');
                    displayInstancesList([]);
                    return;
                }
                
                let instances;
                try {
                    const responseData = JSON.parse(text);
                    console.log('Dados da resposta completa:', responseData);
                    
                    // Verificar se a resposta tem a estrutura esperada
                    if (responseData.success && responseData.instances) {
                        // Resposta do WhatsAppController
                        instances = responseData.instances;
                    } else if (Array.isArray(responseData)) {
                        // Resposta direta da Evolution API
                        instances = responseData;
                    } else {
                        console.error('Estrutura de resposta não reconhecida:', responseData);
                        instances = [];
                    }
                    
                    console.log('Instâncias extraídas:', instances);
                    // Definir instâncias globalmente para uso em outras funções
                    window.instances = instances;
                } catch (e) {
                    console.error('Erro ao fazer parse JSON das instâncias:', e);
                    console.error('Texto da resposta:', text);
                    window.instances = [];
                    displayInstancesList([]);
                    return;
                }
                
                displayInstancesList(instances);
            } catch (error) {
                console.error('Erro ao carregar instâncias:', error);
                displayInstancesList([]);
            }
        }

        function displayInstancesList(instances) {
            console.log('Exibindo lista de instâncias:', instances);
            const container = document.getElementById('instancesList');
            const connectedOnly = document.getElementById('connectedOnly').checked;
            
            // Verificar se instances é válido
            if (!instances) {
                console.error('Instâncias é null ou undefined:', instances);
                container.innerHTML = '<div class="p-4 text-center text-gray-500">Nenhuma instância encontrada</div>';
                return;
            }
            
            // Se instances é um objeto com propriedade instances, extrair o array
            let instancesArray = instances;
            if (typeof instances === 'object' && !Array.isArray(instances)) {
                if (instances.success && instances.instances) {
                    instancesArray = instances.instances;
                    console.log('Extraindo array de instâncias do objeto de resposta:', instancesArray);
                } else {
                    console.error('Objeto de instâncias não tem a estrutura esperada:', instances);
                    container.innerHTML = '<div class="p-4 text-center text-red-500">Estrutura de resposta inválida</div>';
                    return;
                }
            }
            
            if (!Array.isArray(instancesArray)) {
                console.error('Instâncias não é um array após processamento:', instancesArray);
                container.innerHTML = '<div class="p-4 text-center text-red-500">Dados de instâncias inválidos</div>';
                return;
            }
            
            if (instancesArray.length === 0) {
                console.log('Array de instâncias está vazio');
                container.innerHTML = '<div class="p-4 text-center text-gray-500">Nenhuma instância encontrada</div>';
                return;
            }
            
            console.log('Filtro "Apenas conectadas":', connectedOnly);
            
            const filteredInstances = connectedOnly ? 
                instancesArray.filter(instance => {
                    const isConnected = instance.connectionStatus === 'open';
                    console.log(`Instância ${instance.instance?.instanceName || instance.name || 'sem nome'}: ${instance.connectionStatus} (conectada: ${isConnected})`);
                    return isConnected;
                }) : 
                instancesArray;
                
            console.log('Instâncias filtradas:', filteredInstances);
            
            if (filteredInstances.length === 0) {
                container.innerHTML = '<div class="p-4 text-center text-gray-500">Nenhuma instância ' + (connectedOnly ? 'conectada ' : '') + 'encontrada</div>';
                return;
            }

            container.innerHTML = filteredInstances.map(instance => {
                console.log('Estrutura completa da instância (webhook):', JSON.stringify(instance, null, 2));
                
                // Tentar diferentes caminhos para o nome da instância (igual à aba Gerenciar)
                let instanceName = 'Nome não disponível';
                
                if (instance.instance?.instanceName) {
                    instanceName = instance.instance.instanceName;
                    console.log('Nome encontrado em instance.instance.instanceName (webhook):', instanceName);
                } else if (instance.instanceName) {
                    instanceName = instance.instanceName;
                    console.log('Nome encontrado em instance.instanceName (webhook):', instanceName);
                } else if (instance.name) {
                    instanceName = instance.name;
                    console.log('Nome encontrado em instance.name (webhook):', instanceName);
                } else if (instance.id) {
                    instanceName = instance.id;
                    console.log('Nome encontrado em instance.id (webhook):', instanceName);
                } else {
                    console.log('Nenhum nome encontrado na aba webhook, usando fallback');
                }
                
                const connectionStatus = instance.connectionStatus || instance.status || 'unknown';
                
                console.log(`Processando instância (webhook): ${instanceName}, status: ${connectionStatus}`);
                
                // Obter foto de perfil
                const profilePicUrl = instance.profilePicUrl || null;
                const profileName = instance.profileName || instanceName;
                
                const isConnected = connectionStatus === 'open';
                
                return `
                    <div class="flex items-center space-x-3 p-3 border rounded-lg">
                        <div class="relative">
                            <input type="checkbox" class="instance-checkbox" value="${instanceName}" ${!isConnected ? 'disabled' : ''} />
                            ${!isConnected ? `
                                <div class="absolute -top-1 -right-1 w-4 h-4 bg-gray-400 rounded-full flex items-center justify-center" title="Instância desconectada">
                                    <svg class="w-2.5 h-2.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            ` : ''}
                        </div>
                        <div class="flex items-center justify-center">
                            ${profilePicUrl ? `
                                <img src="${profilePicUrl}" 
                                     alt="Foto de ${profileName}" 
                                     class="w-10 h-10 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                />
                                <div class="w-10 h-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-gray-600 dark:text-gray-300 text-sm font-medium" style="display: none;">
                                    ${profileName.charAt(0).toUpperCase()}
                                </div>
                            ` : `
                                <div class="w-10 h-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-gray-600 dark:text-gray-300 text-sm font-medium">
                                    ${profileName.charAt(0).toUpperCase()}
                                </div>
                            `}
                        </div>
                        <div class="flex-1 ml-3">
                            <div class="font-medium">${instanceName}</div>
                            ${profileName !== instanceName ? `
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    ${profileName}
                                </div>
                            ` : ''}
                            <span class="inline-block mt-1 px-2 py-1 text-xs rounded-full ${
                                connectionStatus === 'open' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                            }">
                                ${connectionStatus === 'open' ? 'Conectado' : 'Desconectado'}
                            </span>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Atualizar webhooks
        async function updateWebhooks() {
            const webhookUrl = document.getElementById('webhookUrl').value;
            const selectedInstances = Array.from(document.querySelectorAll('.instance-checkbox:checked'))
                .map(cb => cb.value);

            if (!webhookUrl || selectedInstances.length === 0) {
                showError('Selecione instâncias e digite a URL do webhook');
                return;
            }

            const resultsContainer = document.getElementById('webhookResults');
            resultsContainer.innerHTML = '';
            resultsContainer.classList.remove('hidden');
            console.log('Atualizando webhooks para instâncias selecionadas:', selectedInstances);

            for (const instanceName of selectedInstances) {
                try {
                    console.log(`Atualizando webhook para instância: ${instanceName}`);
                    console.log('URL do webhook:', webhookUrl);
                    console.log('Fazendo requisição direta para Evolution API');
                    console.log('URL da Evolution API:', config.evolution_url);
                    console.log('API Key:', config.has_evolution_api_key ? 'Configurada' : 'NÃO CONFIGURADA');
                    
                    // Codificar o nome da instância para lidar com espaços e caracteres especiais
                    const encodedInstanceName = encodeURIComponent(instanceName);
                    const requestUrl = config.evolution_url + `/webhook/set/${encodedInstanceName}`;
                    // Remover espaços extras e caracteres de acento grave da URL do webhook
                    let cleanWebhookUrl = webhookUrl.trim();
                    // Remover caracteres de acento grave que podem estar presentes
                    cleanWebhookUrl = cleanWebhookUrl.replace(/`/g, '');
                    
                    // Garantir que a URL não tenha aspas extras
                    const finalWebhookUrl = cleanWebhookUrl.replace(/["']/g, '');
                    
                    // Estrutura correta conforme exigido pela API Evolution
                    const requestBody = {
                        webhook: {
                            url: finalWebhookUrl,
                            webhook_by_events: false,
                            webhook_base64: false,
                            enabled: true,
                            events: [
                                'APPLICATION_STARTUP',
                                'QRCODE_UPDATED',
                                'CONNECTION_UPDATE',
                                'MESSAGES_UPSERT',
                                'MESSAGES_UPDATE',
                                'SEND_MESSAGE'
                            ]
                        }
                    };
                    
                    console.log('URL da requisição:', requestUrl);
                    console.log('Body da requisição (objeto):', requestBody);
                    console.log('Body da requisição (JSON):', JSON.stringify(requestBody, null, 2));
                    console.log('URL final do webhook:', finalWebhookUrl);
                    
                    // Verificar se a URL do webhook está formatada corretamente
                    console.log('URL do webhook após limpeza:', cleanWebhookUrl);
                    if (cleanWebhookUrl.includes('`')) {
                        console.warn('A URL do webhook ainda contém caracteres de acento grave (`) que podem causar problemas');
                    }
                    if (cleanWebhookUrl.startsWith(' ') || cleanWebhookUrl.endsWith(' ')) {
                        console.warn('A URL do webhook ainda contém espaços no início ou fim');
                    }
                    
                    // Fazer requisição para o endpoint seguro do backend
                    const response = await fetch(`/client/api/instances/${encodedInstanceName}/webhook`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            webhook_url: finalWebhookUrl
                        })
                    });

                    console.log(`Response status para ${instanceName}:`, response.status, response.statusText);
                    console.log('Response headers:', Object.fromEntries(response.headers.entries()));
                    
                    if (!response.ok) {
                        console.error(`Erro HTTP para ${instanceName}:`, response.status, response.statusText);
                        
                        // Tentar obter mais detalhes sobre o erro
                        const errorText = await response.text();
                        console.error(`Detalhes do erro para ${instanceName}:`, errorText);
                        
                        throw new Error(`Erro HTTP: ${response.status} ${response.statusText}`);
                    }
                    
                    const text = await response.text();
                    console.log(`Response text para ${instanceName}:`, text.substring(0, 500));
                    
                    let result = null;
                    if (text && text.trim() !== '') {
                        try {
                            result = JSON.parse(text);
                            console.log(`Resultado parseado para ${instanceName}:`, result);
                        } catch (parseError) {
                            console.error(`Erro ao fazer parse JSON para ${instanceName}:`, parseError);
                            console.log('Texto que causou erro:', text);
                            if (response.status === 400) {
                                throw new Error(`Erro 400: ${text.substring(0, 100)}${text.length > 100 ? '...' : ''}`);
                            } else {
                                throw new Error('Resposta inválida do servidor');
                            }
                        }
                    }
                    
                    resultsContainer.innerHTML += `
                        <div class="flex items-center justify-between p-3 border rounded-lg">
                            <span>${instanceName}</span>
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                Webhook atualizado com sucesso
                            </span>
                        </div>
                    `;
                } catch (error) {
                    console.error(`Erro ao atualizar webhook para ${instanceName}:`, error);
                    resultsContainer.innerHTML += `
                        <div class="flex items-center justify-between p-3 border rounded-lg">
                            <span>${instanceName}</span>
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Erro: ${error.message}</span>
                        </div>
                    `;
                }
            }
        }

        // Gerenciar instâncias
        async function refreshInstances() {
            try {
                console.log('Atualizando lista de instâncias...');
                const response = await fetch('/client/api/instances', {
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                console.log('Refresh Instances - Response Status:', response.status, response.statusText);
                
                if (!response.ok) {
                    throw new Error(`Erro HTTP: ${response.status} ${response.statusText}`);
                }
                
                const text = await response.text();
                console.log('Refresh Instances - Response text:', text.substring(0, 500));
                
                if (!text || text.trim() === '') {
                    console.error('Resposta vazia ao atualizar instâncias');
                    displayInstancesTable([]);
                    return;
                }
                
                let instances;
                try {
                    const responseData = JSON.parse(text);
                    console.log('Dados da resposta completa (refreshInstances):', responseData);
                    
                    // Verificar se a resposta tem a estrutura esperada
                    if (responseData.success && responseData.instances) {
                        // Resposta do WhatsAppController
                        instances = responseData.instances;
                    } else if (Array.isArray(responseData)) {
                        // Resposta direta da Evolution API
                        instances = responseData;
                    } else {
                        console.error('Estrutura de resposta não reconhecida (refreshInstances):', responseData);
                        instances = [];
                    }
                    
                    console.log('Instâncias extraídas (refreshInstances):', instances);
                    console.log('Tipo de dados (refreshInstances):', typeof instances);
                    console.log('É array? (refreshInstances)', Array.isArray(instances));
                    
                    // Definir instâncias globalmente para uso em outras funções
                    window.instances = instances;
                    
                    // Log detalhado de cada instância recebida na refreshInstances
                    if (Array.isArray(instances)) {
                        instances.forEach((instance, index) => {
                            console.log(`RefreshInstances - Instância ${index}:`, JSON.stringify(instance, null, 2));
                            console.log(`RefreshInstances - Chaves disponíveis na instância ${index}:`, Object.keys(instance));
                        });
                    }
                } catch (e) {
                    console.error('Erro ao fazer parse JSON das instâncias:', e);
                    console.error('Texto da resposta:', text);
                    window.instances = [];
                    displayInstancesTable([]);
                    return;
                }
                
                displayInstancesTable(instances);
            } catch (error) {
                console.error('Erro ao carregar instâncias:', error);
                displayInstancesTable([]);
            }
        }

        function displayInstancesTable(instances) {
            console.log('Exibindo tabela de instâncias:', instances);
            const container = document.getElementById('instancesTable');
            
            if (!instances || !Array.isArray(instances)) {
                console.error('Instâncias inválidas para tabela:', instances);
                container.innerHTML = '<div class="p-4 text-center text-gray-500">Erro ao carregar instâncias</div>';
                return;
            }
            
            if (instances.length === 0) {
                console.log('Nenhuma instância encontrada para tabela');
                container.innerHTML = '<div class="p-4 text-center text-gray-500">Nenhuma instância encontrada</div>';
                return;
            }
            
            container.innerHTML = `
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Foto</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instância</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            ${instances.map(instance => {
                                console.log('Estrutura completa da instância:', JSON.stringify(instance, null, 2));
                                
                                // Tentar diferentes caminhos para o nome da instância
                                let instanceName = 'Nome não disponível';
                                
                                if (instance.instance?.instanceName) {
                                    instanceName = instance.instance.instanceName;
                                    console.log('Nome encontrado em instance.instance.instanceName:', instanceName);
                                } else if (instance.instanceName) {
                                    instanceName = instance.instanceName;
                                    console.log('Nome encontrado em instance.instanceName:', instanceName);
                                } else if (instance.name) {
                                    instanceName = instance.name;
                                    console.log('Nome encontrado em instance.name:', instanceName);
                                } else if (instance.id) {
                                    instanceName = instance.id;
                                    console.log('Nome encontrado em instance.id:', instanceName);
                                } else {
                                    console.log('Nenhum nome encontrado, usando fallback');
                                }
                                
                                const connectionStatus = instance.connectionStatus || instance.status || 'unknown';
                                
                                console.log(`Processando instância: ${instanceName}, status: ${connectionStatus}`);
                                
                                // Obter foto de perfil
                                const profilePicUrl = instance.profilePicUrl || null;
                                const profileName = instance.profileName || instanceName;
                                
                                return `
                                    <tr>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="flex items-center justify-center">
                                                ${profilePicUrl ? `
                                                    <img src="${profilePicUrl}" 
                                                         alt="Foto de ${profileName}" 
                                                         class="w-10 h-10 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600"
                                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                                    />
                                                    <div class="w-10 h-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-gray-600 dark:text-gray-300 text-sm font-medium" style="display: none;">
                                                        ${profileName.charAt(0).toUpperCase()}
                                                    </div>
                                                ` : `
                                                    <div class="w-10 h-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-gray-600 dark:text-gray-300 text-sm font-medium">
                                                        ${profileName.charAt(0).toUpperCase()}
                                                    </div>
                                                `}
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="font-medium text-gray-900 dark:text-gray-100">
                                                ${instanceName}
                                            </div>
                                            ${profileName !== instanceName ? `
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    ${profileName}
                                                </div>
                                            ` : ''}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs rounded-full ${
                                                connectionStatus === 'open' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                                            }">
                                                ${connectionStatus === 'open' ? 'Conectado' : 'Desconectado'}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap space-x-2">
                                            ${connectionStatus === 'open' ? `
                                                <button onclick="disconnectInstance('${instanceName}')" 
                                                        class="text-yellow-600 hover:text-yellow-900 text-sm">
                                                    Desconectar
                                                </button>
                                            ` : `
                                                <button onclick="connectInstance('${instanceName}')" 
                                                        class="text-green-600 hover:text-green-900 text-sm">
                                                    Conectar
                                                </button>
                                            `}
                                            <button onclick="deleteInstance('${instanceName}', '${connectionStatus}')" 
                                                    class="text-red-600 hover:text-red-900 text-sm">
                                                Excluir
                                            </button>
                                        </td>
                                    </tr>
                                `;
                            }).join('')}
                        </tbody>
                    </table>
                </div>
            `;
        }

        function disconnectInstance(instanceName) {
            showConfirmModal(
                `Deseja desconectar a instância "${instanceName}"?`,
                () => performDisconnect(instanceName)
            );
        }

        function connectInstance(instanceName) {
            showConfirmModal(
                `Deseja conectar a instância "${instanceName}"?`,
                () => performConnect(instanceName)
            );
        }

        function deleteInstance(instanceName, connectionStatus) {
            if (connectionStatus === 'open') {
                showConfirmModal(
                    'Para excluir esta instância, você deve desconectá-la primeiro.',
                    null
                );
                return;
            }
            
            showConfirmModal(
                `Deseja excluir permanentemente a instância "${instanceName}"?`,
                () => performDelete(instanceName)
            );
        }

        async function performConnect(instanceName) {
            try {
                showLoading();
                
                console.log(`Tentando conectar instância: ${instanceName}`);
                
                // Primeiro, tentar conectar a instância para gerar o QR Code
                const response = await fetch(`/client/api/instances/${instanceName}/connect`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                console.log(`Connect Response Status: ${response.status} ${response.statusText}`);
                
                if (response.ok) {
                    const text = await response.text();
                    console.log(`Connect Response Text: ${text.substring(0, 500)}`);
                    
                    let data;
                    try {
                        data = JSON.parse(text);
                        console.log('Connect Response Data:', data);
                    } catch (parseError) {
                        console.error('Erro ao fazer parse da resposta de conexão:', parseError);
                        console.error('Texto da resposta:', text);
                        hideLoading();
                        showError('Resposta inválida da API Evolution');
                        return;
                    }
                    
                    // Se retornou um QR Code, exibir
                    if (data.qrcode || data.base64 || data.qr) {
                        let qrCode = data.qrcode || data.base64 || data.qr;
                        console.log('QR Code encontrado:', qrCode.substring(0, 100) + '...');
                        
                        // Verificar se o QR Code já tem o prefixo data:image
                        if (!qrCode.startsWith('data:image')) {
                            qrCode = `data:image/png;base64,${qrCode}`;
                        }
                        
                        console.log('QR Code formatado, exibindo em modal...');
                        hideLoading();
                        showQRCodeModal(qrCode, instanceName);
                        
                        // Iniciar timer do QR Code
                        startQRTimer();
                        
                        // Iniciar monitoramento da conexão
                        startConnectionMonitoring(instanceName);
                    } else if (data.connected || data.status === 'open') {
                        // Se já está conectada
                        console.log('Instância já conectada');
                        hideLoading();
                        refreshInstances();
                        showSuccess('Instância já está conectada!');
                    } else {
                        console.log('Resposta da API não contém QR Code nem status de conectado:', data);
                        hideLoading();
                        showError('Não foi possível gerar o QR Code para conexão. Verifique se a instância existe e não está já conectada.');
                    }
                } else {
                    const errorText = await response.text();
                    console.error(`Erro HTTP ${response.status}: ${errorText}`);
                    hideLoading();
                    showError(`Erro ao conectar instância: ${response.status} ${response.statusText}`);
                }
            } catch (error) {
                console.error('Erro na função performConnect:', error);
                hideLoading();
                showError('Erro de conexão: ' + error.message);
            }
        }

        async function performDisconnect(instanceName) {
            try {
                const response = await fetch(`/client/api/instances/${instanceName}/logout`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (response.ok) {
                    refreshInstances();
                    showSuccess('Instância desconectada com sucesso');
                } else {
                    showError('Erro ao desconectar instância');
                }
            } catch (error) {
                showError('Erro de conexão: ' + error.message);
            }
        }

        async function performDelete(instanceName) {
            try {
                const response = await fetch(`/client/api/instances/${instanceName}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (response.ok) {
                    refreshInstances();
                    showSuccess('Instância excluída com sucesso');
                } else {
                    showError('Erro ao excluir instância');
                }
            } catch (error) {
                showError('Erro de conexão: ' + error.message);
            }
        }

        // Funções auxiliares
        function showLoading() {
            document.getElementById('loadingState').classList.remove('hidden');
            document.getElementById('qrCodeContainer').classList.add('hidden');
            document.getElementById('errorState').classList.add('hidden');
            document.getElementById('successState').classList.add('hidden');
        }

        function hideLoading() {
            document.getElementById('loadingState').classList.add('hidden');
        }

        function showError(message) {
            hideLoading();
            console.error('Evolution Manager Error:', message);
            document.getElementById('errorMessage').textContent = message;
            document.getElementById('errorState').classList.remove('hidden');
            document.getElementById('qrCodeContainer').classList.add('hidden');
            document.getElementById('successState').classList.add('hidden');
        }

        // Função para debug de respostas da API
        function debugResponse(response, data) {
            console.log('Response Status:', response.status);
            console.log('Response Headers:', Object.fromEntries(response.headers.entries()));
            console.log('Response Data:', data);
        }

        function showSuccess(message = 'Operação realizada com sucesso!') {
            hideLoading();
            if (qrTimer) clearInterval(qrTimer);
            
            // Atualiza o texto da mensagem de sucesso
            const successMessage = document.getElementById('successMessage');
            if (successMessage) {
                successMessage.textContent = message;
            }
            
            document.getElementById('successState').classList.remove('hidden');
            document.getElementById('qrCodeContainer').classList.add('hidden');
            document.getElementById('qrPlaceholder').classList.remove('hidden');
            document.getElementById('errorState').classList.add('hidden');
        }

        function showConfirmModal(message, action) {
            document.getElementById('confirmMessage').textContent = message;
            document.getElementById('confirmModal').classList.remove('hidden');
            pendingAction = action;
            
            // Se não há ação (apenas aviso), ocultar botão confirmar
            const confirmButton = document.querySelector('#confirmModal flux\\:button[variant="danger"]');
            if (action === null) {
                if (confirmButton) confirmButton.style.display = 'none';
            } else {
                if (confirmButton) confirmButton.style.display = 'inline-flex';
            }
        }

        function closeModal() {
            document.getElementById('confirmModal').classList.add('hidden');
            pendingAction = null;
        }

        function confirmAction() {
            if (pendingAction) {
                pendingAction();
                closeModal();
            }
        }

        // Monitorar conexão da instância
        function startConnectionMonitoring(instanceName) {
            const connectionTimer = setInterval(async () => {
                try {
                    const response = await fetch('/client/api/instances', {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    
                    if (response.ok) {
                        const instances = await response.json();
                        const targetInstance = instances.find(inst => 
                            (inst.instance?.instanceName === instanceName) ||
                            (inst.instanceName === instanceName) ||
                            (inst.name === instanceName) ||
                            (inst.id === instanceName)
                        );
                        
                        if (targetInstance && targetInstance.connectionStatus === 'open') {
                            clearInterval(connectionTimer);
                            // Limpar também o timer do QR Code para evitar mensagem de expiração
                            if (qrTimer) {
                                clearInterval(qrTimer);
                                qrTimer = null;
                            }
                            closeQRCodeModal();
                            refreshInstances();
                            showSuccess('Instância conectada com sucesso!');
                        }
                    }
                } catch (error) {
                    console.error('Erro ao monitorar conexão:', error);
                }
            }, 3000); // Verificar a cada 3 segundos
            
            // Parar monitoramento após 5 minutos
            setTimeout(() => {
                clearInterval(connectionTimer);
            }, 300000);
        }

        // Carregar instâncias ao inicializar
        document.addEventListener('DOMContentLoaded', function() {
            loadInstances();
            refreshInstances();
            
            // Filtro de instâncias conectadas
            const connectedOnlyElement = document.getElementById('connectedOnly');
            if (connectedOnlyElement) {
                connectedOnlyElement.addEventListener('change', loadInstances);
            }
            
            // Event listener para fechar modal
            const closeQRModalElement = document.getElementById('closeQRModal');
            if (closeQRModalElement) {
                closeQRModalElement.addEventListener('click', closeQRCodeModal);
            }
            
            const qrCodeModalElement = document.getElementById('qrCodeModal');
            if (qrCodeModalElement) {
                qrCodeModalElement.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeQRCodeModal();
                    }
                });
            }
        });

        // Funções do Modal QR Code
        function showQRCodeModal(qrCode, instanceName) {
            document.getElementById('modalInstanceName').textContent = instanceName;
            document.getElementById('modalQRCodeDisplay').innerHTML = `<div class="text-center p-4">📱 QR Code disponível</div>`;
            document.getElementById('qrCodeModal').classList.remove('hidden');
            
            // Iniciar timer do modal
            startModalQRTimer();
        }

        function closeQRCodeModal() {
            document.getElementById('qrCodeModal').classList.add('hidden');
            document.getElementById('modalQRCodeDisplay').innerHTML = '';
            document.getElementById('modalQRTimer').textContent = '';
            
            // Limpar timers
            if (qrTimer) {
                clearInterval(qrTimer);
                qrTimer = null;
            }
        }

        function startModalQRTimer() {
            let timeLeft = 120; // 2 minutos
            const timerElement = document.getElementById('modalQRTimer');
            
            qrTimer = setInterval(async () => {
                timeLeft--;
                timerElement.textContent = `QR Code expira em: ${timeLeft}s`;
                
                if (timeLeft <= 0) {
                    clearInterval(qrTimer);
                    closeQRCodeModal();
                    showError('QR Code expirado. Tente conectar novamente.');
                }
            }, 1000);
        }

        // Funções de seleção de instâncias
        function selectConnectedInstances() {
            const checkboxes = document.querySelectorAll('.instance-checkbox');
            checkboxes.forEach(checkbox => {
                // Encontrar o elemento pai que contém o status
                const parentDiv = checkbox.closest('div');
                const statusElement = parentDiv.querySelector('.bg-green-100');
                
                // Marcar apenas se tiver status conectado (classe bg-green-100)
                checkbox.checked = statusElement !== null;
            });
        }

        function clearAllInstances() {
            const checkboxes = document.querySelectorAll('.instance-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
        }
        

        

        

    </script>

    <!-- Modal para QR Code -->
    <div id="qrCodeModal" class="fixed inset-0 hidden z-50 flex items-center justify-center" style="background-color: rgba(0, 0, 0, 0.25);">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Conectar Instância</h3>
                <button onclick="closeQRCodeModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="text-center">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Escaneie o QR Code abaixo com seu WhatsApp para conectar a instância <strong id="modalInstanceName"></strong>:</p>
                <div id="modalQRCodeDisplay" class="bg-white p-4 rounded inline-block mb-4"></div>
                <div id="modalQRTimer" class="text-sm text-gray-600 dark:text-gray-400 mb-4"></div>
                <div class="flex justify-center space-x-3">
                    <button onclick="closeQRCodeModal()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>