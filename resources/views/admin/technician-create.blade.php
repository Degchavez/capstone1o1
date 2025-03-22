<x-app-layout>
    <div class="max-w-3xl mx-auto py-8 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <!-- Header Section -->
            <div class="p-6 border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-800">Add New Technician</h1>
            </div>

            <!-- Form Section -->
            <div class="p-6">
                <form action="{{ route('veterinary-technicians.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" 
                               name="full_name" 
                               id="full_name" 
                               class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                               required>
                    </div>

                    <div class="mb-4">
                        <label for="contact_number" class="block text-sm font-medium text-gray-700">Contact Number</label>
                        <input type="text" 
                               name="contact_number" 
                               id="contact_number" 
                               class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                               required>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                               required>
                    </div>

                    <button type="submit" 
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                        Save Technician
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
