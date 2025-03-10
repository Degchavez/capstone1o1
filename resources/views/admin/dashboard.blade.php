<x-app-layout>
    <!-- Main Container with gradient background -->
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
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
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-md text-white text-xs font-medium 
                            {{ $transaction->status === 0 ? 'bg-yellow-500' : ($transaction->status === 1 ? 'bg-green-500' : 'bg-red-500') }}">
                            {{ $statuses[$transaction->status] ?? 'Unknown' }}
                        </span>
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
                {{ $recentTransactions->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
