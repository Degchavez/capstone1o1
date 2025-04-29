<x-app-layout>
 
    <div class="container mx-auto p-8 bg-gray-50 shadow-lg rounded-xl">
        <a href="{{ route('rec.profile-owner', ['owner_id' => $owner_id]) }}" class="bg-yellow-500 text-white p-2 rounded-md hover:bg-yellow-600 transition-colors duration-300">
            Back to Profile
        </a>
        <div class="bg-white p-6 rounded-lg shadow-xl">
            <!-- Page Header -->
            <h1 class="text-3xl font-semibold text-darkgreen text-center mb-6">All Transactions for {{ $owner->user->complete_name }}</h1>

            <!-- Search and Filters -->
            <div class="mb-6">
                <form method="GET" action="{{ route('owner.Newtransactions', ['owner_id' => $owner->owner_id]) }}" class="space-x-4" id="filterForm">
                    <!-- Search Input -->
                    <input type="text" name="search" value="{{ request()->get('search') }}" placeholder="Search..." 
                           class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                           onchange="this.form.submit()">

                    <!-- Transaction Type Filter -->
                    <select name="transaction_type" class="px-4 py-2 border border-gray-300 rounded-lg" onchange="this.form.submit()">
                        <option value="">All Transaction Types</option>
                        @foreach($transactionTypes as $type)
                            <option value="{{ $type->type_name }}" {{ request()->get('transaction_type') == $type->type_name ? 'selected' : '' }}>
                                {{ $type->type_name }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Transaction Subtype Filter -->
                    <select name="transaction_subtype" class="px-4 py-2 border border-gray-300 rounded-lg" onchange="this.form.submit()">
                        <option value="">All Transaction Subtypes</option>
                        @foreach($transactionSubtypes as $subtype)
                            <option value="{{ $subtype->subtype_name }}" {{ request()->get('transaction_subtype') == $subtype->subtype_name ? 'selected' : '' }}>
                                {{ $subtype->subtype_name }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Status Filter -->
                    <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="zero" {{ request()->get('status') == 'zero' ? 'selected' : '' }}>Pending</option>
                        <option value="1" {{ request()->get('status') == '1' ? 'selected' : '' }}>Completed</option>
                        <option value="2" {{ request()->get('status') == '2' ? 'selected' : '' }}>Canceled</option>
                    </select>

                    <!-- Reset Filters Button -->
                    <button type="button" onclick="resetFilters()" class="px-4 py-2 bg-green-500 text-white rounded-lg">Reset Filters</button>
                </form>
            </div>

            <!-- Transactions Table -->
            <div class="space-y-6">
                @if($transactions->isNotEmpty())
                    <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
                        <thead class="bg-green-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Transaction Type</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Owner</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Assigned Veterinarian</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Assigned Technician</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Animal</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Breed</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Specie</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Date</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>

                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $transaction)
                                <tr class="hover:bg-gray-100 transition-all">
                                    <td class="px-6 py-3 text-sm text-gray-800">
                                        @if ($transaction->transactionSubtype && $transaction->transactionSubtype->subtype_name == 'Vaccination')
                                            {{ $transaction->transactionSubtype->subtype_name }} 
                                            - {{ $transaction->vaccine_name ?? 'No Vaccine Selected' }}
                                        @else
                                            {{ $transaction->transactionSubtype ? $transaction->transactionSubtype->subtype_name : 'N/A' }}
                                        @endif
                                    </td>
                                                                        <td class="px-6 py-3 text-sm text-gray-800">
                                        {{ $owner->user ? $owner->user->complete_name : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-3 text-sm text-gray-800">
                                        {{ $transaction->vet ? $transaction->vet->complete_name : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-3 text-sm text-gray-800">
                                        {{ $transaction->technician_name ? $transaction->technician_name : 'N/A' }} <!-- Add technician name here -->
                                    </td>
                                    <td class="px-6 py-3 text-sm text-gray-800">{{ $transaction->animal ? $transaction->animal->name : 'N/A' }}</td>
                                    <td class="px-6 py-3 text-sm text-gray-800">{{ $transaction->animal ? $transaction->animal->breed->name : 'N/A' }}</td>
                                    <td class="px-6 py-3 text-sm text-gray-800">{{ $transaction->animal ? $transaction->animal->species->name : 'N/A' }}</td>
                                    <td class="px-6 py-3 text-sm text-gray-600">{{ $transaction->created_at->format('F d, Y') }}</td>
                                    <td class="px-6 py-3 text-sm">
                                        <span class="font-medium 
                                            @if($transaction->status == 0) text-yellow-500 
                                            @elseif($transaction->status == 1) text-green-500 
                                            @elseif($transaction->status == 2) text-red-500 
                                            @else text-gray-500 @endif">
                                            @if($transaction->status == 0) Pending 
                                            @elseif($transaction->status == 1) Completed 
                                            @elseif($transaction->status == 2) Canceled 
                                            @else Unknown @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-3 text-sm">
                                        <div class="mt-4 flex justify-end space-x-2">
                                            @if ($transaction->status == 1)
                                                <!-- If status is 'Completed', show a button to view transaction details -->
                                                <button type="button"
                                                        onclick="openTransactionModal('{{ $transaction->transaction_id }}')"
                                                        class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white font-semibold rounded-lg shadow-md transition-all duration-200 ease-in-out hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
                                                    <span class="text-sm">See Transaction Details</span>
                                                </button>
                                            @else
                                                <!-- If not completed, show Edit and Delete buttons -->
                                                <a href="{{ route('rec.editTransactionForm', ['transaction_id' => $transaction->transaction_id]) }}" 
                                                    class="text-blue-500 hover:text-blue-700 px-4 py-2 border border-blue-500 rounded-md">
                                                    Update
                                                </a>
                                                <!-- Delete Button -->
                                             
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination Controls -->
                    <div class="mt-6">
                        {{ $transactions->links() }}
                    </div>
                @else
                    <p class="text-sm text-gray-600 text-center mt-4">No transactions available.</p>
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
        // Reset Filters function
    
    // Reset Filters function
    function resetFilters() {
        // Redirect to the base URL without query parameters
        window.location.href = "{{ route('rec.transactions', ['owner_id' => $owner->owner_id]) }}";
    }
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

    </script>
</x-app-layout>
