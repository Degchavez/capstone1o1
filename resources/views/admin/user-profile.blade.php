<x-app-layout>
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-2xl">

        <!-- Title Section -->
        <div class="text-center mb-8">
            <h2 class="text-4xl font-bold text-black">User Profile</h2>
            <p class="text-lg text-gray-600">View and manage your profile details.</p>
        </div>

        <!-- Profile Banner -->
        <div class="w-full h-40 bg-gradient-to-b from-transparent to-gray-800 mb-6 rounded-lg shadow-md overflow-hidden">
            <img class="w-full h-full object-cover" 
                 src="{{ $user->profile_banner ? Storage::url($user->profile_banner) : asset('assets/profile_banner.jpg') }}" 
                 alt="Profile Banner">
        </div>
<!-- Profile Info Section -->
<div class="flex flex-col items-center mb-12">
    <!-- Profile Image -->
    <div class="flex-shrink-0 flex justify-center mb-6">
        <img class="w-60 h-60 object-cover rounded-full border-4 border-dark-green shadow-xl hover:scale-105 transition-all duration-300 ease-in-out" 
             src="{{ $user->profile_image ? Storage::url($user->profile_image) : asset('assets/default-avatar.png') }}" 
             alt="Profile Image">
    </div>

    <!-- User Info -->
    <div class="space-y-4 text-center">
        <h3 class="text-2xl font-semibold text-dark-green">{{ $user->complete_name }}</h3>
        <p class="text-lg text-gray-700">
            <span class="font-semibold">Role:</span> 
            @switch($user->role)
                @case(0) Admin @break
                @case(1) Animal Owner @break
                @case(2) Veterinarian @break
                @case(3) Veterinary Receptionist @break
                @default Unknown
            @endswitch
        </p>
        <p class="text-lg text-gray-700"><span class="font-semibold">Gender:</span> {{ $user->gender }}</p>
        <p class="text-lg text-gray-700"><span class="font-semibold">Email:</span> {{ $user->email }}</p>
        <p class="text-lg text-gray-700"><span class="font-semibold">Status:</span> 
            @switch($user->status)
                @case(0) Pending @break
                @case(1) Active @break
                @case(2) Disabled @break
                @default Unknown
            @endswitch
        </p>
    </div>
</div>


        <!-- Profile Info Cards Section -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Contact No Card -->
            <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 ease-in-out">
                <h3 class="font-semibold text-dark-green"><i class="fas fa-phone-alt mr-2"></i> Contact No.</h3>
                <p class="text-gray-700">{{ $user->contact_no }}</p>
            </div>

            <!-- Birth Date Card -->
            <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 ease-in-out">
                <h3 class="font-semibold text-dark-green"><i class="fas fa-birthday-cake mr-2"></i> Birth Date</h3>
                <p class="text-gray-700">{{ $user->birth_date }}</p>
            </div>

            <!-- Address Card -->
            <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 ease-in-out">
                <h3 class="font-semibold text-dark-green"><i class="fas fa-map-marker-alt mr-2"></i> Address</h3>
                <p class="text-gray-700">{{ $user->address->street ?? 'No street information' }},
                    {{ $user->address->barangay->barangay_name ?? 'No barangay information' }}
                </p>
            </div>

            <!-- Status Card -->
            <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 ease-in-out">
                <h3 class="font-semibold text-dark-green"><i class="fas fa-flag-checkered mr-2"></i> Account Status</h3>
                <p class="text-gray-700">
                    @switch($user->status)
                        @case(1) <span class="text-green-500">Active</span> @break
                        @case(2) <span class="text-red-500">Disabled</span> @break
                        @default Unknown
                    @endswitch
                </p>
            </div>
        </div>
        

        <!-- Animal Owner Specific Info -->
        @if ($user->role == 1)
            <div class="mt-6 space-y-4">
                <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 ease-in-out">
                    <h3 class="font-semibold text-dark-green"><i class="fas fa-heart mr-2"></i> Civil Status</h3>
                    <p class="text-gray-700">{{ $user->owner->civil_status ?? 'N/A' }}</p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 ease-in-out">
                    <h3 class="font-semibold text-dark-green"><i class="fas fa-tags mr-2"></i> Category</h3>
                    <p class="text-gray-700">{{ $user->owner->category ?? 'N/A' }}</p>
                </div>
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="mt-8 flex justify-center space-x-4">
            <!-- Back Button -->
            <a href="/admin/users" class="inline-block px-6 py-3 bg-dark-green text-white font-semibold rounded-lg shadow-md hover:bg-black transition duration-300 ease-in-out">
                Back to Users List
            </a>

            <!-- Edit Button -->
            <a href="{{ route('users.profile-edit-form', ['id' => $user->user_id]) }}" class="inline-block px-6 py-3 bg-dark-green text-white font-semibold rounded-lg shadow-md hover:bg-black transition duration-300 ease-in-out">
                Edit Profile
            </a>
        </div>

    </div>

    <style>
        .text-dark-green {
            color: #000000;
        }

        .bg-dark-green {
            background-color: #000000;
        }

        .bg-dark-green:hover {
            background-color: #2c6d37;
        }

        .hover\:shadow-xl:hover {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.08);
        }
    </style>

</x-app-layout>
