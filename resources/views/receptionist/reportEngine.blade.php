<x-app-layout>
    <div class="container mx-auto p-6">
        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Report Engine</h1>
            <p class="text-gray-600">Generate comprehensive reports for reception and administrative needs</p>
        </div>

        <!-- Tabbed Report Interface -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <!-- Tab Navigation -->
            <div class="flex border-b border-gray-200">
                <button type="button" id="tab-transactions" class="tab-button px-6 py-4 text-blue-600 border-b-2 border-blue-600 font-medium text-sm">
                    <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Transactions
                </button>
                <button type="button" id="tab-clients" class="tab-button px-6 py-4 text-gray-500 hover:text-gray-700 font-medium text-sm">
                    <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Owners
                </button>
                <button type="button" id="tab-animals" class="tab-button px-6 py-4 text-gray-500 hover:text-gray-700 font-medium text-sm">
                    <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                    </svg>
                    Animals
                </button>
                <button type="button" id="tab-vaccinations" class="tab-button px-6 py-4 text-gray-500 hover:text-gray-700 font-medium text-sm">
                    <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                    </svg>
                    Vaccinations
                </button>
        
            </div>

            <!-- Tab Contents -->
            <div class="p-6">
                <!-- Transactions Tab -->
                <div id="content-transactions" class="tab-content grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left: Form -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Transaction Report Settings</h3>
                        <form id="transactions-form" action="{{ route('receptionist.view.transactions') }}" method="GET" class="space-y-4">
                            @csrf
                            <input type="hidden" name="preview_type" value="transactions">
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">From Date</label>
                                    <input type="date" name="date_from" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">To Date</label>
                                    <input type="date" name="date_to" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Transaction Type</label>
                                <select name="transaction_type_id" id="transaction_type_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">All Transaction Types</option>
                                    @foreach($transactionTypes as $type)
                                        <option value="{{ $type->id }}" data-subtypes="{{ $type->subtypes }}">
                                            {{ $type->type_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="subtype_container" style="display: none;">
                                <label class="block text-sm font-medium text-gray-700">Transaction Subtype</label>
                                <select name="transaction_subtype_id" id="transaction_subtype_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">All Subtypes</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">All Statuses</option>
                                    <option value="0">Pending</option>
                                    <option value="1">Completed</option>
                                    <option value="2">Cancelled</option>
                                </select>
                            </div>
                            
                            <button type="submit" 
                                class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Generate Transaction Report
                            </button>
                        </form>
                    </div>
                    
                    <!-- Right: Preview -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Report Preview</h3>
                        <div id="transactions-preview" class="bg-gray-50 border rounded-lg p-4">
                            <!-- Preview Header -->
                            <div class="text-center border-b pb-4 mb-4">
                                <h3 class="text-xl font-bold text-gray-800">Transaction Report</h3>
                                <p class="text-sm text-gray-600">
                                    Generated by: {{ auth()->user()->complete_name }}
                                </p>
                                <p id="transactions-preview-date-range" class="text-sm text-gray-600 mt-1">
                                    Period: Select dates to see preview
                                </p>
                            </div>

                            <!-- Preview Filters -->
                            <div class="mb-4">
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Applied Filters</h4>
                                <div class="space-y-1">
                                    <p id="transactions-preview-type" class="text-sm text-gray-600">
                                        Type: All Types
                                    </p>
                                    <p id="transactions-preview-subtype" class="text-sm text-gray-600">
                                        Subtype: All Subtypes
                                    </p>
                                    <p id="transactions-preview-status" class="text-sm text-gray-600">
                                        Status: All Statuses
                                    </p>
                                </div>
                            </div>

                            <!-- Loading Indicator -->
                            <div id="transactions-preview-loading" class="hidden">
                                <div class="flex items-center justify-center py-4">
                                    <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span class="ml-2 text-sm text-gray-600">Loading preview...</span>
                                </div>
                            </div>

                            <!-- Preview Summary -->
                            <div class="mb-4">
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Summary</h4>
                                <div class="grid grid-cols-3 gap-4">
                                    <div class="bg-white p-3 rounded-lg border">
                                        <div class="text-xs text-gray-500">Total</div>
                                        <div id="transactions-preview-total" class="text-lg font-bold text-gray-800">-</div>
                                    </div>
                                    <div class="bg-white p-3 rounded-lg border">
                                        <div class="text-xs text-gray-500">Completed</div>
                                        <div id="transactions-preview-completed" class="text-lg font-bold text-green-600">-</div>
                                    </div>
                                    <div class="bg-white p-3 rounded-lg border">
                                        <div class="text-xs text-gray-500">Pending</div>
                                        <div id="transactions-preview-pending" class="text-lg font-bold text-yellow-600">-</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Preview Sample Data -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Sample Data</h4>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Date</th>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Type</th>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="transactions-preview-table-body" class="bg-white divide-y divide-gray-200">
                                            <tr>
                                                <td colspan="3" class="px-3 py-2 text-center text-gray-500">
                                                    Select filters to see preview data
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Clients Tab -->
                <div id="content-clients" class="tab-content hidden grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left: Form -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Owner Report Settings</h3>
                        <form id="clients-form" action="{{ route('receptionist.reports.owners') }}" method="GET" class="space-y-4">
                            @csrf
                            <input type="hidden" name="preview_type" value="owners">
                    
                            <!-- Date Range Fields -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="date_from" class="block text-sm font-medium text-gray-700">From Date</label>
                                    <input type="date" name="date_from" id="date_from" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                    
                                <div>
                                    <label for="date_to" class="block text-sm font-medium text-gray-700">To Date</label>
                                    <input type="date" name="date_to" id="date_to" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>
                    
                            <!-- Barangay Selection -->
                            <div>
                                <label for="barangay_id" class="block text-sm font-medium text-gray-700">Barangay</label>
                                <select name="barangay_id" id="barangay_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">All Barangays</option>
                                    @foreach($barangays as $barangay)
                                        <option value="{{ $barangay->id }}">{{ $barangay->barangay_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                    
                            <!-- Submit Button -->
                            <button type="submit"
                                class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                Generate Owner Report
                            </button>
                        </form>
                    </div>
                    
                    
                    <!-- Right: Preview -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Report Preview</h3>
                        <div id="clients-preview" class="bg-gray-50 border rounded-lg p-4">
                            <!-- Preview Header -->
                            <div class="text-center border-b pb-4 mb-4">
                                <h3 class="text-xl font-bold text-gray-800">Owner Report</h3>
                                <p class="text-sm text-gray-600">
                                    Generated by: {{ auth()->user()->complete_name }}
                                </p>
                                <p id="clients-preview-date-range" class="text-sm text-gray-600 mt-1">
                                    Period: Select dates to see preview
                                </p>
                            </div>

                            <!-- Preview Filters -->
                            <div class="mb-4">
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Applied Filters</h4>
                                <div class="space-y-1">
                                    <p id="clients-preview-barangay" class="text-sm text-gray-600">
                                        Barangay: All Barangays
                                    </p>
                                </div>
                            </div>

                            <!-- Loading Indicator -->
                            <div id="clients-preview-loading" class="hidden">
                                <div class="flex items-center justify-center py-4">
                                    <svg class="animate-spin h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span class="ml-2 text-sm text-gray-600">Loading preview...</span>
                                </div>
                            </div>

                            <!-- Preview Summary -->
                            <div class="mb-4">
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Summary</h4>
                                <div class="grid grid-cols-1 gap-4">
                                    <div class="bg-white p-3 rounded-lg border">
                                        <div class="text-xs text-gray-500">Total Clients</div>
                                        <div id="clients-preview-total" class="text-lg font-bold text-gray-800">-</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Preview Sample Data -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Sample Data</h4>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Name</th>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Barangay</th>
                                            </tr>
                                        </thead>
                                        <tbody id="clients-preview-table-body" class="bg-white divide-y divide-gray-200">
                                            <tr>
                                                <td colspan="2" class="px-3 py-2 text-center text-gray-500">
                                                    Select filters to see preview data
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Animals Tab -->
                <div id="content-animals" class="tab-content hidden grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left: Form -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Animal Report Settings</h3>
                        <form id="animals-form" action="{{ route('receptionist.reports.animals') }}" method="GET" class="space-y-4">
                            @csrf
                            <input type="hidden" name="preview_type" value="animals">
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">From Date</label>
                                    <input type="date" name="date_from" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">To Date</label>
                                    <input type="date" name="date_to" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Species</label>
                                <select name="species_id" id="species_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">All Species</option>
                                    @foreach($species as $specie)
                                        <option value="{{ $specie->id }}" data-breeds="{{ $specie->breeds }}">
                                            {{ $specie->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="breed_container" style="display: none;">
                                <label class="block text-sm font-medium text-gray-700">Breed</label>
                                <select name="breed_id" id="breed_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">All Breeds</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Vaccination Status</label>
                                <select name="is_vaccinated"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">All</option>
                                    <option value="1">Vaccinated</option>
                                    <option value="0">Not Vaccinated</option>
                                    <option value="2">No Vaccination Required</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="barangay_id" class="block text-sm font-medium text-gray-700">Barangay</label>
                                <select name="barangay_id" id="barangay_id" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">-- All Barangays --</option>
                                    @foreach($barangays as $barangay)
                                        <option value="{{ $barangay->id }}" {{ request('barangay_id') == $barangay->id ? 'selected' : '' }}>
                                            {{ $barangay->barangay_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <button type="submit" 
                                class="w-full bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                                Generate Animal Report
                            </button>
                        </form>
                    </div>
                    
                    <!-- Right: Preview -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Report Preview</h3>
                        <div id="animals-preview" class="bg-gray-50 border rounded-lg p-4">
                            <!-- Preview Header -->
                            <div class="text-center border-b pb-4 mb-4">
                                <h3 class="text-xl font-bold text-gray-800">Animal Report</h3>
                                <p class="text-sm text-gray-600">
                                    Generated by: {{ auth()->user()->complete_name }}
                                </p>
                                <p id="animals-preview-date-range" class="text-sm text-gray-600 mt-1">
                                    Period: Select dates to see preview
                                </p>
                            </div>

                        <!-- Preview Filters -->
                    <div class="mb-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Applied Filters</h4>
                        <div class="space-y-1">
                            <p id="animals-preview-species" class="text-sm text-gray-600">
                                Species: All Species
                            </p>
                            <p id="animals-preview-breed" class="text-sm text-gray-600">
                                Breed: All Breeds
                            </p>
                            <p id="animals-preview-vaccinated" class="text-sm text-gray-600">
                                Vaccination: All
                            </p>
                            <p id="animals-preview-barangay" class="text-sm text-gray-600">
                                Barangay: All Barangays
                            </p>
                        </div>
                    </div>

                            </div>

                            <!-- Loading Indicator -->
                            <div id="animals-preview-loading" class="hidden">
                                <div class="flex items-center justify-center py-4">
                                    <svg class="animate-spin h-5 w-5 text-purple-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span class="ml-2 text-sm text-gray-600">Loading preview...</span>
                                </div>
                            </div>

                            <!-- Preview Summary -->
                            <div class="mb-4">
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Summary</h4>
                                <div class="grid grid-cols-4 gap-4">
                                    <div class="bg-white p-3 rounded-lg border">
                                        <div class="text-xs text-gray-500">Total Animals</div>
                                        <div id="animals-preview-total" class="text-lg font-bold text-gray-800">-</div>
                                    </div>
                                    <div class="bg-white p-3 rounded-lg border">
                                        <div class="text-xs text-gray-500">Vaccinated</div>
                                        <div id="animals-preview-vaccinated-count" class="text-lg font-bold text-green-600">-</div>
                                    </div>
                                    <div class="bg-white p-3 rounded-lg border">
                                        <div class="text-xs text-gray-500">Not Vaccinated</div>
                                        <div id="animals-preview-not-vaccinated" class="text-lg font-bold text-red-600">-</div>
                                    </div>
                                    <div class="bg-white p-3 rounded-lg border">
                                        <div class="text-xs text-gray-500">No Vaccination Required</div>
                                        <div id="animals-preview-not-required" class="text-lg font-bold text-gray-600">-</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Preview Sample Data -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Sample Data</h4>
                                <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 text-sm">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Name</th>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Species</th>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Breed</th>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Barangay</th>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Vaccinated</th>
                                            </tr>
                                        </thead>
                                        <tbody id="animals-preview-table-body" class="bg-white divide-y divide-gray-200">
                                            <tr>
                                                <td colspan="4" class="px-3 py-2 text-center text-gray-500">
                                                    Select filters to see preview data
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vaccinations Tab -->
                <div id="content-vaccinations" class="tab-content hidden grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left: Form -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Vaccination Report Settings</h3>
                        <form id="vaccinations-form" action="{{ route('receptionist.reports.vaccinations') }}" method="GET" class="space-y-4">
                            @csrf
                            <input type="hidden" name="preview_type" value="vaccinations">
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">From Date</label>
                                    <input type="date" name="date_from" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">To Date</label>
                                    <input type="date" name="date_to" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Vaccine Type</label>
                                <select name="vaccine_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">All Vaccines</option>
                                    @foreach($vaccines as $vaccine)
                                        <option value="{{ $vaccine->id }}">{{ $vaccine->vaccine_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Species</label>
                                <select name="species_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">All Species</option>
                                    @foreach($species as $specie)
                                        <option value="{{ $specie->id }}">{{ $specie->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">All Statuses</option>
                                    <option value="0">Pending</option>
                                    <option value="1">Completed</option>
                                    <option value="2">Cancelled</option>
                                </select>
                            </div>
                            
                            <button type="submit" 
                                class="w-full bg-amber-600 text-white px-4 py-2 rounded-md hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                                Generate Vaccination Report
                            </button>
                        </form>
                    </div>
                    
                    <!-- Right: Preview -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Report Preview</h3>
                        <div id="vaccinations-preview" class="bg-gray-50 border rounded-lg p-4">
                            <!-- Preview Header -->
                            <div class="text-center border-b pb-4 mb-4">
                                <h3 class="text-xl font-bold text-gray-800">Vaccination Report</h3>
                                <p class="text-sm text-gray-600">
                                    Generated by: {{ auth()->user()->complete_name }}
                                </p>
                                <p id="vaccinations-preview-date-range" class="text-sm text-gray-600 mt-1">
                                    Period: Select dates to see preview
                                </p>
                            </div>

                            <!-- Preview Filters -->
                            <div class="mb-4">
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Applied Filters</h4>
                                <div class="space-y-1">
                                    <p id="vaccinations-preview-vaccine" class="text-sm text-gray-600">
                                        Vaccine: All Vaccines
                                    </p>
                                    <p id="vaccinations-preview-species" class="text-sm text-gray-600">
                                        Species: All Species
                                    </p>
                                    <p id="vaccinations-preview-status" class="text-sm text-gray-600">
                                        Status: All Statuses
                                    </p>
                                </div>
                            </div>

                            <!-- Loading Indicator -->
                            <div id="vaccinations-preview-loading" class="hidden">
                                <div class="flex items-center justify-center py-4">
                                    <svg class="animate-spin h-5 w-5 text-amber-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span class="ml-2 text-sm text-gray-600">Loading preview...</span>
                                </div>
                            </div>

                            <!-- Preview Summary -->
                            <div class="mb-4">
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Summary</h4>
                                <div class="grid grid-cols-3 gap-4">
                                    <div class="bg-white p-3 rounded-lg border">
                                        <div class="text-xs text-gray-500">Total</div>
                                        <div id="vaccinations-preview-total" class="text-lg font-bold text-gray-800">-</div>
                                    </div>
                                    <div class="bg-white p-3 rounded-lg border">
                                        <div class="text-xs text-gray-500">Completed</div>
                                        <div id="vaccinations-preview-completed" class="text-lg font-bold text-green-600">-</div>
                                    </div>
                                    <div class="bg-white p-3 rounded-lg border">
                                        <div class="text-xs text-gray-500">Pending</div>
                                        <div id="vaccinations-preview-pending" class="text-lg font-bold text-yellow-600">-</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Preview Sample Data -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Sample Data</h4>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Animal</th>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Vaccine</th>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Status</th>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Date</th>
                                            </tr>
                                        </thead>
                                        <tbody id="vaccinations-preview-table-body" class="bg-white divide-y divide-gray-200">
                                            <tr>
                                                <td colspan="4" class="px-3 py-2 text-center text-gray-500">
                                                    Select filters to see preview data
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Reports -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Recent Reports</h2>
            
            @if($recentReports->isEmpty())
                <div class="text-center py-6">
                    <p class="text-gray-500">No reports generated yet.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Report Details
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date Range
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Filters Applied
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Generated At
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentReports->sortByDesc('created_at') as $report)
                                <tr class="{{ $loop->first ? 'bg-blue-50' : '' }}">
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <div class="flex items-center">
                                                @if($loop->first)
                                                    <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-1 text-xs font-medium text-blue-700 mr-2">
                                                        Latest
                                                    </span>
                                                @endif
                                                <span class="font-medium">
                                                    {{ Str::title(str_replace('_', ' ', $report->report_type)) }}
                                                </span>
                                            </div>
                                            <span class="text-sm text-gray-500 mt-1">
                                                Generated by: {{ optional($report->generator)->complete_name }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm">
                                            <div>From: {{ $report->date_from->format('M d, Y') }}</div>
                                            <div>To: {{ $report->date_to->format('M d, Y') }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm">
                                            @if($report->parameters)
                                                @php
                                                    $params = json_decode(json_encode($report->parameters), true);
                                                @endphp
                                                
                                                @foreach($params as $key => $value)
                                                    @if($value && !in_array($key, ['date_from', 'date_to', 'format', 'preview_type']))
                                                        <div class="mb-1">
                                                            <span class="font-medium">{{ Str::title(str_replace('_', ' ', $key)) }}:</span>
                                                            
                                                            @if($key == 'status')
                                                                @switch($value)
                                                                    @case(0)
                                                                        <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">Pending</span>
                                                                        @break
                                                                    @case(1)
                                                                        <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Completed</span>
                                                                        @break
                                                                    @case(2)
                                                                        <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">Cancelled</span>
                                                                        @break
                                                                    @default
                                                                        <span>{{ $value }}</span>
                                                                @endswitch
                                                            @elseif($key == 'is_vaccinated')
                                                                {{ $value == 1 ? 'Yes' : 'No' }}
                                                            @else
                                                                {{ $value }}
                                                            @endif
                                                        </div>
                                                    @endif
                                                @endforeach
                                                
                                                @if(count(array_filter($params, function($v, $k) { return $v && !in_array($k, ['date_from', 'date_to', 'format', 'preview_type']); }, ARRAY_FILTER_USE_BOTH)) == 0)
                                                    <span class="text-gray-500">No filters applied</span>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <span>{{ $report->created_at->format('M d, Y h:i A') }}</span>
                                            <span class="text-xs text-gray-500">
                                                {{ $report->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex space-x-3">
                                            <a href="{{ route('reports.downloadfromRec', $report->id) }}" 
                                                class="text-blue-600 hover:text-blue-900 flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                </svg>
                                                Download
                                            </a>
                                            <form action="{{ route('reports.deletefromRec', $report->id) }}" method="POST" 
                                                  onsubmit="return confirm('Are you sure you want to delete this report?');"
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                    class="text-red-600 hover:text-red-900 flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

<script>
// Initialize tabbed interface and report features
function initializeReportEngine() {
    // Utility functions
    // Function to format date
    function formatDate(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric'
        });
    }

    // Function to get status badge HTML
    function getStatusBadge(status) {
        const colors = {
            'Completed': 'bg-green-100 text-green-800',
            'Pending': 'bg-yellow-100 text-yellow-800',
            'Cancelled': 'bg-red-100 text-red-800'
        };
        return `<span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ${colors[status]}">${status}</span>`;
    }
    
    // Tab switching functionality
    const tabs = document.querySelectorAll('.tab-button');
    const contents = document.querySelectorAll('.tab-content');
    
    // Add click event listeners to tabs
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Get the target content ID from the tab ID
            const targetId = this.id.replace('tab-', 'content-');
            
            // Update tab styles - remove active state from all tabs
            tabs.forEach(t => {
                t.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
                t.classList.add('text-gray-500', 'hover:text-gray-700');
            });
            
            // Add active state to clicked tab
            this.classList.remove('text-gray-500', 'hover:text-gray-700');
            this.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
            
            // Hide all content sections
            contents.forEach(content => {
                content.classList.add('hidden');
            });
            
            // Show the corresponding content
            const targetContent = document.getElementById(targetId);
            if (targetContent) {
                targetContent.classList.remove('hidden');
                
                // Initialize the preview for the newly selected tab
                const reportType = this.id.replace('tab-', '');
                if (['transactions', 'clients', 'animals', 'vaccinations', 'staff'].includes(reportType)) {
                    updatePreview(reportType);
                }
            }
        });
    });

    // Transaction Type and Subtype handlers
    const typeSelect = document.getElementById('transaction_type_id');
    const subtypeSelect = document.getElementById('transaction_subtype_id');
    const subtypeContainer = document.getElementById('subtype_container');

    if (typeSelect) {
        typeSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const subtypes = selectedOption.dataset.subtypes ? JSON.parse(selectedOption.dataset.subtypes) : [];

            if (this.value && subtypes.length > 0) {
                subtypeSelect.innerHTML = '<option value="">All Subtypes</option>';
                subtypes.forEach(subtype => {
                    const option = document.createElement('option');
                    option.value = subtype.id;
                    option.textContent = subtype.subtype_name;
                    subtypeSelect.appendChild(option);
                });
                subtypeContainer.style.display = 'block';
            } else {
                subtypeContainer.style.display = 'none';
                subtypeSelect.value = '';
            }
            updatePreview('transactions');
        });
    }

    // Species and Breed handlers
    const speciesSelect = document.getElementById('species_id');
    const breedSelect = document.getElementById('breed_id');
    const breedContainer = document.getElementById('breed_container');
    
    if (speciesSelect) {
        speciesSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const breeds = selectedOption.dataset.breeds ? JSON.parse(selectedOption.dataset.breeds) : [];

            if (this.value && breeds.length > 0) {
                breedSelect.innerHTML = '<option value="">All Breeds</option>';
                breeds.forEach(breed => {
                    const option = document.createElement('option');
                    option.value = breed.id;
                    option.textContent = breed.name;
                    breedSelect.appendChild(option);
                });
                breedContainer.style.display = 'block';
            } else {
                breedContainer.style.display = 'none';
                breedSelect.value = '';
            }
            updatePreview('animals');
        });
    }

    // Function to update preview data based on form values
    async function updatePreview(reportType) {
        // Get the form for the specific report type
        const form = document.getElementById(`${reportType}-form`);
        if (!form) return;

        // Get date fields
        const dateFrom = form.querySelector('input[name="date_from"]');
        const dateTo = form.querySelector('input[name="date_to"]');

        // Check if we have the minimum required data
        if (!dateFrom.value || !dateTo.value) {
            return; // Don't make API call if dates aren't set
        }

        // Update date range in preview
        document.getElementById(`${reportType}-preview-date-range`).textContent = 
            `Period: ${formatDate(dateFrom.value)} - ${formatDate(dateTo.value)}`;

        // Show loading indicator
        const loadingEl = document.getElementById(`${reportType}-preview-loading`);
        if (loadingEl) loadingEl.classList.remove('hidden');

        try {
            // Prepare form data
            const formData = new FormData(form);
            
            // Debug - log form data
            console.log('Submitting form data for', reportType);
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }
            
            // Make API request
            const response = await fetch('/rec_reports/api/receptionist/reports/preview', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: formData
            });

            if (!response.ok) {
                throw new Error(`Network response was not ok: ${response.status}`);
            }

            const data = await response.json();
            console.log('Response data:', data);
            
            // Update preview based on report type
            switch (reportType) {
                case 'transactions':
                    updateTransactionsPreview(data, form);
                    break;
                case 'clients':
                    updateClientsPreview(data, form);
                    break;
                case 'animals':
                    updateAnimalsPreview(data, form);
                    break;
                case 'vaccinations':
                    updateVaccinationsPreview(data, form);
                    break;
                case 'staff':
                    updateStaffPreview(data, form);
                    break;
            }
        } catch (error) {
            console.error(`${reportType} preview update failed:`, error);
            
            // Show error in preview
            const tableBody = document.getElementById(`${reportType}-preview-table-body`);
            if (tableBody) {
                const columnCount = tableBody.closest('table').querySelectorAll('thead th').length;
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="${columnCount}" class="px-3 py-2 text-center text-red-500">
                            Failed to load preview data: ${error.message}
                        </td>
                    </tr>
                `;
            }
        } finally {
            // Hide loading indicator
            if (loadingEl) loadingEl.classList.add('hidden');
        }
    }

    // Update transactions preview with data
    function updateTransactionsPreview(data, form) {
        // Update filter displays
        const typeSelect = form.querySelector('select[name="transaction_type_id"]');
        const subtypeSelect = form.querySelector('select[name="transaction_subtype_id"]');
        const statusSelect = form.querySelector('select[name="status"]');
        
        document.getElementById('transactions-preview-type').textContent = 
            `Type: ${typeSelect.options[typeSelect.selectedIndex].text}`;
        document.getElementById('transactions-preview-subtype').textContent = 
            `Subtype: ${subtypeSelect && subtypeSelect.value ? subtypeSelect.options[subtypeSelect.selectedIndex].text : 'All Subtypes'}`;
        document.getElementById('transactions-preview-status').textContent = 
            `Status: ${statusSelect.options[statusSelect.selectedIndex].text}`;

        // Update summary statistics
        document.getElementById('transactions-preview-total').textContent = data.summary.total;
        document.getElementById('transactions-preview-completed').textContent = data.summary.completed;
        document.getElementById('transactions-preview-pending').textContent = data.summary.pending;

        // Update sample data table
        const tableBody = document.getElementById('transactions-preview-table-body');
        if (data.samples && data.samples.length > 0) {
            tableBody.innerHTML = data.samples.map(transaction => `
                <tr>
                    <td class="px-3 py-2">${formatDate(transaction.created_at)}</td>
                    <td class="px-3 py-2">${transaction.type}</td>
                    <td class="px-3 py-2">${getStatusBadge(transaction.status)}</td>
                </tr>
            `).join('');
        } else {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="3" class="px-3 py-2 text-center text-gray-500">
                        No data available for selected filters
                    </td>
                </tr>
            `;
        }
    }

    // Update clients preview with data
    function updateClientsPreview(data, form) {
        // Update filter displays - only show barangay filter
        const barangaySelect = form.querySelector('select[name="barangay_id"]');
        
        // Hide the category filter display if it exists
        const categoryElement = document.getElementById('clients-preview-category');
        if (categoryElement) {
            categoryElement.style.display = 'none';
        }
        
        document.getElementById('clients-preview-barangay').textContent = 
            `Barangay: ${barangaySelect.value ? barangaySelect.options[barangaySelect.selectedIndex].text : 'All Barangays'}`;

        // Update summary statistics
        document.getElementById('clients-preview-total').textContent = data.summary.total;

        // Update sample data table
        const tableBody = document.getElementById('clients-preview-table-body');
        
        // Update table header to only show name and barangay columns
        const tableHeader = document.querySelector('#clients-preview table thead tr');
        if (tableHeader) {
            tableHeader.innerHTML = `
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Name</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Barangay</th>
            `;
        }
        
        if (data.samples && data.samples.length > 0) {
            tableBody.innerHTML = data.samples.map(client => {
                // Handle barangay display - check multiple possible paths
                let barangayDisplay = 'N/A';
                
                if (client.address && client.address.barangay) {
                    barangayDisplay = client.address.barangay.barangay_name || client.address.barangay;
                } else if (client.barangay) {
                    barangayDisplay = client.barangay.barangay_name || client.barangay;
                } else if (client.barangay_name) {
                    barangayDisplay = client.barangay_name;
                } else if (client.user && client.user.address && client.user.address.barangay) {
                    barangayDisplay = client.user.address.barangay.barangay_name || client.user.address.barangay;
                }
                
                return `
                <tr>
                    <td class="px-3 py-2">${client.complete_name || client.name || 'N/A'}</td>
                    <td class="px-3 py-2">${barangayDisplay}</td>
                </tr>
                `;
            }).join('');
        } else {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="2" class="px-3 py-2 text-center text-gray-500">
                        No data available for selected filters
                    </td>
                </tr>
            `;
        }
    }

    // Update animals preview with data
    function updateAnimalsPreview(data, form) {
        // Update filter displays
        const speciesSelect = form.querySelector('select[name="species_id"]');
        const breedSelect = form.querySelector('select[name="breed_id"]');
        const vaccinatedSelect = form.querySelector('select[name="is_vaccinated"]');
        const barangaySelect = form.querySelector('select[name="barangay_id"]');

        document.getElementById('animals-preview-species').textContent = 
            `Species: ${speciesSelect.options[speciesSelect.selectedIndex].text}`;
        document.getElementById('animals-preview-breed').textContent = 
            `Breed: ${breedSelect && breedSelect.value ? breedSelect.options[breedSelect.selectedIndex].text : 'All Breeds'}`;
        document.getElementById('animals-preview-vaccinated').textContent = 
            `Vaccination: ${vaccinatedSelect.options[vaccinatedSelect.selectedIndex].text}`;
        document.getElementById('animals-preview-barangay').textContent = 
            `Barangay: ${barangaySelect && barangaySelect.value ? barangaySelect.options[barangaySelect.selectedIndex].text : 'All Barangays'}`;

        // Update summary statistics
        document.getElementById('animals-preview-total').textContent = data.summary.total;
        document.getElementById('animals-preview-vaccinated-count').textContent = data.summary.vaccinated;
        document.getElementById('animals-preview-not-vaccinated').textContent = data.summary.not_vaccinated;
        document.getElementById('animals-preview-not-required').textContent = data.summary.not_required;

        // Update sample data table
        const tableBody = document.getElementById('animals-preview-table-body');
        if (data.samples && data.samples.length > 0) {
            tableBody.innerHTML = data.samples.map(animal => `
                <tr>
                    <td class="px-3 py-2">${animal.name}</td>
                    <td class="px-3 py-2">${animal.species}</td>
                    <td class="px-3 py-2">${animal.breed}</td>
                    <td class="px-3 py-2">${animal.barangay ?? 'N/A'}</td>
                    <td class="px-3 py-2">${getVaccinationStatusBadge(animal.is_vaccinated)}</td>
                </tr>
            `).join('');
        } else {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="5" class="px-3 py-2 text-center text-gray-500">
                        No data available for selected filters
                    </td>
                </tr>
            `;
        }
    }

    // Add this helper function for vaccination status badges
    function getVaccinationStatusBadge(status) {
        switch(status) {
            case 'Yes':
                return '<span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Vaccinated</span>';
            case 'No':
                return '<span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">Not Vaccinated</span>';
            case 'Not Required':
                return '<span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800">Not Required</span>';
            default:
                return '<span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800">Unknown</span>';
        }
    }

    // Update vaccinations preview with data
    function updateVaccinationsPreview(data, form) {
        // Update filter displays
        const vaccineSelect = form.querySelector('select[name="vaccine_id"]');
        const speciesSelect = form.querySelector('select[name="species_id"]');
        const statusSelect = form.querySelector('select[name="status"]');
        
        document.getElementById('vaccinations-preview-vaccine').textContent = 
            `Vaccine: ${vaccineSelect.options[vaccineSelect.selectedIndex].text}`;
        document.getElementById('vaccinations-preview-species').textContent = 
            `Species: ${speciesSelect.options[speciesSelect.selectedIndex].text}`;
        document.getElementById('vaccinations-preview-status').textContent = 
            `Status: ${statusSelect.options[statusSelect.selectedIndex].text}`;

        // Update summary statistics
        document.getElementById('vaccinations-preview-total').textContent = data.summary.total;
        document.getElementById('vaccinations-preview-completed').textContent = data.summary.completed;
        document.getElementById('vaccinations-preview-pending').textContent = data.summary.pending;

        // Update sample data table
        const tableBody = document.getElementById('vaccinations-preview-table-body');
        if (data.samples && data.samples.length > 0) {
            tableBody.innerHTML = data.samples.map(vaccination => `
                <tr>
                    <td class="px-3 py-2">${vaccination.animal}</td>
                    <td class="px-3 py-2">${vaccination.vaccine}</td>
                    <td class="px-3 py-2">${getStatusBadge(vaccination.status)}</td>
                    <td class="px-3 py-2">${formatDate(vaccination.created_at)}</td>
                </tr>
            `).join('');
        } else {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="4" class="px-3 py-2 text-center text-gray-500">
                        No data available for selected filters
                    </td>
                </tr>
            `;
        }
    }

    

    // Date validation and default date setting
    const forms = ['transactions', 'clients', 'animals', 'vaccinations'];
    forms.forEach(formType => {
        const form = document.getElementById(`${formType}-form`);
        if (!form) return;
        
        // Form submission validation
        form.addEventListener('submit', function(e) {
            const dateFrom = this.querySelector('input[name="date_from"]');
            const dateTo = this.querySelector('input[name="date_to"]');
            
            if (dateFrom && dateTo) {
                const fromDate = new Date(dateFrom.value);
                const toDate = new Date(dateTo.value);
                
                if (fromDate > toDate) {
                    e.preventDefault();
                    alert('The From Date must be earlier than or equal to the To Date');
                }
            }
        });

        // Remove the default date setting code
        // Just keep the change event listeners
        form.querySelectorAll('select, input[type="date"]').forEach(input => {
            input.addEventListener('change', () => updatePreview(formType));
        });
    });

    // Initialize previews for the first tab (transactions)
    updatePreview('transactions');
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', initializeReportEngine);

// Listen for turbo:load or similar navigation events if you're using Turbo/Hotwire
document.addEventListener('turbo:load', initializeReportEngine);

// For SPAs or other frameworks
document.addEventListener('app:navigation-complete', initializeReportEngine);

// For Laravel Livewire
if (window.Livewire) {
    document.addEventListener('livewire:load', initializeReportEngine);
}

// Add this to your existing JavaScript code
document.addEventListener('DOMContentLoaded', function() {
    // Handle staff role selection
    const staffRoleSelect = document.getElementById('staff-role-select');
    const designationContainer = document.getElementById('designation-container');
    
    if (staffRoleSelect) {
        staffRoleSelect.addEventListener('change', function() {
            // Only show designation selector for veterinarians (role 2) or no selection
            if (this.value === '2' || this.value === '') {
                designationContainer.style.display = 'block';
            } else {
                // If receptionist is selected, hide designation field
                designationContainer.style.display = 'none';
                // Reset the designation value when receptionist is selected
                const designationSelect = document.querySelector('select[name="designation_id"]');
                if (designationSelect) {
                    designationSelect.value = '';
                }
            }
            
            // Update preview with the new selection
            updatePreview('staff');
        });
    }
});
</script>