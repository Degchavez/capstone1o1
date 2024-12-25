<!-- resources/views/admin/transaction-subtypes/create.blade.php -->
<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Add Transaction Subtype</h1>
        <form action="{{ route('transaction-subtypes.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="transaction_type_id" class="block text-sm font-medium text-gray-700">Transaction Type</label>
                <select name="transaction_type_id" id="transaction_type_id" class="mt-1 block w-full px-4 py-2 border rounded-md" required>
                    @foreach($transactionTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->type_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="subtype_name" class="block text-sm font-medium text-gray-700">Subtype Name</label>
                <input type="text" name="subtype_name" id="subtype_name" class="mt-1 block w-full px-4 py-2 border rounded-md" required>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Add Transaction Subtype</button>
        </form>
    </div>
</x-app-layout>
