<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Edit Species</h1>

        <form action="{{ route('newspecies.updates', ['species' => $species->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" name="name" id="name" value="{{ $species->name }}" 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded-md hover:bg-green-600">
                Save Changes
            </button>
        </form>
    </div>
</x-app-layout>
