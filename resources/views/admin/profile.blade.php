<x-app-layout>
    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Main Profile Card -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Profile Banner with Overlay -->
            <div class="relative h-56">
                <img class="w-full h-full object-cover" 
                     src="{{ $user->profile_banner ? Storage::url($user->profile_banner) : asset('assets/profile_banner.jpg') }}" 
                     alt="Profile Banner">
                <div class="absolute inset-0 bg-gradient-to-b from-transparent via-black/20 to-black/40"></div>
                
                <!-- Profile Image & Name Overlay -->
                <div class="absolute -bottom-16 left-8 flex items-end space-x-6">
                    <div class="relative">
                        <img class="w-32 h-32 rounded-xl border-4 border-white shadow-xl object-cover" 
                             src="{{ $user->profile_image ? Storage::url($user->profile_image) : asset('assets/default-avatar.png') }}" 
                             alt="Profile Image">
                        <div class="absolute -top-2 -right-2 w-6 h-6 rounded-full flex items-center justify-center {{ $user->status == 1 ? 'bg-green-500' : 'bg-red-500' }} border-2 border-white">
                            <i class="fas fa-{{ $user->status == 1 ? 'check' : 'times' }} text-white text-xs"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="pt-20 px-8 pb-8">
                <!-- User Basic Info -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $user->complete_name }}</h1>
                        <div class="mt-2 flex items-center space-x-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                {{ $user->role == 0 ? 'bg-purple-100 text-purple-800' :
                                   ($user->role == 1 ? 'bg-blue-100 text-blue-800' :
                                   ($user->role == 2 ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800')) }}">
                                <i class="fas fa-{{ $user->role == 0 ? 'shield-alt' : 
                                                   ($user->role == 1 ? 'user' : 
                                                   ($user->role == 2 ? 'user-md' : 'user-nurse')) }} mr-2"></i>
                                @switch($user->role)
                                    @case(0) Admin @break
                                    @case(1) Animal Owner @break
                                    @case(2) Veterinarian @break
                                    @case(3) Veterinary Receptionist @break
                                    @default Unknown
                                @endswitch
                            </span>
                            <span class="text-gray-500">•</span>
                            <span class="text-gray-600">{{ $user->email }}</span>
                        </div>
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Personal Information -->
                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <i class="fas fa-user text-blue-600"></i>
                            </div>
                            <h3 class="font-semibold text-gray-900">Personal Information</h3>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-venus-mars w-6 text-gray-400"></i>
                                <span class="ml-2">{{ $user->gender }}</span>
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-birthday-cake w-6 text-gray-400"></i>
                                <span class="ml-2">{{ $user->birth_date }}</span>
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-phone w-6 text-gray-400"></i>
                                <span class="ml-2">{{ $user->contact_no }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Address Information -->
                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <i class="fas fa-map-marker-alt text-green-600"></i>
                            </div>
                            <h3 class="font-semibold text-gray-900">Address Details</h3>
                        </div>
                        <p class="text-gray-600 leading-relaxed">
                            {{ $user->address->street ?? 'No street information' }},
                            {{ $user->address->barangay->barangay_name ?? 'No barangay information' }}
                        </p>
                    </div>

                    <!-- Account Status -->
                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="p-2 bg-yellow-100 rounded-lg">
                                <i class="fas fa-shield-alt text-yellow-600"></i>
                            </div>
                            <h3 class="font-semibold text-gray-900">Account Status</h3>
                        </div>
                        <div class="flex items-center">
                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                {{ $user->status == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                @switch($user->status)
                                    @case(1) Active @break
                                    @case(2) Disabled @break
                                    @default Unknown
                                @endswitch
                            </span>
                        </div>
                    </div>

                    </div>
                </div>

                <!-- Animal Owner Specific Info -->
                @if ($user->role == 1)
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="p-2 bg-pink-100 rounded-lg">
                                    <i class="fas fa-heart text-pink-600"></i>
                                </div>
                                <h3 class="font-semibold text-gray-900">Civil Status</h3>
                            </div>
                            <p class="text-gray-600">{{ $user->owner->civil_status ?? 'N/A' }}</p>
                        </div>

                        
                    <!-- Categories -->
                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="p-2 bg-indigo-100 rounded-lg">
                                <i class="fas fa-tags text-indigo-600"></i>
                            </div>
                            <h3 class="font-semibold text-gray-900">Categories</h3>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @if($user->categories && $user->categories->count() > 0)
                                @foreach($user->categories as $category)
                                    @php
                                        $colorClasses = [
                                            1 => 'bg-blue-100 text-blue-800',
                                            2 => 'bg-green-100 text-green-800',
                                            3 => 'bg-purple-100 text-purple-800',
                                            4 => 'bg-yellow-100 text-yellow-800',
                                            5 => 'bg-pink-100 text-pink-800',
                                            6 => 'bg-indigo-100 text-indigo-800',
                                            7 => 'bg-red-100 text-red-800',
                                            'default' => 'bg-gray-100 text-gray-800'
                                        ];
                                        $classes = $colorClasses[$category->id] ?? $colorClasses['default'];
                                    @endphp
                                    
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $classes }}">
                                        {{ $category->name }}
                                    </span>
                                @endforeach
                            @else
                                <span class="text-gray-500">No categories assigned</span>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="mt-8 flex justify-end space-x-4">
                    <a href="/admin/users" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Users List
                    </a>
                    <a href="{{ route('users.edit-form', ['id' => $user->user_id]) }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
