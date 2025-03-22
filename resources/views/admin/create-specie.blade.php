<!-- resources/views/admin/species/create.blade.php -->
<x-app-layout>
    <div class="max-w-2xl mx-auto p-8 bg-white rounded-lg shadow-md mt-10">
        <div class="mb-8">
            <h1 class="text-3xl font-semibold text-gray-800">Add New Species</h1>
            <p class="text-gray-600 mt-2">Enter the details for the new species below</p>
        </div>

        <form action="{{ route('species.store') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label for="name" class="text-sm font-medium text-gray-700 block mb-2">
                    Species Name
                </label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                    placeholder="Enter species name"
                    required
                >
            </div>

            <div class="pt-4">
                <button 
                    type="submit" 
                    class="w-full sm:w-auto px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out"
                >
                    Create Species
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
