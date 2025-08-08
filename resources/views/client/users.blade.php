<x-layouts.app title="Client - Users">
    <flux:heading size="xl">Usuários do Sistema</flux:heading>
    <flux:subheading>Todos os usuários cadastrados no sistema</flux:subheading>

    <div class="mt-6 space-y-6">
        <!-- User Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <div class="flex items-center">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Total de Usuários</p>
                        <p class="text-2xl font-semibold text-neutral-900 dark:text-neutral-100">{{ $stats['total_users'] }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <div class="flex items-center">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-100 text-green-600 dark:bg-green-900/20 dark:text-green-400">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Usuários Cliente</p>
                        <p class="text-2xl font-semibold text-neutral-900 dark:text-neutral-100">{{ $stats['client_users'] }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <div class="flex items-center">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-red-100 text-red-600 dark:bg-red-900/20 dark:text-red-400">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Usuários Admin</p>
                        <p class="text-2xl font-semibold text-neutral-900 dark:text-neutral-100">{{ $stats['admin_users'] }}</p>
                    </div>
                </div>
            </div>
        </div>



        <!-- Users List -->
        <flux:card :padding="false">
            <flux:card.header>
                <flux:heading size="lg">Users in Your Tenant</flux:heading>
                <flux:subheading>All users who belong to the same tenant as you</flux:subheading>
            </flux:card.header>
            
            <div class="overflow-x-auto p-6">
                <flux:table>
                    <flux:table.header>
                        <flux:table.row>
                            <flux:table.column>Nome</flux:table.column>
                            <flux:table.column>Email</flux:table.column>
                            <flux:table.column>Role</flux:table.column>
                        </flux:table.row>
                    </flux:table.header>

                    <flux:table.body>
                        @foreach($tenantUsers as $user)
                        <flux:table.row>
                            <flux:table.cell>
                                <div class="flex items-center space-x-3">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                        {{ $user->initials() }}
                                    </div>
                                    <div>
                                        <div class="font-medium">
                                            {{ $user->name }}
                                            @if($user->id === auth()->id())
                                                <flux:badge color="blue" size="xs">You</flux:badge>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </flux:table.cell>
                            <flux:table.cell>{{ $user->email }}</flux:table.cell>
                            <flux:table.cell>
                                <div class="flex flex-wrap gap-1">
                                    @foreach($user->roles as $role)
                                        <flux:badge 
                                            color="{{ $role->name === 'admin' ? 'red' : 'green' }}" 
                                            size="sm"
                                        >
                                            {{ ucfirst($role->name) }}
                                        </flux:badge>
                                    @endforeach
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                        @endforeach
                    </flux:table.body>
                </flux:table>
            </div>
        </flux:card>

        @if($tenantUsers->isEmpty())
        <flux:card>
            <div class="text-center py-12">
                <flux:icon.users class="mx-auto h-12 w-12 text-gray-400" />
                <flux:heading size="lg" class="mt-4">No users found</flux:heading>
                <flux:subheading>There are no other users in your tenant yet.</flux:subheading>
            </div>
        </flux:card>
        @endif


    </div>
</x-layouts.app>