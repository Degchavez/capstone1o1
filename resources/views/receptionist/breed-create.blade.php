<!-- resources/views/breeds/create.blade.php -->

<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Add Breed</h1>
        
        <form action="{{ route('newbreeds.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-sm font-semibold text-gray-700">Breed Name</label>
                <input type="text" name="name" id="name" class="w-full px-4 py-2 border rounded-md" required>
            </div>

            <div class="mb-4">
                <label for="species_id" class="block text-sm font-semibold text-gray-700">Species</label>
                <select name="species_id" id="species_id" class="w-full px-4 py-2 border rounded-md" required>
                    <option value="">Select Species</option>
                    @foreach ($species as $specie)
                        <option value="{{ $specie->id }}">{{ $specie->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                Add Breed
            </button>
        </form>
    </div>
</x-app-layout>
