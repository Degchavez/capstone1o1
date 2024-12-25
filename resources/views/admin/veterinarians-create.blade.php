<x-app-layout>
    @if (session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <div class="max-w-3xl mx-auto p-6 bg-white rounded-lg shadow-lg">
        <h1 class="text-2xl font-semibold text-gray-800 mb-6">Register New Veterinarian</h1>
        
        <form action="{{ route('veterinarians.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Complete Name -->
            <div class="mb-4">
                <label for="complete_name" class="block text-gray-700 font-medium">Complete Name</label>
                <input type="text" class="form-control mt-2 block w-full px-4 py-2 border rounded-md shadow-sm focus:ring-2 focus:ring-blue-400" id="complete_name" name="complete_name" value="{{ old('complete_name') }}" required>
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-medium">Email</label>
                <input type="email" class="form-control mt-2 block w-full px-4 py-2 border rounded-md shadow-sm focus:ring-2 focus:ring-blue-400" id="email" name="email" value="{{ old('email') }}" required>
            </div>

            <!-- Contact Number -->
            <div class="mb-4">
                <label for="contact_no" class="block text-gray-700 font-medium">Contact Number</label>
                <input type="text" class="form-control mt-2 block w-full px-4 py-2 border rounded-md shadow-sm focus:ring-2 focus:ring-blue-400" id="contact_no" name="contact_no" value="{{ old('contact_no') }}" required>
            </div>

            <!-- Gender -->
            <div class="mb-4">
                <label for="gender" class="block text-gray-700 font-medium">Gender</label>
                <select class="form-control mt-2 block w-full px-4 py-2 border rounded-md shadow-sm focus:ring-2 focus:ring-blue-400" id="gender" name="gender" required>
                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>

            <!-- Birthdate -->
            <div class="mb-4">
                <label for="birth_date" class="block text-gray-700 font-medium">Birthdate</label>
                <input type="date" class="form-control mt-2 block w-full px-4 py-2 border rounded-md shadow-sm focus:ring-2 focus:ring-blue-400" id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required>
            </div>


            <!-- Designation -->
            <div class="mb-4">
                <label for="designation_id" class="block text-gray-700 font-medium">Designation</label>
                <select class="form-control mt-2 block w-full px-4 py-2 border rounded-md shadow-sm focus:ring-2 focus:ring-blue-400" id="designation_id" name="designation_id" required>
                    @foreach($designations as $designation)
                        <option value="{{ $designation->designation_id }}" {{ old('designation_id') == $designation->designation_id ? 'selected' : '' }}>{{ $designation->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Barangay -->
            <div class="mb-4">
                <label for="barangay_id" class="block text-gray-700 font-medium">Barangay</label>
                <select class="form-control mt-2 block w-full px-4 py-2 border rounded-md shadow-sm focus:ring-2 focus:ring-blue-400" id="barangay_id" name="barangay_id" required>
                    @foreach($barangays as $barangay)
                        <option value="{{ $barangay->id }}" {{ old('barangay_id') == $barangay->id ? 'selected' : '' }}>{{ $barangay->barangay_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Street -->
            <div class="mb-4">
                <label for="street" class="block text-gray-700 font-medium">Street Address</label>
                <input type="text" class="form-control mt-2 block w-full px-4 py-2 border rounded-md shadow-sm focus:ring-2 focus:ring-blue-400" id="street" name="street" value="{{ old('street') }}" required>
            </div>

            <!-- Profile Image -->
            <div class="mb-4">
                <label for="profile_image" class="block text-gray-700 font-medium">Profile Image</label>
                <input type="file" class="form-control mt-2 block w-full px-4 py-2 border rounded-md shadow-sm focus:ring-2 focus:ring-blue-400" id="profile_image" name="profile_image">
            </div>

            <!-- Submit Button -->
            <div class="mb-6">
                <button type="submit" class="w-full py-3 px-4 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">
                    Register Veterinarian
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
