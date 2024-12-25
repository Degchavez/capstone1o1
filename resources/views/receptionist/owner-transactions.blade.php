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
                <form method="GET" action="{{ route('rec.transactions', ['owner_id' => $owner->owner_id]) }}" class="space-x-4" id="filterForm">
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
                        <option value="">All Statuses</option>
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
                                        <div class="flex space-x-2">
                                            <a href="{{ route('rec.editTransactionForm', ['transaction_id' => $transaction->transaction_id]) }}" 
                                                class="text-blue-500 hover:text-blue-700 px-4 py-2 border border-blue-500 rounded-md">
                                                Update
                                            </a>
                                            <!-- Delete Button -->
                                           
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

    <script>
        // Reset Filters function
    
    // Reset Filters function
    function resetFilters() {
        // Redirect to the base URL without query parameters
        window.location.href = "{{ route('rec.transactions', ['owner_id' => $owner->owner_id]) }}";
    }
</script>

    </script>
</x-app-layout>
