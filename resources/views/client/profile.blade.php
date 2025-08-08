<x-layouts.app title="Client - Profile">
    <flux:header>
        <flux:heading size="xl">My Profile</flux:heading>
        <flux:subheading>Manage your personal information and settings</flux:subheading>
    </flux:header>

    <div class="space-y-6">
        <!-- Profile Information -->
        <flux:card :padding="false">
            <flux:card.header>
                <flux:heading size="lg">Profile Information</flux:heading>
                <flux:subheading>Update your account's profile information and email address</flux:subheading>
            </flux:card.header>
            
            <form method="POST" action="{{ route('client.profile.update') }}" class="space-y-6 p-6">
                @csrf
                @method('PUT')
                
                <div class="flex items-center space-x-6">
                    <div class="flex h-20 w-20 items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white text-2xl font-bold">
                        {{ $user->initials() }}
                    </div>
                    <div class="flex-1">
                        <flux:field>
                            <flux:label>Full Name</flux:label>
                            <flux:input 
                                name="name" 
                                value="{{ old('name', $user->name) }}" 
                                placeholder="Enter your full name"
                                required 
                            />
                            <flux:error name="name" />
                        </flux:field>
                    </div>
                </div>

                <flux:field>
                    <flux:label>Email Address</flux:label>
                    <flux:input 
                        name="email" 
                        type="email" 
                        value="{{ old('email', $user->email) }}" 
                        placeholder="Enter your email address"
                        required 
                    />
                    <flux:error name="email" />
                </flux:field>

                <div class="flex justify-end">
                    <flux:button type="submit" variant="primary">Update Profile</flux:button>
                </div>
            </form>
        </flux:card>

        <!-- Account Information -->
        <flux:card :padding="false">
            <flux:card.header>
                <flux:heading size="lg">Account Information</flux:heading>
                <flux:subheading>Your account details and tenant information</flux:subheading>
            </flux:card.header>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
                <div>
                    <flux:label>Função no Sistema</flux:label>
                    <div class="mt-1 flex flex-wrap gap-2">
                        @foreach($user->roles as $role)
                            <flux:badge 
                                color="{{ $role->name === 'admin' ? 'red' : 'green' }}" 
                                size="sm"
                            >
                                {{ ucfirst($role->name) }}
                            </flux:badge>
                        @endforeach
                    </div>
                </div>
                
                <div>
                    <flux:label>Account Type</flux:label>
                    <div class="mt-1">
                        <flux:badge color="green" size="lg">
                            Client Account
                        </flux:badge>
                    </div>
                </div>
                
                <div>
                    <flux:label>Member Since</flux:label>
                    <div class="mt-1 text-sm text-gray-600">
                        {{ $user->created_at->format('F j, Y') }}
                    </div>
                </div>
                
                <div>
                    <flux:label>Last Updated</flux:label>
                    <div class="mt-1 text-sm text-gray-600">
                        {{ $user->updated_at->format('F j, Y g:i A') }}
                    </div>
                </div>
            </div>
        </flux:card>



        <!-- Security Settings -->
        <flux:card :padding="false">
            <flux:card.header>
                <flux:heading size="lg">Security Settings</flux:heading>
                <flux:subheading>Manage your account security</flux:subheading>
            </flux:card.header>
            
            <div class="space-y-4 p-6">
                <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                    <div>
                        <div class="font-medium">Password</div>
                        <div class="text-sm text-gray-600">Last updated {{ $user->updated_at->diffForHumans() }}</div>
                    </div>
                    <flux:button variant="outline" size="sm">Change Password</flux:button>
                </div>
                
                <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                    <div>
                        <div class="font-medium">Two-Factor Authentication</div>
                        <div class="text-sm text-gray-600">Add an extra layer of security to your account</div>
                    </div>
                    <flux:button variant="outline" size="sm">Enable 2FA</flux:button>
                </div>
            </div>
        </flux:card>
    </div>
</x-layouts.app>