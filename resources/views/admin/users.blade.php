<x-layouts.app title="Admin - Users Management">
    <flux:header>
        <flux:heading size="xl">Users Management</flux:heading>
        <flux:subheading>Manage all users in the system</flux:subheading>
    </flux:header>

    <div class="space-y-6">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <flux:card>
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600">{{ $totalUsers }}</div>
                    <div class="text-sm text-gray-600">Total Users</div>
                </div>
            </flux:card>
            
            <flux:card>
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600">{{ $adminUsers }}</div>
                    <div class="text-sm text-gray-600">Admin Users</div>
                </div>
            </flux:card>
            
            <flux:card>
                <div class="text-center">
                    <div class="text-3xl font-bold text-purple-600">{{ $clientUsers }}</div>
                    <div class="text-sm text-gray-600">Client Users</div>
                </div>
            </flux:card>
        </div>

        <!-- Users Table -->
        <flux:card :padding="false">
            <flux:card.header>
                <flux:heading size="lg">All Users</flux:heading>
            </flux:card.header>
            
            <div class="overflow-x-auto p-6">
                <flux:table>
                    <flux:table.header>
                        <flux:table.row>
                            <flux:table.column>Name</flux:table.column>
                            <flux:table.column>Email</flux:table.column>
                            <flux:table.column>Roles</flux:table.column>
                            <flux:table.column>Created</flux:table.column>
                            <flux:table.column>Actions</flux:table.column>
                        </flux:table.row>
                    </flux:table.header>

                    <flux:table.body>
                        @foreach($users as $user)
                        <flux:table.row>
                            <flux:table.cell>
                                <div class="flex items-center space-x-3">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                        {{ $user->initials() }}
                                    </div>
                                    <div>
                                        <div class="font-medium">{{ $user->name }}</div>
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
                            <flux:table.cell>{{ $user->created_at->format('M d, Y') }}</flux:table.cell>
                            <flux:table.cell>
                                <div class="flex space-x-2">
                                    <flux:button size="sm" variant="ghost">Edit</flux:button>
                                    <flux:button size="sm" variant="ghost" color="red">Delete</flux:button>
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                        @endforeach
                    </flux:table.body>
                </flux:table>
            </div>
        </flux:card>
    </div>
</x-layouts.app>