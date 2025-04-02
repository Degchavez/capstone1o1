<x-app-layout>
    <div class="min-h-screen bg-gray-50/30 p-4 lg:p-8">
        <!-- Main Container -->
        <div class="mx-auto max-w-[96rem] space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 bg-white p-6 rounded-xl shadow-sm">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Animals</h1>
                    <p class="text-sm text-gray-500">Manage and monitor all registered animals</p>
                </div>
                <a href="{{ route('rec.add-animal-form') }}" 
                   class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"/>
                    </svg>
                    Add Animal
                </a>
            </div>

            <!-- Filters Section -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-900">Filters</h2>
                    <a href="{{ route('rec-animals') }}" 
                       class="inline-flex items-center px-3 py-1.5 bg-gray-50 text-gray-600 text-sm font-medium rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors duration-150 ease-in-out">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset Filters
                    </a>
                </div>
                <form class="p-4" method="GET" action="{{ route('rec-animals') }}" id="filterForm">
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                        <!-- Search -->
                        <div class="relative">
                            <input type="text" name="search" 
                                   class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                   placeholder="Search animals..."
                                   value="{{ request('search') }}"
                                   oninput="document.getElementById('filterForm').submit()">
                            <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>

                        <!-- Species -->
                        <div>
                            <select name="species_id" 
                                    class="w-full border border-gray-200 rounded-lg text-sm focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                    onchange="document.getElementById('filterForm').submit()">
                                <option value="">All Species</option>
                                @foreach($species as $specie)
                                    <option value="{{ $specie->id }}" {{ request('species_id') == $specie->id ? 'selected' : '' }}>
                                        {{ $specie->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Breed -->
                        <div>
                            <select name="breed_id" 
                                    class="w-full border border-gray-200 rounded-lg text-sm focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                    onchange="document.getElementById('filterForm').submit()">
                                <option value="">All Breeds</option>
                                @foreach($breeds as $breed)
                                    <option value="{{ $breed->id }}" {{ request('breed_id') == $breed->id ? 'selected' : '' }}>
                                        {{ $breed->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Owner -->
                        <div>
                            <select name="owner_id" 
                                    class="w-full border border-gray-200 rounded-lg text-sm focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                    onchange="document.getElementById('filterForm').submit()">
                                <option value="">All Owners</option>
                                @foreach($owners as $owner)
                                    <option value="{{ $owner->owner_id }}" {{ request('owner_id') == $owner->owner_id ? 'selected' : '' }}>
                                        {{ $owner->user->complete_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date Range -->
                        <div>
                            <div class="flex space-x-2">
                                <input type="date" name="fromDate" 
                                       class="w-full border border-gray-200 rounded-lg text-sm focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                       value="{{ request('fromDate') }}"
                                       onchange="document.getElementById('filterForm').submit()">
                                <input type="date" name="toDate" 
                                       class="w-full border border-gray-200 rounded-lg text-sm focus:border-geen-500 focus:ring-1 focus:ring-green-500"
                                       value="{{ request('toDate') }}"
                                       onchange="document.getElementById('filterForm').submit()">
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Table Section -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Animal Details
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Owner Information
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Medical History
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($animals as $animal)
                                <tr class="hover:bg-gray-50 transition-colors duration-150 ease-in-out">
                                    <!-- Animal Details -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-4">
                                            <img class="h-10 w-10 rounded-full object-cover" 
                                                 src="{{ asset($animal->photo_front ? 'storage/' . $animal->photo_front : 'assets/default-avatar.png') }}" 
                                                 alt="">
                                            <div>
                                                <a href="{{ route('rec.profile', ['animal_id' => $animal->animal_id]) }}" 
                                                   class="font-medium text-green-600 hover:text-green-900 hover:underline">{{ $animal->name }}</a>
                                                <div class="text-sm text-gray-500">{{ $animal->species->name ?? 'N/A' }} • {{ $animal->breed->name ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Owner Information -->
                                    <td class="px-6 py-4">
                                        <div class="text-sm">
                                            <a href="{{ route('rec.profile-owner', $owner->owner_id) }}" 
                                               class="font-medium text-indigo-600 hover:text-indigo-900 hover:underline">{{ $animal->owner->user->complete_name }}</a>
                                            <div class="text-gray-500">{{ $animal->owner->user->contact_no }}</div>
                                        </div>
                                    </td>

                                    <!-- Medical History -->
                                    <td class="px-6 py-4">
                                        <div class="text-sm">
                                            @if($animal->transactions->isNotEmpty())
                                                @php $latestTransaction = $animal->transactions->sortByDesc('created_at')->first(); @endphp
                                                <div class="font-medium text-gray-900">
                                                    {{ $latestTransaction->transactionSubtype->subtype_name ?? 'N/A' }}
                                                </div>
                                                <div class="text-gray-500">
                                                    @if($latestTransaction->vet)
                                                        <a href="{{ route('rec.veterinarian.profile', $latestTransaction->vet->user_id) }}" 
                                                           class="font-medium text-indigo-600 hover:text-indigo-900 hover:underline">
                                                            Dr. {{ $latestTransaction->vet->complete_name }}
                                                        </a>
                                                    @else
                                                        <span class="text-gray-500">No veterinarian assigned</span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-gray-500">No medical records</span>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Status/Vaccination Column -->
                                    <td class="px-6 py-4">
                                        <div class="space-y-2">
                                            <!-- Vaccination Status Badge -->
                                            @if($animal->is_vaccinated == 1)
                                                <div class="flex items-center">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                        </svg>
                                                        Vaccinated
                                                    </span>
                                                </div>
                                                <!-- Latest Transaction Info -->
                                                @if($animal->transactions->isNotEmpty())
                                                    @php
                                                        $latestVaccinationTransaction = $animal->transactions
                                                            ->where('transaction_subtype_id', 8) // Assuming 8 is vaccination transaction type
                                                            ->sortByDesc('created_at')
                                                            ->first();
                                                    @endphp
                                                    @if($latestVaccinationTransaction)
                                                        <div class="text-xs text-gray-500">
                                                            <div class="flex flex-col">
                                                                <span>Last Updated: {{ $latestVaccinationTransaction->created_at->format('M d, Y') }}</span>
                                                                @if($latestVaccinationTransaction->next_visit_date)
                                                                    <span>Next Due: {{ $latestVaccinationTransaction->next_visit_date->format('M d, Y') }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            @elseif($animal->is_vaccinated == 2)
                                                <div class="flex items-center">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                        </svg>
                                                        Not Required
                                                    </span>
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    <span>Vaccination not needed</span>
                                                </div>
                                            @else
                                                <div class="flex items-center">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                        </svg>
                                                        Not Vaccinated
                                                    </span>
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    <span>No vaccination records</span>
                                                </div>
                                            @endif

                                            <!-- Vaccination Alert -->
                                            @if($animal->transactions->isNotEmpty())
                                                @php
                                                    $latestVaccinationTransaction = $animal->transactions
                                                        ->where('transaction_subtype_id', 8)
                                                        ->sortByDesc('created_at')
                                                        ->first();
                                                @endphp
                                                @if($latestVaccinationTransaction && $latestVaccinationTransaction->next_visit_date)
                                                    @if($latestVaccinationTransaction->next_visit_date->isPast())
                                                        <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                            </svg>
                                                            Vaccination Overdue
                                                        </div>
                                                    @elseif($latestVaccinationTransaction->next_visit_date->diffInDays(now()) <= 30)
                                                        <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                            </svg>
                                                            Due Soon
                                                        </div>
                                                    @endif
                                                @endif
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Actions -->
                                    <td class="px-6 py-4 text-right text-sm font-medium">
                                        <a href="{{ route('rec-animals.edit', ['animal_id' => $animal->animal_id]) }}" 
                                           class="text-gray-600 hover:text-gray-900">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $animals->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
