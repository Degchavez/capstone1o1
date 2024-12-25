<x-app-layout>
    <div class="container mx-auto px-4 py-6 bg-gray-50 rounded-lg shadow-lg">
        <!-- Profile Section -->
        <div class="flex items-center space-x-6 mb-8 bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition duration-300 ease-in-out">
            <img class="w-36 h-36 object-cover rounded-full border-4 border-green-500 shadow-lg hover:scale-105 transition-transform duration-300" 
            src="{{ $veterinarian->profile_image ? Storage::url($veterinarian->profile_image) : asset('assets/default-avatar.png') }}" 
            alt="Profile Image">            
            <div>
                <h2 class="text-2xl font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-user-md text-blue-500 mr-2"></i>{{ $veterinarian->complete_name }}'s Profile
                </h2>
                <p class="text-lg text-gray-600"><strong>Designation:</strong> {{ $veterinarian->designation->name }}</p>
                <p class="text-sm text-gray-500"><i class="fas fa-phone-alt text-green-500"></i> Contact: {{ $veterinarian->contact_no ?? 'N/A' }}</p>
                <p class="text-sm text-gray-500 mt-2"><strong>Transactions Handled:</strong> {{ $transactionCount }}</p>
        
                <!-- Edit Profile Button -->
                <a href="{{ route('vets.edit', $veterinarian->user_id) }}" class="mt-4 inline-block px-6 py-2 text-white bg-blue-500 hover:bg-blue-600 rounded-lg shadow-md text-sm">
                    Edit Profile
                </a>
            </div>
        </div>
        

        <hr class="my-6 border-t-2 border-gray-200">

        <!-- Transactions Section -->
        <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-clipboard-list text-blue-500 mr-2"></i>Transactions
        </h2>

     <!-- Search and Filters Section -->

        <div class="container mx-auto px-4 py-6 bg-gray-50 rounded-lg shadow-lg">
            <!-- Search and Filters Section -->
            <div class="flex items-center justify-between mb-6 bg-white p-4 rounded-lg shadow-md">
                <form action="{{ route('admin.veterinarian.profile', $veterinarian->user_id) }}" method="GET" id="filtersForm" class="flex items-center space-x-4">
                    <!-- Search Input -->
                    <div class="flex items-center">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}" 
                            placeholder="Search by Animal/Owner Name" 
                            class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                            id="searchInput" />
                    </div>
    
                    <!-- Status Filter -->
                    <div class="flex items-center">
                        <select 
                            name="status" 
                            class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                            onchange="document.getElementById('filtersForm').submit()">
                            <option value="">All Statuses</option>
                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Pending</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Completed</option>
                            <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
    
                    <!-- Transaction Type Filter -->
                    <div class="flex items-center">
                        <select 
                            name="transaction_type" 
                            class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                            onchange="document.getElementById('filtersForm').submit()">
                            <option value="">All Transaction Types</option>
                            @foreach ($transactionTypes as $type)
                                <option value="{{ $type->id }}" {{ request('transaction_type') == $type->id ? 'selected' : '' }}>
                                    {{ $type->type_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
    
                    <!-- Transaction Subtype Filter -->
                    <div class="flex items-center">
                        <select 
                            name="transaction_subtype" 
                            class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                            onchange="document.getElementById('filtersForm').submit()">
                            <option value="">All Subtypes</option>
                            @foreach ($transactionSubtypes as $subtype)
                                <option value="{{ $subtype->id }}" {{ request('transaction_subtype') == $subtype->id ? 'selected' : '' }}>
                                    {{ $subtype->subtype_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
    
                    <!-- Date Filter -->
                    <div class="flex items-center">
                        <input 
                            type="date" 
                            name="date" 
                            value="{{ request('date') }}" 
                            class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                            onchange="document.getElementById('filtersForm').submit()" />
                    </div>
                                        <!-- Transaction Counter -->

    
                    <!-- Reset Button -->
                    <div class="flex items-center">
                        <a href="{{ route('admin.veterinarian.profile', $veterinarian->user_id) }}" class="px-6 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition duration-200">
                            Reset
                        </a>
                    </div>
                </form>
            </div>
    
            <!-- Transactions Table -->
            <div class="overflow-x-auto bg-white p-4 rounded-lg shadow-md">
                <table class="min-w-full table-auto">
                    <thead>
                        <tr class="text-sm font-semibold text-gray-700 bg-gray-100 border-b">
                            <th class="px-4 py-2 text-left">Animal Owner Name</th>
                            <th class="px-4 py-2 text-left">Animal Name</th>
                            <th class="px-4 py-2 text-left">Transaction</th>
                            <th class="px-4 py-2 text-left">Technician</th>
                            <th class="px-4 py-2 text-left">Animal Species</th>
                            <th class="px-4 py-2 text-left">Animal Breed</th>
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2 text-left">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $transaction)
                        @php
                            $animal = $transaction->animal; // Assuming the 'animal' relationship is defined in the Transaction model
                        @endphp
                    
                        @if ($animal)
                            <tr class="text-sm border-b hover:bg-blue-50 transition duration-300">
                                <td class="px-6 py-3 text-start">
                                    <a href="{{ route('owners.profile-owner', ['owner_id' => $animal->owner->owner_id]) }}" class="text-blue-500 hover:text-blue-700 font-bold">
                                        {{ $animal->owner->user->complete_name ?? 'Unknown Owner' }}
                                    </a><br>
                                    
                                </td>
                                <td class="px-6 py-3 text-start">

                                <a href="{{ route('animals.profile', ['animal_id' => $animal->animal_id]) }}" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">
                                    <strong>{{ $animal->name ?? 'Unknown Animal' }}</strong>
                                </a>
                            </td>

                                <td class="px-4 py-2 text-gray-700">
                                    {{ $transaction->transactionType->type_name ?? 'Unknown' }}
                                    @if($transaction->transactionSubtype)
                                        - {{ $transaction->transactionSubtype->subtype_name ?? 'Unknown' }}
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-gray-700">{{ $transaction->technician->full_name ?? 'Unknown Technician' }}</td>
                                <td class="px-4 py-2 text-gray-700">{{ $transaction->animal->species->name ?? 'Unknown Species' }}</td>
                                <td class="px-4 py-2 text-gray-700">{{ $transaction->animal->breed->name ?? 'Unknown Breed' }}</td>
                                <td class="px-4 py-2 text-gray-700">
                                    <form action="{{ route('updateStatus', $transaction->transaction_id) }}" method="POST" class="flex items-center" id="statusForm-{{ $transaction->transaction_id }}">
                                        @csrf
                                        @method('PUT')
                                        <select 
                                            name="status" 
                                            class="px-8 py-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none text-gray-700 font-medium transition-all duration-200 ease-in-out" 
                                            onchange="confirmStatusChange(event, {{ $transaction->transaction_id }})">
                                            @if ($transaction->status == 0)
                                                <option value="0" {{ $transaction->status == 0 ? 'selected' : '' }} disabled>
                                                    Pending
                                                </option>
                                            @endif
                                            <option value="1" {{ $transaction->status == 1 ? 'selected' : '' }}>Completed</option>
                                            <option value="2" {{ $transaction->status == 2 ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="px-4 py-2 text-gray-700">{{ $transaction->created_at->format('F j, Y') }}</td>
                            </tr>
                        @else
                           
                        @endif
                    @endforeach
                    
                    </tbody>
                </table>
            </div>
    
            <!-- Pagination Links -->
            <div class="mt-4">
                {{ $transactions->links() }}
            </div>
        </div>

    

    <hr class="my-6 border-t-2 border-gray-200">
</div>

<!-- Add Font Awesome CDN -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
<script>
    function confirmStatusChange(event, transactionId) {
        const selectedOption = event.target.value;

        let message = '';
        if (selectedOption == 1) {
            message = "Are you sure you want to mark this transaction as Completed?";
        } else if (selectedOption == 2) {
            message = "Are you sure you want to cancel this transaction?";
        }

        if (message) {
            if (!confirm(message)) {
                // If the user cancels, reset the dropdown to its previous value
                event.target.selectedIndex = [...event.target.options].findIndex(option => option.defaultSelected);
            } else {
                // If confirmed, submit the form
                document.getElementById(`statusForm-${transactionId}`).submit();
            }
        }
    }
</script>
</x-app-layout>