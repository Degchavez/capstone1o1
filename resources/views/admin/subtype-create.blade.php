<!-- resources/views/admin/transaction-subtypes/create.blade.php -->
<x-app-layout>
    <div class="container mx-auto p-6 max-w-2xl">
        <div class="bg-white rounded-lg shadow-md p-8">
            <h1 class="text-3xl font-semibold text-gray-800 mb-6">Add Transaction Subtype</h1>
            
            <form action="{{ route('transaction-subtypes.store') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label for="transaction_type_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Transaction Type
                        </label>
                        <select 
                            name="transaction_type_id" 
                            id="transaction_type_id" 
                            class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                            required
                        >
                            <option value="">Select a transaction type</option>
                            @foreach($transactionTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->type_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="subtype_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Subtype Name
                        </label>
                        <input 
                            type="text" 
                            name="subtype_name" 
                            id="subtype_name" 
                            class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                            required
                            placeholder="Enter subtype name"
                        >
                    </div>

                    <div class="pt-4">
                        <button 
                            type="submit" 
                            class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200"
                        >
                            Add Transaction Subtype
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
