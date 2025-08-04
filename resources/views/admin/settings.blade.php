<x-layouts.app title="Admin - System Settings">
    <flux:header>
        <flux:heading size="xl">System Settings</flux:heading>
        <flux:subheading>Manage roles, permissions, and system configuration</flux:subheading>
    </flux:header>

    <div class="space-y-6">
        @if(session('success'))
            <flux:banner variant="success">
                {{ session('success') }}
            </flux:banner>
        @endif

        <!-- Evolution API Configuration -->
        <flux:card>
            <flux:card.header>
                <flux:heading size="lg">Configurações da Evolution API</flux:heading>
                <flux:subheading>Configure as URLs e chaves de API para integração com Evolution e n8n</flux:subheading>
            </flux:card.header>
            
            <form action="{{ route('admin.evolution-settings.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:field>
                        <flux:label>URL da Evolution API</flux:label>
                        <flux:input 
                            name="evolution_api_url" 
                            type="url" 
                            value="{{ old('evolution_api_url', $evolutionSettings['api_url']) }}"
                            placeholder="https://evolution.iaconversas.com"
                            required
                        />
                        @error('evolution_api_url')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </flux:field>
                    
                    <flux:field>
                        <flux:label>Chave da API Evolution</flux:label>
                        <flux:input 
                            name="evolution_api_key" 
                            type="password" 
                            value="{{ old('evolution_api_key', $evolutionSettings['api_key']) }}"
                            placeholder="Digite a chave da API"
                            required
                        />
                        @error('evolution_api_key')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </flux:field>
                </div>
                
                <flux:field>
                    <flux:label>URL do n8n (Webhooks)</flux:label>
                    <flux:input 
                        name="n8n_webhook_url" 
                        type="url" 
                        value="{{ old('n8n_webhook_url', $evolutionSettings['n8n_url']) }}"
                        placeholder="https://n8n.iaconversas.com"
                        required
                    />
                    @error('n8n_webhook_url')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </flux:field>
                
                <div class="flex justify-end">
                    <flux:button type="submit" variant="primary">
                        <flux:icon.check class="size-4" />
                        Salvar Configurações
                    </flux:button>
                </div>
            </form>
        </flux:card>

        <!-- Roles Section -->
        <flux:card>
            <flux:card.header>
                <flux:heading size="lg">Roles Management</flux:heading>
                <flux:subheading>System roles and their permissions</flux:subheading>
            </flux:card.header>
            
            <div class="space-y-4">
                @foreach($roles as $role)
                <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-3">
                            <flux:badge 
                                color="{{ $role->name === 'admin' ? 'red' : 'green' }}" 
                                size="lg"
                            >
                                {{ ucfirst($role->name) }}
                            </flux:badge>
                            <div>
                                <div class="font-medium">{{ ucfirst($role->name) }} Role</div>
                                <div class="text-sm text-gray-600">
                                    {{ $role->users->count() }} {{ Str::plural('user', $role->users->count()) }} assigned
                                </div>
                            </div>
                        </div>
                        <flux:button size="sm" variant="ghost">Edit Role</flux:button>
                    </div>
                    
                    <div>
                        <flux:subheading class="mb-2">Permissions:</flux:subheading>
                        <div class="flex flex-wrap gap-2">
                            @foreach($role->permissions as $permission)
                                <flux:badge color="blue" size="sm">
                                    {{ $permission->name }}
                                </flux:badge>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </flux:card>

        <!-- Permissions Section -->
        <flux:card>
            <flux:card.header>
                <flux:heading size="lg">Permissions Management</flux:heading>
                <flux:subheading>All available system permissions</flux:subheading>
            </flux:card.header>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($permissions as $permission)
                <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                    <div class="flex items-center justify-between mb-2">
                        <flux:badge color="purple" size="sm">
                            {{ $permission->name }}
                        </flux:badge>
                        <flux:button size="xs" variant="ghost">Edit</flux:button>
                    </div>
                    <div class="text-sm text-gray-600">
                        Used by {{ $permission->roles->count() }} {{ Str::plural('role', $permission->roles->count()) }}
                    </div>
                    <div class="mt-2">
                        <div class="text-xs text-gray-500">Assigned to roles:</div>
                        <div class="flex flex-wrap gap-1 mt-1">
                            @foreach($permission->roles as $role)
                                <flux:badge 
                                    color="{{ $role->name === 'admin' ? 'red' : 'green' }}" 
                                    size="xs"
                                >
                                    {{ $role->name }}
                                </flux:badge>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </flux:card>

        <!-- System Statistics -->
        <flux:card>
            <flux:card.header>
                <flux:heading size="lg">System Statistics</flux:heading>
            </flux:card.header>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $stats['total_roles'] }}</div>
                    <div class="text-sm text-gray-600">Total Roles</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-purple-600">{{ $stats['total_permissions'] }}</div>
                    <div class="text-sm text-gray-600">Total Permissions</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $stats['users_with_roles'] }}</div>
                    <div class="text-sm text-gray-600">Users with Roles</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-red-600">{{ $stats['permission_assignments'] }}</div>
                    <div class="text-sm text-gray-600">Permission Assignments</div>
                </div>
            </div>
        </flux:card>

        <!-- Actions -->
        <flux:card>
            <flux:card.header>
                <flux:heading size="lg">Quick Actions</flux:heading>
            </flux:card.header>
            
            <div class="flex flex-wrap gap-4">
                <flux:button>Create New Role</flux:button>
                <flux:button variant="outline">Create New Permission</flux:button>
                <flux:button variant="outline" color="red">Reset Permissions</flux:button>
                <flux:button variant="ghost">Export Configuration</flux:button>
            </div>
        </flux:card>
    </div>
</x-layouts.app>