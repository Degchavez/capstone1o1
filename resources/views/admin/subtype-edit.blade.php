<!-- resources/views/admin/transaction-subtypes/edit.blade.php -->
<x-app-layout>
    <div class="container mx-auto p-6 max-w-2xl">
        <div class="bg-white rounded-lg shadow-md p-8">
            <h1 class="text-3xl font-bold mb-6 text-gray-800">Edit Transaction Subtype</h1>
            
            <form action="{{ route('transaction-subtypes.update', $transactionSubtype) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <div>
                        <label for="transaction_type_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Transaction Type
                        </label>
                        <select 
                            name="transaction_type_id" 
                            id="transaction_type_id" 
                            class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('transaction_type_id') border-red-500 @enderror"
                        >
                            @foreach($transactionTypes as $type)
                                <option value="{{ $type->id }}" {{ $type->id == $transactionSubtype->transaction_type_id ? 'selected' : '' }}>
                                    {{ $type->type_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('transaction_type_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="subtype_name" class="block text-sm font-semibold text-gray-700 mb-2">
                            Subtype Name
                        </label>
                        <input 
                            type="text" 
                            name="subtype_name" 
                            id="subtype_name" 
                            value="{{ old('subtype_name', $transactionSubtype->subtype_name) }}" 
                            class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('subtype_name') border-red-500 @enderror"
                            required
                        >
                        @error('subtype_name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between pt-4">
                        <a href="{{ route('transaction-subtypes.index') }}" class="text-gray-600 hover:text-gray-800">
                            Cancel
                        </a>
                        <button 
                            type="submit" 
                            class="bg-blue-500 text-white px-6 py-3 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                        >
                            Update Transaction Subtype
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
