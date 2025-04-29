<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Success Message -->
        @if (session()->has('message'))
            <div class="mb-6 rounded-lg bg-green-50 p-4 text-sm text-green-800 flex items-center justify-between" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2 text-green-500"></i>
                    <span>{{ session('message') }}</span>
                </div>
            </div>
        @endif

        <!-- Main Profile Section -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Profile Header with Banner -->
            <div class="relative h-48 bg-gradient-to-r from-green-600 to-green-800">
                <div class="absolute inset-0 bg-black/20"></div>
                <!-- Action Buttons -->
                <div class="absolute top-4 right-4 flex space-x-3">
                    <a href="{{ route('owner.owner-edit-form', ['id' => $owner->user_id]) }}" 
                       class="inline-flex items-center px-4 py-2 bg-yellow-500/90 backdrop-blur-sm rounded-lg text-sm font-medium text-white hover:bg-yellow-500 transition-all">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Profile
                    </a>
                </div>
            </div>

            <!-- Profile Info -->
            <div class="relative px-6 pb-6">
                <!-- Profile Image -->
                <div class="absolute -top-16 left-6">
                    <div class="relative">
                        <img class="w-32 h-32 rounded-xl object-cover border-4 border-white shadow-lg" 
                             src="{{ $owner->profile_image ? Storage::url($owner->profile_image) : asset('assets/default-avatar.png') }}" 
                             alt="{{ $owner->complete_name }}">
                        <div class="absolute bottom-2 right-2 w-4 h-4 rounded-full bg-green-500 border-2 border-white"></div>
                    </div>
                </div>

                <!-- Profile Details -->
                <div class="pt-20">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $owner->complete_name }}</h1>
                            <p class="text-sm text-gray-500 flex items-center mt-1">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                {{ $owner->street }}, {{ $owner->barangay_name }}
                            </p>
                            <p class="text-sm text-gray-500 flex items-center mt-1">
                                <i class="fas fa-clock mr-2"></i>
                                Member since {{ $owner->created_at->format('F d, Y') }}
                            </p>
                        </div>
                    </div>

                    <!-- Contact Grid -->
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-blue-100 rounded-lg">
                                    <i class="fas fa-envelope text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Email</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $owner->email }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-green-100 rounded-lg">
                                    <i class="fas fa-phone text-green-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Contact</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $owner->contact_no }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-purple-100 rounded-lg">
                                    <i class="fas fa-user text-purple-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Personal Info</p>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $owner->gender }} • {{ \Carbon\Carbon::parse($owner->birth_date)->age }} yrs
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Animals and Transactions Sections -->
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Animals Section -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-semibold text-gray-900">Registered Animals</h2>
                        <a href="{{ route('owner.addAnimalForm', ['owner_id' => $owner->owner_id]) }}"
                           class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-all text-sm font-medium">
                            <i class="fas fa-plus mr-2"></i>
                            Add Animal
                        </a>
                    </div>

                    <!-- Search and Filters -->
                    <form method="GET" action="{{ route('owners.profile', ['owner_id' => $owner->owner_id]) }}" 
                          class="mt-4 space-y-4" id="filterForm">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Search animals..."
                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            
                            <select name="species_id" 
                                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    onchange="submitForm()">
                                <option value="">All Species</option>
                                @foreach($species as $specie)
                                    <option value="{{ $specie->id }}" {{ request('species_id') == $specie->id ? 'selected' : '' }}>
                                        {{ $specie->name }}
                                    </option>
                                @endforeach
                            </select>

                            <select name="breed_id"
                                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    onchange="submitForm()">
                                <option value="">All Breeds</option>
                                @foreach($breeds as $breed)
                                    <option value="{{ $breed->id }}" {{ request('breed_id') == $breed->id ? 'selected' : '' }}>
                                        {{ $breed->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>

                <!-- Animals List -->
                @if($animals->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Animal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($animals as $animal)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <img class="h-10 w-10 rounded-full object-cover" 
                                                     src="{{ $animal->photo_front ? asset('storage/' . $animal->photo_front) : asset('assets/default-avatar.png') }}"
                                                     alt="{{ $animal->name }}">
                                                <div class="ml-4">
                                                    <a href="{{ route('newanimals.profile', ['animal_id' => $animal->animal_id]) }}"
                                                       class="text-sm font-medium text-blue-600 hover:text-blue-900">
                                                        {{ $animal->name }}
                                                        @if ($animal->is_group)
                                                            <span class="text-gray-500">({{ $animal->group_count }})</span>
                                                        @endif
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $animal->species ? $animal->species->name : 'N/A' }}</div>
                                            <div class="text-sm text-gray-500">{{ $animal->breed ? $animal->breed->name : 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $animal->is_vaccinated == 1 ? 'bg-green-100 text-green-800' : 
                                                   ($animal->is_vaccinated == 2 ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800') }}">
                                                @if($animal->is_vaccinated == 1)
                                                    Vaccinated
                                                @elseif($animal->is_vaccinated == 2)
                                                    No Vaccination Required
                                                @else
                                                    Not Vaccinated
                                                @endif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('owner.editAnimal', ['owner_id' => $owner->owner_id, 'animal_id' => $animal->animal_id]) }}"
                                                   class="text-blue-600 hover:text-blue-900">Edit</a>
                                              
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $animals->links() }}
                    </div>
                @else
                    <div class="p-6 text-center text-gray-500">
                        No animals registered yet.
                    </div>
                @endif
            </div>

            <!-- Transactions Section -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">


                @if($owner->transactions->isNotEmpty())
                    <div class="divide-y divide-gray-200">
                        @foreach($owner->transactions->take(4) as $transaction)
                            <div class="p-6 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <img class="h-12 w-12 rounded-lg object-cover" 
                                                 src="{{ $transaction->animal && $transaction->animal->photo_front ? asset('storage/' . $transaction->animal->photo_front) : asset('assets/default-avatar.png') }}"
                                                 alt="Animal Photo">
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-900">
                                                {{ $transaction->transactionSubtype ? $transaction->transactionSubtype->subtype_name : 'N/A' }}
                                                @if ($transaction->transactionSubtype && $transaction->transactionSubtype->id == 8)
                                                    - {{ $transaction->vaccine ? $transaction->vaccine->vaccine_name : 'No Vaccine Selected' }}
                                                @endif
                                            </h3>
                                            <p class="text-sm text-gray-500">{{ $transaction->animal ? $transaction->animal->name : 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $transaction->status == 0 ? 'bg-yellow-100 text-yellow-800' : 
                                               ($transaction->status == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                            {{ $transaction->status == 0 ? 'Pending' : 
                                               ($transaction->status == 1 ? 'Completed' : 'Canceled') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-2 text-sm text-gray-500">
                                    <p><span class="font-medium">Details:</span> {{ $transaction->details ?? 'N/A' }}</p>
                                    <p class="mt-1">
                                        <span class="font-medium">Veterinarian:</span> {{ $transaction->vet ? $transaction->vet->complete_name : 'N/A' }}
                                    </p>
                                </div>
                                <div class="mt-4 flex justify-end space-x-2">
                                    @if ($transaction->status == 1)
                                        <!-- If status is 'Completed', show a button to view transaction details -->
                                        <button type="button"
                                                onclick="openTransactionModal('{{ $transaction->transaction_id }}')"
                                                class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white font-semibold rounded-lg shadow-md transition-all duration-200 ease-in-out hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
                                            <span class="text-sm">See Transaction Details</span>
                                        </button>
                                    @else
                                   <!-- If status is 'Completed', show a button to view transaction details -->
                                   <button type="button"
                                   onclick="openTransactionModal('{{ $transaction->transaction_id }}')"
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white font-semibold rounded-lg shadow-md transition-all duration-200 ease-in-out hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
                               <span class="text-sm">See Transaction Details</span>
                           </button>
                                       
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="p-6 border-t border-gray-200 text-center">
                        <a href="{{ route('owner.Newtransactions', ['owner_id' => $owner->owner_id]) }}"
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                            View All Transactions
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                @else
                    <div class="p-6 text-center text-gray-500">
                        No transactions available.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Transaction Modal -->
    <div id="transactionModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full">
                <div class="absolute top-0 right-0 pt-4 pr-4">
                    <button type="button" onclick="closeTransactionModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <!-- Modal content will be loaded here -->
                <div id="transactionModalContent" class="p-6">
                    <div class="flex justify-center">
                        <svg class="animate-spin -ml-1 mr-3 h-10 w-10 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="text-lg font-medium text-gray-700">Loading transaction details...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

   <script>
        function submitForm() {
            document.getElementById('filterForm').submit();
        }
        
        function openTransactionModal(transactionId) {
            // Show modal
            document.getElementById('transactionModal').classList.remove('hidden');
            
            // Fetch transaction details via AJAX
            fetch(`/admin/transactions/${transactionId}/details-partial`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('transactionModalContent').innerHTML = html;
                })
                .catch(error => {
                    document.getElementById('transactionModalContent').innerHTML = `
                        <div class="text-center text-red-500">
                            <p>Error loading transaction details. Please try again.</p>
                        </div>
                    `;
                    console.error('Error fetching transaction details:', error);
                });
        }
        
        function closeTransactionModal() {
            document.getElementById('transactionModal').classList.add('hidden');
            // Reset content to loading state for next time
            document.getElementById('transactionModalContent').innerHTML = `
                <div class="flex justify-center">
                    <svg class="animate-spin -ml-1 mr-3 h-10 w-10 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-lg font-medium text-gray-700">Loading transaction details...</p>
                </div> 
            `;
        }
    </script>
</x-app-layout>
