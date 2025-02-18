<x-app-layout>
    <div class="container mx-auto p-6">
        <!-- Header Section -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                <h1 class="text-2xl font-semibold text-gray-800">Veterinary Receptionist Dashboard</h1>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-600">Welcome, {{ auth()->user()->complete_name }}</span>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <!-- Dashboard Widgets -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Widget: Total Appointments -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-700">Today's Appointments</h2>
                    <p class="text-2xl font-bold text-blue-600 mt-4">{{ $appointments_count }}</p>
                </div>

                <!-- Widget: New Clients -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-700">New Owners</h2>
                    <p class="text-2xl font-bold text-green-500 mt-4">{{ $new_clients_count }}</p>
                </div>

                <!-- Widget: Pets Registered -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-700">Pets Registered</h2>
                    <p class="text-2xl font-bold text-yellow-500 mt-4">{{ $pets_registered_count }}</p>
                </div>
            </div>

            <!-- Quick Links Section -->
            <div class="bg-white shadow rounded-lg p-6 mb-8">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Quick Links</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="{{ route('rec-owners') }}" class="bg-green-500 text-white px-4 py-3 rounded-lg text-center hover:bg-green-600">Owners</a>
                    <a href="{{ route('rec-animals') }}" class="bg-yellow-500 text-white px-4 py-3 rounded-lg text-center hover:bg-yellow-600">Animals</a>
                </div>
            </div>

          <!-- Recent Activities Section -->
          <div class="bg-white shadow-lg rounded-xl p-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Recent Activities</h2>
        
            <!-- Filter Form -->
            <form method="GET" action="{{ route('receptionist-dashboard') }}" class="mb-6" id="filterForm">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Date Range Filter -->
                    <div class="flex flex-col sm:flex-row sm:items-center">
                        <label for="start_date" class="text-gray-700 font-medium mr-2 mb-2 sm:mb-0">From</label>
                        <input 
                            type="date" 
                            name="start_date" 
                            id="start_date" 
                            class="border border-gray-300 rounded-lg px-4 py-2 shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" 
                            value="{{ request('start_date', now()->subWeeks(2)->format('Y-m-d')) }}"
                        >
                        <label for="end_date" class="text-gray-700 font-medium mr-2 mb-2 sm:mb-0">To</label>
                        <input 
                            type="date" 
                            name="end_date" 
                            id="end_date" 
                            class="border border-gray-300 rounded-lg px-4 py-2 shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" 
                            value="{{ request('end_date', now()->format('Y-m-d')) }}"
                        >
                    </div>
                    <p class="text-gray-600 text-sm mt-2">Select the date range for which you want to view activities.</p>
                </div>
                <div class="mt-4 flex justify-end">
                    <button 
                        type="button" 
                        onclick="resetFilter()" 
                        class="bg-gray-300 text-gray-700 hover:bg-gray-400 px-6 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 transition duration-200 ease-in-out">
                        Reset Filter
                    </button>
                </div>
            </form>
        
            <!-- Scrollable Container for Activities -->
            <div class="overflow-y-auto max-h-72 border-t border-gray-200">
                <ul class="divide-y divide-gray-200">
                    @forelse ($recent_activities as $activity)
                        <li class="py-4 flex justify-between items-center hover:bg-gray-50 transition duration-150 ease-in-out">
                            <div class="flex-1">
                                <!-- Dynamic Activity Description -->
                                <span class="text-gray-800 font-semibold">{!! $activity['description'] !!}</span>
        
                                <div class="text-sm mt-1">
                                    <!-- Owner Link -->
                                    @if(isset($activity['owner']))
                                        <a 
                                            href="{{ route('owner.profile', ['id' => $activity['owner']['id']]) }}" 
                                            class="text-blue-600 font-medium hover:underline">
                                            Owner: {{ $activity['owner']['name'] }}
                                        </a>
                                    @endif
        
                                    <!-- Transaction Info -->
                                    @if(isset($activity['transaction']))
                                        <span class="text-orange-600 font-medium ml-4">Transaction: #{{ $activity['transaction']['transaction_id'] }}</span>
                                    @endif
        
                                    <!-- Animal Link -->
                                    @if(isset($activity['animal']))
                                        <a 
                                            href="{{ route('animal.profile', ['id' => $activity['animal']['id']]) }}" 
                                            class="text-teal-600 font-medium ml-4 hover:underline">
                                            Animal: {{ $activity['animal']['name'] }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <!-- Activity Timestamp -->
                            <time class="text-sm text-gray-400" datetime="{{ $activity['created_at'] }}">
                                {{ $activity['created_at']->diffForHumans() }}
                            </time>
                        </li>
                    @empty
                        <!-- Empty State -->
                        <li class="py-4 text-gray-600">No recent activities found.</li>
                    @endforelse
                </ul>
            </div>
        </div>
        
        
            
        </main>

        <!-- Footer Section -->
        <footer class="bg-white shadow mt-8">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 text-center text-gray-500">
                © {{ now()->year }} Veterinary Clinic. All rights reserved.
            </div>
        </footer>
    </div>
    <script>
        // Automatically submit the form when the date inputs change
        document.getElementById('start_date').addEventListener('change', function () {
            document.getElementById('filterForm').submit();
        });
        document.getElementById('end_date').addEventListener('change', function () {
            document.getElementById('filterForm').submit();
        });

        function resetFilter() {
        document.getElementById('start_date').value = '{{ now()->subWeeks(2)->format('Y-m-d') }}';
        document.getElementById('end_date').value = '{{ now()->format('Y-m-d') }}';
        document.getElementById('filterForm').submit();
    }
    </script>
</x-app-layout>
