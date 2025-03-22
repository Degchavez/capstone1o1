<!-- resources/views/admin/transaction-subtypes/index.blade.php -->
<x-app-layout>
    <div class="container mx-auto p-8 bg-white shadow-lg rounded-lg">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Transaction Subtypes</h1>
            <a href="{{ route('transaction-subtypes.create') }}" 
                class="flex items-center bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition duration-200 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3H6a1 1 0 000 2h3v3a1 1 0 102 0v-3h3a1 1 0 000-2h-3V7z" clip-rule="evenodd" />
                </svg>
                Add New Subtype
            </a>
        </div>

        <div class="overflow-x-auto bg-gray-50 rounded-xl shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Transaction Type</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($transactionSubtypes as $subtype)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $subtype->subtype_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $subtype->transactionType->type_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex justify-center items-center space-x-4">
                                    <a href="{{ route('transaction-subtypes.edit', $subtype) }}" 
                                       class="text-blue-600 hover:text-blue-800 flex items-center transition duration-150">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M17.414 2.586a2 2 0 010 2.828L15 7.828l-4-4 2.414-2.414a2 2 0 012.828 0l1.172 1.172zM14 6l-4 4H9l1-1v-1H8l-4 4v1h1v1H5v-1H4v1a1 1 0 001 1h1a1 1 0 001-1v-1h1v-1h1v-1h1l4-4V6z"/>
                                        </svg>
                                        <span class="font-medium">Edit</span>
                                    </a>
                                    <form action="{{ route('transaction-subtypes.destroy', $subtype) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                            class="text-red-600 hover:text-red-800 flex items-center transition duration-150"
                                            onclick="return confirm('Are you sure you want to delete this subtype?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M7 4V3a1 1 0 112 0v1h2V3a1 1 0 112 0v1h3a1 1 0 110 2h-1v11a2 2 0 01-2 2H6a2 2 0 01-2-2V6H3a1 1 0 110-2h3zM5 6v11a1 1 0 001 1h6a1 1 0 001-1V6H5zm2 3a1 1 0 012 0v5a1 1 0 11-2 0V9zm4 0a1 1 0 012 0v5a1 1 0 11-2 0V9z" clip-rule="evenodd" />
                                            </svg>
                                            <span class="font-medium">Delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
