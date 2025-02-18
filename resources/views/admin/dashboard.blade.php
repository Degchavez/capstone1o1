<x-app-layout>
    <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto space-y-10">
        <!-- Statistics Section -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-blue-500 text-white p-6 rounded-lg shadow-md">
                <h3 class="text-2xl font-semibold">{{ $totalOwners }}</h3>
                <p class="mt-2 text-sm">Total Owners</p>
            </div>
            <div class="bg-green-500 text-white p-6 rounded-lg shadow-md">
                <h3 class="text-2xl font-semibold">{{ $successfulTransactions }}</h3>
                <p class="mt-2 text-sm">Successful Transactions</p>
            </div>
            <div class="bg-yellow-500 text-white p-6 rounded-lg shadow-md">
                <h3 class="text-2xl font-semibold">{{ $totalAnimals }}</h3>
                <p class="mt-2 text-sm">Total Animals</p>
            </div>
        </div>

        <!-- Filters and Search Section -->
        <div class="flex flex-wrap justify-between gap-4 mb-6">
            <!-- Search Input -->
            <div>
                <form method="GET" action="{{ route('admin-dashboard') }}">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="p-2 border rounded-lg shadow-sm"
                           placeholder="Search by Owner or Animal">
                    <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-lg">Search</button>
                </form>
            </div>

            <!-- Filters -->
            <div class="flex gap-4">
                <!-- Status Filter -->
                <form method="GET" action="{{ route('admin-dashboard') }}">
                    <select name="status" class="p-2 border rounded-lg" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        @foreach($statuses as $key => $status)
                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                </form>

                <!-- Veterinarian Filter -->
                <form method="GET" action="{{ route('admin-dashboard') }}">
                    <select name="veterinarian" class="p-2 border rounded-lg" onchange="this.form.submit()">
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
                    <select name="technician" class="p-2 border rounded-lg" onchange="this.form.submit()">
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

        <!-- Recent Transactions Section -->

      <div class="overflow-x-auto bg-white rounded-lg shadow">
    <!-- Title -->
    <div class="px-6 py-4">
        <h2 class="text-xl font-bold text-gray-700">Recent Transactions</h2>
    </div>

    <table class="min-w-full text-sm text-left text-gray-700">
        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
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
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4">
                        @if($transaction->owner->user->profile_image)
                            <img src="{{ asset('storage/' . $transaction->owner->user->profile_image) }}" alt="Profile Image" class="w-10 h-10 rounded-full">
                        @else
                        <img src="{{asset('assets/default-avatar.png') }}" class="w-12 h-12 rounded-full" alt="Profile">
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
        <div class="mt-4">
            {{ $recentTransactions->links() }}
        </div>
    </div>
</x-app-layout>
