<x-layouts.app>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="mt-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Coluna Principal -->
                <div class="lg:col-span-2">
                    <!-- Header -->
                    <div class="mb-8">
                        <div class="mb-4">
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Lead Hunter</h1>
                            <p class="text-gray-600 dark:text-gray-400">Encontre leads no Google Maps com inteligência artificial</p>
                        </div>
                    </div>

                    <!-- Formulário de Busca -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Digite a busca no Google Maps</h2>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">Descreva o tipo de negócio e localização que você está procurando</p>

                        <div class="space-y-4">
                            <div>
                                <textarea 
                                    id="searchQuery"
                                    placeholder="Ex: lojas de carro em Betim - Minas Gerais"
                                    rows="3"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-white resize-none"
                                ></textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Limite de resultados</label>
                                    <input 
                                        type="number" 
                                        id="resultLimit"
                                        value="20"
                                        min="1"
                                        max="100"
                                        placeholder="Ex: 20"
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                    />
                                </div>

                                <div class="flex items-end">
                                    <flux:button 
                                        onclick="startScraping()"
                                        id="startScrapingBtn"
                                        variant="primary"
                                        class="w-full"
                                    >
                                        <flux:icon.magnifying-glass class="w-5 h-5 mr-2" />
                                        Executar Busca
                                    </flux:button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Coluna Lateral -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Como Funciona -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-center mb-4">
                            <flux:icon.information-circle class="w-5 h-5 text-blue-500 mr-2" />
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Como Funciona</h3>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3 mt-0.5">1</div>
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white">Busca Inteligente</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Utiliza a API do Google Maps para encontrar empresas</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3 mt-0.5">2</div>
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white">Filtragem Automática</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Filtra apenas telefones brasileiros válidos</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3 mt-0.5">3</div>
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white">Export CSV</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Clique no botão para baixar um arquivo CSV com os resultados</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Exemplos de Busca -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-center mb-4">
                            <flux:icon.document-text class="w-5 h-5 mr-2" style="color: #155DFC" />
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Exemplos de Busca</h3>
                        </div>
                        <div class="space-y-3">
                            <div class="cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 p-3 rounded-lg transition-colors" onclick="setSearchQuery('restaurantes em São Paulo')">
                                <p class="text-sm text-gray-900 dark:text-white font-medium">restaurantes em São Paulo</p>
                            </div>
                            <div class="cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 p-3 rounded-lg transition-colors" onclick="setSearchQuery('lojas de roupas no Rio de Janeiro')">
                                <p class="text-sm text-gray-900 dark:text-white font-medium">lojas de roupas no Rio de Janeiro</p>
                            </div>
                            <div class="cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 p-3 rounded-lg transition-colors" onclick="setSearchQuery('oficinas mecânicas em Belo Horizonte')">
                                <p class="text-sm text-gray-900 dark:text-white font-medium">oficinas mecânicas em Belo Horizonte</p>
                            </div>
                            <div class="cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 p-3 rounded-lg transition-colors" onclick="setSearchQuery('clínicas veterinárias em Curitiba')">
                                <p class="text-sm text-gray-900 dark:text-white font-medium">clínicas veterinárias em Curitiba</p>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        



        <!-- Modal de Status da Execução -->
        <div id="executionStatus" class="hidden fixed inset-0 flex items-center justify-center z-50" style="background-color: rgba(0, 0, 0, 0.10);">
            <div class="bg-white rounded-lg shadow-xl border border-gray-200 p-6 max-w-md w-full mx-4" onclick="event.stopPropagation()">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Status da Busca</h2>

                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-green-500"></div>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-medium text-gray-900">
                                Buscando leads...
                            </div>
                            <div id="statusMessage" class="text-sm text-gray-500">
                                Iniciando busca no Google Maps
                            </div>
                        </div>
                    </div>

                    <!-- Barra de Progresso Simulada -->
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div id="progressBar" class="bg-green-600 h-2 rounded-full transition-all duration-1000" style="width: 0%"></div>
                    </div>

                    <div class="text-center">
                        <button 
                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                            onclick="cancelScraping()"
                            id="cancelBtn"
                        >
                            ✕ Cancelar Busca
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Resultados -->
        <div id="resultsSection" class="hidden fixed inset-0 flex items-center justify-center z-50" style="background-color: rgba(0, 0, 0, 0.10);" onclick="closeResultsModal()">
            <div class="bg-white rounded-lg shadow-xl border border-gray-200 p-6 max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">Resultados da Busca</h2>
                        <p class="text-sm text-gray-600 mt-1" id="resultsSubheading">Leads capturados com sucesso</p>
                    </div>
                    <div class="flex space-x-2">
                        <button 
                            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                            onclick="downloadCSV()"
                            id="downloadBtn"
                        >
                            📥 Download CSV
                        </button>
                        <button 
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                            onclick="closeResultsModal()"
                        >
                            ✕ Fechar
                        </button>
                    </div>
                </div>

                <!-- Estatísticas -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-green-600" id="totalLeads">0</div>
                        <div class="text-sm text-green-600">Total de Leads</div>
                    </div>
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-blue-600" id="validPhones">0</div>
                        <div class="text-sm text-blue-600">Telefones Válidos</div>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="flex flex-wrap gap-4 mb-4">
                    <select id="filterType" onchange="filterResults()" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="phone">Apenas Telefones Válidos</option>
                    </select>
                    
                    <input 
                        type="text" 
                        id="searchFilter"
                        placeholder="Buscar por nome..."
                        onkeyup="filterResults()"
                        class="flex-1 max-w-xs border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    />
                </div>

                <!-- Tabela de Resultados -->
                <div class="overflow-x-auto">
                    <table class="w-full" id="resultsTable">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telefone</th>
                            </tr>
                        </thead>
                        <tbody id="resultsTableBody" class="divide-y divide-gray-200">
                            <!-- Resultados serão inseridos aqui -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Mensagens de Erro -->
        <div id="errorSection" class="hidden">
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="font-medium text-red-800">Erro na Busca</div>
                        <div id="errorMessage" class="text-sm mt-1 text-red-600"></div>
                    </div>
                    <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors" onclick="resetSearch()">
                        Tentar Novamente
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Sucesso -->
    <div id="successModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full mx-4">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                    <flux:icon.check class="h-6 w-6 text-green-600" />
                </div>
                <flux:heading size="lg" class="mb-2">Leads Enviados!</flux:heading>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    Os leads foram processados com sucesso.
                </p>
                <flux:button variant="primary" onclick="closeSuccessModal()">
                    Entendi
                </flux:button>
            </div>
        </div>
    </div>

    <script>
        let scrapingActive = false;
        let scrapingResults = [];
        let filteredResults = [];

        function setSearchQuery(query) {
            document.getElementById('searchQuery').value = query;
        }

        function switchTab(tabName) {
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active', 'border-blue-500', 'text-blue-600', 'dark:text-blue-400');
                button.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'dark:text-gray-400', 'dark:hover:text-gray-300');
            });
            
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Ativar aba selecionada
            const activeButton = document.getElementById(`tab-${tabName}`);
            activeButton.classList.add('active', 'border-blue-500', 'text-blue-600', 'dark:text-blue-400');
            activeButton.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'dark:text-gray-400', 'dark:hover:text-gray-300');
            
            // Mostrar conteúdo da aba selecionada
            document.getElementById(`content-${tabName}`).classList.remove('hidden');
        }

        async function startScraping() {
            const query = document.getElementById('searchQuery').value.trim();
            const limit = parseInt(document.getElementById('resultLimit').value);

            if (!query) {
                alert('Por favor, digite uma query de busca');
                return;
            }

            if (limit < 1 || limit > 100) {
                alert('O limite deve estar entre 1 e 100');
                return;
            }

            scrapingActive = true;
            showExecutionStatus();
            
            try {
                // Iniciar captura via Apify
                const startResponse = await fetch('/client/apify/start-capture', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        search_query: query,
                        max_results: limit
                    })
                });

                if (!startResponse.ok) {
                    throw new Error(`Erro ao iniciar busca: ${startResponse.status}`);
                }

                const startData = await startResponse.json();
                
                if (!startData.success) {
                    throw new Error(startData.error || 'Erro ao iniciar busca');
                }

                const runId = startData.run_id;
                
                // Monitorar progresso
                await monitorApifyRun(runId);

            } catch (error) {
                if (scrapingActive) {
                    showError('Erro ao buscar leads: ' + error.message);
                }
            }
        }

        async function monitorApifyRun(runId) {
            const maxAttempts = 60; // 5 minutos máximo
            let attempts = 0;
            
            const checkStatus = async () => {
                if (!scrapingActive || attempts >= maxAttempts) {
                    return;
                }
                
                try {
                    const statusResponse = await fetch(`/client/apify/run-status/${runId}`);
                    const statusData = await statusResponse.json();
                    
                    if (statusData.success) {
                        const status = statusData.status;
                        
                        if (status === 'SUCCEEDED') {
                            // Buscar resultados
                            const resultsResponse = await fetch(`/client/apify/run-results/${runId}`);
                            const resultsData = await resultsResponse.json();
                            
                            if (resultsData.success && scrapingActive) {
                                processScrapingResults(resultsData.results);
                            }
                            return;
                        } else if (status === 'FAILED' || status === 'ABORTED') {
                            throw new Error('Busca falhou ou foi cancelada');
                        }
                        
                        // Continuar monitorando
                        attempts++;
                        setTimeout(checkStatus, 5000); // Verificar a cada 5 segundos
                    } else {
                        throw new Error(statusData.error || 'Erro ao verificar status');
                    }
                } catch (error) {
                    if (scrapingActive) {
                        showError('Erro ao monitorar busca: ' + error.message);
                    }
                }
            };
            
            // Simular progresso enquanto monitora
            simulateProgress();
            
            // Iniciar monitoramento
            setTimeout(checkStatus, 2000); // Primeira verificação após 2 segundos
        }

        function simulateProgress() {
            const progressBar = document.getElementById('progressBar');
            const statusMessage = document.getElementById('statusMessage');
            
            const steps = [
                { progress: 20, message: 'Conectando ao Google Maps...' },
                { progress: 40, message: 'Buscando estabelecimentos...' },
                { progress: 60, message: 'Extraindo informações de contato...' },
                { progress: 80, message: 'Validando apenas telefones brasileiros válidos...' },
                { progress: 95, message: 'Finalizando busca...' }
            ];

            let currentStep = 0;
            
            const interval = setInterval(() => {
                if (!scrapingActive || currentStep >= steps.length) {
                    clearInterval(interval);
                    return;
                }

                const step = steps[currentStep];
                progressBar.style.width = `${step.progress}%`;
                statusMessage.textContent = step.message;
                
                currentStep++;
            }, 1500);
        }

        function processScrapingResults(data) {
            scrapingActive = false;
            
            // Processar diferentes formatos de resposta
            let results = [];
            
            if (Array.isArray(data)) {
                results = data;
            } else if (data.results && Array.isArray(data.results)) {
                results = data.results;
            } else if (data.leads && Array.isArray(data.leads)) {
                results = data.leads;
            } else {
                throw new Error('Formato de resposta inválida');
            }



            // Validar e normalizar telefones brasileiros - APENAS TELEFONES VÁLIDOS
            scrapingResults = results.map(lead => {
                const telefoneOriginal = lead.telefone || lead.phone || '';
                const telefoneValidado = validateAndNormalizeBrazilianPhone(telefoneOriginal);
                

                
                return {
                    nome: lead.nome || lead.name || lead.title || 'N/A',
                    telefone: telefoneValidado,
                    email: lead.email || '',
                    website: lead.website || lead.site || '',
                    endereco: lead.endereco || lead.address || '',
                    avaliacao: lead.avaliacao || lead.rating || '',
                    avaliacoes_count: lead.avaliacoes_count || lead.reviews_count || 0
                };
            }).filter(lead => lead.nome !== 'N/A' && lead.telefone !== ''); // FILTRAR APENAS COM TELEFONE VÁLIDO


            
            filteredResults = [...scrapingResults];
            
            hideExecutionStatus();
            showResults();
            updateStatistics();
            renderResultsTable();
        }

        function validateAndNormalizeBrazilianPhone(phone) {
            if (!phone) return '';
            
            const cleaned = phone.replace(/\D/g, '');
            
            // Verifica se é um número brasileiro válido
            // Celular com 9 dígitos (11 dígitos total com DDD)
            if (cleaned.length === 11 && /^[1-9][1-9]9\d{8}$/.test(cleaned)) {
                return '55' + cleaned; // Adiciona código do país
            } 
            // Telefone fixo (10 dígitos total com DDD)
            else if (cleaned.length === 10 && /^[1-9][1-9][2-5]\d{7}$/.test(cleaned)) {
                return '55' + cleaned; // Adiciona código do país
            } 
            // Número já com código do país
            else if (cleaned.length === 13 && cleaned.startsWith('55')) {
                const localNumber = cleaned.substring(2);
                // Validar se o número local é válido
                if (/^[1-9][1-9](9\d{8}|[2-5]\d{7})$/.test(localNumber)) {
                    return cleaned;
                }
            }
            // Número com código do país e mais dígitos (formato internacional)
            else if (cleaned.length === 14 && cleaned.startsWith('55')) {
                const localNumber = cleaned.substring(2);
                if (/^[1-9][1-9]9\d{8}$/.test(localNumber)) {
                    return cleaned.substring(0, 13); // Remove dígito extra
                }
            }
            // NOVA VALIDAÇÃO: Aceitar números com formatos mais flexíveis
            else if (cleaned.length >= 10 && cleaned.length <= 15) {
                // Tentar extrair um número brasileiro válido
                let possibleNumber = cleaned;
                
                // Se tem código do país, remove
                if (possibleNumber.startsWith('55') && possibleNumber.length > 11) {
                    possibleNumber = possibleNumber.substring(2);
                }
                
                // Se ainda tem mais de 11 dígitos, pega os últimos 11
                if (possibleNumber.length > 11) {
                    possibleNumber = possibleNumber.substring(possibleNumber.length - 11);
                }
                
                // Verifica se agora é um número válido
                if (possibleNumber.length === 11 && /^[1-9][1-9]9\d{8}$/.test(possibleNumber)) {
                    return '55' + possibleNumber;
                }
                if (possibleNumber.length === 10 && /^[1-9][1-9][2-5]\d{7}$/.test(possibleNumber)) {
                    return '55' + possibleNumber;
                }
            }
            
            return ''; // Número inválido
        }

        function updateStatistics() {
            const totalLeads = scrapingResults.length;
            const validPhones = scrapingResults.filter(lead => lead.telefone).length;

            document.getElementById('totalLeads').textContent = totalLeads;
            document.getElementById('validPhones').textContent = validPhones;

            document.getElementById('resultsSubheading').textContent = 
                `${totalLeads} leads encontrados, ${validPhones} com telefone válido`;
        }

        function renderResultsTable() {
            const tbody = document.getElementById('resultsTableBody');
            tbody.innerHTML = filteredResults.map(lead => `
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                    <td class="px-4 py-4 whitespace-nowrap">
                        <div class="font-medium text-gray-900 dark:text-gray-100">${lead.nome}</div>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        ${lead.telefone ? `
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                ${formatPhoneDisplay(lead.telefone)}
                            </span>
                        ` : `
                            <span class="text-gray-400">-</span>
                        `}
                    </td>
                </tr>
            `).join('');
        }

        function formatPhoneDisplay(phone) {
            if (phone.length === 13 && phone.startsWith('55')) {
                const number = phone.substring(2);
                return `+55 (${number.substring(0, 2)}) ${number.substring(2, 7)}-${number.substring(7)}`;
            }
            return phone;
        }

        function filterResults() {
            const filterType = document.getElementById('filterType').value;
            const searchTerm = document.getElementById('searchFilter').value.toLowerCase();

            filteredResults = scrapingResults.filter(lead => {
                // SEMPRE filtrar apenas leads com telefone válido
                const hasValidPhone = !!lead.telefone;
                
                // Filtro por busca
                const searchMatch = !searchTerm || 
                    lead.nome.toLowerCase().includes(searchTerm);

                return hasValidPhone && searchMatch;
            });

            renderResultsTable();
        }

        function downloadCSV() {
            const headers = ['Nome', 'Telefone'];
            const csvContent = [
                headers.join(','),
                ...filteredResults.map(lead => [
                    `"${lead.nome}"`,
                    lead.telefone || ''
                ].join(','))
            ].join('\n');

            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            
            link.setAttribute('href', url);
            link.setAttribute('download', `leads-${new Date().toISOString().split('T')[0]}.csv`);
            link.style.visibility = 'hidden';
            
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function showExecutionStatus() {
            document.getElementById('executionStatus').classList.remove('hidden');
            document.getElementById('resultsSection').classList.add('hidden');
            document.getElementById('errorSection').classList.add('hidden');
            document.getElementById('startScrapingBtn').disabled = true;
        }

        function hideExecutionStatus() {
            document.getElementById('executionStatus').classList.add('hidden');
            document.getElementById('startScrapingBtn').disabled = false;
        }

        function showResults() {
            document.getElementById('resultsSection').classList.remove('hidden');
            document.getElementById('errorSection').classList.add('hidden');
        }

        function showError(message) {
            document.getElementById('errorMessage').textContent = message;
            document.getElementById('errorSection').classList.remove('hidden');
            document.getElementById('resultsSection').classList.add('hidden');
            hideExecutionStatus();
        }

        function showSuccessModal() {
            document.getElementById('successModal').classList.remove('hidden');
        }

        function closeSuccessModal() {
            document.getElementById('successModal').classList.add('hidden');
        }

        function closeResultsModal() {
            document.getElementById('resultsSection').classList.add('hidden');
        }

        function cancelScraping() {
            scrapingActive = false;
            hideExecutionStatus();
        }

        function resetSearch() {
            document.getElementById('errorSection').classList.add('hidden');
            document.getElementById('searchQuery').value = '';
            document.getElementById('resultLimit').value = '20';
        }
    </script>
</x-layouts.app>