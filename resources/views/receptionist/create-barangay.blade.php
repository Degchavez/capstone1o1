<!-- resources/views/barangays/create.blade.php -->

<x-app-layout>
    <div class="max-w-4xl mx-auto mt-10 p-6 bg-white rounded-lg shadow-md">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Create New Barangay</h1>
        <p class="text-gray-600 mb-6">Fill out the form below to add a new barangay.</p>

        <!-- Success and Error Messages -->
        @if (session('success'))
            <div class="mb-6 p-4 text-green-800 bg-green-100 border border-green-200 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 text-red-800 bg-red-100 border border-red-200 rounded-lg">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Barangay Form -->
        <form action="{{ route('newbarangays.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Barangay Name -->
            <div>
                <label for="barangay_name" class="block text-sm font-medium text-gray-700">Barangay Name</label>
                <input 
                    type="text" 
                    name="barangay_name" 
                    id="barangay_name" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" 
                    placeholder="Enter Barangay Name" 
                    value="{{ old('barangay_name') }}" 
                    required>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button 
                    type="submit" 
                    class="px-6 py-2 text-white bg-blue-600 rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Save Barangay
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
