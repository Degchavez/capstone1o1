<x-app-layout>
    <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto space-y-10">
        <!-- Stats Section -->
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Total Owners -->
            <div class="flex flex-col items-center justify-center p-6 bg-white border rounded-lg shadow-md">
                <div class="flex items-center space-x-3">
                    <span class="inline-block w-4 h-4 bg-gray-500 rounded-full"></span>
                    <span class="text-sm font-semibold text-gray-500 uppercase">Total Owners</span>
                </div>
                <h3 class="text-4xl font-bold text-gray-800 mt-4">{{ $totalOwners }}</h3>
                <p class="text-sm text-gray-500 mt-2">Last Week: <span class="font-medium">{{ $lastWeekOwners }}</span></p>
            </div>

            <!-- Successful Transactions -->
            <div class="flex flex-col items-center justify-center p-6 bg-white border rounded-lg shadow-md">
                <div class="flex items-center space-x-3">
                    <span class="inline-block w-4 h-4 bg-green-500 rounded-full"></span>
                    <span class="text-sm font-semibold text-gray-500 uppercase">Successful Transactions</span>
                </div>
                <h3 class="text-4xl font-bold text-gray-800 mt-4">{{ $successfulTransactions }}</h3>
                <p class="text-sm text-gray-500 mt-2">Last Week: <span class="font-medium">{{ $lastWeekTransactions }}</span></p>
            </div>

            <!-- Total Animals -->
            <div class="flex flex-col items-center justify-center p-6 bg-white border rounded-lg shadow-md">
                <div class="flex items-center space-x-3">
                    <span class="inline-block w-4 h-4 bg-blue-500 rounded-full"></span>
                    <span class="text-sm font-semibold text-gray-500 uppercase">Total Animals</span>
                </div>
                <h3 class="text-4xl font-bold text-gray-800 mt-4">{{ $totalAnimals }}</h3>
                <p class="text-sm text-gray-500 mt-2">Last Week: <span class="font-medium">{{ $lastWeekAnimals }}</span></p>
            </div>
        </div>

        <!-- Recent Transactions Section -->
        <div class="space-y-6">
            <h2 class="text-3xl font-bold text-center text-gray-800">Recent Transactions</h2>
            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="min-w-full text-sm text-left text-gray-700">
                    <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-4"></th>
                            <th class="px-6 py-4">Owner</th>
                            <th class="px-6 py-4">Animal</th>
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
                                        <span class="text-gray-500">No Image</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">{{ $transaction->owner->user->complete_name ?? 'N/A' }}</td>
                                <td class="px-6 py-4">{{ $transaction->animal->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        $status = '';
                                        $statusClass = '';

                                        // Set the status and style based on the numeric status
                                        if ($transaction->status === 0) {
                                            $status = 'Pending';
                                            $statusClass = 'bg-yellow-500'; // Pending status
                                        } elseif ($transaction->status === 1) {
                                            $status = 'Completed';
                                            $statusClass = 'bg-green-500'; // Successful status
                                        } elseif ($transaction->status === 2) {
                                            $status = 'Canceled';
                                            $statusClass = 'bg-red-500'; // Canceled status
                                        }
                                    @endphp
                                    <span class="px-2 py-1 rounded-md text-white text-xs font-medium {{ $statusClass }}">
                                        {{ $status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">{{ $transaction->created_at->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">No recent transactions</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
