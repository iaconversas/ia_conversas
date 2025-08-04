<x-layouts.app>
    <flux:heading size="xl">Lead Hunter</flux:heading>
    <flux:subheading>Captura de Leads do Google Maps</flux:subheading>

    <div class="mt-6 space-y-6">
        <!-- Formulário de Busca -->
        <flux:card class="border-l-4 border-l-green-500">
            <flux:card.header>
                <flux:heading size="lg">Configurar Busca</flux:heading>
                <flux:subheading>Configure os parâmetros para capturar leads</flux:subheading>
            </flux:card.header>

            <div class="space-y-6">
                <flux:field>
                    <flux:label>Query de Busca</flux:label>
                    <flux:textarea 
                        id="searchQuery"
                        placeholder="Digite sua busca do Google Maps..."
                        rows="3"
                        class="w-full"
                    ></flux:textarea>
                    <flux:description>
                        Exemplos: "restaurantes em São Paulo", "dentistas em Rio de Janeiro", "academias em Belo Horizonte"
                    </flux:description>
                </flux:field>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:field>
                        <flux:label>Limite de Resultados</flux:label>
                        <flux:input 
                            type="number" 
                            id="resultLimit"
                            value="20"
                            min="1"
                            max="100"
                            class="w-full"
                        />
                        <flux:description>Máximo: 100 resultados</flux:description>
                    </flux:field>

                    <div class="flex items-end">
                        <flux:button 
                            variant="primary" 
                            onclick="startScraping()"
                            id="startScrapingBtn"
                            class="w-full"
                        >
                            <flux:icon.magnifying-glass class="size-4 mr-2" />
                            Iniciar Busca
                        </flux:button>
                    </div>
                </div>

                <!-- Exemplos de Busca -->
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <flux:heading size="sm" class="mb-3">Exemplos de Buscas Eficazes</flux:heading>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                        <div class="space-y-1">
                            <div class="cursor-pointer hover:text-blue-600" onclick="setSearchQuery('restaurantes em São Paulo')">
                                • "restaurantes em São Paulo"
                            </div>
                            <div class="cursor-pointer hover:text-blue-600" onclick="setSearchQuery('dentistas em Rio de Janeiro')">
                                • "dentistas em Rio de Janeiro"
                            </div>
                            <div class="cursor-pointer hover:text-blue-600" onclick="setSearchQuery('academias em Belo Horizonte')">
                                • "academias em Belo Horizonte"
                            </div>
                        </div>
                        <div class="space-y-1">
                            <div class="cursor-pointer hover:text-blue-600" onclick="setSearchQuery('salões de beleza em Brasília')">
                                • "salões de beleza em Brasília"
                            </div>
                            <div class="cursor-pointer hover:text-blue-600" onclick="setSearchQuery('oficinas mecânicas em Curitiba')">
                                • "oficinas mecânicas em Curitiba"
                            </div>
                            <div class="cursor-pointer hover:text-blue-600" onclick="setSearchQuery('pet shops em Fortaleza')">
                                • "pet shops em Fortaleza"
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </flux:card>

        <!-- Status da Execução -->
        <div id="executionStatus" class="hidden">
            <flux:card class="border-l-4 border-l-yellow-500">
                <flux:card.header>
                    <flux:heading size="lg">Status da Busca</flux:heading>
                </flux:card.header>

                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-green-500"></div>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
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
                        <flux:button 
                            variant="danger" 
                            size="sm"
                            onclick="cancelScraping()"
                            id="cancelBtn"
                        >
                            <flux:icon.x-mark class="size-4 mr-2" />
                            Cancelar Busca
                        </flux:button>
                    </div>
                </div>
            </flux:card>
        </div>

        <!-- Resultados -->
        <div id="resultsSection" class="hidden">
            <flux:card class="border-l-4 border-l-blue-500">
                <flux:card.header>
                    <div class="flex items-center justify-between">
                        <div>
                            <flux:heading size="lg">Resultados da Busca</flux:heading>
                            <flux:subheading id="resultsSubheading">Leads capturados com sucesso</flux:subheading>
                        </div>
                        <div class="flex space-x-2">
                            <flux:button 
                                variant="outline" 
                                size="sm"
                                onclick="downloadCSV()"
                                id="downloadBtn"
                            >
                                <flux:icon.document-arrow-down class="size-4 mr-2" />
                                Download CSV
                            </flux:button>
                            <flux:button 
                                variant="primary" 
                                size="sm"
                                onclick="sendToDisparo()"
                                id="sendToDisparoBtn"
                            >
                                <flux:icon.paper-airplane class="size-4 mr-2" />
                                Enviar para Disparo
                            </flux:button>
                        </div>
                    </div>
                </flux:card.header>

                <!-- Estatísticas -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-green-600" id="totalLeads">0</div>
                        <div class="text-sm text-green-600">Total de Leads</div>
                    </div>
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-blue-600" id="validPhones">0</div>
                        <div class="text-sm text-blue-600">Telefones Válidos</div>
                    </div>
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-yellow-600" id="withEmail">0</div>
                        <div class="text-sm text-yellow-600">Com E-mail</div>
                    </div>
                    <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-purple-600" id="withWebsite">0</div>
                        <div class="text-sm text-purple-600">Com Website</div>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="flex flex-wrap gap-4 mb-4">
                    <flux:select id="filterType" onchange="filterResults()">
                        <option value="all">Todos os Leads</option>
                        <option value="phone">Apenas com Telefone</option>
                        <option value="email">Apenas com E-mail</option>
                        <option value="website">Apenas com Website</option>
                    </flux:select>
                    
                    <flux:input 
                        type="text" 
                        id="searchFilter"
                        placeholder="Buscar por nome..."
                        onkeyup="filterResults()"
                        class="flex-1 max-w-xs"
                    />
                </div>

                <!-- Tabela de Resultados -->
                <div class="overflow-x-auto">
                    <table class="w-full" id="resultsTable">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telefone</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">E-mail</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Website</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Endereço</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avaliação</th>
                            </tr>
                        </thead>
                        <tbody id="resultsTableBody" class="divide-y divide-gray-200 dark:divide-gray-700">
                            <!-- Resultados serão inseridos aqui -->
                        </tbody>
                    </table>
                </div>
            </flux:card>
        </div>

        <!-- Mensagens de Erro -->
        <div id="errorSection" class="hidden">
            <flux:banner variant="danger">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="font-medium">Erro na Busca</div>
                        <div id="errorMessage" class="text-sm mt-1"></div>
                    </div>
                    <flux:button variant="ghost" size="sm" onclick="resetSearch()">
                        Tentar Novamente
                    </flux:button>
                </div>
            </flux:banner>
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
                    Os leads foram enviados para o Disparo Inteligente com sucesso.
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
                // Simular progresso
                simulateProgress();

                const response = await fetch('https://n8n.iaconversas.com/webhook/raspar', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        busca: query,
                        limite: limit
                    })
                });

                if (!response.ok) {
                    throw new Error(`Erro HTTP: ${response.status}`);
                }

                const data = await response.json();
                
                if (scrapingActive) {
                    processScrapingResults(data);
                }

            } catch (error) {
                if (scrapingActive) {
                    showError('Erro ao buscar leads: ' + error.message);
                }
            }
        }

        function simulateProgress() {
            const progressBar = document.getElementById('progressBar');
            const statusMessage = document.getElementById('statusMessage');
            
            const steps = [
                { progress: 20, message: 'Conectando ao Google Maps...' },
                { progress: 40, message: 'Buscando estabelecimentos...' },
                { progress: 60, message: 'Extraindo informações de contato...' },
                { progress: 80, message: 'Validando telefones brasileiros...' },
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
                throw new Error('Formato de resposta inválido');
            }

            // Validar e normalizar telefones brasileiros
            scrapingResults = results.map(lead => ({
                nome: lead.nome || lead.name || lead.title || 'N/A',
                telefone: validateAndNormalizeBrazilianPhone(lead.telefone || lead.phone || ''),
                email: lead.email || '',
                website: lead.website || lead.site || '',
                endereco: lead.endereco || lead.address || '',
                avaliacao: lead.avaliacao || lead.rating || '',
                avaliacoes_count: lead.avaliacoes_count || lead.reviews_count || 0
            })).filter(lead => lead.nome !== 'N/A');

            filteredResults = [...scrapingResults];
            
            hideExecutionStatus();
            showResults();
            updateStatistics();
            renderResultsTable();
        }

        function validateAndNormalizeBrazilianPhone(phone) {
            if (!phone) return '';
            
            // Remove todos os caracteres não numéricos
            const cleaned = phone.replace(/\D/g, '');
            
            // Verifica se é um número brasileiro válido
            if (cleaned.length === 11 && /^[1-9][1-9]9\d{8}$/.test(cleaned)) {
                return '55' + cleaned; // Adiciona código do país
            } else if (cleaned.length === 10 && /^[1-9][1-9]\d{8}$/.test(cleaned)) {
                return '55' + cleaned; // Adiciona código do país
            } else if (cleaned.length === 13 && cleaned.startsWith('55')) {
                return cleaned;
            }
            
            return ''; // Número inválido
        }

        function updateStatistics() {
            const totalLeads = scrapingResults.length;
            const validPhones = scrapingResults.filter(lead => lead.telefone).length;
            const withEmail = scrapingResults.filter(lead => lead.email).length;
            const withWebsite = scrapingResults.filter(lead => lead.website).length;

            document.getElementById('totalLeads').textContent = totalLeads;
            document.getElementById('validPhones').textContent = validPhones;
            document.getElementById('withEmail').textContent = withEmail;
            document.getElementById('withWebsite').textContent = withWebsite;

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
                    <td class="px-4 py-4 whitespace-nowrap">
                        ${lead.email ? `
                            <a href="mailto:${lead.email}" class="text-blue-600 hover:text-blue-800 text-sm">
                                ${lead.email}
                            </a>
                        ` : `
                            <span class="text-gray-400">-</span>
                        `}
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        ${lead.website ? `
                            <a href="${lead.website}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm">
                                ${lead.website.replace(/^https?:\/\//, '')}
                            </a>
                        ` : `
                            <span class="text-gray-400">-</span>
                        `}
                    </td>
                    <td class="px-4 py-4">
                        <div class="text-sm text-gray-900 dark:text-gray-100 max-w-xs truncate">
                            ${lead.endereco || '-'}
                        </div>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        ${lead.avaliacao ? `
                            <div class="flex items-center">
                                <span class="text-yellow-400">★</span>
                                <span class="ml-1 text-sm">${lead.avaliacao}</span>
                                ${lead.avaliacoes_count ? `
                                    <span class="ml-1 text-xs text-gray-500">(${lead.avaliacoes_count})</span>
                                ` : ''}
                            </div>
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
                // Filtro por tipo
                let typeMatch = true;
                switch (filterType) {
                    case 'phone':
                        typeMatch = !!lead.telefone;
                        break;
                    case 'email':
                        typeMatch = !!lead.email;
                        break;
                    case 'website':
                        typeMatch = !!lead.website;
                        break;
                }

                // Filtro por busca
                const searchMatch = !searchTerm || 
                    lead.nome.toLowerCase().includes(searchTerm) ||
                    lead.endereco.toLowerCase().includes(searchTerm);

                return typeMatch && searchMatch;
            });

            renderResultsTable();
        }

        function downloadCSV() {
            const headers = ['Nome', 'Telefone', 'Email', 'Website', 'Endereço', 'Avaliação', 'Número de Avaliações'];
            const csvContent = [
                headers.join(','),
                ...filteredResults.map(lead => [
                    `"${lead.nome}"`,
                    lead.telefone,
                    lead.email,
                    lead.website,
                    `"${lead.endereco}"`,
                    lead.avaliacao,
                    lead.avaliacoes_count
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

        function sendToDisparo() {
            // Filtrar apenas leads com telefone válido
            const leadsWithPhone = filteredResults.filter(lead => lead.telefone);
            
            if (leadsWithPhone.length === 0) {
                alert('Nenhum lead com telefone válido encontrado');
                return;
            }

            // Armazenar no localStorage para uso no Disparo Inteligente
            const contactsData = leadsWithPhone.map(lead => ({
                numero: lead.telefone,
                nome: lead.nome,
                email: lead.email,
                website: lead.website,
                endereco: lead.endereco
            }));

            localStorage.setItem('leadHunterContacts', JSON.stringify(contactsData));
            localStorage.setItem('leadHunterVariables', JSON.stringify(['nome', 'email', 'website', 'endereco']));
            
            showSuccessModal();
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