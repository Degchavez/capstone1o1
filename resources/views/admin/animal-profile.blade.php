<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Main Profile Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Profile Header -->
            <div class="relative h-48 bg-gradient-to-r from-green-600 to-green-800">
                <div class="absolute inset-0 bg-black/20"></div>
                <!-- Action Button -->
                <div class="absolute top-4 right-4">
                    <a href="{{ route('animals.edit', $animal->animal_id) }}" 
                       class="inline-flex items-center px-4 py-2 bg-white/90 backdrop-blur-sm rounded-lg text-sm font-medium text-gray-700 hover:bg-white transition-all">
                        <i class="fas fa-edit mr-2"></i>
                        Update Info
                    </a>
                </div>
            </div>

            <!-- Animal Info Section -->
            <div class="relative px-8 pb-8">
                <!-- Animal Photo -->
                <div class="absolute -top-16 left-8">
                    <div class="relative">
                        <img class="w-32 h-32 rounded-xl object-cover border-4 border-white shadow-lg" 
                             src="{{ $animal->photo_front ? asset('storage/' . $animal->photo_front) : asset('assets/default-avatar.png') }}" 
                             alt="{{ $animal->name }}">
                        <div class="absolute bottom-2 right-2 w-4 h-4 rounded-full 
                            {{ $animal->is_vaccinated == 1 ? 'bg-green-500' : 
                              ($animal->is_vaccinated == 0 ? 'bg-red-500' : 'bg-yellow-500') }} 
                            border-2 border-white">
                        </div>
                    </div>
                </div>

                <!-- Animal Details -->
                <div class="pt-20">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">{{ $animal->name }}</h1>
                            <div class="mt-2 flex items-center space-x-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ $animal->species ? $animal->species->name : 'Species not specified' }}
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                    {{ $animal->breed ? $animal->breed->name : 'Breed not specified' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Info Grid -->
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Owner Info -->
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-blue-100 rounded-lg">
                                    <i class="fas fa-user text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Owner</p>
                                    <a href="{{ route('vet.profile-owner', ['owner_id' => $animal->owner->owner_id]) }}" 
                                       class="text-sm font-medium text-blue-600 hover:text-blue-800">
                                        {{ $animal->owner->user->complete_name }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Age Info -->
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-green-100 rounded-lg">
                                    <i class="fas fa-birthday-cake text-green-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Age</p>
                                    <p class="text-sm font-medium text-gray-900">
                                        @if ($animal->birth_date)
                                            {{ $animal->birth_date->age }} years old
                                        @else
                                            Not Available
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Vaccination Status -->
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-yellow-100 rounded-lg">
                                    <i class="fas fa-syringe text-yellow-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Vaccination Status</p>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $animal->is_vaccinated == 1 ? 'bg-green-100 text-green-800' : 
                                          ($animal->is_vaccinated == 0 ? 'bg-red-100 text-red-800' : 
                                          'bg-yellow-100 text-yellow-800') }}">
                                        @if ($animal->is_vaccinated == 1)
                                            Vaccinated
                                        @elseif ($animal->is_vaccinated == 0)
                                            Not Vaccinated
                                        @else
                                            No Vaccination Required
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Details -->
                    @if($animal->medical_condition)
                        <div class="mt-6 p-4 bg-red-50 rounded-lg border border-red-100">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-exclamation-circle text-red-500"></i>
                                <span class="text-sm font-medium text-red-800">Medical Condition:</span>
                                <span class="text-sm text-red-600">{{ $animal->medical_condition }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Transactions Section -->
        <div class="mt-8 bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-xl font-semibold text-gray-900">Transaction History</h2>
            </div>

            <!-- Filters -->
            <div class="p-6 bg-gray-50 border-b border-gray-100">
                <form id="filterForm" method="GET" action="{{ route('animals.profile', ['animal_id' => $animal->animal_id]) }}" 
                      class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date</label>
                        <input type="date" name="search_date" value="{{ request('search_date') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Transaction Type</label>
                        <select name="transaction_type" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">All Types</option>
                            @foreach($transactionTypes as $type)
                                <option value="{{ $type->id }}" {{ request('transaction_type') == $type->id ? 'selected' : '' }}>
                                    {{ $type->type_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="transaction_status" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">All Status</option>
                            <option value="0" {{ request('transaction_status') == '0' ? 'selected' : '' }}>Pending</option>
                            <option value="1" {{ request('transaction_status') == '1' ? 'selected' : '' }}>Completed</option>
                            <option value="2" {{ request('transaction_status') == '2' ? 'selected' : '' }}>Canceled</option>
                        </select>
                    </div>
                </form>
            </div>

            <!-- Transactions Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtype</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Veterinarian</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($animal->transactions as $transaction)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transaction->created_at->format('Y-m-d') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $transaction->transactionType->type_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $transaction->transactionSubtype->subtype_name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $transaction->vet->complete_name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $transaction->status == 0 ? 'bg-yellow-100 text-yellow-800' : 
                                          ($transaction->status == 1 ? 'bg-green-100 text-green-800' : 
                                          'bg-red-100 text-red-800') }}">
                                        {{ $transaction->status == 0 ? 'Pending' : 
                                           ($transaction->status == 1 ? 'Completed' : 'Canceled') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ Str::limit($transaction->details, 50) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('transactions.edit', $transaction->transaction_id) }}" 
                                           class="text-blue-600 hover:text-blue-900">Edit</a>
                                        <form action="{{ route('transactions.destroy', $transaction->transaction_id) }}" 
                                              method="POST" 
                                              class="inline-block" 
                                              onsubmit="return confirmDelete()">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                    No transactions available.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Transaction Form -->
        <div class="mt-8 bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-xl font-semibold text-gray-900">Add New Transaction</h2>
            </div>
            
            <div class="p-6">
                <form method="POST" action="{{ route('transactions.store', ['animal_id' => $animal->animal_id]) }}" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Transaction Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Transaction</label>
                            <select id="transaction_type_id" name="transaction_type_id" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="" selected disabled>Select Transaction</option>
                                @foreach($transactionTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->type_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Transaction Subtype -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Transaction Type</label>
                            <select id="transaction_subtype_id" name="transaction_subtype_id" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="" selected disabled>Select Type</option>
                            </select>
                        </div>

                        <!-- Veterinarian -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Veterinarian</label>
                            <select id="vet" name="vet_id" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select Veterinarian</option>
                                @foreach($vets as $vet)
                                    <option value="{{ $vet->user_id }}">{{ $vet->complete_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Vaccine Field (Hidden by default) -->
                        <div id="vaccine_field" class="hidden">
                            <label class="block text-sm font-medium text-gray-700">Vaccine</label>
                            <select id="vaccine_id" name="vaccine_id" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select Vaccine (Optional)</option>
                                @foreach($vaccines as $vaccine)
                                    <option value="{{ $vaccine->id }}">{{ $vaccine->vaccine_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Technician -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Veterinary Technician</label>
                            <select id="technician_id" name="technician_id" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select Technician (Optional)</option>
                                @foreach($technicians as $technician)
                                    <option value="{{ $technician->technician_id }}">{{ $technician->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Notes</label>
                        <textarea id="notes" name="notes" rows="4" 
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                  placeholder="Add any additional notes here..."></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-plus mr-2"></i>
                            Add Transaction
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const transactionSubtypes = @json($transactionSubtypes);

        function confirmDelete() {
            return confirm('Are you sure you want to delete this transaction?');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('transaction_type_id');
            const subtypeSelect = document.getElementById('transaction_subtype_id');
            const vaccineField = document.getElementById('vaccine_field');

            typeSelect.addEventListener('change', function() {
                const selectedTypeId = this.value;
                
                // Clear and reset subtype dropdown
                subtypeSelect.innerHTML = '<option value="" selected disabled>Select Type</option>';
                
                if (selectedTypeId) {
                    const filteredSubtypes = transactionSubtypes.filter(
                        subtype => subtype.transaction_type_id == selectedTypeId
                    );
                    
                    filteredSubtypes.forEach(subtype => {
                        const option = document.createElement('option');
                        option.value = subtype.id;
                        option.textContent = subtype.subtype_name;
                        subtypeSelect.appendChild(option);
                    });
                }
            });

            subtypeSelect.addEventListener('change', function() {
                const selectedSubtypeId = this.value;
                vaccineField.classList.toggle('hidden', selectedSubtypeId != 8);
            });

            // Auto-submit filter form on change
            document.querySelectorAll('#filterForm select, #filterForm input').forEach(element => {
                element.addEventListener('change', () => document.getElementById('filterForm').submit());
            });
        });
    </script>
</x-app-layout>
