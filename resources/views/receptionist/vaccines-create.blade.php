<x-app-layout>
    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg p-8">
            <h1 class="text-2xl font-semibold text-gray-800 mb-6">Create Vaccine</h1>

            <!-- Form -->
            <form action="{{ route('newvaccines.store') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label for="vaccine_name" class="block text-sm font-medium text-gray-700">Vaccine Name</label>
                    <input 
                        type="text" 
                        name="vaccine_name" 
                        id="vaccine_name" 
                        class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                        required 
                        placeholder="Enter vaccine name"
                    >
                </div>
                
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea 
                        name="description" 
                        id="description" 
                        class="mt-2 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                        rows="4" 
                        placeholder="Enter a description"
                    ></textarea>
                </div>

                <!-- Save Button -->
                <button 
                    type="submit" 
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    Save
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
