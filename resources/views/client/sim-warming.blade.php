<x-layouts.app>
    <flux:heading size="xl">SIM Warming</flux:heading>
    <flux:subheading>Aquecimento de Chips WhatsApp</flux:subheading>

    <div class="mt-6 space-y-6">
        <!-- Card Principal com Tema Verde -->
        <flux:card class="border-l-4 border-l-green-500 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20">
            <flux:card.header>
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-green-100 dark:bg-green-800 rounded-lg">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div>
                        <flux:heading size="lg" class="text-green-700 dark:text-green-300">Aquecimento de Instâncias</flux:heading>
                        <flux:subheading class="text-green-600 dark:text-green-400">Configure o aquecimento automático dos seus chips</flux:subheading>
                    </div>
                </div>
            </flux:card.header>

            <div class="space-y-6">
                <!-- Seleção de Instâncias -->
                <flux:field>
                    <flux:label class="text-green-700 dark:text-green-300">Instâncias Conectadas</flux:label>
                    <div id="instancesContainer" class="space-y-2 p-4 bg-white dark:bg-gray-800 rounded-lg border border-green-200 dark:border-green-700">
                        <div class="flex items-center justify-center py-8">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-green-500"></div>
                            <span class="ml-3 text-green-600">Carregando instâncias...</span>
                        </div>
                    </div>
                    <flux:description class="text-green-600 dark:text-green-400">
                        Selecione as instâncias que deseja aquecer
                    </flux:description>
                </flux:field>

                <!-- Configuração de Interações -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <flux:field>
                        <flux:label class="text-green-700 dark:text-green-300">Número de Interações</flux:label>
                        <flux:input 
                            type="number" 
                            id="interactionCount"
                            value="10"
                            min="1"
                            max="100"
                            class="border-green-300 focus:border-green-500 focus:ring-green-500"
                        />
                        <flux:description class="text-green-600 dark:text-green-400">
                            Quantidade de interações por instância (1-100)
                        </flux:description>
                    </flux:field>

                    <div class="flex items-end">
                        <flux:button 
                            variant="primary" 
                            onclick="startWarming()"
                            id="startWarmingBtn"
                            class="w-full bg-green-600 hover:bg-green-700 focus:ring-green-500"
                        >
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path>
                            </svg>
                            Iniciar Aquecimento
                        </flux:button>
                    </div>
                </div>

                <!-- Informações sobre o Aquecimento -->
                <div class="bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-700 rounded-lg p-4">
                    <div class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-green-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <flux:heading size="sm" class="text-green-700 dark:text-green-300 mb-2">Como funciona o aquecimento?</flux:heading>
                            <ul class="text-sm text-green-600 dark:text-green-400 space-y-1">
                                <li>• Cada interação simula atividade natural no WhatsApp</li>
                                <li>• Intervalo de 1 segundo entre cada interação</li>
                                <li>• Ajuda a evitar bloqueios e melhorar a entregabilidade</li>
                                <li>• Recomendado para números novos ou pouco utilizados</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </flux:card>

        <!-- Status do Aquecimento -->
        <div id="warmingStatus" class="hidden">
            <flux:card class="border-l-4 border-l-orange-500 bg-gradient-to-r from-orange-50 to-yellow-50 dark:from-orange-900/20 dark:to-yellow-900/20">
                <flux:card.header>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-orange-100 dark:bg-orange-800 rounded-lg">
                                <div class="animate-pulse">
                                    <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <flux:heading size="lg" class="text-orange-700 dark:text-orange-300">Aquecimento em Andamento</flux:heading>
                                <flux:subheading class="text-orange-600 dark:text-orange-400">Processando interações...</flux:subheading>
                            </div>
                        </div>
                        <flux:button 
                            variant="danger" 
                            size="sm"
                            onclick="stopWarming()"
                            id="stopWarmingBtn"
                        >
                            <flux:icon.stop class="size-4 mr-2" />
                            Parar Aquecimento
                        </flux:button>
                    </div>
                </flux:card.header>

                <!-- Progresso Global -->
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm text-orange-700 dark:text-orange-300 mb-2">
                            <span>Progresso Global</span>
                            <span id="globalProgress">0%</span>
                        </div>
                        <div class="w-full bg-orange-200 dark:bg-orange-800 rounded-full h-3">
                            <div id="globalProgressBar" class="bg-gradient-to-r from-orange-500 to-yellow-500 h-3 rounded-full transition-all duration-500" style="width: 0%"></div>
                        </div>
                    </div>

                    <!-- Status por Instância -->
                    <div id="instancesProgress" class="space-y-3">
                        <!-- Progresso das instâncias será inserido aqui -->
                    </div>

                    <!-- Estatísticas -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                        <div class="bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-700 rounded-lg p-3 text-center">
                            <div class="text-lg font-bold text-green-600" id="successCount">0</div>
                            <div class="text-xs text-green-600">Sucessos</div>
                        </div>
                        <div class="bg-red-100 dark:bg-red-900/30 border border-red-200 dark:border-red-700 rounded-lg p-3 text-center">
                            <div class="text-lg font-bold text-red-600" id="errorCount">0</div>
                            <div class="text-xs text-red-600">Erros</div>
                        </div>
                        <div class="bg-blue-100 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-lg p-3 text-center">
                            <div class="text-lg font-bold text-blue-600" id="totalInteractions">0</div>
                            <div class="text-xs text-blue-600">Total</div>
                        </div>
                        <div class="bg-purple-100 dark:bg-purple-900/30 border border-purple-200 dark:border-purple-700 rounded-lg p-3 text-center">
                            <div class="text-lg font-bold text-purple-600" id="remainingTime">--</div>
                            <div class="text-xs text-purple-600">Tempo Rest.</div>
                        </div>
                    </div>
                </div>
            </flux:card>
        </div>

        <!-- Resultados -->
        <div id="resultsSection" class="hidden">
            <flux:card class="border-l-4 border-l-blue-500">
                <flux:card.header>
                    <flux:heading size="lg">Resultados do Aquecimento</flux:heading>
                    <flux:subheading id="resultsSubheading">Aquecimento concluído</flux:subheading>
                </flux:card.header>

                <div class="space-y-4">
                    <!-- Resumo Final -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-green-600" id="finalSuccessCount">0</div>
                            <div class="text-sm text-green-600">Interações Bem-sucedidas</div>
                        </div>
                        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-red-600" id="finalErrorCount">0</div>
                            <div class="text-sm text-red-600">Erros Encontrados</div>
                        </div>
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-blue-600" id="finalDuration">0s</div>
                            <div class="text-sm text-blue-600">Duração Total</div>
                        </div>
                    </div>

                    <!-- Log Detalhado -->
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                        <flux:heading size="sm" class="mb-3">Log Detalhado</flux:heading>
                        <div id="detailedLog" class="max-h-64 overflow-y-auto space-y-2 text-sm font-mono">
                            <!-- Log será inserido aqui -->
                        </div>
                    </div>

                    <div class="flex justify-center">
                        <flux:button variant="outline" onclick="resetWarming()">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                            </svg>
                            Novo Aquecimento
                        </flux:button>
                    </div>
                </div>
            </flux:card>
        </div>

        <!-- Mensagens de Erro -->
        <div id="errorSection" class="hidden">
            <flux:banner variant="danger">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="font-medium">Erro no Aquecimento</div>
                        <div id="errorMessage" class="text-sm mt-1"></div>
                    </div>
                    <flux:button variant="ghost" size="sm" onclick="resetWarming()">
                        Tentar Novamente
                    </flux:button>
                </div>
            </flux:banner>
        </div>
    </div>

    <script>
        let warmingActive = false;
        let selectedInstances = [];
        let warmingStats = {
            success: 0,
            errors: 0,
            total: 0,
            startTime: null
        };
        let warmingLog = [];

        // Carregar instâncias ao carregar a página
        document.addEventListener('DOMContentLoaded', function() {
            loadInstances();
        });

        async function loadInstances() {
            try {
                const response = await fetch('https://evolution.iaconversas.com/instance/fetchInstances', {
                    headers: {
                        'apikey': '5863c643c8bf6d84e8da8bb564ea13fc',
                        'Content-Type': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error(`Erro HTTP: ${response.status}`);
                }

                const instances = await response.json();
                renderInstances(instances);

            } catch (error) {
                showInstancesError('Erro ao carregar instâncias: ' + error.message);
            }
        }

        function renderInstances(instances) {
            const container = document.getElementById('instancesContainer');
            
            if (!instances || instances.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m13-8l-4 4m0 0l-4-4m4 4V3"></path>
                        </svg>
                        <p>Nenhuma instância encontrada</p>
                    </div>
                `;
                return;
            }

            // Filtrar apenas instâncias conectadas
            const connectedInstances = instances.filter(instance => 
                instance.connectionStatus === 'open' || 
                instance.status === 'open' ||
                instance.state === 'open'
            );

            if (connectedInstances.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-8 text-yellow-600">
                        <svg class="w-12 h-12 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <p>Nenhuma instância conectada encontrada</p>
                        <p class="text-sm mt-2">Conecte pelo menos uma instância no Evolution Manager</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = `
                <div class="space-y-3">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-medium text-green-700 dark:text-green-300">
                            ${connectedInstances.length} instância(s) conectada(s)
                        </span>
                        <div class="flex space-x-2">
                            <button onclick="selectAllInstances()" class="text-xs text-green-600 hover:text-green-800">
                                Selecionar Todas
                            </button>
                            <button onclick="deselectAllInstances()" class="text-xs text-green-600 hover:text-green-800">
                                Desmarcar Todas
                            </button>
                        </div>
                    </div>
                    ${connectedInstances.map(instance => `
                        <label class="flex items-center p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 cursor-pointer transition-colors">
                            <input 
                                type="checkbox" 
                                value="${instance.instanceName || instance.name || instance.instance}"
                                onchange="updateSelectedInstances()"
                                class="h-4 w-4 text-green-600 focus:ring-green-500 border-green-300 rounded"
                            >
                            <div class="ml-3 flex-1">
                                <div class="flex items-center justify-between">
                                    <span class="font-medium text-green-800 dark:text-green-200">
                                        ${instance.instanceName || instance.name || instance.instance}
                                    </span>
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 border border-green-200">
                                        Conectado
                                    </span>
                                </div>
                                ${instance.profileName ? `
                                    <div class="text-sm text-green-600 dark:text-green-400 mt-1">
                                        ${instance.profileName}
                                    </div>
                                ` : ''}
                            </div>
                        </label>
                    `).join('')}
                </div>
            `;
        }

        function showInstancesError(message) {
            const container = document.getElementById('instancesContainer');
            container.innerHTML = `
                <div class="text-center py-8 text-red-600">
                    <svg class="w-12 h-12 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="font-medium">Erro ao carregar instâncias</p>
                    <p class="text-sm mt-2">${message}</p>
                    <button onclick="loadInstances()" class="mt-4 px-4 py-2 bg-red-100 text-red-800 rounded-lg hover:bg-red-200 transition-colors">
                        Tentar Novamente
                    </button>
                </div>
            `;
        }

        function selectAllInstances() {
            const checkboxes = document.querySelectorAll('#instancesContainer input[type="checkbox"]');
            checkboxes.forEach(checkbox => checkbox.checked = true);
            updateSelectedInstances();
        }

        function deselectAllInstances() {
            const checkboxes = document.querySelectorAll('#instancesContainer input[type="checkbox"]');
            checkboxes.forEach(checkbox => checkbox.checked = false);
            updateSelectedInstances();
        }

        function updateSelectedInstances() {
            const checkboxes = document.querySelectorAll('#instancesContainer input[type="checkbox"]:checked');
            selectedInstances = Array.from(checkboxes).map(cb => cb.value);
        }

        async function startWarming() {
            const interactionCount = parseInt(document.getElementById('interactionCount').value);

            if (selectedInstances.length === 0) {
                alert('Selecione pelo menos uma instância');
                return;
            }

            if (interactionCount < 1 || interactionCount > 100) {
                alert('O número de interações deve estar entre 1 e 100');
                return;
            }

            warmingActive = true;
            warmingStats = {
                success: 0,
                errors: 0,
                total: selectedInstances.length * interactionCount,
                startTime: Date.now()
            };
            warmingLog = [];

            showWarmingStatus();
            await processWarming(interactionCount);
        }

        async function processWarming(interactionCount) {
            const totalInteractions = selectedInstances.length * interactionCount;
            let completedInteractions = 0;

            // Inicializar progresso das instâncias
            initializeInstancesProgress();

            for (let interaction = 1; interaction <= interactionCount && warmingActive; interaction++) {
                addToLog(`Iniciando interação ${interaction}/${interactionCount}...`);

                for (let i = 0; i < selectedInstances.length && warmingActive; i++) {
                    const instance = selectedInstances[i];
                    
                    try {
                        await sendWarmingRequest(instance, interaction, interactionCount);
                        warmingStats.success++;
                        updateInstanceProgress(instance, interaction, interactionCount, 'success');
                        addToLog(`✓ ${instance}: Interação ${interaction} bem-sucedida`);
                        
                    } catch (error) {
                        warmingStats.errors++;
                        updateInstanceProgress(instance, interaction, interactionCount, 'error');
                        addToLog(`✗ ${instance}: Erro na interação ${interaction} - ${error.message}`);
                    }

                    completedInteractions++;
                    updateGlobalProgress(completedInteractions, totalInteractions);
                    updateStatistics();

                    // Delay de 1 segundo entre interações
                    if (warmingActive && (i < selectedInstances.length - 1 || interaction < interactionCount)) {
                        await new Promise(resolve => setTimeout(resolve, 1000));
                    }
                }
            }

            if (warmingActive) {
                finishWarming();
            }
        }

        async function sendWarmingRequest(instance, interaction, totalInteractions) {
            const response = await fetch('https://n8n.iaconversas.com/webhook/aquecer', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    instancias: [instance],
                    apikey_global: '5863c643c8bf6d84e8da8bb564ea13fc',
                    url_evolution: 'https://evolution.iaconversas.com',
                    interaction: interaction,
                    total_interactions: totalInteractions
                })
            });

            if (!response.ok) {
                throw new Error(`Erro HTTP: ${response.status}`);
            }

            return await response.json();
        }

        function initializeInstancesProgress() {
            const container = document.getElementById('instancesProgress');
            container.innerHTML = selectedInstances.map(instance => `
                <div class="bg-white dark:bg-gray-800 border border-green-200 dark:border-green-700 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-medium text-green-800 dark:text-green-200">${instance}</span>
                        <span id="status-${instance}" class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-600">
                            Aguardando
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div id="progress-${instance}" class="bg-green-500 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                    <div class="text-xs text-gray-500 mt-1" id="progress-text-${instance}">0%</div>
                </div>
            `).join('');
        }

        function updateInstanceProgress(instance, currentInteraction, totalInteractions, status) {
            const progress = (currentInteraction / totalInteractions) * 100;
            const progressBar = document.getElementById(`progress-${instance}`);
            const progressText = document.getElementById(`progress-text-${instance}`);
            const statusBadge = document.getElementById(`status-${instance}`);

            if (progressBar) {
                progressBar.style.width = `${progress}%`;
            }

            if (progressText) {
                progressText.textContent = `${Math.round(progress)}% (${currentInteraction}/${totalInteractions})`;
            }

            if (statusBadge) {
                if (status === 'success') {
                    statusBadge.className = 'px-2 py-1 text-xs rounded-full bg-green-100 text-green-800';
                    statusBadge.textContent = currentInteraction === totalInteractions ? 'Concluído' : 'Processando';
                } else if (status === 'error') {
                    statusBadge.className = 'px-2 py-1 text-xs rounded-full bg-red-100 text-red-800';
                    statusBadge.textContent = 'Erro';
                }
            }
        }

        function updateGlobalProgress(completed, total) {
            const progress = (completed / total) * 100;
            const progressBar = document.getElementById('globalProgressBar');
            const progressText = document.getElementById('globalProgress');

            if (progressBar) {
                progressBar.style.width = `${progress}%`;
            }

            if (progressText) {
                progressText.textContent = `${Math.round(progress)}%`;
            }

            // Calcular tempo restante
            const elapsed = Date.now() - warmingStats.startTime;
            const rate = completed / elapsed;
            const remaining = (total - completed) / rate;
            
            const remainingElement = document.getElementById('remainingTime');
            if (remainingElement && remaining > 0 && isFinite(remaining)) {
                const minutes = Math.floor(remaining / 60000);
                const seconds = Math.floor((remaining % 60000) / 1000);
                remainingElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
            }
        }

        function updateStatistics() {
            document.getElementById('successCount').textContent = warmingStats.success;
            document.getElementById('errorCount').textContent = warmingStats.errors;
            document.getElementById('totalInteractions').textContent = warmingStats.total;
        }

        function addToLog(message) {
            const timestamp = new Date().toLocaleTimeString();
            warmingLog.push(`[${timestamp}] ${message}`);
            
            // Manter apenas os últimos 50 logs
            if (warmingLog.length > 50) {
                warmingLog.shift();
            }
        }

        function finishWarming() {
            warmingActive = false;
            const duration = Math.round((Date.now() - warmingStats.startTime) / 1000);
            
            hideWarmingStatus();
            showResults(duration);
        }

        function showWarmingStatus() {
            document.getElementById('warmingStatus').classList.remove('hidden');
            document.getElementById('resultsSection').classList.add('hidden');
            document.getElementById('errorSection').classList.add('hidden');
            document.getElementById('startWarmingBtn').disabled = true;
        }

        function hideWarmingStatus() {
            document.getElementById('warmingStatus').classList.add('hidden');
            document.getElementById('startWarmingBtn').disabled = false;
        }

        function showResults(duration) {
            document.getElementById('resultsSection').classList.remove('hidden');
            
            document.getElementById('finalSuccessCount').textContent = warmingStats.success;
            document.getElementById('finalErrorCount').textContent = warmingStats.errors;
            document.getElementById('finalDuration').textContent = `${duration}s`;
            
            const logContainer = document.getElementById('detailedLog');
            logContainer.innerHTML = warmingLog.map(log => `
                <div class="text-gray-700 dark:text-gray-300">${log}</div>
            `).join('');
            
            document.getElementById('resultsSubheading').textContent = 
                `${warmingStats.success} sucessos, ${warmingStats.errors} erros em ${duration}s`;
        }

        function stopWarming() {
            warmingActive = false;
            const duration = Math.round((Date.now() - warmingStats.startTime) / 1000);
            addToLog('Aquecimento interrompido pelo usuário');
            finishWarming();
        }

        function resetWarming() {
            warmingActive = false;
            selectedInstances = [];
            warmingStats = { success: 0, errors: 0, total: 0, startTime: null };
            warmingLog = [];
            
            document.getElementById('resultsSection').classList.add('hidden');
            document.getElementById('errorSection').classList.add('hidden');
            document.getElementById('interactionCount').value = '10';
            
            // Desmarcar todas as instâncias
            deselectAllInstances();
        }
    </script>
</x-layouts.app>