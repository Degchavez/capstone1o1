<x-app-layout>
    <div class="container mx-auto p-6 max-w-2xl">
        <div class="bg-white rounded-lg shadow-md p-8">
            <h1 class="text-3xl font-semibold text-gray-800 mb-6">Edit Designation</h1>

            <form action="{{ route('designations.update', $designation) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Name</label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name" 
                            value="{{ old('name', $designation->name) }}" 
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150" 
                            required
                        >
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                        <textarea 
                            name="description" 
                            id="description" 
                            rows="4"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                        >{{ old('description', $designation->description) }}</textarea>
                    </div>

                    <div class="flex items-center justify-end mt-8">
                        <a href="{{ route('designations.index') }}" class="mr-4 px-4 py-2 text-gray-600 hover:text-gray-800 transition duration-150">
                            Cancel
                        </a>
                        <button 
                            type="submit" 
                            class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-md shadow-sm transition duration-150 focus:ring-2 focus:ring-blue-300"
                        >
                            Update Designation
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
