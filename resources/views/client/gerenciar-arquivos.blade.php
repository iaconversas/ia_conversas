<x-layouts.app>
    <flux:heading size="xl">Gerenciador de Arquivos</flux:heading>
    <flux:subheading class="pb-5">Upload e Gerenciamento de Mídia</flux:subheading>

    <div class="space-y-6">
        <!-- Estatísticas -->
        <div class="flex gap-6">
            <!-- Primeiros 3 cards ocupam 50% -->
            <div class="w-1/2 grid grid-cols-1 md:grid-cols-3 gap-4">
                <flux:card>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600" id="totalFiles">0</div>
                        <div class="text-sm text-blue-600">Total de Arquivos</div>
                    </div>
                </flux:card>
                
                <flux:card>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600" id="totalImages">0</div>
                        <div class="text-sm text-green-600">Imagens</div>
                    </div>
                </flux:card>
                
                <flux:card>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600" id="totalVideos">0</div>
                        <div class="text-sm text-purple-600">Vídeos</div>
                    </div>
                </flux:card>
            </div>
            
            <!-- Últimos 2 cards ocupam os outros 50% -->
            <div class="w-1/2 grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:card>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-orange-600" id="totalSize">0 MB</div>
                        <div class="text-sm text-orange-600">Espaço Usado</div>
                    </div>
                </flux:card>
                
                <!-- Card adicional para completar o layout -->
                <flux:card>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-red-600" id="totalDocuments">0</div>
                        <div class="text-sm text-red-600">Documentos</div>
                    </div>
                </flux:card>
            </div>
        </div>

        <!-- Upload de Arquivos -->
        <flux:card>
            <flux:card.header>
                <flux:heading size="lg">Upload de Arquivos</flux:heading>
                <flux:subheading>Arraste e solte ou clique para selecionar arquivos</flux:subheading>
            </flux:card.header>

            <div class="space-y-4">
                <!-- Área de Drop -->
                <div 
                    id="dropZone"
                    class="border-2 border-dashed border-indigo-300 dark:border-indigo-600 rounded-lg p-8 text-center hover:border-indigo-400 dark:hover:border-indigo-500 transition-colors cursor-pointer"
                    ondrop="handleDrop(event)" 
                    ondragover="handleDragOver(event)"
                    ondragleave="handleDragLeave(event)"
                    onclick="document.getElementById('fileInput').click()"
                >
                    <div class="space-y-4">
                        <div class="mx-auto w-16 h-16 text-indigo-400">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-lg font-medium text-indigo-600 dark:text-indigo-400">
                                Arraste arquivos aqui ou clique para selecionar
                            </p>
                            <p class="text-sm text-gray-500">
                                Suporte: Imagens, Vídeos, Áudios (máx. 100MB por arquivo)
                            </p>
                        </div>
                    </div>
                </div>

                <input 
                    type="file" 
                    id="fileInput" 
                    multiple 
                    accept="image/*,video/*,audio/*"
                    class="hidden"
                    onchange="handleFileSelect(event)"
                />

                <!-- Progresso de Upload -->
                <div id="uploadProgress" class="hidden space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium">Fazendo upload...</span>
                        <span id="uploadPercentage" class="text-sm text-gray-500">0%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div id="uploadProgressBar" class="bg-indigo-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                    <div id="uploadStatus" class="text-sm text-gray-600"></div>
                </div>
            </div>
        </flux:card>

        <!-- Filtros e Busca -->
        <flux:card>
            <div class="flex flex-wrap gap-4 items-center">
                <!-- Abas de Categoria -->
                <div class="flex space-x-1 bg-gray-100 dark:bg-gray-800 rounded-lg p-1">
                    <button onclick="setActiveCategory('all')" class="category-tab active px-4 py-2 rounded-md text-sm font-medium transition-colors" data-category="all">
                        Todos
                    </button>
                    <button onclick="setActiveCategory('image')" class="category-tab px-4 py-2 rounded-md text-sm font-medium transition-colors" data-category="image">
                        Imagens
                    </button>
                    <button onclick="setActiveCategory('video')" class="category-tab px-4 py-2 rounded-md text-sm font-medium transition-colors" data-category="video">
                        Vídeos
                    </button>
                    <button onclick="setActiveCategory('audio')" class="category-tab px-4 py-2 rounded-md text-sm font-medium transition-colors" data-category="audio">
                        Áudios
                    </button>
                </div>

                <!-- Busca -->
                <div class="flex-1 max-w-md">
                    <flux:input 
                        type="text" 
                        id="searchInput"
                        placeholder="Buscar arquivos..."
                        onkeyup="filterFiles()"
                        class="w-full"
                    />
                </div>

                <!-- Botão Atualizar -->
                <flux:button variant="outline" onclick="loadFiles()">
                    <flux:icon.arrow-path class="size-4 mr-2" />
                    Atualizar
                </flux:button>
            </div>
        </flux:card>

        <!-- Lista de Arquivos -->
        <flux:card>
            <flux:card.header>
                <flux:heading size="lg">Meus Arquivos</flux:heading>
                <flux:subheading id="filesSubheading">Carregando arquivos...</flux:subheading>
            </flux:card.header>

            <div id="filesContainer">
                <!-- Loading -->
                <div id="filesLoading" class="flex items-center justify-center py-12">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-500"></div>
                    <span class="ml-3 text-indigo-600">Carregando arquivos...</span>
                </div>

                <!-- Grid de Arquivos -->
                <div id="filesGrid" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    <!-- Arquivos serão inseridos aqui -->
                </div>

                <!-- Estado Vazio -->
                <div id="emptyState" class="hidden text-center py-12">
                    <div class="mx-auto w-16 h-16 text-gray-400 mb-4">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-500">Nenhum arquivo encontrado</p>
                    <p class="text-sm text-gray-400 mt-2">Faça upload de alguns arquivos para começar</p>
                </div>

                <!-- Erro -->
                <div id="errorState" class="hidden text-center py-12">
                    <div class="mx-auto w-16 h-16 text-red-400 mb-4">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <p class="text-red-500 font-medium">Erro ao carregar arquivos</p>
                    <p id="errorMessage" class="text-sm text-red-400 mt-2"></p>
                    <flux:button variant="outline" onclick="loadFiles()" class="mt-4">
                        Tentar Novamente
                    </flux:button>
                </div>
            </div>
        </flux:card>
    </div>

    <!-- Modal de Preview -->
    <div id="previewModal" class="hidden fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50" onclick="closePreviewModal()">
        <div class="max-w-4xl max-h-full p-4" onclick="event.stopPropagation()">
            <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden">
                <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                    <div>
                        <h3 id="previewTitle" class="text-lg font-medium"></h3>
                        <p id="previewInfo" class="text-sm text-gray-500"></p>
                    </div>
                    <button onclick="closePreviewModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div id="previewContent" class="p-4 max-h-96 overflow-auto">
                    <!-- Conteúdo do preview será inserido aqui -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full mx-4">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <flux:icon.trash class="h-6 w-6 text-red-600" />
                </div>
                <flux:heading size="lg" class="mb-2">Excluir Arquivo</flux:heading>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    Tem certeza que deseja excluir este arquivo? Esta ação não pode ser desfeita.
                </p>
                <div class="flex space-x-3 justify-center">
                    <flux:button variant="ghost" onclick="closeDeleteModal()">
                        Cancelar
                    </flux:button>
                    <flux:button variant="danger" onclick="confirmDelete()">
                        Excluir
                    </flux:button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast de Notificação -->
    <div id="toast" class="hidden fixed top-4 right-4 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 max-w-sm">
            <div class="flex items-center">
                <div id="toastIcon" class="flex-shrink-0 mr-3">
                    <!-- Ícone será inserido aqui -->
                </div>
                <div>
                    <div id="toastTitle" class="font-medium"></div>
                    <div id="toastMessage" class="text-sm text-gray-500"></div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Animações customizadas para os previews */
        @keyframes pulse-slow {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        .pulse-slow {
            animation: pulse-slow 2s infinite;
        }
        
        /* Efeito de hover melhorado para cards */
        .file-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .file-card:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        /* Melhorias para o modal */
        .modal-backdrop {
            backdrop-filter: blur(8px);
            background-color: rgba(0, 0, 0, 0.6);
        }
        
        /* Animação de entrada para imagens */
        .image-fade-in {
            animation: fadeInScale 0.5s ease-out;
        }
        
        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        /* Efeito shimmer para loading */
        .shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }
        
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        
        /* Melhorias para vídeo thumbnails */
        .video-thumbnail {
            position: relative;
            overflow: hidden;
        }
        
        .video-thumbnail::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.1) 50%, transparent 70%);
            transform: translateX(-100%);
            transition: transform 0.6s;
        }
        
        .video-thumbnail:hover::after {
            transform: translateX(100%);
        }
    </style>

    <script>
        let currentFiles = [];
        let filteredFiles = [];
        let activeCategory = 'all';
        let apiToken = null;
        let fileToDelete = null;
        
        // Gerar token de API na inicialização
        async function generateApiToken() {
            try {
                const response = await fetch('/api/generate-token', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await response.json();
                if (data.token) {
                    apiToken = data.token;
                }
            } catch (error) {
                console.error('Erro ao gerar token de API:', error);
            }
        }

        // Carregar arquivos ao inicializar
        document.addEventListener('DOMContentLoaded', async function() {
            await generateApiToken();
            await setupStorage();
            loadFiles();
        });

        async function setupStorage() {
            try {
                const response = await fetch('/api/storage/setup', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${apiToken}`,
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();
                
                if (!data.success) {
                    console.warn('Falha na configuração automática do storage:', data.message);
                }
            } catch (error) {
                console.warn('Erro ao configurar storage:', error);
            }
        }

        async function loadFiles() {
            showFilesLoading();
            
            try {
                const params = new URLSearchParams();
                if (activeCategory !== 'all') {
                    params.append('category', activeCategory);
                }
                
                const searchTerm = document.getElementById('searchInput').value.trim();
                if (searchTerm) {
                    params.append('search', searchTerm);
                }

                const headers = {
                    'Content-Type': 'application/json'
                };
                
                if (apiToken) {
                    headers['Authorization'] = `Bearer ${apiToken}`;
                } else {
                    headers['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                }
                
                const response = await fetch(`/api/files/list?${params}`, {
                    headers: headers
                });

                // Verificar se a resposta é JSON válido
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const text = await response.text();
                    console.error('Resposta não é JSON:', text.substring(0, 200));
                    throw new Error(`Servidor retornou ${response.status}: ${response.statusText}. Resposta não é JSON válido.`);
                }

                const data = await response.json();
                
                if (data.success) {
                    currentFiles = data.files;
                    filteredFiles = [...currentFiles];
                    updateStatistics(data.stats);
                    renderFiles();
                } else {
                    showError(data.message || 'Erro ao carregar arquivos');
                }

            } catch (error) {
                showError('Erro de conexão: ' + error.message);
            }
        }

        function updateStatistics(stats) {
            document.getElementById('totalFiles').textContent = stats.total;
            document.getElementById('totalImages').textContent = stats.images;
            document.getElementById('totalVideos').textContent = stats.videos + stats.audios;
            document.getElementById('totalSize').textContent = formatBytes(stats.total_size);
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        function renderFiles() {
            const container = document.getElementById('filesGrid');
            const subheading = document.getElementById('filesSubheading');
            
            hideAllStates();
            
            if (filteredFiles.length === 0) {
                document.getElementById('emptyState').classList.remove('hidden');
                subheading.textContent = 'Nenhum arquivo encontrado';
                return;
            }

            subheading.textContent = `${filteredFiles.length} arquivo(s) encontrado(s)`;
            container.classList.remove('hidden');
            
            container.innerHTML = filteredFiles.map(file => `
                <div class="bg-white dark:bg-gray-800 rounded-lg hover:shadow-lg hover:scale-105 transition-all duration-200">
                    <!-- Preview -->
                    <div class="aspect-square bg-gray-100 dark:bg-gray-700 flex items-center justify-center cursor-pointer overflow-hidden" onclick="previewFile('${escapeHtml(file.path)}', '${file.category}', '${escapeHtml(file.original_name)}')">
                        ${getFilePreview(file)}
                    </div>
                    
                    <!-- Info -->
                    <div class="p-3">
                        <div class="font-medium text-sm truncate" title="${escapeHtml(file.original_name)}">
                            ${escapeHtml(file.original_name)}
                        </div>
                        <div class="text-xs text-gray-500">
                            ${file.size_formatted} • ${file.modified_at_formatted}
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="px-2 py-1 text-xs rounded-full ${getCategoryBadgeClass(file.category)}">
                                ${getCategoryLabel(file.category)}
                            </span>
                            <div class="flex space-x-1">
                                <button onclick="copyFileUrl('${escapeHtml(file.direct_url || file.url)}')" class="p-1 text-gray-400 hover:text-blue-600 transition-colors" title="Copiar Link">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                </button>
                                <button onclick="downloadFile('${escapeHtml(file.path)}', '${escapeHtml(file.original_name)}')" class="p-1 text-gray-400 hover:text-green-600 transition-colors" title="Download">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-4-4m4 4l4-4m-4-4V3"></path>
                                    </svg>
                                </button>
                                <button onclick="deleteFile('${escapeHtml(file.path)}', '${escapeHtml(file.original_name)}')" class="p-1 text-gray-400 hover:text-red-600 transition-colors" title="Excluir">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
            
            // Inicializar lazy loading após renderizar
            setTimeout(() => {
                initLazyLoading();
            }, 100);
        }

        function getFilePreview(file) {
            if (file.category === 'image') {
                let url = file.url;
                // Adicionar token de autenticação para URLs da API
                if (url.startsWith('/api/files/serve/')) {
                    const token = apiToken || document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    url += `?token=${encodeURIComponent(token)}`;
                }
                
                const imageId = `img-${Math.random().toString(36).substr(2, 9)}`;
                return `
                    <div class="relative w-full h-full bg-gray-100 dark:bg-gray-700">
                        <!-- Loading Spinner -->
                        <div id="loading-${imageId}" class="absolute inset-0 flex items-center justify-center">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                        </div>
                        
                        <!-- Image -->
                        <img 
                            id="${imageId}"
                            data-src="${url}" 
                            alt="${escapeHtml(file.original_name)}" 
                            class="w-full h-full object-cover opacity-0 transition-opacity duration-300 lazy-image"
                            onload="handleImageLoad('${imageId}')"
                            onerror="handleImageError('${imageId}', '${escapeHtml(file.original_name)}')"
                        >
                        
                        <!-- Image Overlay -->
                        <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-20 transition-all duration-200 flex items-center justify-center opacity-0 hover:opacity-100">
                            <div class="text-white m-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                `;
            } else if (file.category === 'video') {
                let url = file.url;
                if (url.startsWith('/api/files/serve/')) {
                    const token = apiToken || document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    url += `?token=${encodeURIComponent(token)}`;
                }
                
                return `
                    <div class="relative w-full h-full bg-gradient-to-br from-purple-100 to-purple-200 dark:from-purple-800 dark:to-purple-900 flex items-center justify-center group">
                        <!-- Video Thumbnail (if possible) -->
                        <video class="w-full h-full object-cover opacity-20" muted preload="metadata">
                            <source src="${url}#t=1" type="video/mp4">
                        </video>
                        
                        <!-- Play Icon Overlay -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="bg-purple-600 bg-opacity-90 rounded-full p-4 group-hover:bg-opacity-100 transition-all duration-200 group-hover:scale-110">
                                <svg class="w-8 h-8 text-white ml-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"></path>
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Video Label -->
                        <div class="absolute bottom-2 left-2 bg-purple-600 text-white text-xs px-2 py-1 rounded">
                            VÍDEO
                        </div>
                    </div>
                `;
            } else if (file.category === 'audio') {
                return `
                    <div class="relative w-full h-full bg-gradient-to-br from-orange-100 to-orange-200 dark:from-orange-800 dark:to-orange-900 flex items-center justify-center group">
                        <!-- Audio Waveform Background -->
                        <div class="absolute inset-0 opacity-10">
                            <svg class="w-full h-16" viewBox="0 0 200 100" fill="currentColor">
                                <rect x="10" y="30" width="4" height="40" class="text-orange-600"/>
                                <rect x="20" y="20" width="4" height="60" class="text-orange-600"/>
                                <rect x="30" y="35" width="4" height="30" class="text-orange-600"/>
                                <rect x="40" y="15" width="4" height="70" class="text-orange-600"/>
                                <rect x="50" y="25" width="4" height="50" class="text-orange-600"/>
                                <rect x="60" y="40" width="4" height="20" class="text-orange-600"/>
                                <rect x="70" y="10" width="4" height="80" class="text-orange-600"/>
                                <rect x="80" y="30" width="4" height="40" class="text-orange-600"/>
                                <rect x="90" y="20" width="4" height="60" class="text-orange-600"/>
                                <rect x="100" y="35" width="4" height="30" class="text-orange-600"/>
                                <rect x="110" y="25" width="4" height="50" class="text-orange-600"/>
                                <rect x="120" y="15" width="4" height="70" class="text-orange-600"/>
                                <rect x="130" y="40" width="4" height="20" class="text-orange-600"/>
                                <rect x="140" y="30" width="4" height="40" class="text-orange-600"/>
                                <rect x="150" y="20" width="4" height="60" class="text-orange-600"/>
                                <rect x="160" y="35" width="4" height="30" class="text-orange-600"/>
                                <rect x="170" y="25" width="4" height="50" class="text-orange-600"/>
                                <rect x="180" y="15" width="4" height="70" class="text-orange-600"/>
                            </svg>
                        </div>
                        
                        <!-- Audio Icon -->
                        <div class="relative z-10 bg-orange-600 bg-opacity-90 rounded-full p-4 group-hover:bg-opacity-100 transition-all duration-200 group-hover:scale-110">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.707.707L4.586 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.586l3.707-3.707a1 1 0 011.09-.217zM15.657 6.343a1 1 0 011.414 0A9.972 9.972 0 0119 12a9.972 9.972 0 01-1.929 5.657 1 1 0 11-1.414-1.414A7.971 7.971 0 0017 12c0-2.21-.895-4.21-2.343-5.657a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        
                        <!-- Audio Label -->
                        <div class="absolute bottom-2 left-2 bg-orange-600 text-white text-xs px-2 py-1 rounded">
                            ÁUDIO
                        </div>
                    </div>
                `;
            } else {
                return `
                    <div class="relative w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 flex items-center justify-center group">
                        <!-- Document Icon -->
                        <div class="bg-gray-600 bg-opacity-90 rounded-full p-4 group-hover:bg-opacity-100 transition-all duration-200 group-hover:scale-110">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        
                        <!-- File Label -->
                        <div class="absolute bottom-2 left-2 bg-gray-600 text-white text-xs px-2 py-1 rounded">
                            ARQUIVO
                        </div>
                    </div>
                `;
            }
        }

        function getCategoryBadgeClass(category) {
            const classes = {
                'image': 'bg-green-100 text-green-800',
                'video': 'bg-purple-100 text-purple-800',
                'audio': 'bg-orange-100 text-orange-800',
                'other': 'bg-gray-100 text-gray-800'
            };
            return classes[category] || classes['other'];
        }

        function getCategoryLabel(category) {
            const labels = {
                'image': 'Imagem',
                'video': 'Vídeo',
                'audio': 'Áudio',
                'other': 'Outro'
            };
            return labels[category] || 'Arquivo';
        }

        function setActiveCategory(category) {
            activeCategory = category;
            
            // Atualizar visual das abas
            document.querySelectorAll('.category-tab').forEach(tab => {
                tab.classList.remove('active', 'bg-indigo-600', 'text-white');
                tab.classList.add('text-gray-600', 'hover:text-gray-800');
            });
            
            const activeTab = document.querySelector(`[data-category="${category}"]`);
            activeTab.classList.add('active', 'bg-indigo-600', 'text-white');
            activeTab.classList.remove('text-gray-600', 'hover:text-gray-800');
            
            loadFiles();
        }

        function filterFiles() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase().trim();
            
            if (!searchTerm) {
                filteredFiles = [...currentFiles];
            } else {
                filteredFiles = currentFiles.filter(file => 
                    file.original_name.toLowerCase().includes(searchTerm)
                );
            }
            
            renderFiles();
        }

        // Drag and Drop
        function handleDragOver(e) {
            e.preventDefault();
            e.stopPropagation();
            document.getElementById('dropZone').classList.add('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20');
        }

        function handleDragLeave(e) {
            e.preventDefault();
            e.stopPropagation();
            document.getElementById('dropZone').classList.remove('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20');
        }

        function handleDrop(e) {
            e.preventDefault();
            e.stopPropagation();
            document.getElementById('dropZone').classList.remove('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20');
            
            const files = Array.from(e.dataTransfer.files);
            uploadFiles(files);
        }

        function handleFileSelect(e) {
            const files = Array.from(e.target.files);
            uploadFiles(files);
        }

        async function uploadFiles(files) {
            if (files.length === 0) return;

            // Validar arquivos
            const validFiles = files.filter(file => {
                const maxSize = 100 * 1024 * 1024; // 100MB
                const validTypes = ['image/', 'video/', 'audio/'];
                
                if (file.size > maxSize) {
                    showToast('error', 'Arquivo muito grande', `${file.name} excede 100MB`);
                    return false;
                }
                
                if (!validTypes.some(type => file.type.startsWith(type))) {
                    showToast('error', 'Tipo inválido', `${file.name} não é um arquivo de mídia válido`);
                    return false;
                }
                
                return true;
            });

            if (validFiles.length === 0) return;

            showUploadProgress();
            
            let uploadedCount = 0;
            const totalFiles = validFiles.length;

            for (const file of validFiles) {
                try {
                    await uploadSingleFile(file);
                    uploadedCount++;
                    updateUploadProgress(uploadedCount, totalFiles, `Uploaded ${file.name}`);
                } catch (error) {
                    showToast('error', 'Erro no upload', `Falha ao enviar ${file.name}: ${error.message}`);
                }
            }

            hideUploadProgress();
            
            if (uploadedCount > 0) {
                showToast('success', 'Upload concluído', `${uploadedCount} arquivo(s) enviado(s) com sucesso`);
                loadFiles(); // Recarregar lista
            }

            // Limpar input
            document.getElementById('fileInput').value = '';
        }

        async function uploadSingleFile(file) {
            const formData = new FormData();
            formData.append('file', file);
            
            // Determinar categoria baseada no tipo MIME
            let category = null;
            if (file.type.startsWith('image/')) {
                category = 'image';
            } else if (file.type.startsWith('video/')) {
                category = 'video';
            } else if (file.type.startsWith('audio/')) {
                category = 'audio';
            }
            
            // Enviar categoria apenas se for uma das válidas
            if (category) {
                formData.append('category', category);
            }
            
            const headers = {};
            
            if (apiToken) {
                headers['Authorization'] = `Bearer ${apiToken}`;
            } else {
                headers['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            }

            const response = await fetch('/api/files/upload', {
                method: 'POST',
                headers: headers,
                body: formData
            });

            // Verificar se a resposta é JSON válido
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const text = await response.text();
                console.error('Resposta não é JSON:', text.substring(0, 200));
                throw new Error(`Servidor retornou ${response.status}: ${response.statusText}. Resposta não é JSON válido.`);
            }

            const data = await response.json();
            
            if (!data.success) {
                throw new Error(data.message || 'Erro no upload');
            }

            return data.file;
        }

        function showUploadProgress() {
            document.getElementById('uploadProgress').classList.remove('hidden');
        }

        function hideUploadProgress() {
            document.getElementById('uploadProgress').classList.add('hidden');
        }

        function updateUploadProgress(current, total, status) {
            const percentage = Math.round((current / total) * 100);
            document.getElementById('uploadPercentage').textContent = `${percentage}%`;
            document.getElementById('uploadProgressBar').style.width = `${percentage}%`;
            document.getElementById('uploadStatus').textContent = status;
        }

        function previewFile(path, category, name) {
            const modal = document.getElementById('previewModal');
            const title = document.getElementById('previewTitle');
            const info = document.getElementById('previewInfo');
            const content = document.getElementById('previewContent');
            
            title.textContent = name;
            
            // Encontrar o arquivo na lista atual para obter informações completas
            const file = currentFiles.find(f => f.path === path);
            let url = file ? file.url : `/storage/${path}`;
            
            // Adicionar token de autenticação para URLs da API
            if (url.startsWith('/api/files/serve/')) {
                const token = apiToken || document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                url += `?token=${encodeURIComponent(token)}`;
            }
            
            // Atualizar informações do arquivo
            if (file) {
                info.innerHTML = `
                    <div class="flex flex-wrap gap-4 text-sm text-gray-600 dark:text-gray-400">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a.997.997 0 01-1.414 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            ${getCategoryLabel(category)}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                            </svg>
                            ${file.size_formatted}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            ${file.modified_at_formatted}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"></path>
                            </svg>
                            ${file.storage === 'supabase' ? 'Supabase' : 'Local'}
                        </span>
                    </div>
                `;
            } else {
                info.textContent = `Categoria: ${getCategoryLabel(category)}`;
            }
            
            // Loading state
            content.innerHTML = `
                <div class="flex items-center justify-center h-64">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
                </div>
            `;
            
            // Renderizar preview baseado na categoria
            setTimeout(() => {
                if (category === 'image') {
                    content.innerHTML = `
                        <div class="relative">
                            <img 
                                src="${url}" 
                                alt="${escapeHtml(name)}" 
                                class="max-w-full max-h-[70vh] object-contain mx-auto rounded-lg shadow-lg"
                                onload="this.parentElement.querySelector('.image-loading')?.remove()"
                                onerror="this.parentElement.innerHTML='<div class=&quot;text-center p-8 text-gray-500&quot;><svg class=&quot;w-16 h-16 mx-auto mb-4&quot; fill=&quot;none&quot; stroke=&quot;currentColor&quot; viewBox=&quot;0 0 24 24&quot;><path stroke-linecap=&quot;round&quot; stroke-linejoin=&quot;round&quot; stroke-width=&quot;2&quot; d=&quot;M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z&quot;></path></svg><p>Erro ao carregar imagem</p></div>'"
                            >
                            <div class="image-loading absolute inset-0 flex items-center justify-center bg-gray-100 dark:bg-gray-800 rounded-lg">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                            </div>
                        </div>
                    `;
                } else if (category === 'video') {
                    content.innerHTML = `
                        <div class="relative">
                            <video 
                                controls 
                                class="max-w-full max-h-[70vh] mx-auto rounded-lg shadow-lg"
                                preload="metadata"
                                onloadstart="this.parentElement.querySelector('.video-loading')?.remove()"
                            >
                                <source src="${url}" type="video/mp4">
                                <source src="${url}" type="video/webm">
                                <source src="${url}" type="video/ogg">
                                Seu navegador não suporta reprodução de vídeo.
                            </video>
                            <div class="video-loading absolute inset-0 flex items-center justify-center bg-gray-100 dark:bg-gray-800 rounded-lg">
                                <div class="text-center">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-600 mx-auto mb-2"></div>
                                    <p class="text-sm text-gray-600">Carregando vídeo...</p>
                                </div>
                            </div>
                        </div>
                    `;
                } else if (category === 'audio') {
                    content.innerHTML = `
                        <div class="text-center p-8">
                            <!-- Audio Visualizer -->
                            <div class="mb-6">
                                <div class="w-32 h-32 mx-auto bg-gradient-to-br from-orange-400 to-orange-600 rounded-full flex items-center justify-center shadow-lg">
                                    <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.707.707L4.586 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.586l3.707-3.707a1 1 0 011.09-.217zM15.657 6.343a1 1 0 011.414 0A9.972 9.972 0 0119 12a9.972 9.972 0 01-1.929 5.657 1 1 0 11-1.414-1.414A7.971 7.971 0 0017 12c0-2.21-.895-4.21-2.343-5.657a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            
                            <!-- Audio Player -->
                            <audio 
                                controls 
                                class="w-full max-w-md mx-auto"
                                preload="metadata"
                            >
                                <source src="${url}" type="audio/mpeg">
                                <source src="${url}" type="audio/wav">
                                <source src="${url}" type="audio/ogg">
                                Seu navegador não suporta reprodução de áudio.
                            </audio>
                        </div>
                    `;
                } else {
                    content.innerHTML = `
                        <div class="text-center p-8">
                            <div class="w-24 h-24 mx-auto bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500 mb-4">Preview não disponível para este tipo de arquivo</p>
                            <button 
                                onclick="downloadFile('${escapeHtml(path)}', '${escapeHtml(name)}')" 
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-4-4m4 4l4-4m-4-4V3"></path>
                                </svg>
                                Baixar Arquivo
                            </button>
                        </div>
                    `;
                }
            }, 100);
            
            modal.classList.remove('hidden');
        }

        function closePreviewModal() {
            document.getElementById('previewModal').classList.add('hidden');
        }

        function copyFileUrl(url) {
            // Se a URL já é completa (Supabase), usar diretamente, senão construir URL completa
            const fullUrl = url.startsWith('http') ? url : window.location.origin + url;
            
            // Verificar se o clipboard API está disponível
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(fullUrl).then(() => {
                    showToast('success', 'Link copiado', 'URL do arquivo copiada para a área de transferência');
                }).catch(() => {
                    showToast('error', 'Erro', 'Falha ao copiar link');
                });
            } else {
                // Fallback para navegadores mais antigos
                const textArea = document.createElement('textarea');
                textArea.value = fullUrl;
                document.body.appendChild(textArea);
                textArea.select();
                try {
                    document.execCommand('copy');
                    showToast('success', 'Link copiado', 'URL do arquivo copiada para a área de transferência');
                } catch (err) {
                    showToast('error', 'Erro', 'Falha ao copiar link');
                }
                document.body.removeChild(textArea);
            }
        }

        function downloadFile(path, name) {
            const file = currentFiles.find(f => f.path === path);
            if (file) {
                let url = file.url;
                
                // Adicionar token de autenticação para URLs da API
                if (url.startsWith('/api/files/serve/')) {
                    const token = apiToken || document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    url += `?token=${encodeURIComponent(token)}&download=1`;
                }
                
                const link = document.createElement('a');
                link.href = url;
                link.download = name;
                link.target = '_blank';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        }

        function deleteFile(path, name) {
            fileToDelete = { path, name };
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            fileToDelete = null;
        }

        async function confirmDelete() {
            if (!fileToDelete) return;

            try {
                const headers = {
                    'Content-Type': 'application/json'
                };
                
                if (apiToken) {
                    headers['Authorization'] = `Bearer ${apiToken}`;
                } else {
                    headers['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                }
                
                const response = await fetch('/api/files/delete', {
                    method: 'DELETE',
                    headers: headers,
                    body: JSON.stringify({ path: fileToDelete.path })
                });

                // Verificar se a resposta é JSON válido
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const text = await response.text();
                    console.error('Resposta não é JSON:', text.substring(0, 200));
                    throw new Error(`Servidor retornou ${response.status}: ${response.statusText}. Resposta não é JSON válido.`);
                }

                const data = await response.json();
                
                if (data.success) {
                    showToast('success', 'Arquivo excluído', 'Arquivo removido com sucesso');
                    loadFiles(); // Recarregar lista
                } else {
                    showToast('error', 'Erro', data.message || 'Falha ao excluir arquivo');
                }

            } catch (error) {
                showToast('error', 'Erro', 'Erro de conexão: ' + error.message);
            }

            closeDeleteModal();
        }

        function showFilesLoading() {
            hideAllStates();
            document.getElementById('filesLoading').classList.remove('hidden');
        }

        function showError(message) {
            hideAllStates();
            document.getElementById('errorMessage').textContent = message;
            document.getElementById('errorState').classList.remove('hidden');
        }

        function hideAllStates() {
            document.getElementById('filesLoading').classList.add('hidden');
            document.getElementById('filesGrid').classList.add('hidden');
            document.getElementById('emptyState').classList.add('hidden');
            document.getElementById('errorState').classList.add('hidden');
        }

        function showToast(type, title, message) {
            const toast = document.getElementById('toast');
            const icon = document.getElementById('toastIcon');
            const titleEl = document.getElementById('toastTitle');
            const messageEl = document.getElementById('toastMessage');

            // Definir ícone baseado no tipo
            if (type === 'success') {
                icon.innerHTML = '<div class="w-5 h-5 text-green-500"><svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg></div>';
            } else {
                icon.innerHTML = '<div class="w-5 h-5 text-red-500"><svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg></div>';
            }

            titleEl.textContent = title;
            messageEl.textContent = message;

            toast.classList.remove('hidden');

            // Auto-hide após 5 segundos
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 5000);
        }

        function formatBytes(bytes) {
            if (bytes === 0) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Funções para lazy loading de imagens
        function handleImageLoad(imageId) {
            const img = document.getElementById(imageId);
            const loading = document.getElementById(`loading-${imageId}`);
            
            if (img && loading) {
                img.classList.remove('opacity-0');
                img.classList.add('opacity-100');
                loading.style.display = 'none';
            }
        }

        function handleImageError(imageId, fileName) {
            const img = document.getElementById(imageId);
            const loading = document.getElementById(`loading-${imageId}`);
            
            if (img && loading) {
                loading.innerHTML = `
                    <div class="text-center text-gray-500">
                        <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <div class="text-xs">Erro ao carregar</div>
                    </div>
                `;
            }
        }

        // Implementar lazy loading quando as imagens entram na viewport
        function initLazyLoading() {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        const src = img.getAttribute('data-src');
                        if (src) {
                            img.src = src;
                            img.removeAttribute('data-src');
                            observer.unobserve(img);
                        }
                    }
                });
            });

            // Observar todas as imagens lazy
            document.querySelectorAll('.lazy-image').forEach(img => {
                imageObserver.observe(img);
            });
        }

        // Inicializar aba ativa
        document.addEventListener('DOMContentLoaded', async function() {
            await generateApiToken();
            setActiveCategory('all');
        });
    </script>
</x-layouts.app>