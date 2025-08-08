<x-layouts.app title="Aquecimento de Chip">
    <flux:heading size="xl">Aquecimento de Chip</flux:heading>
    <flux:subheading>Aqueça seus chips WhatsApp de forma automatizada</flux:subheading>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <div class="mt-6">
        <div class="bg-white rounded-lg shadow-lg p-6">
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Configuração -->
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Configuração</h2>
                    
                    <!-- Seleção de Instâncias -->
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Selecionar Instâncias
                            </label>
                            <flux:button 
                                variant="ghost" 
                                size="sm"
                                onclick="refreshInstances()"
                                id="refreshBtn"
                            >
                                <flux:icon.arrow-path class="size-4" />
                                Atualizar
                            </flux:button>
                        </div>
                        <div id="instances-container" class="space-y-2 max-h-60 overflow-y-auto border rounded-lg p-3">
                            <div class="text-gray-500 text-center py-4">
                                Carregando instâncias...
                            </div>
                        </div>
                    </div>
                    
                    <!-- Número de Interações -->
                    <div class="mb-6">
                        <label for="interactions" class="block text-sm font-medium text-gray-700 mb-2">
                            Número de Interações (1-100)
                        </label>
                        <input type="number" id="interactions" min="1" max="100" value="10" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <!-- Botão Iniciar -->
                    <flux:button 
                        id="start-warming" 
                        variant="primary" 
                        class="w-full"
                    >
                        <flux:icon.fire class="w-5 h-5 mr-2" />
                        Iniciar Aquecimento
                    </flux:button>
                </div>
            </div>
            
            <!-- Status e Progresso -->
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Status</h2>
                    
                    <!-- Status Atual -->
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700">Status:</span>
                            <span id="status-text" class="text-sm font-semibold text-gray-600">Aguardando</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div id="progress-bar" class="bg-orange-500 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>
                        <div class="text-xs text-gray-500 mt-1" id="progress-text">0%</div>
                    </div>
                    
                    <!-- Instâncias Selecionadas -->
                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Instâncias Selecionadas:</h3>
                        <div id="selected-instances" class="text-sm text-gray-600">
                            Nenhuma instância selecionada
                        </div>
                    </div>
                    
                    <!-- Log de Atividades -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Log de Atividades:</h3>
                        <div id="activity-log" class="bg-white border rounded-lg p-3 h-40 overflow-y-auto text-xs text-gray-600">
                            <div class="text-gray-400">Aguardando início do aquecimento...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let instances = [];
let selectedInstances = [];
let isWarming = false;

// Carregar instâncias ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    loadInstances();
    updateSelectedInstancesDisplay();
});

// Função para carregar instâncias da API Evolution
async function loadInstances() {
    try {
        const response = await fetch('/client/api/instances');
        const data = await response.json();
        
        if (data.success && data.instances) {
            instances = data.instances.filter(instance => instance.connectionStatus === 'open');
            renderInstances();
            addToLog('Instâncias carregadas: ' + instances.length + ' conectadas');
        } else {
            addToLog('Erro ao carregar instâncias: ' + (data.message || 'Erro desconhecido'));
        }
    } catch (error) {
        console.error('Erro ao carregar instâncias:', error);
        addToLog('Erro ao carregar instâncias: ' + error.message);
    }
}

// Renderizar lista de instâncias
function renderInstances() {
    const container = document.getElementById('instances-container');
    
    if (instances.length === 0) {
        container.innerHTML = '<div class="text-gray-500 text-center py-4">Nenhuma instância conectada encontrada</div>';
        return;
    }
    
    container.innerHTML = instances.map(instance => {
        // Obter informações da instância
        const instanceName = instance.instanceName || instance.name || 'Nome não disponível';
        const profilePicUrl = instance.profilePicUrl || null;
        const profileName = instance.profileName || instanceName;
        const ownerJid = instance.ownerJid || 'N/A';
        
        return `
            <div class="flex items-center space-x-3 p-3 hover:bg-gray-100 rounded-lg border border-gray-200">
                <input type="checkbox" id="instance-${instanceName}" 
                       value="${instanceName}" 
                       onchange="toggleInstance('${instanceName}')"
                       class="w-4 h-4 text-orange-600 bg-gray-100 border-gray-300 rounded focus:ring-orange-500">
                
                <!-- Foto da instância -->
                <div class="flex-shrink-0">
                    ${profilePicUrl ? `
                        <img src="${profilePicUrl}" 
                             alt="Foto de ${profileName}" 
                             class="w-10 h-10 rounded-full object-cover border-2 border-gray-200"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 text-sm font-medium" style="display: none;">
                            ${profileName.charAt(0).toUpperCase()}
                        </div>
                    ` : `
                        <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 text-sm font-medium">
                            ${profileName.charAt(0).toUpperCase()}
                        </div>
                    `}
                </div>
                
                <!-- Informações da instância -->
                <label for="instance-${instanceName}" class="flex-1 cursor-pointer">
                    <div class="font-medium text-gray-900">${instanceName}</div>
                    ${profileName !== instanceName ? `
                        <div class="text-sm text-gray-600">${profileName}</div>
                    ` : ''}
                    <div class="text-xs text-gray-500">${ownerJid}</div>
                </label>
                
                <!-- Status de conexão -->
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-green-500 rounded-full" title="Conectado"></div>
                    <span class="text-xs text-green-600 font-medium">Online</span>
                </div>
            </div>
        `;
    }).join('');
}

