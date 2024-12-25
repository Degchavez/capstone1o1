<x-app-layout>
    <div>
        <!-- Page Wrapper -->
        <div class="max-w-full px-6 py-6 mx-auto">
            <!-- Success Message -->
            @if (session()->has('message'))
                <div class="mt-4 bg-green-100 border border-green-400 text-green-800 text-sm rounded-lg p-4" role="alert">
                    <span class="font-semibold">Success:</span> {{ session('message') }}
                </div>
            @endif

            <!-- Page Header -->
            <div class="text-center mt-8">
                <h2 class="text-3xl font-bold text-gray-800 dark:text-white">
                    <span style="color: #006400;">Users</span> Management
                </h2>
                <p class="text-lg text-gray-500 dark:text-gray-300 mt-2">
                    Add, edit, or manage users from this section.
                </p>
            </div>
            
            <br>

            <!-- Add User and Reset Button Section -->
            <div class="flex items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-4">
                    <!-- Add User Button aligned to the left -->
                    <a href="/admin/create/users" class="bg-green-600 text-white text-sm font-semibold px-6 py-2 rounded-lg hover:bg-green-700 focus:outline-none shadow-md transition duration-200">
                        + Add User
                    </a>
                    <a href="{{ route('admin-users') }}" class="bg-gray-300 text-gray-800 text-sm font-semibold px-6 py-2 rounded-lg hover:bg-gray-400 focus:outline-none shadow-md transition duration-200">
                        Reset
                    </a>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="flex flex-wrap justify-between gap-2 items-center mb-4">
                <!-- Filter Form -->
                <form method="GET" action="{{ route('admin-users') }}" class="flex flex-wrap items-center gap-6 w-full sm:w-auto" id="filter-form">
                    <div class="flex items-center gap-4">
                        <input type="text" name="search" class="form-input w-52 border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-800 dark:text-neutral-200 shadow-sm hover:border-gray-400 transition-all" placeholder="Search by name, email, or contact" value="{{ request('search') }}" onchange="this.form.submit()">
                    </div>
                    <div class="flex items-center gap-4">
                        <select name="gender" class="form-select w-40 border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-800 dark:text-neutral-200 shadow-sm hover:border-gray-400 transition-all" onchange="this.form.submit()">
                            <option value="">All Genders</option>
                            <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-4">
                        <select name="role" class="form-select w-40 border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-800 dark:text-neutral-200 shadow-sm hover:border-gray-400 transition-all" onchange="this.form.submit()">
                            <option value="">All Roles</option>
                            <option value="zero" {{ request('role') === 'zero' ? 'selected' : '' }}>Admin</option>
                            <option value="1" {{ request('role') === '1' ? 'selected' : '' }}>Animal Owner</option>
                            <option value="2" {{ request('role') === '2' ? 'selected' : '' }}>Veterinarian</option>
                            <option value="3" {{ request('role') === '3' ? 'selected' : '' }}>Veterinary Receptionist</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-4">
                        <select name="status" class="form-select w-40 border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-800 dark:text-neutral-200 shadow-sm hover:border-gray-400 transition-all {{ request('status') === 'zero' ? 'bg-yellow-200' : (request('status') === '1' ? 'bg-green-200' : (request('status') === '2' ? 'bg-red-200' : '')) }}" onchange="this.form.submit()">
                            <option value="">All Statuses</option>
                            <option value="zero" {{ request('status') === 'zero' ? 'selected' : '' }} class="bg-yellow-200">Pending</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }} class="bg-green-200">Active</option>
                            <option value="2" {{ request('status') === '2' ? 'selected' : '' }} class="bg-red-200">Disabled</option>
                        </select>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <input type="date" name="fromDate" class="form-input w-40 border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-800 dark:text-neutral-200 shadow-sm hover:border-gray-400 transition-all" value="{{ request('fromDate') }}" onchange="this.form.submit()">
                    </div>
                    TO
                    <div class="flex items-center gap-2">
                        <input type="date" name="toDate" class="form-input w-40 border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-800 dark:text-neutral-200 shadow-sm hover:border-gray-400 transition-all" value="{{ request('toDate') }}" onchange="this.form.submit()">
                    </div>
                    <div class="flex items-center gap-2">
                        <select name="barangay_id" class="form-select w-40 border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-800 dark:text-neutral-200 shadow-sm hover:border-gray-400 transition-all" onchange="this.form.submit()">
                            <option value="">All Barangays</option>
                            @foreach ($barangays as $barangay)
                                <option value="{{ $barangay->id }}" {{ request('barangay_id') == $barangay->id ? 'selected' : '' }}>
                                    {{ $barangay->barangay_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>

                <!-- Users Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto bg-white dark:bg-neutral-800 border border-gray-300 rounded-lg shadow-md">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-neutral-900 text-gray-600 dark:text-gray-300">
                                <th class="px-6 py-3 text-left text-sm font-semibold">Profile</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold">Name</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold">Position</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold">Address</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold">Contact</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold">Gender</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold">Birthdate</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold">Status</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold">Email</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold">Password</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-300 dark:divide-gray-700">
                            @if (count($users) > 0)
                                @foreach ($users as $user)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-4 py-3">
                                            <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('assets/default-avatar.png') }}" class="w-12 h-12 rounded-full" alt="Profile">
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                                            <b>
                                                <a 
                                                    href="{{ $user->user_id === auth()->user()->user_id 
                                                        ? route('users.nav-profile', ['id' => auth()->user()->user_id]) 
                                                        : route('users.profile-form', $user->user_id) }}" 
                                                    class="text-blue-500 hover:text-blue-700">
                                                    {{ $user->complete_name }}
                                                </a>
                                            </b>
                                        </td>
                                        

                                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                                            @php
                                                $roles = [
                                                    0 => 'Admin',
                                                    1 => 'Animal Owner',
                                                    2 => 'Veterinarian',
                                                    3 => 'Veterinary Receptionist',
                                                ];
                                                $roleName = $roles[$user->role] ?? 'Unknown';
                                            @endphp
                                            
                                            {{ $roleName }}
                                        
                                            @if ($user->role === 2 && $user->designation) 
                                                <!-- Display the designation if the user is a Veterinarian -->
                                                - {{ $user->designation->name }}
                                            @endif
                                        </td>
                                        
                                        <td>
                                            <!-- Display Barangay Name from Address -->
                                            @if ($user->address && $user->address->barangay)
                                                {{ $user->address->barangay->barangay_name }}
                                            @else
                                                N/A
                                            @endif
                                        </td>

                                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $user->contact_no }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ ucfirst($user->gender) }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $user->birth_date->format('F j, Y') }}</td>
                                        <td class="px-4 py-3 text-sm {{ 
                                            $user->status === 0 ? 'text-yellow-500' : 
                                            ($user->status === 1 ? 'text-green-500' : 
                                            ($user->status === 2 ? 'text-red-500' : 'text-gray-500')) }} dark:text-gray-300">
                                            @php
                                                $status = [
                                                    0 => 'Pending',
                                                    1 => 'Active',
                                                    2 => 'Disabled',
                                                ];
                                                $statusName = $status[$user->status] ?? 'Unknown';
                                            @endphp
                                            {{ $statusName }}
                                        </td>
                                        
                                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $user->email }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                                         <!-- Reset Password Button -->
                                         <form action="{{ route('users.reset-password', $user->user_id) }}" method="POST" class="inline-block" onsubmit="return confirmReset()">
                                            @csrf
                                            <button type="submit" 
                                                    class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-opacity-50 rounded-lg shadow-md transition-all duration-200 transform hover:scale-105">
                                                <!-- Reset Icon -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 20v-8m0 0H4m8 0h8M12 4v8" />
                                                </svg>
                                                Reset
                                            </button>
                                        </form>
                                        
                                        
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <div class="flex flex-col space-y-2">
                                            @if ($user->user_id !== auth()->id())
                                                <!-- Update Button at the top -->
                                                <a href="{{ route('users.edit-form', $user->user_id) }}" 
                                                   class="inline-flex items-center px-2 py-1 text-xs font-semibold text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                                                    </svg>
                                                    Update
                                                </a>
                                    
                                                <!-- Delete Button at the bottom -->
                                                <form action="{{ route('users.destroy', $user->user_id) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                        class="inline-flex items-center px-2 py-1 text-xs font-semibold text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 transition duration-200"
                                                        onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4m16 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        Delete
                                                    </button>
                                                </form>
                                            @else
                                                <!-- Placeholder or message for own profile -->
                                                <span class="text-gray-500">No actions available</span>
                                            @endif
                                        </div>
                                    </td>
                                    
                                    
                                        
                                        
                                        
                                        
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="10" class="px-4 py-3 text-center text-gray-500">No users found.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $users->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
   <!-- JavaScript for Confirmation -->
<script>
    function confirmReset() {
        return confirm("Are you sure you want to reset this user's password?");
    }
</script>

</x-app-layout>
