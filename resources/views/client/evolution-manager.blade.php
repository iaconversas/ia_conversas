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
                <div class="space-y-6">
                    <flux:card class="border-l-4 border-l-orange-500">
                        <flux:card.header>
                            <flux:heading size="lg">Gerar QR Code</flux:heading>
                            <flux:subheading>Crie uma nova instância WhatsApp</flux:subheading>
                        </flux:card.header>

                        <div class="space-y-4">
                            <flux:field>
                                <flux:label>Nome da Instância</flux:label>
                                <flux:input 
                                    id="instanceName" 
                                    placeholder="Digite o nome da instância"
                                    class="w-full"
                                />
                            </flux:field>

                            <flux:button 
                                variant="primary" 
                                size="sm"
                                onclick="generateQRCode()"
                                id="generateBtn"
                            >
                                <flux:icon.qr-code class="size-4" />
                                Gerar QR Code
                            </flux:button>

                            <div id="qrCodeContainer" class="hidden">
                                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6 text-center">
                                    <div id="qrCodeDisplay" class="mb-4"></div>
                                    <div id="qrTimer" class="text-sm text-gray-600 dark:text-gray-400"></div>
                                </div>
                            </div>

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
                                <flux:banner variant="success">
                                    Instância conectada com sucesso!
                                </flux:banner>
                            </div>
                        </div>
                    </flux:card>
                </div>
            </flux:tab.panel>

            <flux:tab.panel name="webhook">
                <div class="space-y-6">
                    <flux:card class="border-l-4 border-l-blue-500">
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
                <div class="space-y-6">
                    <flux:card class="border-l-4 border-l-green-500">
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
    <div id="confirmModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
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

            showLoading();
            
            try {
                const response = await fetch('https://n8n.iaconversas.com/webhook/criar-instancia-evolution', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ instanceName })
                });

                const data = await response.json();
                
                if (response.ok) {
                    displayQRCode(data);
                    startQRTimer();
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
            
            // Suporte a diferentes formatos de resposta
            if (data.qrcode) {
                if (data.qrcode.startsWith('data:image')) {
                    display.innerHTML = `<img src="${data.qrcode}" alt="QR Code" class="mx-auto max-w-xs">`;
                } else {
                    display.innerHTML = `<div class="bg-white p-4 rounded inline-block"><img src="data:image/png;base64,${data.qrcode}" alt="QR Code" class="mx-auto max-w-xs"></div>`;
                }
            } else if (data.base64) {
                display.innerHTML = `<div class="bg-white p-4 rounded inline-block"><img src="data:image/png;base64,${data.base64}" alt="QR Code" class="mx-auto max-w-xs"></div>`;
            } else {
                display.innerHTML = '<p class="text-gray-500">QR Code gerado com sucesso</p>';
            }
            
            container.classList.remove('hidden');
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
                const response = await fetch('https://n8n.iaconversas.com/webhook/atualiza-qrcode', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ 
                        instanceName: document.getElementById('instanceName').value 
                    })
                });

                const data = await response.json();
                
                if (data.connected) {
                    clearInterval(qrTimer);
                    showSuccess();
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
                const response = await fetch('https://evolution.iaconversas.com/instance/fetchInstances', {
                    headers: {
                        'apikey': '5863c643c8bf6d84e8da8bb564ea13fc',
                        'Content-Type': 'application/json'
                    }
                });

                const instances = await response.json();
                displayInstancesList(instances);
            } catch (error) {
                console.error('Erro ao carregar instâncias:', error);
            }
        }

        function displayInstancesList(instances) {
            const container = document.getElementById('instancesList');
            const connectedOnly = document.getElementById('connectedOnly').checked;
            
            const filteredInstances = connectedOnly ? 
                instances.filter(instance => instance.connectionStatus === 'open') : 
                instances;

            container.innerHTML = filteredInstances.map(instance => `
                <div class="flex items-center space-x-3 p-3 border rounded-lg">
                    <input type="checkbox" class="instance-checkbox" value="${instance.instance.instanceName}" />
                    <div class="flex-1">
                        <span class="font-medium">${instance.instance.instanceName}</span>
                        <span class="ml-2 px-2 py-1 text-xs rounded-full ${
                            instance.connectionStatus === 'open' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                        }">
                            ${instance.connectionStatus === 'open' ? 'Conectado' : 'Desconectado'}
                        </span>
                    </div>
                </div>
            `).join('');
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

            for (const instanceName of selectedInstances) {
                try {
                    const response = await fetch('https://n8n.iaconversas.com/webhook/atualiza-webhook', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ instanceName, webhookUrl })
                    });

                    const result = await response.json();
                    
                    resultsContainer.innerHTML += `
                        <div class="flex items-center justify-between p-3 border rounded-lg">
                            <span>${instanceName}</span>
                            <span class="px-2 py-1 text-xs rounded-full ${
                                response.ok ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                            }">
                                ${response.ok ? 'Sucesso' : 'Erro'}
                            </span>
                        </div>
                    `;
                } catch (error) {
                    resultsContainer.innerHTML += `
                        <div class="flex items-center justify-between p-3 border rounded-lg">
                            <span>${instanceName}</span>
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Erro</span>
                        </div>
                    `;
                }
            }
        }

        // Gerenciar instâncias
        async function refreshInstances() {
            try {
                const response = await fetch('https://evolution.iaconversas.com/instance/fetchInstances', {
                    headers: {
                        'apikey': '5863c643c8bf6d84e8da8bb564ea13fc',
                        'Content-Type': 'application/json'
                    }
                });

                const instances = await response.json();
                displayInstancesTable(instances);
            } catch (error) {
                console.error('Erro ao carregar instâncias:', error);
            }
        }

        function displayInstancesTable(instances) {
            const container = document.getElementById('instancesTable');
            
            container.innerHTML = `
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instância</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            ${instances.map(instance => `
                                <tr>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="font-medium text-gray-900 dark:text-gray-100">
                                            ${instance.instance.instanceName}
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded-full ${
                                            instance.connectionStatus === 'open' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                                        }">
                                            ${instance.connectionStatus === 'open' ? 'Conectado' : 'Desconectado'}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap space-x-2">
                                        ${instance.connectionStatus === 'open' ? `
                                            <button onclick="disconnectInstance('${instance.instance.instanceName}')" 
                                                    class="text-yellow-600 hover:text-yellow-900 text-sm">
                                                Desconectar
                                            </button>
                                        ` : ''}
                                        <button onclick="deleteInstance('${instance.instance.instanceName}')" 
                                                class="text-red-600 hover:text-red-900 text-sm">
                                            Excluir
                                        </button>
                                    </td>
                                </tr>
                            `).join('')}
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

        function deleteInstance(instanceName) {
            showConfirmModal(
                `Deseja excluir permanentemente a instância "${instanceName}"?`,
                () => performDelete(instanceName)
            );
        }

        async function performDisconnect(instanceName) {
            try {
                const response = await fetch(`https://evolution.iaconversas.com/instance/logout/${instanceName}`, {
                    method: 'DELETE',
                    headers: {
                        'apikey': '5863c643c8bf6d84e8da8bb564ea13fc',
                        'Content-Type': 'application/json'
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
                const response = await fetch(`https://evolution.iaconversas.com/instance/delete/${instanceName}`, {
                    method: 'DELETE',
                    headers: {
                        'apikey': '5863c643c8bf6d84e8da8bb564ea13fc',
                        'Content-Type': 'application/json'
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
            document.getElementById('errorMessage').textContent = message;
            document.getElementById('errorState').classList.remove('hidden');
            document.getElementById('qrCodeContainer').classList.add('hidden');
            document.getElementById('successState').classList.add('hidden');
        }

        function showSuccess(message = 'Operação realizada com sucesso!') {
            hideLoading();
            if (qrTimer) clearInterval(qrTimer);
            document.getElementById('successState').classList.remove('hidden');
            document.getElementById('qrCodeContainer').classList.add('hidden');
            document.getElementById('errorState').classList.add('hidden');
        }

        function showConfirmModal(message, action) {
            document.getElementById('confirmMessage').textContent = message;
            document.getElementById('confirmModal').classList.remove('hidden');
            pendingAction = action;
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

        // Carregar instâncias ao inicializar
        document.addEventListener('DOMContentLoaded', function() {
            loadInstances();
            refreshInstances();
        });

        // Filtro de instâncias conectadas
        document.getElementById('connectedOnly').addEventListener('change', loadInstances);
    </script>
</x-layouts.app>