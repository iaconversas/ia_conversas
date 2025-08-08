<x-layouts.app>
    <flux:heading size="xl">Disparo Inteligente</flux:heading>
    <flux:subheading>Sistema de envio em massa para WhatsApp</flux:subheading>

    <div class="mt-6">
        <!-- Indicador de Etapas -->
        <div class="max-w-4xl mx-auto mb-8">
            <div class="flex items-center justify-between overflow-x-auto px-4 sm:px-0">
                <!-- Etapa 1 -->
                <div class="flex items-center min-w-0 flex-shrink-0" id="step1-indicator">
                    <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-medium mr-3">
                        1
                    </div>
                    <div class="hidden md:block">
                        <div class="text-sm font-medium text-gray-900 whitespace-nowrap">Configuração</div>
                        <div class="text-xs text-gray-500 whitespace-nowrap">Instâncias e contatos</div>
                    </div>
                </div>
                
                <!-- Linha conectora -->
                <div class="flex-1 h-px bg-gray-300 mx-4 hidden md:block"></div>
                
                <!-- Etapa 2 -->
                <div class="flex items-center min-w-0 flex-shrink-0" id="step2-indicator">
                    <div class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-medium mr-3">
                        2
                    </div>
                    <div class="hidden md:block">
                        <div class="text-sm font-medium text-gray-500 whitespace-nowrap">Mensagens</div>
                        <div class="text-xs text-gray-400 whitespace-nowrap">Configure as mensagens</div>
                    </div>
                </div>
                
                <!-- Linha conectora -->
                <div class="flex-1 h-px bg-gray-300 mx-4 hidden md:block"></div>
                
                <!-- Etapa 3 -->
                <div class="flex items-center min-w-0 flex-shrink-0" id="step3-indicator">
                    <div class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-medium mr-3">
                        3
                    </div>
                    <div class="hidden md:block">
                        <div class="text-sm font-medium text-gray-500 whitespace-nowrap">Preview & Execução</div>
                        <div class="text-xs text-gray-400 whitespace-nowrap">Visualize e execute o disparo</div>
                    </div>
                </div>
            </div>
        </div>

        <flux:card class="max-w-4xl mx-auto">
            <!-- Etapa 1: Configuração -->
            <div id="step1" class="step-content">
                <!-- Seção: Instâncias do WhatsApp -->
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6 mb-6">
                    <div class="flex items-center mb-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 text-green-600 dark:bg-green-900/20 dark:text-green-400 mr-3">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Instâncias do WhatsApp</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Selecione as instâncias que serão usadas para enviar as mensagens (seleção desejada)</p>
                        </div>
                    </div>
                    
                    <flux:field>
                        <div class="flex items-center justify-between mb-3">
                            <flux:label>Selecione uma Instância</flux:label>
                            <flux:button 
                                variant="outline" 
                                size="sm"
                                onclick="carregarInstancias()"
                                id="refreshInstancesBtn"
                            >
                                <flux:icon.arrow-path class="size-4" />
                                Atualizar Instâncias
                            </flux:button>
                        </div>
                        <div id="instanciasContainer" class="space-y-3">
                            <!-- Instâncias serão carregadas via JavaScript -->
                        </div>
                    </flux:field>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <!-- Seção: Contatos e Números -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-6">
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Contatos e Números</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Adicione os números manualmente ou faça upload de arquivo</p>
                        </div>
                        
                        <div class="space-y-4">
                            <flux:field>
                                <flux:label>Números (1 por linha)</flux:label>
                                <flux:textarea id="numeros" rows="5" placeholder="Ex: 55849992053434,5584988887777" class="w-full"></flux:textarea>
                            </flux:field>

                            <flux:field>
                                <flux:label>Upload CSV/XLSX (telefone;nome;...)</flux:label>
                                <flux:input id="arquivoCsv" type="file" accept=".csv,.xlsx" class="w-full" placeholder="Escolher arquivo" />
                            </flux:field>
                        </div>
                    </div>

                    <!-- Seção: Variáveis Disponíveis -->
                    <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-6">
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Variáveis Disponíveis</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Personalize suas mensagens com variáveis dinâmicas</p>
                        </div>
                        
                        <flux:field>
                            <flux:label>Variáveis disponíveis</flux:label>
                            <div id="variaveisContainer" class="flex flex-wrap p-2 border border-gray-300 rounded min-h-[80px]">
                                <!-- Variáveis serão adicionadas dinamicamente -->
                            </div>
                            <flux:description>Dê duplo clique em uma variável para usar apenas a primeira palavra</flux:description>
                        </flux:field>
                    </div>
                </div>

                <!-- Botão para próxima etapa -->
                <div class="flex justify-end mt-6">
                    <flux:button onclick="nextStep(2)" variant="primary">
                        Próximo: Mensagens
                        <flux:icon.arrow-right class="size-4 ml-2" />
                    </flux:button>
                </div>
            </div>

            <!-- Etapa 2: Mensagens -->
            <div id="step2" class="step-content" style="display: none;">
                <!-- Quantidade de Mensagens -->
                <flux:field>
                    <flux:label>Quantidade de mensagens por número</flux:label>
                    <flux:input id="qtdMensagens" type="number" value="1" min="1" class="w-full" />
                </flux:field>

                <!-- Container de Mensagens -->
                <div id="mensagensContainer" class="space-y-4">
                    <!-- Mensagens serão adicionadas dinamicamente -->
                </div>

                <!-- Configurações de Tempo -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:field>
                        <flux:label>Tempo mínimo (s)</flux:label>
                        <flux:input id="tempoMin" type="number" placeholder="2" class="w-full" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Tempo máximo (s)</flux:label>
                        <flux:input id="tempoMax" type="number" placeholder="5" class="w-full" />
                    </flux:field>
                </div>

                <!-- Usar IA -->
                <flux:field>
                    <flux:checkbox id="usarIA" name="usar_ia" value="1">
                        Usar IA para variar mensagens de texto
                    </flux:checkbox>
                    <flux:description>A IA irá criar variações das suas mensagens de texto para evitar detecção de spam</flux:description>
                </flux:field>

                <!-- Botões de navegação -->
                <div class="flex justify-between mt-6">
                    <flux:button onclick="previousStep(1)" variant="outline">
                        <flux:icon.arrow-left class="size-4 mr-2" />
                        Voltar: Configuração
                    </flux:button>
                    <flux:button onclick="nextStep(3)" variant="primary">
                        Próximo: Preview & Execução
                        <flux:icon.arrow-right class="size-4 ml-2" />
                    </flux:button>
                </div>
            </div>

            <!-- Etapa 3: Preview & Execução -->
            <div id="step3" class="step-content" style="display: none;">
                <!-- Preview das configurações -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="text-lg font-semibold mb-4">Resumo da Configuração</h3>
                    <div id="configPreview" class="space-y-2 text-sm">
                        <!-- Preview será preenchido via JavaScript -->
                    </div>
                </div>

                <!-- Botões de Ação -->
                <div class="space-y-2">
                    <flux:button id="dispararBtn" variant="primary" class="w-full">🚀 Disparar</flux:button>
                    <flux:button id="pararBtn" variant="danger" class="w-full" style="display:none;">⏹️ Parar</flux:button>
                </div>

                <!-- Barra de Progresso -->
                <div id="progressBarContainer" class="w-full bg-gray-200 rounded-full h-6" style="display:none;">
                    <div id="progressBar" class="bg-blue-600 h-6 rounded-full flex items-center justify-center text-white text-sm font-medium" style="width:0%">0%</div>
                </div>

                <!-- Status -->
                <div id="status" class="text-center text-lg font-medium"></div>

                <!-- Botão para voltar -->
                <div class="flex justify-start mt-6">
                    <flux:button onclick="previousStep(2)" variant="outline">
                        <flux:icon.arrow-left class="size-4 mr-2" />
                        Voltar: Mensagens
                    </flux:button>
                </div>
            </div>
        </flux:card>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>
        // Configurações do sistema (similar ao Evolution Manager)
        var config = @json($config);
        
        // Variável para controlar a etapa atual
        let currentStep = 1;
        
        // Função para navegar para a próxima etapa
        function nextStep(step) {
            // Validar etapa atual antes de prosseguir
            if (!validateCurrentStep()) {
                return;
            }
            
            showStep(step);
        }
        
        // Função para navegar para a etapa anterior
        function previousStep(step) {
            showStep(step);
        }
        
        // Função para mostrar uma etapa específica
        function showStep(step) {
            // Ocultar todas as etapas
            document.querySelectorAll('.step-content').forEach(el => {
                el.style.display = 'none';
            });
            
            // Mostrar a etapa selecionada
            document.getElementById('step' + step).style.display = 'block';
            
            // Atualizar indicadores visuais
            updateStepIndicators(step);
            
            // Atualizar etapa atual
            currentStep = step;
            
            // Se for a etapa 3, atualizar o preview
            if (step === 3) {
                updateConfigPreview();
            }
        }
        
        // Função para atualizar os indicadores visuais das etapas
        function updateStepIndicators(activeStep) {
            for (let i = 1; i <= 3; i++) {
                const indicator = document.getElementById('step' + i + '-indicator');
                const circle = indicator.querySelector('div:first-child');
                const textContainer = indicator.querySelector('div:last-child');
                
                if (textContainer) {
                    const title = textContainer.querySelector('div:first-child');
                    const subtitle = textContainer.querySelector('div:last-child');
                    
                    if (i <= activeStep) {
                        // Etapa ativa ou concluída
                        circle.className = 'w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-medium mr-3';
                        if (title) title.className = 'text-sm font-medium text-gray-900 whitespace-nowrap';
                        if (subtitle) subtitle.className = 'text-xs text-gray-500 whitespace-nowrap';
                    } else {
                        // Etapa inativa
                        circle.className = 'w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-medium mr-3';
                        if (title) title.className = 'text-sm font-medium text-gray-500 whitespace-nowrap';
                        if (subtitle) subtitle.className = 'text-xs text-gray-400 whitespace-nowrap';
                    }
                } else {
                    // Fallback para quando não há texto (telas pequenas)
                    if (i <= activeStep) {
                        circle.className = 'w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-medium mr-3';
                    } else {
                        circle.className = 'w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-medium mr-3';
                    }
                }
            }
        }
        
        // Função para validar a etapa atual
        function validateCurrentStep() {
            if (currentStep === 1) {
                // Validar se pelo menos uma instância foi selecionada
                const selectedInstances = document.querySelectorAll('input[name="instancia"]:checked');
                if (selectedInstances.length === 0) {
                    alert('Por favor, selecione pelo menos uma instância antes de continuar.');
                    return false;
                }
                
                // Validar se há números ou arquivo CSV
                const numeros = document.getElementById('numeros').value.trim();
                const arquivo = document.getElementById('arquivoCsv').files[0];
                if (!numeros && !arquivo) {
                    alert('Por favor, insira números ou faça upload de um arquivo CSV antes de continuar.');
                    return false;
                }
            }
            
            if (currentStep === 2) {
                // Validar se há pelo menos uma mensagem
                const mensagens = document.querySelectorAll('.mensagem-item');
                if (mensagens.length === 0) {
                    alert('Por favor, adicione pelo menos uma mensagem antes de continuar.');
                    return false;
                }
            }
            
            return true;
        }
        
        // Função para atualizar o preview da configuração
        function updateConfigPreview() {
            const preview = document.getElementById('configPreview');
            let html = '';
            
            // Instâncias selecionadas
            const selectedInstances = document.querySelectorAll('input[name="instancia"]:checked');
            if (selectedInstances.length > 0) {
                const instanceNames = Array.from(selectedInstances).map(instance => instance.value).join(', ');
                html += `<div><strong>Instâncias:</strong> ${instanceNames}</div>`;
            }
            
            // Números de contatos
            const numeros = document.getElementById('numeros').value.trim();
            const arquivo = document.getElementById('arquivoCsv').files[0];
            if (numeros) {
                const numerosList = numeros.split(/[,\n]/).filter(n => n.trim());
                html += `<div><strong>Números:</strong> ${numerosList.length} contatos</div>`;
            }
            if (arquivo) {
                html += `<div><strong>Arquivo:</strong> ${arquivo.name}</div>`;
            }
            
            // Quantidade de mensagens
            const qtdMensagens = document.getElementById('qtdMensagens').value;
            html += `<div><strong>Mensagens por contato:</strong> ${qtdMensagens}</div>`;
            
            // Tempo entre mensagens
            const tempoMin = document.getElementById('tempoMin').value || '2';
            const tempoMax = document.getElementById('tempoMax').value || '5';
            html += `<div><strong>Intervalo:</strong> ${tempoMin}s - ${tempoMax}s</div>`;
            

            
            preview.innerHTML = html;
        }
        
        // Carregar instâncias disponíveis (similar ao Evolution Manager)
        window.carregarInstancias = async function() {
            // Mostrar indicador de carregamento no botão
            const refreshBtn = document.getElementById('refreshInstancesBtn');
            const originalContent = refreshBtn.innerHTML;
            refreshBtn.innerHTML = '<svg class="size-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Carregando...';
            refreshBtn.disabled = true;
            
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
                     return;
                 }
                 
                 let responseData;
                 try {
                     responseData = JSON.parse(text);
                     console.log('Dados da resposta:', responseData);
                 } catch (e) {
                     console.error('Erro ao fazer parse JSON das instâncias:', e);
                     console.error('Texto da resposta:', text);
                     return;
                 }
                 
                 // Verificar se a resposta tem a estrutura esperada
                 let instancias = [];
                 if (responseData.success && responseData.instances) {
                     // Resposta do WhatsAppController
                     instancias = responseData.instances;
                 } else if (Array.isArray(responseData)) {
                     // Resposta direta da Evolution API
                     instancias = responseData;
                 } else {
                     console.error('Estrutura de resposta não reconhecida:', responseData);
                     const container = document.getElementById('instanciasContainer');
                     if (container) {
                         container.innerHTML = '<div class="text-red-500 text-center p-4">Estrutura de resposta inválida</div>';
                     }
                     return;
                 }
                 
                 console.log('Instâncias processadas:', instancias);
                 
                 // Filtrar apenas instâncias conectadas
                 const instanciasConectadas = instancias.filter(instancia => 
                     instancia.connectionStatus === 'open'
                 );
                 
                 const container = document.getElementById('instanciasContainer');
                 if (!container) {
                     console.error('Elemento container com ID "instanciasContainer" não encontrado');
                     alert('Erro interno: Container de instâncias não encontrado');
                     return;
                 }
                 
                 if (instanciasConectadas.length === 0) {
                     container.innerHTML = '<div class="text-gray-500 text-center p-4">Nenhuma instância conectada encontrada</div>';
                     return;
                 }
                 
                 container.innerHTML = instanciasConectadas.map(instancia => {
                     const instanceName = instancia.name || instancia.instanceName || instancia.id || 'Nome não disponível';
                     const profilePicUrl = instancia.profilePicUrl || null;
                     const profileName = instancia.profileName || instanceName;
                     
                     return `
                         <div class="flex items-center space-x-3 p-3 border rounded-lg hover:bg-gray-50 cursor-pointer" onclick="toggleInstance('${instanceName}')">
                             <input type="checkbox" name="instancia" value="${instanceName}" class="instance-checkbox" />
                             <div class="flex items-center justify-center">
                                 <div class="w-10 h-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-gray-600 dark:text-gray-300 text-sm font-medium">
                                     ${profileName.charAt(0).toUpperCase()}
                                 </div>
                             </div>
                             <div class="flex-1 ml-3">
                                 <div class="font-medium">${instanceName}</div>
                                 ${profileName !== instanceName ? `
                                     <div class="text-sm text-gray-500 dark:text-gray-400">
                                         ${profileName}
                                     </div>
                                 ` : ''}
                                 <span class="inline-block mt-1 px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                     Conectado
                                 </span>
                             </div>
                         </div>
                     `;
                 }).join('');
                 
             } catch (error) {
                 console.error('Erro ao carregar instâncias:', error);
                 const container = document.getElementById('instanciasContainer');
                 if (container) {
                     container.innerHTML = '<div class="text-red-500 text-center p-4">Erro ao carregar instâncias: ' + error.message + '</div>';
                 }
                 // Remover o alert que estava causando problemas
                 console.error('Detalhes do erro:', error);
             } finally {
                 // Restaurar estado original do botão
                 refreshBtn.innerHTML = originalContent;
                 refreshBtn.disabled = false;
             }
         };
         
         // Função para alternar seleção de instância
         window.toggleInstance = function(instanceName) {
             const checkbox = document.querySelector(`input[name="instancia"][value="${instanceName}"]`);
             if (checkbox) {
                 checkbox.checked = !checkbox.checked;
             }
         };
         
        window.addEventListener('DOMContentLoaded', () => {
            let contatos = [];
            const variaveis = [];
            let interromper = false;
            let instances = []; // Armazenar instâncias globalmente

            // Funções utilitárias
            const normalizePhone = raw => String(raw || '').replace(/\D/g, '');
            const extractPhones = raw => String(raw || '').split(',').map(p => normalizePhone(p)).filter(n => n.length >= 10).map(n => n.length <= 11 ? '55' + n : (n.length === 12 && !n.startsWith('55') ? '55' + n : n));
            
            // Chamar a função para carregar instâncias
            carregarInstancias();
            
            // Função para alternar seleção de instância
            window.toggleInstance = function(instanceName) {
                const checkbox = document.querySelector(`input[name="instancia"][value="${instanceName}"]`);
                if (checkbox) {
                    checkbox.checked = !checkbox.checked;
                }
            };

            // Elementos DOM
            const numerosArea = document.getElementById('numeros');
            const fileInput = document.getElementById('arquivoCsv');
            const varsContainer = document.getElementById('variaveisContainer');
            const msgsCont = document.getElementById('mensagensContainer');

            // Upload de arquivo
            fileInput.addEventListener('change', async e => {
                const file = e.target.files[0];
                if (!file) return;

                let header, rows;
                const name = file.name.toLowerCase();

                if (name.endsWith('.xlsx') || name.endsWith('.xls')) {
                    const buf = await file.arrayBuffer();
                    const wb = XLSX.read(buf, { type: 'array' });
                    const ws = wb.Sheets[wb.SheetNames[0]];
                    const arr = XLSX.utils.sheet_to_json(ws, { header: 1, defval: '' });
                    header = arr.shift().map(h => String(h).trim());
                    rows = arr;
                } else {
                    const txt = await file.text();
                    const lines = txt.split(/\r?\n/).filter(l => l.trim());
                    header = lines.shift().split(/[,;\t]/).map(h => h.trim());
                    rows = lines.map(l => l.split(/[,;\t]/).map(c => c.trim()));
                }

                const phoneField = header.find(h => h.toLowerCase().includes('telefone')) || header[0];
                const flat = [];

                rows.forEach(r => {
                    const obj = {};
                    header.forEach((h, i) => obj[h] = r[i] || '');
                    extractPhones(obj[phoneField]).forEach(num => flat.push({ ...obj, telefone: num }));
                });

                contatos = flat;
                numerosArea.value = contatos.map(c => c.telefone).join('\n');
                varsContainer.innerHTML = '';

                header.filter(h => h !== phoneField).forEach(key => {
                    const badge = document.createElement('span');
                    badge.dataset.key = key;
                    badge.dataset.firstOnly = 'false';
                    badge.className = 'inline-block px-2 py-1 m-1 bg-green-500 text-white rounded cursor-pointer select-none';
                    badge.textContent = `{${key}}`;

                    badge.onclick = () => {
                        const inp = document.activeElement;
                        if (inp.classList.contains('conteudo-mensagem')) {
                            const [s, e] = [inp.selectionStart, inp.selectionEnd];
                            inp.value = inp.value.slice(0, s) + `{${key}}` + inp.value.slice(e);
                            inp.dispatchEvent(new Event('input'));
                            inp.setSelectionRange(s + key.length + 2, s + key.length + 2);
                        }
                    };

                    badge.ondblclick = () => {
                        const isFirst = badge.dataset.firstOnly === 'true';
                        badge.dataset.firstOnly = (!isFirst).toString();
                        badge.className = isFirst ? 
                            'inline-block px-2 py-1 m-1 bg-green-500 text-white rounded cursor-pointer select-none' :
                            'inline-block px-2 py-1 m-1 bg-yellow-500 text-black rounded cursor-pointer select-none';
                    };

                    varsContainer.append(badge);
                });
            });

            // Criar campos de mensagem
            document.getElementById('qtdMensagens').addEventListener('input', e => createMsgs(+e.target.value));

            function createMsgs(q) {
                const container = document.getElementById('mensagensContainer');
                if (!container) {
                    console.error('Container de mensagens não encontrado');
                    return;
                }
                
                container.innerHTML = '';
                for (let i = 0; i < q; i++) {
                    const d = document.createElement('div');
                    d.className = 'space-y-2 mensagem-item';
                    d.innerHTML = `
                        <label class="block text-sm font-medium">Mensagem ${i + 1}</label>
                        <select class="form-select tipo-mensagem w-full p-2 border border-gray-300 rounded">
                            <option value="texto">Texto</option>
                            <option value="imagem">Imagem</option>
                            <option value="audio">Áudio</option>
                            <option value="video">Vídeo</option>
                        </select>
                        <input class="form-control conteudo-mensagem w-full p-2 border border-gray-300 rounded" placeholder="Digite texto ou link">
                        <div class="preview"></div>
                    `;
                    container.append(d);
                }
            }

            // Inicializar campos de mensagem
            const qtdInput = document.getElementById('qtdMensagens');
            if (qtdInput) {
                createMsgs(+qtdInput.value);
            }

            // Preview das mensagens
            const mensagensContainer = document.getElementById('mensagensContainer');
            if (mensagensContainer) {
                mensagensContainer.addEventListener('input', () => {
                    const ins = mensagensContainer.querySelectorAll('.conteudo-mensagem');
                    const tps = mensagensContainer.querySelectorAll('.tipo-mensagem');

                    ins.forEach((inp, i) => {
                        const val = inp.value.trim();
                        const prv = mensagensContainer.querySelectorAll('.preview')[i];
                    prv.innerHTML = '';

                    if (!val) return;

                    const tp = tps[i].value;
                    if (tp === 'texto') {
                        let txt = val.replace(/\{(\w+)\}/g, (_, k) => {
                            const badge = Array.from(varsContainer.children).find(b => b.dataset.key === k);
                            const f = (contatos[0] && contatos[0][k]) || '';
                            return badge && badge.dataset.firstOnly === 'true' ? f.split(' ')[0] : f;
                        });
                        prv.innerHTML = `<div class="mt-2 p-2 bg-gray-800 rounded text-white text-sm">${txt}</div>`;
                    } else if (tp === 'imagem') {
                        prv.innerHTML = /(jpe?g|png|gif|webp)$/i.test(val) ?
                            `<div class="text-info">📷 Imagem: ${val}</div>` :
                            '<span class="text-warning">⚠️ Link inválido</span>';
                    } else if (tp === 'audio') {
                        prv.innerHTML = /(mp3|ogg|wav)$/i.test(val) ?
                            `<audio controls src="${val}"></audio>` :
                            '<span class="text-yellow-600">⚠️ Link inválido</span>';
                    } else if (tp === 'video') {
                        prv.innerHTML = /(mp4|webm|mov)$/i.test(val) ?
                            `<video controls class="max-w-full max-h-64 object-contain" src="${val}"></video>` :
                            '<span class="text-yellow-600">⚠️ Link inválido</span>';
                        }
                    });
                });
            }

            // Botão disparar
            document.getElementById('dispararBtn').onclick = async () => {
                interromper = false;
                document.getElementById('pararBtn').style.display = 'block';
                document.getElementById('dispararBtn').disabled = true;

                let list = contatos.length ? contatos : Array.from(numerosArea.value.split(/\r?\n/).filter(n => n)).map(t => ({ telefone: t }));
                const tm = +document.getElementById('tempoMin').value;
                const TM = +document.getElementById('tempoMax').value;

                const selectedCheckboxes = document.querySelectorAll('input[name="instancia"]:checked');
                const inst = Array.from(selectedCheckboxes).map(checkbox => checkbox.value);
                const tps = [...document.querySelectorAll('.tipo-mensagem')];
                const cts = [...document.querySelectorAll('.conteudo-mensagem')];

                if (!list.length || !tm || !TM || !inst.length) {
                    alert('Preencha todos os campos.');
                    document.getElementById('pararBtn').style.display = 'none';
                    document.getElementById('dispararBtn').disabled = false;
                    return;
                }

                const templates = tps.map((t, i) => ({ type: t.value, text: cts[i].value.trim() }));
                const used = new Set();
                templates.forEach(tt => (tt.text.match(/\{(\w+)\}/g) || []).forEach(m => used.add(m.replace(/[{}]/g, ''))));
                const invalid = [...used].filter(k => !Array.from(varsContainer.children).some(b => b.dataset.key === k));

                if (invalid.length) {
                    alert('Variáveis inválidas: ' + invalid.join(','));
                    document.getElementById('pararBtn').style.display = 'none';
                    document.getElementById('dispararBtn').disabled = false;
                    return;
                }

                const bar = document.getElementById('progressBar');
                const pc = document.getElementById('progressBarContainer');
                const st = document.getElementById('status');
                pc.style.display = 'block';

                let sucessos = [], erros = [], log = '';

                for (let i = 0; i < list.length; i++) {
                    if (interromper) {
                        log += '⏹️ Interrompido pelo usuário\n';
                        break;
                    }

                    const item = list[i];
                    const messages = templates.map(tt => ({
                        type: tt.type,
                        text: tt.text.replace(/\{(\w+)\}/g, (_, k) => {
                            const b = Array.from(varsContainer.children).find(x => x.dataset.key === k);
                            const full = item[k] || '';
                            return b && b.dataset.firstOnly === 'true' ? full.split(' ')[0] : full;
                        })
                    }));

                    const choice = inst[Math.floor(Math.random() * inst.length)];

                    try {
                        const usarIA = document.getElementById('usarIA').checked;
                        console.log('Valor do checkbox usarIA:', usarIA);
                        
                        const payload = {
                            numero: normalizePhone(item.telefone),
                            mensagens: messages,
                            tempoMin: tm,
                            tempoMax: TM,
                            usarIA: usarIA,
                            instancia: choice
                        };
                        console.log('Valor do checkbox usarIA:', usarIA);
                        console.log('Payload completo sendo enviado:', JSON.stringify(payload, null, 2));
                        console.log('Payload enviado para n8n:', payload);
                        
                        const res = await fetch('https://n8n.iaconversas.com/webhook/disparar', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(payload)
                        });

                        const txt = await res.text();
                        let msg = '';
                        try {
                            msg = JSON.parse(txt).mensagem || txt;
                        } catch {
                            msg = txt;
                        }
                        sucessos.push(`${item.telefone} via ${choice} – ${msg}`);
                    } catch (e) {
                        erros.push(`${item.telefone} via ${choice} – Erro: ${e.message}`);
                    }

                    const pct = Math.round(((i + 1) / list.length) * 100);
                    bar.style.width = `${pct}%`;
                    bar.textContent = `${pct}%`;
                    st.textContent = `Enviando ${i + 1}/${list.length}`;

                    await new Promise(r => setTimeout(r, ((Math.random() * (TM - tm) + tm) * 1000)));
                }

                // Relatório final
                log = `RELATÓRIO DE ENVIO\n\nTotal enviados: ${sucessos.length}\nTotal com erro: ${erros.length}\n\n--- ENVIADOS COM SUCESSO ---\n`;
                log += sucessos.length ? sucessos.join('\n') : 'Nenhum.';
                log += `\n\n--- ERROS ---\n`;
                log += erros.length ? erros.join('\n') : 'Nenhum.';
                if (interromper) log += '\n\n⏹️ Envio interrompido pelo usuário.';

                st.textContent = interromper ? '⏹️ Envio interrompido!' : '✅ Envio concluído!';

                const blob = new Blob([log], { type: 'text/plain' });
                const a = document.createElement('a');
                a.href = URL.createObjectURL(blob);
                a.download = 'log.txt';
                a.click();

                document.getElementById('pararBtn').style.display = 'none';
                document.getElementById('dispararBtn').disabled = false;
            };

            // Botão parar
            document.getElementById('pararBtn').onclick = () => {
                interromper = true;
            };
        });
    </script>
</x-layouts.app>