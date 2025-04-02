<x-app-layout>
    <div class="bg-gray-50 min-h-screen">
        <!-- Top Navigation Bar -->
        <nav class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-bold text-gray-800">Vet Clinic</h1>
                    </div>
                    <div class="flex items-center">
                        @php
                            $hour = date('H');
                            $greeting = 'Good evening';
                            if($hour < 12) $greeting = 'Good morning';
                            elseif($hour < 17) $greeting = 'Good afternoon';
                        @endphp
                        <span class="text-sm text-gray-600 mr-4">{{ $greeting }}, {{ auth()->user()->complete_name }}</span>
                        <span class="text-sm bg-blue-100 text-blue-800 py-1 px-3 rounded-full">Receptionist</span>
                    </div>
                </div>
            </div>
        </nav>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <!-- Date & Quick Stats Bar -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div class="bg-white rounded-lg shadow-sm px-4 py-3 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-gray-700 font-medium">{{ now()->format('l, F j, Y') }}</span>
                </div>
                
                <div class="flex flex-wrap gap-3">
                    <div class="bg-blue-50 rounded-lg px-4 py-2 flex items-center">
                        <div class="mr-3 bg-blue-100 p-2 rounded-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-blue-600 font-medium">Today's Appointments</p>
                            <p class="text-lg font-bold text-gray-800">{{ $appointments_count }}</p>
                        </div>
                    </div>
                    
                    <div class="bg-green-50 rounded-lg px-4 py-2 flex items-center">
                        <div class="mr-3 bg-green-100 p-2 rounded-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-green-600 font-medium">New Owners</p>
                            <p class="text-lg font-bold text-gray-800">{{ $new_clients_count }}</p>
                        </div>
                    </div>
                    
                    <div class="bg-yellow-50 rounded-lg px-4 py-2 flex items-center">
                        <div class="mr-3 bg-yellow-100 p-2 rounded-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-yellow-600 font-medium">Pets Registered</p>
                            <p class="text-lg font-bold text-gray-800">{{ $pets_registered_count }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column: Quick Actions -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Quick Actions Panel -->
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600">
                            <h2 class="text-white font-medium">Quick Actions</h2>
                        </div>
                        <div class="p-4">
                            <div class="space-y-2">
                                <a href="{{ route('rec-owners') }}" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-blue-50 transition-colors group">
                                    <div class="bg-blue-100 p-2 rounded-md mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-700 group-hover:text-blue-700">Manage Owners</span>
                                        <p class="text-xs text-gray-500">View and edit owner records</p>
                                    </div>
                                </a>
                                
                                <a href="{{ route('rec-animals') }}" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-yellow-50 transition-colors group">
                                    <div class="bg-yellow-100 p-2 rounded-md mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-700 group-hover:text-yellow-700">Manage Animals</span>
                                        <p class="text-xs text-gray-500">View and edit pet records</p>
                                    </div>
                                </a>
                                
                                <a href="{{ route('reports.index') }}" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-green-50 transition-colors group">
                                    <div class="bg-green-100 p-2 rounded-md mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-700 group-hover:text-green-700">Generate Report</span>
                                        <p class="text-xs text-gray-500">Create clinic activity reports</p>
                                    </div>
                                </a>
                                
                                <a href="#" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-indigo-50 transition-colors group">
                                    <div class="bg-indigo-100 p-2 rounded-md mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-700 group-hover:text-indigo-700">Add New Owner</span>
                                        <p class="text-xs text-gray-500">Register a new client</p>
                                    </div>
                                </a>
                                
                                <a href="#" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-pink-50 transition-colors group">
                                    <div class="bg-pink-100 p-2 rounded-md mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-700 group-hover:text-pink-700">Add New Animal</span>
                                        <p class="text-xs text-gray-500">Register a new pet</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column: Recent Activities -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="px-4 py-3 bg-gradient-to-r from-green-500 to-green-600 flex justify-between items-center">
                            <h2 class="text-white font-medium">Recent Activities</h2>
                            
                            <!-- Date Filter Dropdown - Fixed with vanilla JS -->
                            <div class="relative" id="dateFilterDropdown">
                                <button 
                                    type="button" 
                                    onclick="toggleDropdown()"
                                    class="flex items-center text-white text-sm bg-white bg-opacity-20 rounded-md px-3 py-1.5 hover:bg-opacity-30 focus:outline-none"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                    </svg>
                                    Filter
                                </button>
                                
                                <div id="filterDropdownMenu" class="hidden absolute right-0 mt-2 w-64 bg-white rounded-md shadow-lg z-10">
                                    <form method="GET" action="{{ route('receptionist-dashboard') }}" id="filterForm" class="p-4">
                                        <div class="space-y-3">
                                            <div>
                                                <label for="start_date" class="block text-xs font-medium text-gray-700 mb-1">From</label>
                                                <input 
                                                    type="date" 
                                                    name="start_date" 
                                                    id="start_date" 
                                                    class="w-full border border-gray-300 rounded-md px-3 py-1 text-sm" 
                                                    value="{{ request('start_date', now()->subWeeks(2)->format('Y-m-d')) }}"
                                                >
                                            </div>
                                            <div>
                                                <label for="end_date" class="block text-xs font-medium text-gray-700 mb-1">To</label>
                                                <input 
                                                    type="date" 
                                                    name="end_date" 
                                                    id="end_date" 
                                                    class="w-full border border-gray-300 rounded-md px-3 py-1 text-sm" 
                                                    value="{{ request('end_date', now()->format('Y-m-d')) }}"
                                                >
                                            </div>
                                            <div class="flex justify-between">
                                                <button 
                                                    type="button" 
                                                    onclick="resetFilter()" 
                                                    class="text-xs text-gray-600 hover:text-gray-800">
                                                    Reset
                                                </button>
                                                <button 
                                                    type="submit" 
                                                    class="text-xs bg-green-600 text-white px-3 py-1 rounded-md hover:bg-green-700">
                                                    Apply Filter
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-4">
                            <!-- Activity Timeline -->
                            <div class="relative">
                                <!-- Timeline Track -->
                                <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200"></div>
                                
                                <div class="space-y-6 pl-12">
                                    @forelse ($recent_activities as $activity)
                                        <div class="relative">
                                            <!-- Timeline Dot -->
                                            <div class="absolute -left-8 mt-1.5 w-4 h-4 rounded-full bg-green-500 border-2 border-white"></div>
                                            
                                            <div class="bg-gray-50 rounded-lg p-3 hover:bg-white hover:shadow-sm transition-all">
                                                <div class="flex justify-between items-start">
                                                    <span class="text-sm text-gray-800 font-medium">{!! $activity['description'] !!}</span>
                                                    <time class="text-xs text-gray-400 whitespace-nowrap ml-2 bg-gray-100 px-2 py-0.5 rounded-full" datetime="{{ $activity['created_at'] }}">
                                                        {{ $activity['created_at']->diffForHumans() }}
                                                    </time>
                                                </div>
                                                
                                                <div class="text-xs mt-2 flex flex-wrap gap-2">
                                                    @if(isset($activity['owner']))
                                                        <a href="{{ route('owner.profile', ['id' => $activity['owner']['id']]) }}" 
                                                           class="text-blue-600 hover:underline inline-flex items-center bg-blue-50 px-2 py-0.5 rounded-full">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                            </svg>
                                                            {{ $activity['owner']['name'] }}
                                                        </a>
                                                    @endif
                                                    
                                                    @if(isset($activity['transaction']))
                                                        <span class="text-orange-600 inline-flex items-center bg-orange-50 px-2 py-0.5 rounded-full">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                                            </svg>
                                                            #{{ $activity['transaction']['transaction_id'] }}
                                                        </span>
                                                    @endif
                                                    
                                                    @if(isset($activity['animal']))
                                                        <a href="{{ route('animal.profile', ['id' => $activity['animal']['id']]) }}" 
                                                           class="text-teal-600 hover:underline inline-flex items-center bg-teal-50 px-2 py-0.5 rounded-full">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                            </svg>
                                                            {{ $activity['animal']['name'] }}
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="py-8 text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                            <p class="text-gray-500 font-medium">No recent activities found</p>
                                            <p class="text-xs text-gray-400 mt-1">Try adjusting your date filter or check back later</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Vanilla JavaScript dropdown toggle
        function toggleDropdown() {
            const dropdown = document.getElementById('filterDropdownMenu');
            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
            } else {
                dropdown.classList.add('hidden');
            }
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('filterDropdownMenu');
            const dropdownButton = document.getElementById('dateFilterDropdown').querySelector('button');
            
            if (!dropdown.contains(event.target) && event.target !== dropdownButton) {
                dropdown.classList.add('hidden');
            }
        });
        
        // Date filter functionality
        function resetFilter() {
            document.getElementById('start_date').value = '{{ now()->subWeeks(2)->format('Y-m-d') }}';
            document.getElementById('end_date').value = '{{ now()->format('Y-m-d') }}';
            document.getElementById('filterForm').submit();
        }
        
        // Auto-submit on date change
        document.getElementById('start_date').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
        
        document.getElementById('end_date').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    </script>
</x-app-layout>
