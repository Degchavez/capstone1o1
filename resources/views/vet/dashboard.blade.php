<x-app-layout>
    <div class="container mx-auto p-6">
        <!-- Improved welcome banner with gradient background -->
        <div class="bg-gradient-to-r from-primary to-blue-400 rounded-lg p-6 shadow-md mb-8 text-white">
            <h1 class="text-green-700 text-3xl font-bold mb-2">Welcome, {{ auth()->user()->complete_name }}!</h1>
            <p class="text-black/80 font-mono">Here's an overview of your veterinary practice</p>
        </div>

        <!-- Summary Cards with improved styling and icons -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white shadow-md rounded-lg p-6 border-l-4 border-blue-500 hover:shadow-lg transition duration-300">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-700">Assigned Transactions</h2>
                        <p class="text-4xl font-bold text-blue-500 mt-2">{{ $assignedTransactions }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-white shadow-md rounded-lg p-6 border-l-4 border-green-500 hover:shadow-lg transition duration-300">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-700">Successful Transactions</h2>
                        <p class="text-4xl font-bold text-green-500 mt-2">{{ $successfulTransactions }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-white shadow-md rounded-lg p-6 border-l-4 border-yellow-500 hover:shadow-lg transition duration-300">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-700">Pending Transactions</h2>
                        <p class="text-4xl font-bold text-yellow-500 mt-2">{{ $pendingTransactions }}</p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-800">Reports & Analytics</h2>
                <a href="{{ route('reports.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-primary text-black font-semibold rounded-lg shadow-md transition-all duration-200 ease-in-out hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Generate Report
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-lg shadow-sm flex items-center justify-between hover:shadow-md transition duration-300">
                    <div>
                        <h3 class="text-xl font-semibold text-blue-700 mb-2">Transactions in Last Month</h3>
                        <p class="text-blue-600 text-sm">Total activity for past 30 days</p>
                    </div>
                    <div class="flex flex-col items-end">
                        <p class="text-4xl font-bold text-blue-600">{{ $lastMonthTransactions }}</p>
                        <div class="flex items-center mt-1 text-green-600 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                            <span class="ml-1">12% increase</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-6 rounded-lg shadow-sm flex items-center justify-between hover:shadow-md transition duration-300">
                    <div>
                        <h3 class="text-xl font-semibold text-yellow-700 mb-2">Pending Transactions</h3>
                        <p class="text-yellow-600 text-sm">Requires your attention</p>
                    </div>
                    <div class="flex flex-col items-end">
                        <p class="text-4xl font-bold text-yellow-600">{{ $pendingTransactions }}</p>
                        @if($pendingTransactions > 0)
                            <a href="#" class="text-primary hover:underline text-sm mt-1">View all</a>
                        @endif
                    </div>  
                </div>
            </div>
        </div>

        <!-- Recent Transactions Table with improved styling -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-800">Recent Transactions</h2>
                <a href="{{ route('vet.veterinarian.profile', ['user_id' => auth()->user()->user_id]) }}" 
                    class="text-primary hover:text-primary-dark transition-colors text-sm flex items-center">
                     View All
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                     </svg>
                 </a>
                 
            </div>
            
            @if ($recentTransactions->isEmpty())
                <div class="flex flex-col items-center justify-center py-12 text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-lg">No recent transactions available.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="table-auto w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owner</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Animal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($recentTransactions as $transaction)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <!-- Display Owner Profile Image -->
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if ($transaction->owner->user->profile_image)
                                                <img src="{{ asset('storage/' . $transaction->owner->user->profile_image) }}" alt="Owner Profile" class="h-10 w-10 rounded-full object-cover shadow">
                                            @else
                                                <img src="{{ asset('assets/default-avatar.png') }}" alt="Default Owner Photo" class="h-10 w-10 rounded-full object-cover shadow">
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Display Owner Name -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('vet.profile-owner', ['owner_id' => $transaction->owner->owner_id]) }}" class="text-blue-600 hover:text-blue-900 font-medium">
                                            {{ $transaction->owner->user->complete_name ?? 'N/A' }}
                                        </a>
                                    </td>
                                    
                                    <!-- Display Animal Photo -->
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if ($transaction->animal && $transaction->animal->photo_front)
                                                <img src="{{ asset('storage/' . $transaction->animal->photo_front) }}" alt="Animal Photo" class="h-10 w-10 rounded-lg object-cover shadow">
                                            @else
                                                <img src="{{ asset('assets/default-avatar.png') }}" alt="Default Animal Photo" class="h-10 w-10 rounded-lg object-cover shadow">
                                            @endif
                                        </div>
                                    </td>
                    
                                    <!-- Display Animal Name -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('vet.profile', ['animal_id' => $transaction->animal->animal_id]) }}" class="text-blue-600 hover:text-blue-900 font-medium">
                                            {{ $transaction->animal->name ?? 'N/A' }}
                                        </a>
                                    </td>
                                    
                                    <!-- Display Transaction Status -->
                                    <td class="px-2 py-2 whitespace-nowrap">
                                        @if ($transaction->status == 0)
                                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                        @elseif ($transaction->status == 1)
                                        <div class="flex flex-col items-start space-y-2 justify-center items-center ">
                                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 ">
                                                Completed
                                            </span>
                                            
                                            <a href="#" 
                                                onclick="openTransactionModal('{{ $transaction->transaction_id }}')"
                                                class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white font-semibold rounded-lg shadow-md transition-all duration-200 ease-in-out hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
                                                <span class="text-sm">See Transaction Details</span>
                                            </a>
                                        </div>
                                        
                                        @else
                                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Cancelled</span>
                                        @endif
                                    </td>

                                    <!-- Display Date -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $transaction->created_at->format('M d, Y') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Reports Section with improved cards -->
        
    </div>

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
