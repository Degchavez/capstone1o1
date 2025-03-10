<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-[95%] mx-auto py-8">
            <!-- Alert Messages -->
            @if (session()->has('message'))
                <div class="mb-4 flex items-center p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg" role="alert">
                    <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-green-800">{{ session('message') }}</span>
                </div>
            @endif

            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">
                    Veterinarians Management
                </h1>
                <p class="mt-2 text-sm text-gray-600">
                    Manage and monitor all veterinarians in the system
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('veterinarians.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Add Veterinarian
                    </a>
                    <a href="{{ route('admin-veterinarians') }}" class="inline-flex items-center px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Reset Filters
                    </a>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6">
                <form method="GET" action="{{ route('admin-veterinarians') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                    <!-- Search -->
                    <div class="col-span-1 sm:col-span-2 xl:col-span-2">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Search</label>
                        <div class="relative">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                   placeholder="Search veterinarians..." 
                                   id="searchInput">
                            <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Gender Filter -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Gender</label>
                        <select name="gender" 
                                class="w-full border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                id="genderFilter">
                            <option value="" {{ request('gender') == '' ? 'selected' : '' }}>All Genders</option>
                            <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>

                    <!-- Designation Filter -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Designation</label>
                        <select name="designation" 
                                class="w-full border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                id="designationFilter">
                            <option value="">All Designations</option>
                            @foreach ($designations as $designation)
                                <option value="{{ $designation->designation_id }}" {{ request('designation') == $designation->designation_id ? 'selected' : '' }}>
                                    {{ $designation->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date Range -->
                    <div class="col-span-1 sm:col-span-2 xl:col-span-1">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Date Range</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="date" 
                                   name="fromDate" 
                                   class="block w-full border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                   value="{{ request('fromDate') }}"
                                   id="fromDate">
                            <input type="date" 
                                   name="toDate" 
                                   class="block w-full border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                   value="{{ request('toDate') }}"
                                   id="toDate">
                        </div>
                    </div>
                </form>
            </div>

            <!-- Veterinarians Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Profile</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Complete Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Designation</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact #</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transactions</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Created</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($veterinarians as $vet)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full object-cover" 
                                                 src="{{ $vet->profile_image ? asset('storage/' . $vet->profile_image) : asset('assets/default-avatar.png') }}" 
                                                 alt="{{ $vet->complete_name }}">
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            <a href="{{ route('admin.veterinarian.profile', $vet->user_id) }}" class="hover:text-green-600 transition-colors duration-200">
                                                {{ $vet->complete_name }}
                                            </a>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $vet->designation_name ?? 'No designation' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $vet->contact_no }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ ucfirst($vet->gender) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $vet->transaction_count }} transactions</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($vet->created_at)->format('m/d/Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin-veterinarians.edit', $vet->user_id) }}" 
                                               class="text-blue-600 hover:text-blue-900 transition-colors duration-200"
                                               title="Edit Veterinarian">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>

                                            <form action="{{ route('admin-veterinarians.destroy', $vet->user_id) }}" 
                                                  method="POST" 
                                                  class="inline-block"
                                                  onsubmit="return confirm('Are you sure you want to delete this veterinarian?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-900 transition-colors duration-200"
                                                        title="Delete Veterinarian">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-10 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                            <p class="text-gray-500 text-sm">No veterinarians found</p>
                                            <p class="text-gray-400 text-xs mt-1">Try adjusting your search filters</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-5">
                    {{ $veterinarians->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Auto Submitting Forms -->
    <script>
        document.getElementById('genderFilter')?.addEventListener('change', function() {
            this.form.submit();
        });

        document.getElementById('designationFilter')?.addEventListener('change', function() {
            this.form.submit();
        });

        document.getElementById('fromDate')?.addEventListener('change', function() {
            this.form.submit();
        });

        document.getElementById('toDate')?.addEventListener('change', function() {
            this.form.submit();
        });

        document.getElementById('searchInput')?.addEventListener('input', function() {
            this.form.submit();
        });
    </script>
</x-app-layout>
