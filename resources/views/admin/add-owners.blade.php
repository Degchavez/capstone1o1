<x-app-layout>
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg">
        
        <!-- Title Section -->
        <div class="text-center mb-6">
            <h2 class="text-3xl font-semibold text-gray-800">Add Animal Owner</h2>
            <p class="text-lg text-gray-600">Fill in the details below to add a new owner.</p>
        </div>
        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="/admin/users">
                <img class="h-20 w-auto mx-auto" src="{{ asset('assets/1.jpg') }}" alt="Your Logo">
            </a>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="mb-4 bg-red-100 text-red-700 p-4 rounded-lg shadow-sm">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Add Owner Form -->
        <form action="{{route('register-owner.submit') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Image Upload -->
            <div class="mb-6">
                <x-input-label for="profile_image" :value="__('Profile Image')" class="text-lg font-semibold text-gray-800"/>
                <input type="file" name="profile_image" id="profile_image" accept="image/*" class="mt-1 block w-full rounded-lg border-green-600 shadow-sm focus:ring-2 focus:ring-green-500">
                <p class="text-sm text-gray-600 mt-2">Upload a profile image (JPEG/PNG, max: 2MB).</p>
            </div>

            <div class="space-y-6">
                <!-- Complete Name -->
                <div>
                    <x-input-label for="complete_name" :value="__('Complete Name')" class="text-lg font-semibold text-gray-800"/>
                    <x-text-input name="complete_name" id="complete_name" value="{{ old('complete_name') }}" class="mt-1 block w-full rounded-lg border-green-600 shadow-sm focus:ring-2 focus:ring-green-500" required />
                </div>

                <!-- Contact No. -->
                <div>
                    <x-input-label for="contact_no" :value="__('Contact No.')" class="text-lg font-semibold text-gray-800"/>
                    <x-text-input name="contact_no" id="contact_no" value="{{ old('contact_no') }}" class="mt-1 block w-full rounded-lg border-green-600 shadow-sm focus:ring-2 focus:ring-green-500" required />
                </div>

                <!-- Gender -->
                <div>
                    <x-input-label for="gender" :value="__('Gender')" class="text-lg font-semibold text-gray-800"/>
                    <select name="gender" id="gender" class="mt-1 block w-full rounded-lg border-green-600 shadow-sm focus:ring-2 focus:ring-green-500">
                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                <!-- Birth Date -->
                <div>
                    <x-input-label for="birth_date" :value="__('Birth Date')" class="text-lg font-semibold text-gray-800"/>
                    <x-text-input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}" class="mt-1 block w-full rounded-lg border-green-600 shadow-sm focus:ring-2 focus:ring-green-500" required />
                </div>

        

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" class="text-lg font-semibold text-gray-800"/>
                    <x-text-input type="email" name="email" id="email" value="{{ old('email') }}" class="mt-1 block w-full rounded-lg border-green-600 shadow-sm focus:ring-2 focus:ring-green-500" required />
                </div>

                <!-- Address Fields -->
                <div>
                    <x-input-label for="barangay_id" :value="__('Barangay')" class="text-lg font-semibold text-gray-800"/>
                    <select name="barangay_id" id="barangay_id" class="mt-1 block w-full rounded-lg border-green-600 shadow-sm focus:ring-2 focus:ring-green-500" required>
                        <option value="">Select Barangay</option>
                        @foreach ($barangays as $barangay)
                            <option value="{{ $barangay->id }}" {{ old('barangay_id') == $barangay->id ? 'selected' : '' }}>{{ $barangay->barangay_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-input-label for="street" :value="__('Street')" class="text-lg font-semibold text-gray-800"/>
                    <x-text-input type="text" name="street" id="street" value="{{ old('street') }}" class="mt-1 block w-full rounded-lg border-green-600 shadow-sm focus:ring-2 focus:ring-green-500" />
                </div>

              <!-- Owner Fields (Always Visible) -->
<div id="ownerFields" class="mt-6 space-y-4">
    <!-- Civil Status -->
    <div>
        <x-input-label for="civil_status" :value="__('Civil Status')" class="text-lg font-semibold text-gray-800"/>
        <select name="civil_status" id="civil_status" class="mt-1 block w-full rounded-lg border-green-600 shadow-sm focus:ring-2 focus:ring-green-500">
            <option value="">Select Civil Status</option>
            <option value="Married" {{ old('civil_status') == 'Married' ? 'selected' : '' }}>Married</option>
            <option value="Separated" {{ old('civil_status') == 'Separated' ? 'selected' : '' }}>Separated</option>
            <option value="Single" {{ old('civil_status') == 'Single' ? 'selected' : '' }}>Single</option>
            <option value="Widow" {{ old('civil_status') == 'Widow' ? 'selected' : '' }}>Widow</option>
        </select>
    </div>

    <!-- Category -->
    <div>
        <x-input-label for="category" :value="__('Category')" class="text-lg font-semibold text-gray-800"/>
        <select name="category" id="category" class="mt-1 block w-full rounded-lg border-green-600 shadow-sm focus:ring-2 focus:ring-green-500">
            <option value="">Select Category</option>

            <option value="N/A" {{ old('category') == 'N/A' ? 'selected' : '' }}>N/A</option>
            <option value="Indigenous People" {{ old('category') == 'Indigenous People' ? 'selected' : '' }}>Indigenous People</option>
            <option value="Senior" {{ old('category') == 'Senior' ? 'selected' : '' }}>Senior</option>
            <option value="Single Parent" {{ old('category') == 'Single Parent' ? 'selected' : '' }}>Single Parent</option>
            <option value="Pregnant" {{ old('category') == 'Pregnant' ? 'selected' : '' }}>Pregnant</option>
            <option value="Person with Disability" {{ old('category') == 'Person with Disability' ? 'selected' : '' }}>Person with Disability</option>
            <option value="Lactating Mother" {{ old('category') == 'Lactating Mother' ? 'selected' : '' }}>Lactating Mother</option>
            <option value="LGBT" {{ old('category') == 'LGBT' ? 'selected' : '' }}>LGBT</option>
        </select>
    </div>
</div>

                <!-- Submit Button -->
                <div class="flex items-center justify-center mt-8">
                    <x-primary-button class="bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 focus:outline-none text-white font-semibold rounded-lg px-6 py-3 shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                        {{ __('Save Owner') }}
                    </x-primary-button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
