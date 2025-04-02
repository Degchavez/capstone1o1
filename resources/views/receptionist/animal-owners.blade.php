<x-app-layout>
    <!-- Main Container -->
    <div class="min-h-screen bg-gray-50 py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-4">
                <h1 class="text-2xl font-semibold text-gray-900">Owner Management</h1>
                <p class="mt-1 text-sm text-gray-600">View and manage all registered pet owners in the system.</p>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Owners</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $owners->total() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Bar and Filters -->
            <div class="bg-white rounded-lg shadow-sm mb-4">
                <div class="p-4">
                    <!-- Search and Add Owner Button -->
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <div class="flex-1">
                            <form method="GET" action="{{ route('rec-owners') }}" id="searchForm">
                                <div class="relative">
                                    <input type="text" 
                                        name="search" 
                                        id="searchInput"
                                        value="{{ request('search') }}"
                                        class="block w-full rounded-md border-gray-300 pl-10 pr-3 py-2 text-sm focus:border-green-500 focus:ring-green-500"
                                        placeholder="Search owners...">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                                <!-- Preserve other filter values in hidden inputs -->
                                <input type="hidden" name="gender" value="{{ request('gender') }}">
                                <input type="hidden" name="civil_status" value="{{ request('civil_status') }}">
                                <input type="hidden" name="barangay" value="{{ request('barangay') }}">
                                <input type="hidden" name="category" value="{{ request('category') }}">
                                <input type="hidden" name="fromDate" value="{{ request('fromDate') }}">
                                <input type="hidden" name="toDate" value="{{ request('toDate') }}">
                            </form>
                        </div>
                        <a href="{{ route('reg-owner') }}"
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700">
                            <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Owner
                        </a>
                    </div>

                    <!-- Filters Section -->
                    <form method="GET" action="{{ route('rec-owners') }}" class="border-t border-gray-200 pt-3">
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
                            <div>
                                <select name="gender" id="genderFilter" class="block w-full rounded-md border-gray-300 text-sm focus:border-green-500 focus:ring-green-500">
                                    <option value="">All Genders</option>
                                    <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>

                            <div>
                                <select name="civil_status" id="civilStatusFilter" class="block w-full rounded-md border-gray-300 text-sm focus:border-green-500 focus:ring-green-500">
                                    <option value="">All Civil Status</option>
                                    <option value="single" {{ request('civil_status') == 'single' ? 'selected' : '' }}>Single</option>
                                    <option value="married" {{ request('civil_status') == 'married' ? 'selected' : '' }}>Married</option>
                                    <option value="widowed" {{ request('civil_status') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                    <option value="divorced" {{ request('civil_status') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                </select>
                            </div>

                            <div>
                                <select name="barangay" id="barangayFilter" class="block w-full rounded-md border-gray-300 text-sm focus:border-green-500 focus:ring-green-500">
                                    <option value="">All Barangays</option>
                                    @foreach ($barangays as $barangay)
                                        <option value="{{ $barangay->id }}" {{ request('barangay') == $barangay->id ? 'selected' : '' }}>
                                            {{ $barangay->barangay_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <select name="category" id="categoryFilter" class="block w-full rounded-md border-gray-300 text-sm focus:border-green-500 focus:ring-green-500">
                                <option value="zero">All Categories</option>
                                    @foreach($categories as $category)
                                
                                        <option value="{{ $category->id }}" {{ (string)request('category') === (string)$category->id ? 'selected' : '' }}>
                                            {{ ucfirst($category->name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <input type="date" name="fromDate" value="{{ request('fromDate') }}" 
                                    class="block w-full rounded-md border-gray-300 text-sm focus:border-green-500 focus:ring-green-500"
                                    placeholder="From Date"/>
                            </div>

                            <div class="flex items-center gap-2">
                                <input type="date" name="toDate" value="{{ request('toDate') }}" 
                                    class="block w-full rounded-md border-gray-300 text-sm focus:border-green-500 focus:ring-green-500"
                                    placeholder="To Date"/>
                                <a href="{{ route('rec-owners') }}" 
                                    class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                                    Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Success Message -->
            @if (session()->has('message'))
                <div class="mb-4">
                    <div class="rounded-md bg-green-50 p-3">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">{{ session('message') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Owners Table -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owner</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact Info</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Demographics</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categories</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($owners as $owner)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full object-cover" 
                                                    src="{{ $owner->profile_image ? asset('storage/' . $owner->profile_image) : asset('assets/default-avatar.png') }}" 
                                                    alt="{{ $owner->complete_name }}">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <a href="{{ route('rec.profile-owner', $owner->owner_id) }}" class="hover:text-green-600">
                                                        {{ $owner->complete_name }}
                                                    </a>
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $owner->street ?? 'N/A' }}, {{ $owner->barangay_name ?? 'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $owner->contact_no }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ ucfirst($owner->gender) }}</div>
                                        <div class="text-sm text-gray-500">{{ ucfirst($owner->civil_status) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-wrap gap-1">
                                            @if($owner->user->categories && $owner->user->categories->count() > 0)
                                                @foreach($owner->user->categories as $category)
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
                                                    
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $classes }}">
                                                        {{ $category->name }}
                                                    </span>
                                                @endforeach
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    No Categories
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-2">
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ $owner->animals->count() }} animals
                                            </span>
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $owner->transactions->count() }} transactions
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="flex items-center space-x-3">
                                            <a href="{{ route('ownerRec.edit', $owner->user_id) }}" 
                                                class="text-green-600 hover:text-green-900">Edit</a>
                                           
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        No owners found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $owners->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden items-center justify-center">
        <div class="bg-white rounded-lg p-6 max-w-sm mx-auto">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Confirm Deletion</h3>
            <p class="text-sm text-gray-500 mb-4">Are you sure you want to delete <span id="ownerName" class="font-medium"></span>? This action cannot be undone.</p>
            <div class="flex justify-end space-x-3">
                <button onclick="closeDeleteModal()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md text-sm font-medium hover:bg-red-700">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Create a function that will be called when the document is ready
        function initializeOwnersPage() {
            // Auto-submit functionality
            function initializeFilters() {
                ['genderFilter', 'civilStatusFilter', 'categoryFilter', 'barangayFilter'].forEach(function(id) {
                    const element = document.getElementById(id);
                    if (element) {
                        // Remove existing event listeners
                        element.removeEventListener('change', handleFilterChange);
                        // Add new event listener
                        element.addEventListener('change', handleFilterChange);
                    }
                });
            }

            function handleFilterChange() {
                if (this.form) {
                    this.form.submit();
                }
            }

            // Updated search functionality
            function initializeSearch() {
                const searchInput = document.getElementById('searchInput');
                const searchForm = document.getElementById('searchForm');
                
                if (searchInput && searchForm) {
                    // Remove existing event listener
                    searchInput.removeEventListener('input', handleSearchInput);
                    // Add new event listener
                    searchInput.addEventListener('input', handleSearchInput);
                }
            }

            function handleSearchInput() {
                if (window.searchTimeout) {
                    clearTimeout(window.searchTimeout);
                }
                window.searchTimeout = setTimeout(() => {
                    const searchForm = document.getElementById('searchForm');
                    if (searchForm) {
                        searchForm.submit();
                    }
                }, 500);
            }

            // Close modal when clicking outside
            function initializeModal() {
                const deleteModal = document.getElementById('deleteModal');
                if (deleteModal) {
                    // Remove existing event listener
                    deleteModal.removeEventListener('click', handleModalClick);
                    // Add new event listener
                    deleteModal.addEventListener('click', handleModalClick);
                }
            }

            function handleModalClick(e) {
                if (e.target === this) {
                    closeDeleteModal();
                }
            }

            function initialize() {
                initializeFilters();
                initializeSearch();
                initializeModal();
            }

            // Initialize immediately
            initialize();

            // Initialize on Livewire updates
            if (typeof Livewire !== 'undefined') {
                Livewire.on('load', initialize);
                Livewire.hook('message.processed', initialize);
            }

            // Initialize on turbolinks navigation if present
            if (typeof Turbolinks !== 'undefined') {
                document.addEventListener('turbolinks:load', initialize);
            }

            // Initialize on regular page loads
            window.addEventListener('load', initialize);
            document.addEventListener('DOMContentLoaded', initialize);
        }

        // Call the initialization function
        initializeOwnersPage();
    </script>
</x-app-layout>
