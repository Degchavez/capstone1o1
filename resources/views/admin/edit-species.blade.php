<!-- resources/views/admin/species/edit.blade.php -->
<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Edit Species</h1>

        <form action="{{ route('species.update', $species) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Species Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $species->name) }}" class="mt-1 block w-full px-4 py-2 border rounded-md focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                    Update Species
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
