<x-app-layout>
    <div class="text-center mt-8">
        <h2 class="text-3xl font-bold text-gray-800 dark:text-white">
            <span style="color: #006400;">Veterinarians</span> Management
        </h2>
        <p class="text-lg text-gray-500 dark:text-gray-300 mt-2">
            Add, edit, or manage veterinarians from this section.
        </p>
    </div>
      <!-- Add Button -->

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
                                <form method="GET" action="{{ route('admin-veterinarians') }}" class="flex flex-wrap gap-3 items-center">
                                    <input 
                                        type="text" 
                                        name="search" 
                                        value="{{ request('search') }}" 
                                        class="py-2 px-4 text-sm border border-gray-300 rounded-lg dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200" 
                                        placeholder="Search Veterinarians..." 
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

                                    <!-- Barangay Dropdown -->
                               <!-- Designation Dropdown -->
<select name="designation" id="designationFilter" onchange="this.form.submit()" class="px-4 py-2 rounded-lg border border-gray-300 dark:bg-neutral-800 dark:border-neutral-600 dark:text-neutral-200 w-48">
    <option value="">Select All Designations</option>
    @foreach ($designations as $designation)
        <option value="{{ $designation->designation_id }}" {{ request('designation') == $designation->designation_id ? 'selected' : '' }}>
            {{ $designation->name }}
        </option>
    @endforeach
</select>

                                    

                                    <div class="flex items-center gap-4">
                                        <input type="date" name="fromDate" class="form-input w-40 border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-800 dark:text-neutral-200 shadow-sm hover:border-gray-400 transition-all" value="{{ request('fromDate') }}" onchange="this.form.submit()">
                                    </div>
                                    <h5><b>TO</b></h5>
                                    <div class="flex items-center gap-4">
                                        <input type="date" name="toDate" class="form-input w-40 border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-800 dark:text-neutral-200 shadow-sm hover:border-gray-400 transition-all" value="{{ request('toDate') }}" onchange="this.form.submit()">
                                    </div
                                    
                                    <!-- Reset Button -->
                                   <a 
                                        href="{{ route('admin-veterinarians') }}" 
                                        class="py-2 px-4 inline-flex items-center gap-x-2 text-xs font-medium rounded-lg border border-transparent bg-gray-600 text-white hover:bg-gray-700 focus:outline-none"
                                    >
                                        Reset 
                                    </a> 
                                  
                                        <a href="{{ route('veterinarians.create') }}" class="bg-green-600 text-white text-sm font-semibold px-6 py-2 rounded-lg hover:bg-green-700 focus:outline-none shadow-md transition duration-200">
                                            Add Veterinarian
                                        </a>
                             
                                </form>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table id="vetTable" class="min-w-full table-auto divide-y divide-gray-200 dark:divide-neutral-700 table-fixed">
                                <thead class="bg-gray-50 dark:bg-neutral-800">
                                    <tr>
                                        <th class="px-9 py-3"></th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold uppercase tracking-wider">Complete Name</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold uppercase tracking-wider">Designation</th>

                                        <th class="px-4 py-3 text-left text-sm font-semibold uppercase tracking-wider">Contact #</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold uppercase tracking-wider">Gender</th>
                              
                                        <th class="px-4 py-3 text-left text-sm font-semibold uppercase tracking-wider">Transactions</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold uppercase tracking-wider">Date Created</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-neutral-700 divide-y divide-gray-200 dark:divide-neutral-600">
                                    @forelse ($veterinarians as $vet)
                                        <tr>
                                            <!-- Profile Image -->
                                            <td class="px-4 py-3 text-sm">
                                                <img 
                                                    src="{{ $vet->profile_image ? asset('storage/' . $vet->profile_image) : asset('assets/default-avatar.png') }}" 
                                                    alt="{{ $vet->complete_name }}" 
                                                    class="w-8 h-8 rounded-full"
                                                />
                                            </td>

                                            <!-- Name -->
                                            <td class="px-4 py-3 text-sm">
                                                <strong><a href="{{ route('admin.veterinarian.profile', $vet->user_id) }}" class="text-blue-500 hover:underline">
                                                    {{ $vet->complete_name }}
                                                </a></strong>
                                            </td>
                                            
                                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                                                <strong> {{ $vet->designation_name ?? 'No designation' }}</strong>
                                            </td>
                                         

                                            <!-- Contact -->
                                            <td class="px-4 py-3 text-sm">
                                                {{ $vet->contact_no }}
                                            </td>

                                            <!-- Gender -->
                                            <td class="px-4 py-3 text-sm">
                                                {{ ucfirst($vet->gender) }}
                                            </td>

                                            <!-- Transactions -->
                                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-neutral-400">
                                                <div class="flex items-center">
                                                    <span class="font-semibold text-gray-700 dark:text-neutral-300">
                                                        {{ $vet->transaction_count }} <!-- Access the transaction count from the query -->
                                                    </span>
                                                    <span class="ml-2 text-xs text-gray-500 dark:text-neutral-500">
                                                        transactions
                                                    </span>
                                                </div>
                                            </td>
                                            

                                            <!-- Date Created -->
                                            <td class="px-4 py-3 text-sm">
                                                {{ \Carbon\Carbon::parse($vet->created_at)->format('m/d/Y') }}
                                            </td>
                                            

                                            <!-- Actions -->
                                            <!-- Actions -->
                                            <td class="px-4 py-3 text-sm">
                                                <div class="flex space-x-4">
                                                    <!-- Edit Button -->
                                                    <a href="{{ route('admin-veterinarians.edit', $vet->user_id) }}" 
                                                        class="px-4 py-2 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75 transition duration-200">
                                                        Edit
                                                    </a>
                                            
                                                    <!-- Delete Button -->
                                                    <form action="{{ route('admin-veterinarians.destroy', $vet->user_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this veterinarian?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                            class="px-4 py-2 bg-red-500 text-white rounded-lg shadow-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-opacity-75 transition duration-200">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                            
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="px-4 py-3 text-center text-gray-500">
                                                No veterinarians found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $veterinarians->appends(request()->query())->links() }}
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

        document.getElementById('fromDate').addEventListener('change', function() {
            this.form.submit();
        });

        document.getElementById('toDate').addEventListener('change', function() {
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
