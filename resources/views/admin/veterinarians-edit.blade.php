<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <!-- Heading -->
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800 dark:text-white">
                Edit Veterinarian
            </h2>
            <p class="text-lg text-gray-500 dark:text-gray-300 mt-2">
                Update veterinarian details below.
            </p>
        </div>

        <!-- Form -->
        <form action="{{ route('admin-veterinarians.update', $veterinarian->user_id) }}" method="POST" enctype="multipart/form-data" class="max-w-4xl mx-auto bg-white dark:bg-neutral-800 shadow-lg rounded-lg p-6">
            @csrf
            @method('PUT')

            <!-- Complete Name -->
            <div class="mb-6">
                <label for="complete_name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Complete Name</label>
                <input type="text" name="complete_name" id="complete_name" value="{{ old('complete_name', $veterinarian->complete_name) }}" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg dark:bg-neutral-700 dark:border-neutral-600 dark:text-gray-200" required>
            </div>

            <!-- Email -->
            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $veterinarian->email) }}" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg dark:bg-neutral-700 dark:border-neutral-600 dark:text-gray-200" required>
            </div>

            <!-- Contact No -->
            <div class="mb-6">
                <label for="contact_no" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Contact No.</label>
                <input type="text" name="contact_no" id="contact_no" value="{{ old('contact_no', $veterinarian->contact_no) }}" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg dark:bg-neutral-700 dark:border-neutral-600 dark:text-gray-200" required>
            </div>

            <!-- Gender -->
            <div class="mb-6">
                <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Gender</label>
                <select name="gender" id="gender" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg dark:bg-neutral-700 dark:border-neutral-600 dark:text-gray-200" required>
                    <option value="Male" {{ old('gender', $veterinarian->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender', $veterinarian->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>

            <!-- Birth Date -->
            <div class="mb-6">
                <label for="birth_date" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Birth Date</label>
                <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $veterinarian->birth_date->format('Y-m-d')) }}" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg dark:bg-neutral-700 dark:border-neutral-600 dark:text-gray-200" required>
            </div>

            <!-- Designation -->
            <div class="mb-6">
                <label for="designation_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Designation</label>
                <select name="designation_id" id="designation_id" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg dark:bg-neutral-700 dark:border-neutral-600 dark:text-gray-200" required>
                    @foreach($designations as $designation)
                        <option value="{{ $designation->designation_id }}" {{ old('designation_id', $veterinarian->designation_id) == $designation->designation_id ? 'selected' : '' }}>
                            {{ $designation->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Profile Image -->
            <div class="mb-6">
                <label for="profile_image" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Profile Image</label>
                <input type="file" name="profile_image" id="profile_image" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg dark:bg-neutral-700 dark:border-neutral-600 dark:text-gray-200">
            </div>

            <!-- Update Button -->
            <div class="mt-6">
                <button type="submit" class="w-full py-3 bg-green-600 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 transition duration-200">
                    Update Veterinarian
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