// Toggle seleção de instância
function toggleInstance(instanceName) {
    const index = selectedInstances.indexOf(instanceName);
    if (index > -1) {
        selectedInstances.splice(index, 1);
    } else {
        selectedInstances.push(instanceName);
    }
    updateSelectedInstancesDisplay();
}

// Atualizar display de instâncias selecionadas
function updateSelectedInstancesDisplay() {
    const display = document.getElementById('selected-instances');
    if (selectedInstances.length === 0) {
        display.textContent = 'Nenhuma instância selecionada';
    } else {
        display.textContent = selectedInstances.join(', ');
    }
}

// Adicionar mensagem ao log
function addToLog(message) {
    const log = document.getElementById('activity-log');
    const timestamp = new Date().toLocaleTimeString();
    const logEntry = document.createElement('div');
    logEntry.innerHTML = `<span class="text-gray-400">[${timestamp}]</span> ${message}`;
    log.appendChild(logEntry);
    log.scrollTop = log.scrollHeight;
}

// Atualizar status e progresso
function updateStatus(status, progress = null) {
    document.getElementById('status-text').textContent = status;
    if (progress !== null) {
        document.getElementById('progress-bar').style.width = progress + '%';
        document.getElementById('progress-text').textContent = progress + '%';
    }
}

// Iniciar aquecimento
document.getElementById('start-warming').addEventListener('click', async function() {
    if (isWarming) return;
    
    if (selectedInstances.length === 0) {
        alert('Selecione pelo menos uma instância para aquecer.');
        return;
    }
    
    const interactions = parseInt(document.getElementById('interactions').value);
    if (interactions < 1 || interactions > 100) {
        alert('O número de interações deve estar entre 1 e 100.');
        return;
    }
    
    isWarming = true;
    this.disabled = true;
    this.innerHTML = '<svg class="w-5 h-5 inline mr-2 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Aquecendo...';
    
    updateStatus('Iniciando aquecimento...', 0);
    addToLog(`Iniciando aquecimento de ${selectedInstances.length} instância(s) com ${interactions} interações`);
    
    try {
        const response = await fetch('/client/aquecimento-chip/executar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                instances: selectedInstances,
                interactions: interactions
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            updateStatus('Aquecimento em andamento...', 25);
            addToLog('Aquecimento iniciado com sucesso!');
            
            // Simular progresso (em um cenário real, você receberia updates do n8n)
            simulateProgress();
        } else {
            throw new Error(data.message || 'Erro desconhecido');
        }
        
    } catch (error) {
        console.error('Erro ao iniciar aquecimento:', error);
        addToLog('Erro ao iniciar aquecimento: ' + error.message);
        updateStatus('Erro', 0);
        resetButton();
    }
});

// Simular progresso do aquecimento
function simulateProgress() {
    let progress = 25;
    const interval = setInterval(() => {
        progress += Math.random() * 15;
        if (progress >= 100) {
            progress = 100;
            updateStatus('Aquecimento concluído!', progress);
            addToLog('Aquecimento concluído com sucesso!');
            resetButton();
            clearInterval(interval);
        } else {
            updateStatus('Aquecimento em andamento...', Math.floor(progress));
        }
    }, 2000);
}

// Atualizar lista de instâncias
async function refreshInstances() {
    try {
        console.log('Atualizando lista de instâncias...');
        const refreshBtn = document.getElementById('refreshBtn');
        const originalContent = refreshBtn.innerHTML;
        
        // Mostrar loading no botão
        refreshBtn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
        refreshBtn.disabled = true;
        
        const response = await fetch('/client/api/instances', {
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (!response.ok) {
            throw new Error(`Erro HTTP: ${response.status} ${response.statusText}`);
        }
        
        const text = await response.text();
        
        if (!text || text.trim() === '') {
            console.error('Resposta vazia ao atualizar instâncias');
            return;
        }
        
        const responseData = JSON.parse(text);
        
        if (responseData.success && responseData.instances) {
            instances = responseData.instances;
            loadInstances();
            console.log('Instâncias atualizadas com sucesso!');
        } else {
            throw new Error('Estrutura de resposta não reconhecida');
        }
        
        // Restaurar botão
        refreshBtn.innerHTML = originalContent;
        refreshBtn.disabled = false;
        
    } catch (error) {
        console.error('Erro ao atualizar instâncias:', error);
        
        // Restaurar botão em caso de erro
        const refreshBtn = document.getElementById('refreshBtn');
        refreshBtn.innerHTML = '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>Atualizar';
        refreshBtn.disabled = false;
    }
}

// Resetar botão
function resetButton() {
    isWarming = false;
    const button = document.getElementById('start-warming');
    button.disabled = false;
    button.innerHTML = '<svg class="w-5 h-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048 8.287 8.287 0 009 9.6a8.983 8.983 0 013.361-6.867 8.21 8.21 0 003 2.48z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 18a3.75 3.75 0 00.495-7.467 5.99 5.99 0 00-1.925 3.546 5.974 5.974 0 01-2.133-1A3.75 3.75 0 0012 18z" /></svg>Iniciar Aquecimento';
}
</script>
</x-layouts.app>