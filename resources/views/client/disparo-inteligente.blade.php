<x-layouts.app>
    <flux:heading size="xl">Disparo Inteligente</flux:heading>
    <flux:subheading>Sistema de Envio em Massa</flux:subheading>

    <div class="mt-6">
        <!-- Progress Steps -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div id="step1-indicator" class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-500 text-white text-sm font-medium">1</div>
                    <span class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">Configuração</span>
                </div>
                <div class="flex-1 mx-4 h-1 bg-gray-200 dark:bg-gray-700">
                    <div id="progress1" class="h-1 bg-blue-500 transition-all duration-300" style="width: 33%"></div>
                </div>
                <div class="flex items-center">
                    <div id="step2-indicator" class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-300 text-gray-600 text-sm font-medium">2</div>
                    <span class="ml-2 text-sm font-medium text-gray-500">Mensagens</span>
                </div>
                <div class="flex-1 mx-4 h-1 bg-gray-200 dark:bg-gray-700">
                    <div id="progress2" class="h-1 bg-blue-500 transition-all duration-300" style="width: 0%"></div>
                </div>
                <div class="flex items-center">
                    <div id="step3-indicator" class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-300 text-gray-600 text-sm font-medium">3</div>
                    <span class="ml-2 text-sm font-medium text-gray-500">Preview & Execução</span>
                </div>
            </div>
        </div>

        <!-- Step 1: Configuração -->
        <div id="step1" class="step-content">
            <flux:card class="border-l-4 border-l-blue-500">
                <flux:card.header>
                    <flux:heading size="lg">Etapa 1: Configuração</flux:heading>
                    <flux:subheading>Configure instâncias e importe contatos</flux:subheading>
                </flux:card.header>

                <div class="space-y-6">
                    <!-- Seleção de Instâncias -->
                    <div>
                        <flux:heading size="sm" class="mb-3">Instâncias WhatsApp</flux:heading>
                        <div id="instancesSelection" class="space-y-2 max-h-40 overflow-y-auto border rounded-lg p-4">
                            <!-- Instâncias serão carregadas aqui -->
                        </div>
                        <flux:button variant="ghost" size="sm" onclick="loadConnectedInstances()" class="mt-2">
                            <flux:icon.arrow-path class="size-4" />
                            Atualizar Instâncias
                        </flux:button>
                    </div>

                    <!-- Importação de Contatos -->
                    <div>
                        <flux:heading size="sm" class="mb-3">Importação de Contatos</flux:heading>
                        
                        <flux:tab.group>
                            <flux:tabs variant="segmented">
                                <flux:tab name="manual">Manual</flux:tab>
                                <flux:tab name="file">Arquivo CSV/XLSX</flux:tab>
                            </flux:tabs>

                            <flux:tab.panel name="manual">
                                <div class="space-y-4">
                                    <flux:field>
                                        <flux:label>Números de Telefone</flux:label>
                                        <flux:textarea 
                                            id="manualContacts"
                                            placeholder="Digite os números (um por linha ou separados por vírgula)&#10;Exemplo:&#10;11999999999&#10;21888888888&#10;ou&#10;11999999999, 21888888888"
                                            rows="6"
                                            class="w-full"
                                        ></flux:textarea>
                                    </flux:field>
                                    <flux:button variant="outline" size="sm" onclick="processManualContacts()">
                                        <flux:icon.phone class="size-4" />
                                        Processar Números
                                    </flux:button>
                                </div>
                            </flux:tab.panel>

                            <flux:tab.panel name="file">
                                <div class="space-y-4">
                                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center">
                                        <input type="file" id="csvFile" accept=".csv,.xlsx,.xls" class="hidden" onchange="handleFileUpload(event)">
                                        <flux:button variant="outline" onclick="document.getElementById('csvFile').click()">
                                            <flux:icon.document-arrow-up class="size-4" />
                                            Selecionar Arquivo CSV/XLSX
                                        </flux:button>
                                        <p class="mt-2 text-sm text-gray-500">Máximo 10MB</p>
                                    </div>
                                    <div id="fileInfo" class="hidden">
                                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                                            <div class="flex items-center">
                                                <flux:icon.document class="size-5 text-blue-500" />
                                                <span id="fileName" class="ml-2 font-medium"></span>
                                            </div>
                                            <div id="columnMapping" class="mt-3">
                                                <!-- Mapeamento de colunas será exibido aqui -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </flux:tab.panel>
                        </flux:tab.group>
                    </div>

                    <!-- Variáveis Detectadas -->
                    <div id="variablesSection" class="hidden">
                        <flux:heading size="sm" class="mb-3">Variáveis Detectadas</flux:heading>
                        <div id="variablesBadges" class="flex flex-wrap gap-2">
                            <!-- Badges de variáveis serão exibidas aqui -->
                        </div>
                        <p class="text-sm text-gray-500 mt-2">Clique duas vezes em uma variável para alternar o modo "primeira palavra apenas"</p>
                    </div>

                    <!-- Contatos Processados -->
                    <div id="contactsPreview" class="hidden">
                        <flux:heading size="sm" class="mb-3">Contatos Processados</flux:heading>
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                            <div id="contactsCount" class="text-sm text-gray-600 dark:text-gray-400 mb-2"></div>
                            <div id="contactsList" class="max-h-32 overflow-y-auto text-sm">
                                <!-- Lista de contatos será exibida aqui -->
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <flux:button variant="primary" onclick="goToStep(2)" id="nextStep1" disabled>
                            Próximo: Mensagens
                            <flux:icon.arrow-right class="size-4 ml-2" />
                        </flux:button>
                    </div>
                </div>
            </flux:card>
        </div>

        <!-- Step 2: Mensagens -->
        <div id="step2" class="step-content hidden">
            <flux:card class="border-l-4 border-l-green-500">
                <flux:card.header>
                    <flux:heading size="lg">Etapa 2: Mensagens</flux:heading>
                    <flux:subheading>Configure as mensagens a serem enviadas</flux:subheading>
                </flux:card.header>

                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <flux:heading size="sm">Mensagens por Contato</flux:heading>
                        <flux:select id="messageCount" onchange="updateMessageCount()">
                            <option value="1">1 mensagem</option>
                            <option value="2">2 mensagens</option>
                            <option value="3">3 mensagens</option>
                            <option value="4">4 mensagens</option>
                            <option value="5">5 mensagens</option>
                            <option value="6">6 mensagens</option>
                            <option value="7">7 mensagens</option>
                            <option value="8">8 mensagens</option>
                            <option value="9">9 mensagens</option>
                            <option value="10">10 mensagens</option>
                        </flux:select>
                    </div>

                    <div id="messagesContainer">
                        <!-- Mensagens serão adicionadas aqui -->
                    </div>

                    <div class="flex justify-between">
                        <flux:button variant="outline" onclick="goToStep(1)">
                            <flux:icon.arrow-left class="size-4 mr-2" />
                            Voltar
                        </flux:button>
                        <flux:button variant="primary" onclick="goToStep(3)" id="nextStep2">
                            Próximo: Preview
                            <flux:icon.arrow-right class="size-4 ml-2" />
                        </flux:button>
                    </div>
                </div>
            </flux:card>
        </div>

        <!-- Step 3: Preview e Execução -->
        <div id="step3" class="step-content hidden">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Configurações -->
                <flux:card class="border-l-4 border-l-purple-500">
                    <flux:card.header>
                        <flux:heading size="lg">Configurações de Envio</flux:heading>
                    </flux:card.header>

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <flux:field>
                                <flux:label>Tempo Mínimo (segundos)</flux:label>
                                <flux:input type="number" id="tempoMin" value="2" min="1" max="60" />
                            </flux:field>
                            <flux:field>
                                <flux:label>Tempo Máximo (segundos)</flux:label>
                                <flux:input type="number" id="tempoMax" value="5" min="1" max="60" />
                            </flux:field>
                        </div>

                        <div class="flex items-center space-x-3">
                            <flux:checkbox id="usarIA" />
                            <flux:label for="usarIA">Usar IA para variação de textos</flux:label>
                        </div>

                        <div class="space-y-3">
                            <flux:button variant="primary" onclick="startDisparo()" id="startBtn" class="w-full">
                                <flux:icon.play class="size-4 mr-2" />
                                Iniciar Disparo
                            </flux:button>
                            <flux:button variant="danger" onclick="stopDisparo()" id="stopBtn" class="w-full hidden">
                                <flux:icon.stop class="size-4 mr-2" />
                                Parar Execução
                            </flux:button>
                        </div>

                        <!-- Progress -->
                        <div id="disparoProgress" class="hidden space-y-3">
                            <div class="flex justify-between text-sm">
                                <span>Progresso</span>
                                <span id="progressText">0 / 0</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div id="progressBar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                            </div>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div class="text-center">
                                    <div class="text-green-600 font-bold" id="successCount">0</div>
                                    <div class="text-gray-500">Sucessos</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-red-600 font-bold" id="errorCount">0</div>
                                    <div class="text-gray-500">Erros</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </flux:card>

                <!-- Preview WhatsApp -->
                <flux:card class="border-l-4 border-l-green-500">
                    <flux:card.header>
                        <flux:heading size="lg">Preview WhatsApp</flux:heading>
                        <flux:subheading>Visualização das mensagens</flux:subheading>
                    </flux:card.header>

                    <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-4 max-h-96 overflow-y-auto">
                        <div id="whatsappPreview" class="space-y-3">
                            <!-- Preview das mensagens será exibido aqui -->
                        </div>
                    </div>
                </flux:card>
            </div>

            <div class="mt-6 flex justify-between">
                <flux:button variant="outline" onclick="goToStep(2)">
                    <flux:icon.arrow-left class="size-4 mr-2" />
                    Voltar
                </flux:button>
                <flux:button variant="ghost" onclick="downloadReport()" id="downloadBtn" class="hidden">
                    <flux:icon.document-arrow-down class="size-4 mr-2" />
                    Download Relatório
                </flux:button>
            </div>
        </div>
    </div>

    <script>
        let currentStep = 1;
        let selectedInstances = [];
        let contacts = [];
        let variables = [];
        let messages = [];
        let disparoActive = false;
        let disparoResults = [];

        // Navegação entre etapas
        function goToStep(step) {
            // Validações
            if (step === 2 && !validateStep1()) return;
            if (step === 3 && !validateStep2()) return;

            // Ocultar todas as etapas
            document.querySelectorAll('.step-content').forEach(el => el.classList.add('hidden'));
            
            // Mostrar etapa atual
            document.getElementById(`step${step}`).classList.remove('hidden');
            
            // Atualizar indicadores
            updateStepIndicators(step);
            
            currentStep = step;

            // Ações específicas por etapa
            if (step === 3) {
                generateWhatsAppPreview();
            }
        }

        function updateStepIndicators(step) {
            // Reset all indicators
            for (let i = 1; i <= 3; i++) {
                const indicator = document.getElementById(`step${i}-indicator`);
                const progress = document.getElementById(`progress${i}`);
                
                if (i < step) {
                    indicator.className = 'flex items-center justify-center w-8 h-8 rounded-full bg-green-500 text-white text-sm font-medium';
                    if (progress) progress.style.width = '100%';
                } else if (i === step) {
                    indicator.className = 'flex items-center justify-center w-8 h-8 rounded-full bg-blue-500 text-white text-sm font-medium';
                    if (progress) progress.style.width = step === 3 ? '100%' : '50%';
                } else {
                    indicator.className = 'flex items-center justify-center w-8 h-8 rounded-full bg-gray-300 text-gray-600 text-sm font-medium';
                    if (progress) progress.style.width = '0%';
                }
            }
        }

        // Validações
        function validateStep1() {
            if (selectedInstances.length === 0) {
                alert('Selecione pelo menos uma instância');
                return false;
            }
            if (contacts.length === 0) {
                alert('Importe pelo menos um contato');
                return false;
            }
            return true;
        }

        function validateStep2() {
            if (messages.length === 0) {
                alert('Configure pelo menos uma mensagem');
                return false;
            }
            return true;
        }

        // Carregar instâncias conectadas
        async function loadConnectedInstances() {
            try {
                const response = await fetch('https://evolution.iaconversas.com/instance/fetchInstances', {
                    headers: {
                        'apikey': '5863c643c8bf6d84e8da8bb564ea13fc',
                        'Content-Type': 'application/json'
                    }
                });

                const instances = await response.json();
                const connectedInstances = instances.filter(instance => instance.connectionStatus === 'open');
                
                displayInstancesSelection(connectedInstances);
            } catch (error) {
                console.error('Erro ao carregar instâncias:', error);
            }
        }

        function displayInstancesSelection(instances) {
            const container = document.getElementById('instancesSelection');
            
            container.innerHTML = instances.map(instance => `
                <div class="flex items-center space-x-3">
                    <input type="checkbox" 
                           class="instance-checkbox" 
                           value="${instance.instance.instanceName}"
                           onchange="updateSelectedInstances()" />
                    <span class="font-medium">${instance.instance.instanceName}</span>
                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Conectado</span>
                </div>
            `).join('');
        }

        function updateSelectedInstances() {
            selectedInstances = Array.from(document.querySelectorAll('.instance-checkbox:checked'))
                .map(cb => cb.value);
            updateStep1Button();
        }

        // Processamento de contatos manuais
        function processManualContacts() {
            const input = document.getElementById('manualContacts').value;
            if (!input.trim()) return;

            const numbers = input.split(/[,\n]/).map(num => num.trim()).filter(num => num);
            const normalizedNumbers = numbers.map(normalizePhoneNumber).filter(num => num);
            
            contacts = normalizedNumbers.map(number => ({ numero: number }));
            variables = []; // Sem variáveis para contatos manuais
            
            displayContactsPreview();
            updateStep1Button();
        }

        function normalizePhoneNumber(number) {
            // Remove todos os caracteres não numéricos
            const cleaned = number.replace(/\D/g, '');
            
            // Verifica se é um número brasileiro válido
            if (cleaned.length === 11 && cleaned.startsWith('11')) {
                return '55' + cleaned;
            } else if (cleaned.length === 10 && cleaned.startsWith('1')) {
                return '55' + cleaned;
            } else if (cleaned.length === 13 && cleaned.startsWith('55')) {
                return cleaned;
            }
            
            return null; // Número inválido
        }

        // Upload de arquivo
        function handleFileUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            document.getElementById('fileName').textContent = file.name;
            document.getElementById('fileInfo').classList.remove('hidden');

            const reader = new FileReader();
            reader.onload = function(e) {
                parseCSVData(e.target.result, file.name);
            };
            reader.readAsText(file);
        }

        function parseCSVData(data, fileName) {
            const lines = data.split('\n').filter(line => line.trim());
            if (lines.length < 2) return;

            const headers = lines[0].split(',').map(h => h.trim());
            const rows = lines.slice(1).map(line => line.split(',').map(cell => cell.trim()));

            // Detectar coluna de telefone
            const phoneColumnIndex = headers.findIndex(header => 
                header.toLowerCase().includes('telefone') || 
                header.toLowerCase().includes('phone') ||
                header.toLowerCase().includes('numero')
            );

            if (phoneColumnIndex === -1) {
                alert('Coluna de telefone não encontrada. Certifique-se de que há uma coluna com "telefone", "phone" ou "numero".');
                return;
            }

            // Processar contatos
            contacts = rows.map(row => {
                const contact = { numero: normalizePhoneNumber(row[phoneColumnIndex]) };
                headers.forEach((header, index) => {
                    if (index !== phoneColumnIndex) {
                        contact[header] = row[index] || '';
                    }
                });
                return contact;
            }).filter(contact => contact.numero);

            // Detectar variáveis
            variables = headers.filter((header, index) => index !== phoneColumnIndex);
            
            displayColumnMapping(headers, phoneColumnIndex);
            displayVariables();
            displayContactsPreview();
            updateStep1Button();
        }

        function displayColumnMapping(headers, phoneColumnIndex) {
            const container = document.getElementById('columnMapping');
            container.innerHTML = `
                <div class="text-sm">
                    <div class="font-medium mb-2">Mapeamento de Colunas:</div>
                    <div class="space-y-1">
                        ${headers.map((header, index) => `
                            <div class="flex justify-between">
                                <span>${header}</span>
                                <span class="text-gray-500">
                                    ${index === phoneColumnIndex ? 'Telefone' : 'Variável'}
                                </span>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `;
        }

        function displayVariables() {
            if (variables.length === 0) return;

            const container = document.getElementById('variablesBadges');
            container.innerHTML = variables.map(variable => `
                <span class="variable-badge px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm cursor-pointer hover:bg-blue-200 transition-colors"
                      data-variable="${variable}"
                      ondblclick="toggleVariableMode(this)">
                    {${variable}}
                </span>
            `).join('');

            document.getElementById('variablesSection').classList.remove('hidden');
        }

        function toggleVariableMode(element) {
            const isFirstWordMode = element.classList.contains('first-word-mode');
            
            if (isFirstWordMode) {
                element.classList.remove('first-word-mode', 'bg-orange-100', 'text-orange-800');
                element.classList.add('bg-blue-100', 'text-blue-800');
                element.textContent = `{${element.dataset.variable}}`;
            } else {
                element.classList.add('first-word-mode', 'bg-orange-100', 'text-orange-800');
                element.classList.remove('bg-blue-100', 'text-blue-800');
                element.textContent = `{${element.dataset.variable}*}`;
            }
        }

        function displayContactsPreview() {
            const container = document.getElementById('contactsList');
            const countElement = document.getElementById('contactsCount');
            
            countElement.textContent = `${contacts.length} contatos processados`;
            
            container.innerHTML = contacts.slice(0, 10).map(contact => `
                <div class="text-xs text-gray-600 dark:text-gray-400">
                    ${contact.numero} ${Object.keys(contact).filter(k => k !== 'numero').map(k => `${k}: ${contact[k]}`).join(', ')}
                </div>
            `).join('');

            if (contacts.length > 10) {
                container.innerHTML += `<div class="text-xs text-gray-500">... e mais ${contacts.length - 10} contatos</div>`;
            }

            document.getElementById('contactsPreview').classList.remove('hidden');
        }

        function updateStep1Button() {
            const button = document.getElementById('nextStep1');
            button.disabled = selectedInstances.length === 0 || contacts.length === 0;
        }

        // Gerenciamento de mensagens
        function updateMessageCount() {
            const count = parseInt(document.getElementById('messageCount').value);
            const container = document.getElementById('messagesContainer');
            
            container.innerHTML = '';
            messages = [];

            for (let i = 1; i <= count; i++) {
                const messageDiv = document.createElement('div');
                messageDiv.className = 'border rounded-lg p-4 space-y-4';
                messageDiv.innerHTML = `
                    <div class="flex items-center justify-between">
                        <h4 class="font-medium">Mensagem ${i}</h4>
                        <select class="message-type" onchange="updateMessageType(${i-1}, this.value)">
                            <option value="texto">Texto</option>
                            <option value="imagem">Imagem</option>
                            <option value="audio">Áudio</option>
                            <option value="video">Vídeo</option>
                        </select>
                    </div>
                    <div class="message-content">
                        <textarea class="w-full p-3 border rounded-lg" 
                                  placeholder="Digite sua mensagem aqui..."
                                  rows="3"
                                  onchange="updateMessageContent(${i-1}, this.value)"></textarea>
                    </div>
                    ${variables.length > 0 ? `
                        <div class="flex flex-wrap gap-2">
                            <span class="text-sm text-gray-500">Variáveis:</span>
                            ${variables.map(variable => `
                                <button type="button" 
                                        class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs hover:bg-gray-200"
                                        onclick="insertVariable(${i-1}, '{${variable}}')">
                                    {${variable}}
                                </button>
                            `).join('')}
                        </div>
                    ` : ''}
                `;
                
                container.appendChild(messageDiv);
                messages.push({ type: 'texto', content: '' });
            }
        }

        function updateMessageType(index, type) {
            messages[index].type = type;
            
            const contentDiv = document.querySelector(`#messagesContainer > div:nth-child(${index + 1}) .message-content`);
            
            if (type === 'texto') {
                contentDiv.innerHTML = `
                    <textarea class="w-full p-3 border rounded-lg" 
                              placeholder="Digite sua mensagem aqui..."
                              rows="3"
                              onchange="updateMessageContent(${index}, this.value)">${messages[index].content}</textarea>
                `;
            } else {
                contentDiv.innerHTML = `
                    <input type="url" 
                           class="w-full p-3 border rounded-lg" 
                           placeholder="URL do arquivo (${type})"
                           value="${messages[index].content}"
                           onchange="updateMessageContent(${index}, this.value)" />
                `;
            }
        }

        function updateMessageContent(index, content) {
            messages[index].content = content;
            if (currentStep === 3) {
                generateWhatsAppPreview();
            }
        }

        function insertVariable(messageIndex, variable) {
            const textarea = document.querySelector(`#messagesContainer > div:nth-child(${messageIndex + 1}) textarea`);
            if (textarea) {
                const start = textarea.selectionStart;
                const end = textarea.selectionEnd;
                const text = textarea.value;
                
                textarea.value = text.substring(0, start) + variable + text.substring(end);
                textarea.focus();
                textarea.setSelectionRange(start + variable.length, start + variable.length);
                
                updateMessageContent(messageIndex, textarea.value);
            }
        }

        // Preview WhatsApp
        function generateWhatsAppPreview() {
            const container = document.getElementById('whatsappPreview');
            const sampleContact = contacts[0] || { numero: '5511999999999' };
            
            container.innerHTML = messages.map((message, index) => {
                let processedContent = message.content;
                
                // Substituir variáveis
                variables.forEach(variable => {
                    const value = sampleContact[variable] || `[${variable}]`;
                    const firstWordMode = document.querySelector(`[data-variable="${variable}"].first-word-mode`);
                    const finalValue = firstWordMode ? value.split(' ')[0] : value;
                    
                    processedContent = processedContent.replace(new RegExp(`{${variable}\\*?}`, 'g'), finalValue);
                });

                const time = new Date().toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
                
                return `
                    <div class="flex justify-end mb-2">
                        <div class="bg-green-500 text-white rounded-lg p-3 max-w-xs">
                            ${message.type === 'texto' ? 
                                `<div class="text-sm">${processedContent}</div>` :
                                `<div class="text-sm"><strong>[${message.type.toUpperCase()}]</strong><br>${processedContent}</div>`
                            }
                            <div class="text-xs opacity-75 mt-1 flex items-center justify-end">
                                <span>${time}</span>
                                <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Execução do disparo
        async function startDisparo() {
            if (disparoActive) return;

            disparoActive = true;
            disparoResults = [];
            
            document.getElementById('startBtn').classList.add('hidden');
            document.getElementById('stopBtn').classList.remove('hidden');
            document.getElementById('disparoProgress').classList.remove('hidden');

            const tempoMin = parseInt(document.getElementById('tempoMin').value);
            const tempoMax = parseInt(document.getElementById('tempoMax').value);
            const usarIA = document.getElementById('usarIA').checked;

            let successCount = 0;
            let errorCount = 0;
            let processedCount = 0;

            for (const contact of contacts) {
                if (!disparoActive) break;

                for (const instance of selectedInstances) {
                    if (!disparoActive) break;

                    try {
                        // Processar mensagens para este contato
                        const processedMessages = messages.map(message => {
                            let content = message.content;
                            
                            // Substituir variáveis
                            variables.forEach(variable => {
                                const value = contact[variable] || '';
                                const firstWordMode = document.querySelector(`[data-variable="${variable}"].first-word-mode`);
                                const finalValue = firstWordMode ? value.split(' ')[0] : value;
                                
                                content = content.replace(new RegExp(`{${variable}\\*?}`, 'g'), finalValue);
                            });

                            return {
                                type: message.type,
                                text: content
                            };
                        });

                        const payload = {
                            numero: contact.numero,
                            mensagens: processedMessages,
                            tempoMin,
                            tempoMax,
                            usarIA,
                            instancia: instance
                        };

                        const response = await fetch('https://n8n.iaconversas.com/webhook/disparar', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(payload)
                        });

                        if (response.ok) {
                            successCount++;
                            disparoResults.push({ contact: contact.numero, instance, status: 'success' });
                        } else {
                            errorCount++;
                            disparoResults.push({ contact: contact.numero, instance, status: 'error', error: await response.text() });
                        }

                    } catch (error) {
                        errorCount++;
                        disparoResults.push({ contact: contact.numero, instance, status: 'error', error: error.message });
                    }

                    processedCount++;
                    updateProgress(processedCount, contacts.length * selectedInstances.length, successCount, errorCount);

                    // Delay entre envios
                    if (disparoActive) {
                        const delay = Math.random() * (tempoMax - tempoMin) + tempoMin;
                        await new Promise(resolve => setTimeout(resolve, delay * 1000));
                    }
                }
            }

            finishDisparo();
        }

        function stopDisparo() {
            disparoActive = false;
            finishDisparo();
        }

        function finishDisparo() {
            disparoActive = false;
            document.getElementById('startBtn').classList.remove('hidden');
            document.getElementById('stopBtn').classList.add('hidden');
            document.getElementById('downloadBtn').classList.remove('hidden');
        }

        function updateProgress(processed, total, success, error) {
            const percentage = (processed / total) * 100;
            
            document.getElementById('progressBar').style.width = `${percentage}%`;
            document.getElementById('progressText').textContent = `${processed} / ${total}`;
            document.getElementById('successCount').textContent = success;
            document.getElementById('errorCount').textContent = error;
        }

        function downloadReport() {
            const csvContent = [
                ['Contato', 'Instância', 'Status', 'Erro'],
                ...disparoResults.map(result => [
                    result.contact,
                    result.instance,
                    result.status,
                    result.error || ''
                ])
            ].map(row => row.join(',')).join('\n');

            const blob = new Blob([csvContent], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `relatorio-disparo-${new Date().toISOString().split('T')[0]}.csv`;
            a.click();
            window.URL.revokeObjectURL(url);
        }

        // Inicialização
        document.addEventListener('DOMContentLoaded', function() {
            loadConnectedInstances();
            updateMessageCount(); // Inicializar com 1 mensagem
        });
    </script>
</x-layouts.app>