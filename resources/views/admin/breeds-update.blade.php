<x-app-layout>
    <form action="{{ route('breeds.update', $breed) }}" method="POST" class="bg-white p-6 rounded-lg shadow-lg max-w-lg mx-auto">
        @csrf
        @method('PUT')  <!-- Method Spoofing for PUT request -->
    
        <!-- Breed Name Field -->
        <div class="form-group mb-4">
            <label for="name" class="form-label font-weight-bold text-lg">Breed Name</label>
            <input type="text" id="name" name="name" class="form-control py-2 px-4 border rounded-md shadow-sm w-full" value="{{ old('name', $breed->name) }}" required placeholder="Enter the breed name">
            @error('name')
                <div class="text-danger mt-1 text-sm">{{ $message }}</div>
            @enderror
        </div>
    
        <!-- Species Dropdown -->
        <div class="form-group mb-4">
            <label for="species_id" class="form-label font-weight-bold text-lg">Species</label>
            <select id="species_id" name="species_id" class="form-control py-2 px-4 border rounded-md shadow-sm w-full" required>
                <option value="" disabled selected>Select Species</option>
                @foreach ($species as $specie)
                    <option value="{{ $specie->id }}" 
                        {{ old('species_id', $breed->species_id) == $specie->id ? 'selected' : '' }}>
                        {{ $specie->name }}
                    </option>
                @endforeach
            </select>
            @error('species_id')
                <div class="text-danger mt-1 text-sm">{{ $message }}</div>
            @enderror
        </div>
    
        <!-- Update Button -->
        <div class="text-center mt-6">
            <button type="submit" class="btn btn-primary py-2 px-6 rounded-full flex items-center justify-center space-x-2 hover:bg-blue-600 transition duration-300">
                <i class="fas fa-save"></i>
                <span>Update Breed</span>
            </button>
        </div>
    </form>
    
</x-app-layout>
