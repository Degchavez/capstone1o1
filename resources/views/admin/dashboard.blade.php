<x-app-layout>
    <!-- Main Container with gradient background -->
    <div class="min-h-screen ">
        <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto space-y-10">
            <!-- Statistics Section -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-8 rounded-2xl shadow-lg hover:scale-105 transition-all duration-300 transform">
                    <div class="flex flex-col items-center">
                        <span class="text-3xl font-bold mb-2">{{ $totalOwners }}</span>
                        <p class="text-blue-100">Total Owners</p>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-8 rounded-2xl shadow-lg hover:scale-105 transition-all duration-300 transform">
                    <div class="flex flex-col items-center">
                        <span class="text-3xl font-bold mb-2">{{ $successfulTransactions }}</span>
                        <p class="text-green-100">Successful Transactions</p>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white p-8 rounded-2xl shadow-lg hover:scale-105 transition-all duration-300 transform">
                    <div class="flex flex-col items-center">
                        <span class="text-3xl font-bold mb-2">{{ $totalAnimals }}</span>
                        <p class="text-yellow-100">Total Animals</p>
                    </div>
                </div>
            </div>

            <!-- Filters and Search Section -->
            <div class="bg-white p-6 rounded-2xl shadow-md space-y-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Filters & Search</h2>
                <div class="flex flex-col lg:flex-row justify-between gap-6">
                    <!-- Search Input -->
                    <div class="flex-1">
                        <form method="GET" action="{{ route('admin-dashboard') }}" class="flex gap-2">
                            <input name="search" value="{{ request('search') }}" 
                                   class="w-full p-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-300"
                                   placeholder="Search by Owner or Animal">
                            <button type="submit" class="px-6 py-3 bg-green-500 text-white rounded-xl hover:bg-green-600 transition-all duration-300 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Search
                            </button>
                        </form>
                    </div>

                    <!-- Filters -->
                    <div class="flex flex-wrap gap-4">
                        <!-- Status Filter -->
                        <form method="GET" action="{{ route('admin-dashboard') }}">
                            <select name="status" class="p-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-300" onchange="this.form.submit()">
                                <option value="" {{ request('status') === null || request('status') === '' ? 'selected' : '' }}>All Status</option>
                                @foreach($statuses as $key => $status)
                                    <option value="{{ $key }}" {{ request('status') !== null && request('status') !== '' && (string)request('status') === (string)$key ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                        </form>

                        <!-- Veterinarian Filter -->
                        <form method="GET" action="{{ route('admin-dashboard') }}">
                            <select name="veterinarian" class="p-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-300" onchange="this.form.submit()">
                                <option value="">All Veterinarians</option>
                                @foreach($veterinarians as $veterinarian)
                                    <option value="{{ $veterinarian->user_id }}" 
                                            {{ request('veterinarian') == $veterinarian->user_id ? 'selected' : '' }}>
                                        {{ $veterinarian->complete_name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>

                        <!-- Technician Filter -->
                        <form method="GET" action="{{ route('admin-dashboard') }}">
                            <select name="technician" class="p-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-300" onchange="this.form.submit()">
                                <option value="">All Technicians</option>
                                @foreach($technicians as $technician)
                                    <option value="{{ $technician->technician_id }}" 
                                            {{ request('technician') == $technician->technician_id ? 'selected' : '' }}>
                                        {{ $technician->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions Section -->
            <div class="bg-white rounded-2xl shadow-md overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-2xl font-bold text-gray-800">Recent Transactions</h2>
                </div>

    <table class="min-w-full text-sm text-left text-gray-700">
        <thead class="bg-gray-100 text-gray-600 uppercase text-xs ">
            <tr>
                <th class="px-6 py-4"></th>
                <th class="px-6 py-4">Owner</th>
                <th class="px-6 py-4">Animal</th>
                <th class="px-6 py-4">Veterinarian</th>
                <th class="px-6 py-4">Technician</th>
                <th class="px-6 py-4">Transaction Type</th>
                <th class="px-6 py-4">Status</th>
                <th class="px-6 py-4">Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($recentTransactions as $transaction)
                <tr class="border-b hover:bg-gray-50 ">
                    <td class="px-6 py-4">
                        @if($transaction->owner->user->profile_image)
                            <img src="{{ asset('storage/' . $transaction->owner->user->profile_image) }}" alt="Profile Image" class="w-10 h-10 rounded-full hover:scale-105">
                        @else
                        <img src="{{asset('assets/default-avatar.png') }}" class="w-12 h-12 rounded-full hover:scale-105 transition-transform duration-400" alt="Profile">
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <a href="{{ route('owners.profile-owner', $transaction->owner->owner_id) }}" class="text-blue-500 hover:text-blue-700 font-bold">
                            {{ $transaction->owner->user->complete_name ?? 'N/A' }}
                        </a>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('animals.profile', ['animal_id' => $transaction->animal->animal_id]) }}" 
                           class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">
                            <strong>{{ $transaction->animal->name ?? 'N/A' }}</strong>
                        </a>
                    </td>
                    <td class="px-6 py-4">
                        @if($transaction->vet)
                            <strong>
                                <a href="{{ route('admin.veterinarian.profile', $transaction->vet->user_id) }}" 
                                   class="text-blue-500 hover:underline">
                                    {{ $transaction->vet->complete_name }}
                                </a>
                            </strong>
                        @else
                            No Veterinarian Selected
                        @endif
                    </td>
                    <td class="px-6 py-4">{{ $transaction->technician->full_name ?? 'N/A' }}</td>
                    <td class="px-6 py-4">
                        {{ $transaction->transactionType->type_name ?? 'Unknown' }}
                        @if($transaction->transactionSubtype)
                            - {{ $transaction->transactionSubtype->subtype_name ?? 'Unknown' }}
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            {{ $transaction->status == 0 ? 'bg-yellow-100 text-yellow-800' : 
                               ($transaction->status == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                            {{ $transaction->status == 0 ? 'Pending' : 
                               ($transaction->status == 1 ? 'Completed' : 'Canceled') }}
                        </span>
                        
                        @if ($transaction->status == 1)
                            <!-- Button for Completed Transactions -->
                            <div class="mt-2">
                                <button type="button"
                                        onclick="openTransactionModal('{{ $transaction->transaction_id }}')"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white font-semibold rounded-lg shadow-md 
                                               transition-all duration-200 ease-in-out hover:bg-green-800 focus:outline-none focus:ring-2 
                                               focus:ring-green-400 focus:ring-offset-2">
                                    <span class="text-sm">See Transaction Details</span>
                                </button>
                            </div>
                        @endif
                    </td>
                    
                    <td class="px-6 py-4">{{ $transaction->created_at->format('M d, Y') }}</td>
                   
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                        No transactions found. Try adjusting your filters or search criteria.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $recentTransactions->appends(request()->query())->links() }}
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
