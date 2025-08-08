<x-layouts.figma-app :title="__('Venus Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">
        <div class="flex items-center justify-between mb-2">
            <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
            <div class="flex items-center gap-4">
                <button class="bg-white p-2 rounded-full shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                    </svg>
                </button>
                <button class="bg-white p-2 rounded-full shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
        
        <div class="grid auto-rows-min gap-6 md:grid-cols-3">
            <x-figma.medium-card 
                title="Your earnings today" 
                body="Update your payout method in Settings" 
                value="$682.5"
                buttonText="Withdraw All Earnings"
                type="earnings"
            />
            
            <x-figma.medium-card 
                title="Keep you safe!" 
                body="Update your security password, keep your account safe!" 
                icon="fingerprint_icon.svg"
                buttonText="Update your security"
                type="security"
            />
            
            <x-figma.user-profile 
                name="{{ auth()->user()->name }}" 
                location="New York, USA" 
                followers="643" 
                following="76"
            />
        </div>
        
        <div class="grid auto-rows-min gap-6 md:grid-cols-2 lg:grid-cols-4">
            <x-figma.medium-card 
                title="Control card security" 
                body="Discover our cards benefits, with one tap." 
                buttonText="Cards"
                type="safety"
            />
            
            <x-figma.medium-card 
                title="Recent Transactions" 
                body="View all your recent activity" 
                value="$1,256.00"
                buttonText="View All"
                type="transactions"
            />
            
            <x-figma.medium-card 
                title="Monthly Report" 
                body="Your financial summary" 
                buttonText="Download"
                type="calendar"
            />
            
            <x-figma.medium-card 
                title="Security Status" 
                body="Your account is protected" 
                buttonText="Review"
                type="user"
            />
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="figma-card md:col-span-2 h-80">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="figma-card-title">Monthly Performance</h3>
                    <div class="flex items-center gap-2">
                        <button class="text-sm text-gray-500 hover:text-primary px-3 py-1 rounded-full hover:bg-primary-light transition-colors">Week</button>
                        <button class="text-sm text-white bg-primary px-3 py-1 rounded-full">Month</button>
                        <button class="text-sm text-gray-500 hover:text-primary px-3 py-1 rounded-full hover:bg-primary-light transition-colors">Year</button>
                    </div>
                </div>
                <div class="flex-1 flex items-center justify-center">
                    <div class="w-full h-48 bg-gray-50 rounded-lg flex items-center justify-center">
                        <p class="text-gray-400">Chart will be displayed here</p>
                    </div>
                </div>
            </div>
            
            <div class="figma-card h-80">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="figma-card-title">Recent Activity</h3>
                    <button class="text-sm text-primary hover:underline">View All</button>
                </div>
                <div class="flex-1 flex flex-col gap-4">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-primary-light flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <span class="figma-card-body">Payment Received</span>
                        </div>
                        <span class="figma-card-body font-medium text-success">+$250.00</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-warning-light flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-warning" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <span class="figma-card-body">New Client Added</span>
                        </div>
                        <span class="figma-card-body font-medium">John Doe</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-info-light flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-info" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd" />
                                    <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z" />
                                </svg>
                            </div>
                            <span class="figma-card-body">Project Completed</span>
                        </div>
                        <span class="figma-card-body font-medium">Website Redesign</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.figma-app>