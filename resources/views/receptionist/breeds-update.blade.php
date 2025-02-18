<x-app-layout>
  
    <div class="container mx-auto mt-10">
        <h1 class="text-2xl font-bold mb-6">Edit Breed</h1>
    
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
    
        <form action="{{ route('newbreeds.updates', ['breeds' => $breed->id]) }}" method="POST" class="bg-white p-6 rounded-lg shadow-lg max-w-lg mx-auto">
            @csrf
            @method('PUT')
    
            <!-- Breed Name Field -->
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-bold mb-2">Breed Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $breed->name) }}" class="w-full p-2 border border-gray-300 rounded-lg">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
    
            <!-- Species Dropdown -->
            <div class="mb-4">
                <label for="species_id" class="block text-gray-700 font-bold mb-2">Species</label>
                <select name="species_id" id="species_id" class="w-full p-2 border border-gray-300 rounded-lg">
                    @foreach($species as $specie)
                        <option value="{{ $specie->id }}" {{ $breed->species_id == $specie->id ? 'selected' : '' }}>
                            {{ $specie->name }}
                        </option>
                    @endforeach
                </select>
                @error('species_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
    
            <!-- Submit Button -->
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Update Breed</button>
        </form>
    </div>

    
    
</x-app-layout>
