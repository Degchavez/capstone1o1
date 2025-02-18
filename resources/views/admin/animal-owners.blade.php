<x-app-layout>
    <div class="text-center mt-8">
        <h2 class="text-3xl font-bold text-gray-800 dark:text-white">
            <span style="color: #006400;">Owners</span> Management
        </h2>
        <p class="text-lg text-gray-500 dark:text-gray-300 mt-2">
            Add, edit, or manage users from this section.
        </p>
    </div>
    @if (session()->has('message'))
    <div class="mt-4 bg-green-100 border border-green-400 text-green-800 text-sm rounded-lg p-4" role="alert">
        <span class="font-semibold">Success:</span> {{ session('message') }}
    </div>
@endif

        <!-- Table Section -->
        <div class="max-w-[100%] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
          

            <div class="flex flex-col w-full">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="p-1.5 min-w-full inline-block align-middle">
                        <div class="bg-white border border-gray-300 rounded-xl shadow-lg overflow-hidden dark:bg-neutral-800 dark:border-neutral-700">
                            <!-- Header -->
                            <div class="px-6 py-4 grid gap-4 md:flex md:justify-between md:items-center border-b border-gray-200 dark:border-neutral-700">
                                
                                <div class="flex space-x-3 items-center">
                                    <!-- Search Bar -->
                                    <form method="GET" action="{{ route('admin-owners') }}" class="flex flex-wrap gap-3 items-center">
                                        <input 
                                            type="text" 
                                            name="search" 
                                            value="{{ request('search') }}" 
                                            class="py-2 px-4 text-sm border border-gray-300 rounded-lg dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200" 
                                            placeholder="Search Owners..." 
                                            id="searchInput"
                                        />
                                      
                                        <!-- Gender Filter -->
                                        <select 
                                            name="gender" 
                                            class="py-2 px-4 text-sm border border-gray-300 rounded-lg dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200"
                                            id="genderFilter"
                                        >
                                            <option value="" {{ request('gender') == '' ? 'selected' : '' }}>All Genders</option>
                                            <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                    
                                        <!-- Civil Status Filter -->
                                        <select 
                                            name="civil_status" 
                                            class="py-2 px-4 text-sm border border-gray-300 rounded-lg dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200"
                                            id="civilStatusFilter"
                                        >
                                            <option value="" {{ request('civil_status') == '' ? 'selected' : '' }}>All Civil Status</option>
                                            <option value="single" {{ request('civil_status') == 'single' ? 'selected' : '' }}>Single</option>
                                            <option value="married" {{ request('civil_status') == 'married' ? 'selected' : '' }}>Married</option>
                                            <option value="widowed" {{ request('civil_status') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                            <option value="divorced" {{ request('civil_status') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                        </select>

                                       <!-- Category Filter -->

                                       <select 
                                       name="category" 
                                       class="py-2 px-4 text-sm border border-gray-300 rounded-lg dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200"
                                       id="categoryFilter"
                                   >
                                       <option value="" {{ request('category') == '' ? 'selected' : '' }}>All Categories</option>
                                       <option value="N/A" {{ request('category') == 'N/A' ? 'selected' : '' }}>N/A</option>
                                       <option value="indigenous people" {{ request('category') == 'indigenous people' ? 'selected' : '' }}>Indigenous People</option>
                                       <option value="senior" {{ request('category') == 'senior' ? 'selected' : '' }}>Senior</option>
                                       <option value="single parent" {{ request('category') == 'single parent' ? 'selected' : '' }}>Single Parent</option>
                                       <option value="pregnant" {{ request('category') == 'pregnant' ? 'selected' : '' }}>Pregnant</option>
                                       <option value="person with disability" {{ request('category') == 'person with disability' ? 'selected' : '' }}>Person With Disability</option>
                                       <option value="lactating mother" {{ request('category') == 'lactating mother' ? 'selected' : '' }}>Lactating Mother</option>
                                       <option value="LGBT" {{ request('category') == 'LGBT' ? 'selected' : '' }}>LGBT</option>


                                                                    </select>

                                      <!-- Barangay Dropdown -->
                    <select name="barangay" id="barangayFilter" onchange="this.form.submit()" class="px-4 py-2 rounded-lg border border-gray-300 dark:bg-neutral-800 dark:border-neutral-600 dark:text-neutral-200 w-48">
                        <option value="">Select All Barangay</option>
                        @foreach ($barangays as $barangay)
                            <option value="{{ $barangay->id }}" {{ request('barangay') == $barangay->id ? 'selected' : '' }}>
                                {{ $barangay->barangay_name }}
                            </option>
                        @endforeach
                    </select>


                                          
                    <div class="flex items-center gap-4">
                        <input type="date" name="fromDate" class="form-input w-40 border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-800 dark:text-neutral-200 shadow-sm hover:border-gray-400 transition-all" value="{{ request('fromDate') }}" onchange="this.form.submit()">
                    </div>
                    <h5><b>TO</b></h5>
                    <div class="flex items-center gap-4">
                        <input type="date" name="toDate" class="form-input w-40 border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-800 dark:text-neutral-200 shadow-sm hover:border-gray-400 transition-all" value="{{ request('toDate') }}" onchange="this.form.submit()">
                    </div>

                                       
                    <a href="{{ route('register-owner') }}" class="bg-green-600 text-white text-sm font-semibold px-6 py-2 rounded-lg hover:bg-green-700 focus:outline-none shadow-md transition duration-200">
                        Add
                    </a>
                    
                                        <!-- Reset Button -->
                                        <a 
                                            href="{{ route('admin-owners') }}" 
                                            class="py-2 px-4 inline-flex items-center gap-x-2 text-xs font-medium rounded-lg border border-transparent bg-gray-600 text-white hover:bg-gray-700 focus:outline-none"
                                        >
                                            Reset 

                                            
                                        </a>
                                        
                                    </form>
                                </div>
                            </div>
                            
                            <!-- Table -->
                            <div class="overflow-x-auto">
                                <table id="ownerTable" class="min-w-full table-auto divide-y divide-gray-200 dark:divide-neutral-700 table-fixed">
                                    <thead class="bg-gray-50 dark:bg-neutral-800">
                                        <tr>
                                            <th class="px-9 py-3"></th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold uppercase tracking-wider">Complete Name</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold uppercase tracking-wider">Address</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold uppercase tracking-wider">Contact #</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold uppercase tracking-wider">Gender</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold uppercase tracking-wider">Birthdate</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold uppercase tracking-wider">Age</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold uppercase tracking-wider">Civil Status</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold uppercase tracking-wider">Category</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold uppercase tracking-wider">Animals</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold uppercase tracking-wider">Transactions</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold uppercase tracking-wider">Date Created</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-neutral-700 divide-y divide-gray-200 dark:divide-neutral-600">
                                        @forelse ($owners as $owner)
                                            <tr>
                                                <!-- Profile Image -->
                                                <td class="px-4 py-3 text-sm">
                                                    <img 
                                                        src="{{ $owner->profile_image ? asset('storage/' . $owner->profile_image) : asset('assets/default-avatar.png') }}" 
                                                        alt="{{ $owner->complete_name }}" 
                                                        class="w-8 h-8 rounded-full"
                                                    />
                                                </td>

                                                <!-- Name -->
                                                <td class="px-4 py-3 text-sm">
                                                    <a href="{{ route('owners.profile-owner', $owner->owner_id) }}" class="text-blue-500 hover:text-blue-700 font-bold">
                                                        {{ $owner->complete_name }}
                                                    </a>
                                                </td>

                                                <!-- Address -->
                                                <td class="px-4 py-3 text-sm">
                                                    {{ $owner->street ?? 'N/A' }}, {{ $owner->barangay_name ?? 'N/A' }}
                                                </td>

                                                <!-- Contact -->
                                                <td class="px-4 py-3 text-sm">
                                                    {{ $owner->contact_no }}
                                                </td>

                                                <!-- Gender -->
                                                <td class="px-4 py-3 text-sm">
                                                    {{ ucfirst($owner->gender) }}
                                                </td>

                                                <!-- Birthdate -->
                                                <td class="px-4 py-3 text-sm">
                                                    {{ \Carbon\Carbon::parse($owner->birth_date)->format('m/d/Y') }}
                                                </td>

                                                <!-- Age -->
                                                <td class="px-4 py-3 text-sm">
                                                    {{ \Carbon\Carbon::parse($owner->birth_date)->age }}
                                                </td>

                                                <!-- Civil Status -->
                                                <td class="px-4 py-3 text-sm">
                                                    {{ ucfirst($owner->civil_status) }}
                                                </td>

                                                <!-- Category -->
                                                <td class="px-4 py-3 text-sm">
                                                    {{ $owner->category }}
                                                </td>

                                                <!-- Animals -->
                                                <td class="px-4 py-3 text-sm text-gray-600 dark:text-neutral-400">
                                                    <div class="flex items-center">
                                                        <span class="font-semibold text-gray-700 dark:text-neutral-300">
                                                            {{ $owner->animals->count() }}
                                                        </span>
                                                        <span class="ml-2 text-xs text-gray-500 dark:text-neutral-500">
                                                            animals
                                                        </span>
                                                    </div>
                                                </td>
                                                
                                                <td class="px-4 py-3 text-sm text-gray-600 dark:text-neutral-400">
                                                    <div class="flex items-center">
                                                        <span class="font-semibold text-gray-700 dark:text-neutral-300">
                                                            {{ $owner->transactions->count() }}
                                                        </span>
                                                        <span class="ml-2 text-xs text-gray-500 dark:text-neutral-500">
                                                            transactions
                                                        </span>
                                                    </div>
                                                </td>

                                                <!-- Date Created -->
                                                <td class="px-4 py-3 text-sm">
                                                    {{ \Carbon\Carbon::parse($owner->created_at)->format('m/d/Y') }}
                                                </td>

                                                <!-- Actions -->
                                                <td class="px-4 py-3 text-sm">
                                                    <div class="flex flex-col space-y-2">
                                                        <!-- Update Button at the top -->
                                                        <a href="{{ route('ownerList.edit', $owner->user_id) }}"
                                                           class="inline-flex items-center px-2 py-1 text-xs font-semibold text-blue-600 bg-blue-100 rounded-md hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-200">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            Update
                                                        </a>
                                                
                                                        <!-- Delete Button at the bottom -->
                                                        <form action="{{ route('users.destroy', $owner->user_id) }}" method="POST" class="inline-block">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="inline-flex items-center px-2 py-1 text-xs font-semibold text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 transition duration-200"
                                                                onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4m16 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                                
                                                
                                                    
                                         
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="13" class="px-4 py-3 text-center text-gray-500">
                                                    No owners found.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                                    <!-- Pagination -->
                <div class="mt-4">
                    {{ $owners->appends(request()->query())->links() }}
                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Auto Submitting Forms -->
    <script>
        document.getElementById('genderFilter').addEventListener('change', function() {
            this.form.submit();
        });

        document.getElementById('civilStatusFilter').addEventListener('change', function() {
            this.form.submit();
        });

        document.getElementById('categoryFilter').addEventListener('change', function() {
            this.form.submit();
        });

        document.getElementById('fromDate').addEventListener('change', function() {
            this.form.submit();

        });  document.getElementById('toDate').addEventListener('change', function() {
            this.form.submit();
        });

        document.getElementById('barangayFilter').addEventListener('change', function() {
    this.form.submit();
});
        document.getElementById('searchInput').addEventListener('input', function() {
            this.form.submit();
        });
    </script>
</x-app-layout>
