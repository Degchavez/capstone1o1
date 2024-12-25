<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold text-primary mb-4">Welcome, {{ auth()->user()->complete_name }}!</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Summary Cards -->
            <div class="bg-white shadow rounded-lg p-4">
                <h2 class="text-lg font-semibold text-gray-700">Assigned Transactions</h2>
                <p class="text-4xl font-bold text-blue-500">{{ $assignedTransactions }}</p>
            </div>
            <div class="bg-white shadow rounded-lg p-4">
                <h2 class="text-lg font-semibold text-gray-700">Successful Transactions</h2>
                <p class="text-4xl font-bold text-green-500">{{ $successfulTransactions }}</p>
            </div>
            <div class="bg-white shadow rounded-lg p-4">
                <h2 class="text-lg font-semibold text-gray-700">Pending Transactions</h2>
                <p class="text-4xl font-bold text-yellow-500">{{ $pendingTransactions }}</p>
            </div>
        </div>

        <!-- Recent Transactions Table -->
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Recent Transactions</h2>
            @if ($recentTransactions->isEmpty())
                <p class="text-gray-500">No recent transactions available.</p>
            @else
                <table class="table-auto w-full border-collapse">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 text-left"></th>
                            <th class="px-6 py-4 text-left">Owner</th>
                            <th class="px-6 py-4 text-left"></th>
                            <th class="px-6 py-4 text-left">Animal</th>
                            <th class="px-6 py-4 text-left">Status</th>
                            <th class="px-6 py-4 text-left">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentTransactions as $transaction)
                            <tr>
                                <!-- Display Owner Profile Image -->
                                <td class="px-4 py-2">
                                    @if ($transaction->owner->user->profile_image)
                                        <img src="{{ asset('storage/' . $transaction->owner->user->profile_image) }}" alt="Owner Profile" class="w-12 h-12 rounded-full">
                                    @else
                                        <img src="{{ asset('assets/default-avatar.png') }}" alt="Default Owner Photo" class="w-12 h-12 object-cover">
                                    @endif
                                </td>

                                <!-- Display Owner Name -->
                                <td class="px-6 py-4 text-left">
                                    <a href="{{ route('vet.profile-owner', ['owner_id' => $transaction->owner->owner_id]) }}" class="text-blue-500 hover:text-blue-700">
                                        {{ $transaction->owner->user->complete_name ?? 'N/A' }}
                                    </a>
                                </td>
                                
                                                
                                <!-- Display Animal Photo -->
                                <td class="px-4 py-4 text-left">
                                    <!-- Check if the animal exists before accessing its photo -->
                                    @if ($transaction->animal && $transaction->animal->photo_front)
                                        <img src="{{ asset('storage/' . $transaction->animal->photo_front) }}" alt="Animal Photo" class="w-12 h-12 object-cover">
                                    @else
                                        <img src="{{ asset('assets/default-avatar.png') }}" alt="Default Animal Photo" class="w-12 h-12 object-cover">
                                    @endif
                                </td>
                
                                <!-- Display Animal Name -->
                                <td class="px-6 py-4 text-left">
                                    <a href="{{ route('vet.profile', ['animal_id' => $transaction->animal->animal_id]) }}" class="text-blue-500 hover:text-blue-700">
                                        {{ $transaction->animal->name ?? 'N/A' }}
                                    </a>
                                </td>
                                
                                <!-- Display Transaction Status -->
                                <td class="px-6 py-4 text-left">
                                    @if ($transaction->status == 0)
                                        <span class="text-yellow-500">Pending</span>
                                    @elseif ($transaction->status == 1)
                                        <span class="text-green-500">Completed</span>
                                    @else
                                        <span class="text-red-500">Cancelled</span>
                                    @endif
                                </td>

                                <!-- Display Date -->
                                <td class="px-6 py-4 text-left">{{ $transaction->created_at->format('M d, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Reports Section -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Reports</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-blue-100 p-4 rounded-lg flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-blue-700">Transactions in the Last Month</h3>
                    <p class="text-4xl font-bold text-blue-500">{{ $lastMonthTransactions }}</p>
                </div>
                <div class="bg-yellow-100 p-4 rounded-lg flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-yellow-700">Pending Transactions</h3>
                    <p class="text-4xl font-bold text-yellow-500">{{ $pendingTransactions }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
