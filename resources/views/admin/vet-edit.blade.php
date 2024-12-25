<x-app-layout>
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg">
        
        <!-- Title Section -->
        <div class="text-center mb-6">
            <h2 class="text-3xl font-semibold text-gray-800">Edit Veterinarian</h2>
            <p class="text-lg text-gray-600">Update veterinarian details below.</p>
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

        <!-- Veterinarian Edit Form -->
        <form action="{{ route('vets.update', $veterinarian->user_id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Current Profile Image -->
            <div class="mb-6 text-center">
                @if ($veterinarian->profile_image)
                    <img class="w-32 h-32 rounded-full mx-auto object-cover" src="{{ Storage::url($veterinarian->profile_image) }}" alt="Current Profile Image">
                @else
                    <p class="text-gray-500">No profile image uploaded</p>
                @endif
            </div>

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
                    <x-text-input name="complete_name" id="complete_name" value="{{ old('complete_name', $veterinarian->complete_name) }}" class="mt-1 block w-full rounded-lg border-green-600 shadow-sm focus:ring-2 focus:ring-green-500" required />
                </div>

                <!-- Contact No. -->
                <div>
                    <x-input-label for="contact_no" :value="__('Contact No.')" class="text-lg font-semibold text-gray-800"/>
                    <x-text-input name="contact_no" id="contact_no" value="{{ old('contact_no', $veterinarian->contact_no) }}" class="mt-1 block w-full rounded-lg border-green-600 shadow-sm focus:ring-2 focus:ring-green-500" required />
                </div>

                <!-- Gender -->
                <div>
                    <x-input-label for="gender" :value="__('Gender')" class="text-lg font-semibold text-gray-800"/>
                    <select name="gender" id="gender" class="mt-1 block w-full rounded-lg border-green-600 shadow-sm focus:ring-2 focus:ring-green-500">
                        <option value="Male" {{ old('gender', $veterinarian->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender', $veterinarian->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                <!-- Birth Date -->
                <div>
                    <x-input-label for="birth_date" :value="__('Birth Date')" class="text-lg font-semibold text-gray-800"/>
                    <x-text-input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $veterinarian->birth_date->toDateString()) }}" class="mt-1 block w-full rounded-lg border-green-600 shadow-sm focus:ring-2 focus:ring-green-500" required />
                </div>

                <!-- Designation -->
                <div>
                    <x-input-label for="designation_id" :value="__('Designation')" class="text-lg font-semibold text-gray-800"/>
                    <select name="designation_id" id="designation_id" class="mt-1 block w-full rounded-lg border-green-600 shadow-sm focus:ring-2 focus:ring-green-500">
                        @foreach ($designations as $designation)
                            <option value="{{ $designation->designation_id }}" {{ old('designation_id', $veterinarian->designation_id) == $designation->designation_id ? 'selected' : '' }}>
                                {{ $designation->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Barangay -->
                <div>
                    <x-input-label for="barangay_id" :value="__('Barangay')" class="text-lg font-semibold text-gray-800"/>
                    <select name="barangay_id" id="barangay_id" class="mt-1 block w-full rounded-lg border-green-600 shadow-sm focus:ring-2 focus:ring-green-500">
                        @foreach ($barangays as $barangay)
                            <option value="{{ $barangay->id }}" {{ old('barangay_id', $veterinarian->address->barangay_id) == $barangay->id ? 'selected' : '' }}>
                                {{ $barangay->barangay_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-input-label for="street" :value="__('Street')" class="text-lg font-semibold text-gray-800"/>
                    <x-text-input type="text" name="street" id="street" value="{{ old('street', $veterinarian->address->street) }}" class="mt-1 block w-full rounded-lg border-green-600 shadow-sm focus:ring-2 focus:ring-green-500" />
                </div>

                <div class="flex items-center justify-center mt-8">
                    <x-primary-button class="bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 focus:outline-none text-white font-semibold rounded-lg px-6 py-3 shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                        {{ __('Update Veterinarian') }}
                    </x-primary-button><br><br>
                </div>

               

            </div>
        </form>
    </div>

</x-app-layout>
