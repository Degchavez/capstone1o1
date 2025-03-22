<!-- resources/views/breeds/create.blade.php -->

<x-app-layout>
    <div class="container mx-auto p-6">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-8">
            <div class="flex items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Add New Breed</h1>
                <a href="{{ route('breeds.index') }}" class="ml-auto text-gray-600 hover:text-gray-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </a>
            </div>
            
            <form action="{{ route('breeds.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="space-y-2">
                    <label for="name" class="text-sm font-medium text-gray-700">Breed Name</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('name') border-red-500 @enderror"
                        value="{{ old('name') }}"
                        required
                    >
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="species_id" class="text-sm font-medium text-gray-700">Species</label>
                    <select 
                        name="species_id" 
                        id="species_id" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('species_id') border-red-500 @enderror"
                        required
                    >
                        <option value="">Select Species</option>
                        @foreach ($species as $specie)
                            <option value="{{ $specie->id }}" {{ old('species_id') == $specie->id ? 'selected' : '' }}>
                                {{ $specie->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('species_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end pt-4">
                    <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        Create Breed
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
