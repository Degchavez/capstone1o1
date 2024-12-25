<!-- resources/views/admin/vaccines/edit.blade.php -->

<x-app-layout>
    <div class="container mx-auto py-6">
        <h1 class="text-2xl font-semibold mb-4">Edit Vaccine</h1>

        <!-- Form to edit vaccine -->
        <form action="{{ route('vaccines.update', $vaccine) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group mb-4">
                <label for="vaccine_name" class="block text-sm font-medium text-gray-700">Vaccine Name</label>
                <input type="text" name="vaccine_name" id="vaccine_name" value="{{ old('vaccine_name', $vaccine->vaccine_name) }}" class="form-control mt-2 w-full px-4 py-2 border rounded-md" required>
                @error('vaccine_name')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="4" class="form-control mt-2 w-full px-4 py-2 border rounded-md">{{ old('description', $vaccine->description) }}</textarea>
                @error('description')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 mt-4">Update Vaccine</button>
        </form>
    </div>
</x-app-layout>
