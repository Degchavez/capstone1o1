<x-app-layout>
    <div>
        <!-- Page Wrapper -->
        <div class="min-h-screen bg-gray-50">
            <div class="max-w-[95%] mx-auto py-8">
                <!-- Alert Messages -->
                @if (session()->has('message'))
                    <div class="mb-4 flex items-center p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg" role="alert">
                        <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-green-800">{{ session('message') }}</span>
                    </div>
                @endif
                
                @if (session()->has('error'))
                    <div class="mb-4 flex items-center p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg" role="alert">
                        <svg class="h-5 w-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span class="text-red-800">{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Page Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">
                        Users Management
                    </h1>
                    <p class="mt-2 text-sm text-gray-600">
                        Manage and monitor all users in the system
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <a href="/admin/create/users" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Add User
                        </a>
                        <a href="{{ route('admin-users') }}" class="inline-flex items-center px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Reset Filters
                        </a>
                    </div>
                </div>

                <!-- Filters Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6">
                    <form method="GET" action="{{ route('admin-users') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4" id="filter-form">
                        <!-- Search -->
                        <div class="col-span-1 sm:col-span-2 xl:col-span-1">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Search</label>
                            <div class="relative">
                                <input type="text" name="search" 
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                       placeholder="Name, email, or contact" 
                                       value="{{ request('search') }}">
                                <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Role -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Role</label>
                            <select name="role" class="w-full border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500" onchange="this.form.submit()">
                                <option value="">All Roles</option>
                                <option value="zero" {{ request('role') === 'zero' ? 'selected' : '' }}>Admin</option>
                                <option value="1" {{ request('role') === '1' ? 'selected' : '' }}>Animal Owner</option>
                                <option value="2" {{ request('role') === '2' ? 'selected' : '' }}>Veterinarian</option>
                                <option value="3" {{ request('role') === '3' ? 'selected' : '' }}>Receptionist</option>
                            </select>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" class="w-full border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500" onchange="this.form.submit()">
                                <option value="">All Statuses</option>
                                <option value="zero" {{ request('status') === 'zero' ? 'selected' : '' }}>Pending</option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                                <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>Disabled</option>
                            </select>
                        </div>

                        <!-- Gender -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Gender</label>
                            <select name="gender" class="w-full border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500" onchange="this.form.submit()">
                                <option value="">All Genders</option>
                                <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>

                        <!-- Date Range -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">From Date</label>
                            <input type="date" name="fromDate" 
                                   class="w-full border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                   value="{{ request('fromDate') }}"
                                   onchange="this.form.submit()">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">To Date</label>
                            <input type="date" name="toDate" 
                                   class="w-full border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                   value="{{ request('toDate') }}"
                                   onchange="this.form.submit()">
                        </div>
                    </form>
                </div>

                <!-- Users Table -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact Info</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($users as $user)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full object-cover" 
                                                         src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('assets/default-avatar.png') }}" 
                                                         alt="">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <a href="{{ $user->user_id === auth()->user()->user_id 
                                                            ? route('users.nav-profile', ['id' => auth()->user()->user_id]) 
                                                            : route('users.profile-form', $user->user_id) }}" 
                                                           class="hover:text-green-600 transition-colors duration-200">
                                                            {{ $user->complete_name }}
                                                        </a>
                                                    </div>
                                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                @php
                                                    $roles = [
                                                        0 => 'Admin',
                                                        1 => 'Animal Owner',
                                                        2 => 'Veterinarian',
                                                        3 => 'Receptionist'
                                                    ];
                                                @endphp
                                                {{ $roles[$user->role] ?? 'Unknown' }}
                                            </div>
                                            @if ($user->role === 2 && $user->designation)
                                                <div class="text-sm text-gray-500">{{ $user->designation->name }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $user->contact_no }}</div>
                                            <div class="text-sm text-gray-500">
                                                {{ $user->address && $user->address->barangay ? $user->address->barangay->barangay_name : 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $user->status === 0 ? 'bg-yellow-100 text-yellow-800' : 
                                                   ($user->status === 1 ? 'bg-green-100 text-green-800' : 
                                                   'bg-red-100 text-red-800') }}">
                                                @php
                                                    $status = [0 => 'Pending', 1 => 'Active', 2 => 'Disabled'];
                                                @endphp
                                                {{ $status[$user->status] ?? 'Unknown' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            @if ($user->user_id !== auth()->id())
                                                <div class="flex items-center space-x-2">
                                                    <form action="{{ route('users.reset-password', $user->user_id) }}" 
                                                          method="POST" 
                                                          class="inline-block" 
                                                          onsubmit="return confirmReset()">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="text-yellow-600 hover:text-yellow-900 transition-colors duration-200"
                                                                title="Reset Password">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                            </svg>
                                                        </button>
                                                    </form>

                                                    <a href="{{ route('users.edit-form', $user->user_id) }}" 
                                                       class="text-blue-600 hover:text-blue-900 transition-colors duration-200"
                                                       title="Edit User">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                    </a>

                                                    <form action="{{ route('users.destroy', $user->user_id) }}" 
                                                          method="POST" 
                                                          class="inline-block" 
                                                          onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="text-red-600 hover:text-red-900 transition-colors duration-200"
                                                                title="Delete User">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                <span class="text-gray-400">No actions available</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 text-center">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                </svg>
                                                <p class="text-gray-500 text-sm">No users found</p>
                                                <p class="text-gray-400 text-xs mt-1">Try adjusting your search filters</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="mt-5">
                    {{ $users->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmReset() {
            return confirm("Are you sure you want to reset this user's password?");
        }
    </script>
</x-app-layout>
