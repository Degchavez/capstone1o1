<x-app-layout>
    <!-- Page Header -->
    <div class="bg-white shadow mb-4">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-semibold text-gray-800">Welcome to Owner's Dashboard</h1>
        </div>
    </div>

    <!-- Dashboard Content -->
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Data Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Card 1 - Animals Owned -->
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-700">Animals Owned</h2>
                    <p class="text-4xl font-semibold text-blue-600 mt-2">{{ $animalsOwned }}</p>
                </div>

                <!-- Card 2 - Successful Transactions -->
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-700">Successful Transactions</h2>
                    <p class="text-4xl font-semibold text-green-600 mt-2">{{ $successfulTransactions }}</p>
                </div>

               <!-- Card 3 - Notices of Past Transactions -->
<div class="bg-white shadow-md rounded-lg p-6">
    <h2 class="text-lg font-medium text-gray-700">Notices of Past Transactions</h2>
    <ul class="list-disc ml-5 mt-2 text-gray-600">
        @forelse ($pastTransactions as $transaction)
            <li>Transaction on <strong>{{ $transaction->created_at->format('F d, Y') }}</strong> was 
                @if ($transaction->status == 0)
                    <span class="text-yellow-600">Pending</span>
                @elseif ($transaction->status == 1)
                    <span class="text-green-600">Successful</span>
                @elseif ($transaction->status == 2)
                    <span class="text-red-600">Cancelled</span>
                @endif.
            </li>
        @empty
            <li>No past transactions found.</li>
        @endforelse
    </ul>
</div>

            </div>
        </div>
    </div>
</x-app-layout>
