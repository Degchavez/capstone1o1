    <x-app-layout>
        <div class="min-h-screen">
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
                        Animals Management
                    </h1>
                    <p class="mt-2 text-sm text-gray-600">
                        Manage and monitor all animals in the veterinary system
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('animals.add-animal-form') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Add Animal
                        </a>
                        <a href="{{ route('admin-animals') }}" class="inline-flex items-center px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Reset Filters
                        </a>
                    </div>
                </div>

                <!-- Filters Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6">
                    <form method="GET" action="{{ route('admin-animals') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4" id="filterForm">
                        <!-- Search -->
                        <div class="col-span-1 sm:col-span-2 xl:col-span-2">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Search</label>
                            <div class="relative">
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}" 
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                       placeholder="Search animals..." 
                                       id="searchInput">
                                <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Species Filter -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Species</label>
                            <select name="species_id" 
                                    class="w-full border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    id="speciesFilter">
                                <option value="">All Species</option>
                                @foreach($species as $specie)
                                    <option value="{{ $specie->id }}" {{ request('species_id') == $specie->id ? 'selected' : '' }}>{{ $specie->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Breed Filter -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Breed</label>
                            <select name="breed_id" 
                                    class="w-full border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    id="breedFilter">
                                <option value="">All Breeds</option>
                                @foreach($breeds as $breed)
                                    <option value="{{ $breed->id }}" {{ request('breed_id') == $breed->id ? 'selected' : '' }}>{{ $breed->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Owner Filter -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Owner</label>
                            <select name="owner_id" 
                                    class="w-full border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    id="ownerFilter">
                                <option value="">All Owners</option>
                                @foreach($owners as $owner)
                                    <option value="{{ $owner->owner_id }}" {{ request('owner_id') == $owner->owner_id ? 'selected' : '' }}>{{ $owner->user->complete_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date Range -->
                        <div class="col-span-1 sm:col-span-2 xl:col-span-1">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Date Range</label>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="date" 
                                       name="fromDate" 
                                       id="fromDateFilter"
                                       class="block w-full border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                       value="{{ request('fromDate') }}"
                                       placeholder="From date">
                                <input type="date" 
                                       name="toDate" 
                                       id="toDateFilter"
                                       class="block w-full border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                       value="{{ request('toDate') }}"
                                       placeholder="To date">
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Animals Table -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Animal</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owner Info</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Species & Breed</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Latest Veterinarian</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Latest Transaction</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($animals as $animal)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full object-cover" 
                                                         src="{{ asset($animal->photo_front ? 'storage/' . $animal->photo_front : 'assets/default-avatar.png') }}" 
                                                         alt="Animal Photo">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-blue-900">
                                                        <a href="{{ route('animals.profile', ['animal_id' => $animal->animal_id]) }}" 
                                                           class="hover:text-green-600 transition-colors duration-200">
                                                            {{ $animal->name }}
                                                        </a>
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        Created {{ $animal->created_at->format('m/d/Y') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-blue-900">
                                                <a href="{{ route('owners.profile-owner', ['owner_id' => $animal->owner->owner_id]) }}" 
                                                   class="hover:text-green-600 transition-colors duration-200">
                                                    {{ $animal->owner->user->complete_name }}
                                                </a>
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $animal->owner->user->contact_no }}<br>
                                                {{ $animal->owner->user->address && $animal->owner->user->address->barangay ? $animal->owner->user->address->barangay->barangay_name : 'No Barangay Available' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $animal->species->name ?? 'Species not specified' }}</div>
                                            <div class="text-xs text-gray-500">{{ $animal->breed->name ?? 'Breed not specified' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($animal->transactions->isNotEmpty())
                                                @php
                                                    $latestTransaction = $animal->transactions->sortByDesc('created_at')->first();
                                                @endphp
                                                @if($latestTransaction && $latestTransaction->vet)
                                                    <div class="text-sm text-blue-900">
                                                        <a href="{{ route('admin.veterinarian.profile', $latestTransaction->vet->user_id) }}" 
                                                           class="hover:text-green-600 transition-colors duration-200">
                                                            {{ $latestTransaction->vet->complete_name }}
                                                        </a>
                                                    </div>
                                                    <div class="text-xs text-gray-500">{{ $latestTransaction->vet->contact_no }}</div>
                                                    @if($latestTransaction->technician)
                                                        <div class="text-xs text-gray-500 mt-1">
                                                            Tech: {{ $latestTransaction->technician->full_name }}
                                                        </div>
                                                    @endif
                                                @else
                                                    <span class="text-sm text-gray-500">No veterinarian assigned</span>
                                                @endif
                                            @else
                                                <span class="text-sm text-gray-500">No transactions</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($animal->transactions->isNotEmpty())
                                                @php
                                                    $latestTransaction = $animal->transactions->sortByDesc('created_at')->first();
                                                @endphp
                                                <div class="text-sm text-gray-900">
                                                    @if($latestTransaction->transactionSubtype && $latestTransaction->transactionSubtype->id == 8)
                                                        {{ $latestTransaction->transactionSubtype->subtype_name }}
                                                        <div class="text-xs text-gray-500">
                                                            {{ $latestTransaction->vaccine ? $latestTransaction->vaccine->vaccine_name : 'No Vaccine Selected' }}
                                                        </div>
                                                    @else
                                                        {{ $latestTransaction->transactionSubtype ? $latestTransaction->transactionSubtype->subtype_name : 'N/A' }}
                                                    @endif
                                                </div>
                                                <span class="inline-flex mt-1 items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ $latestTransaction->status === 0 ? 'bg-yellow-100 text-yellow-800' : 
                                                       ($latestTransaction->status === 1 ? 'bg-green-100 text-green-800' : 
                                                       'bg-red-100 text-red-800') }}">
                                                    {{ ['Pending', 'Completed', 'Canceled'][$latestTransaction->status] ?? 'Unknown' }}
                                                </span>
                                            @else
                                                <span class="text-sm text-gray-500">No transactions</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $animal->is_vaccinated == 1 ? 'bg-green-100 text-green-800' : 
                                                   ($animal->is_vaccinated == 2 ? 'bg-gray-100 text-gray-800' : 
                                                   'bg-red-100 text-red-800') }}">
                                                {{ $animal->is_vaccinated == 1 ? 'Vaccinated' : 
                                                   ($animal->is_vaccinated == 2 ? 'Not Required' : 'Not Vaccinated') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('admin-animals.edit', ['animal_id' => $animal->animal_id]) }}" 
                                                   class="text-blue-600 hover:text-blue-900 transition-colors duration-200"
                                                   title="Edit Animal">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </a>

                                                <form action="{{ route('animals.delete', ['animal_id' => $animal->animal_id]) }}" 
                                                      method="POST" 
                                                      class="inline-block"
                                                      onsubmit="return confirm('Are you sure you want to delete this animal?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-900 transition-colors duration-200"
                                                            title="Delete Animal">
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
                                        <td colspan="7" class="px-6 py-10 text-center">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                                </svg>
                                                <p class="text-gray-500 text-sm">No animals found</p>
                                                <p class="text-gray-400 text-xs mt-1">Try adjusting your search filters</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="mt-5">
                    {{ $animals->appends(request()->query())->links() }}
                </div>
            </div>
        </div>

        <script>
            // Auto-submit form when filters change
            ['speciesFilter', 'breedFilter', 'ownerFilter', 'fromDateFilter', 'toDateFilter'].forEach(function(id) {
                document.getElementById(id)?.addEventListener('change', function() {
                    this.form.submit();
                });
            });

            // Debounce search input
            let timeout = null;
            document.getElementById('searchInput')?.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    this.form.submit();
                }, 500);
            });
        </script>
    </x-app-layout>
