<x-app-layout>
    <div class="container mx-auto p-6">
        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Generate Reports</h1>
            <p class="text-gray-600">Generate detailed reports for your veterinary practice</p>
        </div>

        <!-- Report Generation Forms and Preview -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Left side: Transaction Report Form -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Transaction Report</h2>
                
                <form action="{{ route('reports.transactions') }}" method="POST" class="space-y-4">
                    @csrf
                    
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
                            <option value="">All Types</option>
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

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Format</label>
                        <div class="mt-2 space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="format" value="pdf" class="form-radio text-blue-600" checked>
                                <span class="ml-2">PDF</span>
                            </label>
                          
                        </div>
                    </div>

                    <button type="submit" 
                        class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Generate Report
                    </button>
                </form>
            </div>

            <!-- Right side: Report Preview -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Report Preview</h2>
                <div id="reportPreview" class="border rounded-lg p-4 bg-gray-50">
                    <!-- Preview Header -->
                    <div class="text-center border-b pb-4 mb-4">
                        <h3 class="text-xl font-bold text-gray-800">Transaction Report</h3>
                        <p class="text-sm text-gray-600">
                            Generated by: {{ auth()->user()->complete_name }}
                        </p>
                        <p id="previewDateRange" class="text-sm text-gray-600 mt-1">
                            Period: Select dates to see preview
                        </p>
                    </div>

                    <!-- Preview Filters -->
                    <div class="mb-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Applied Filters</h4>
                        <div class="space-y-1">
                            <p id="previewType" class="text-sm text-gray-600">
                                Type: All Types
                            </p>
                            <p id="previewSubtype" class="text-sm text-gray-600">
                                Subtype: All Subtypes
                            </p>
                            <p id="previewStatus" class="text-sm text-gray-600">
                                Status: All Statuses
                            </p>
                        </div>
                    </div>

                    <!-- Loading Indicator -->
                    <div id="previewLoading" class="hidden">
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
                        <div id="previewSummary" class="grid grid-cols-3 gap-4">
                            <div class="bg-white p-3 rounded-lg border">
                                <div class="text-xs text-gray-500">Total Transactions</div>
                                <div id="previewTotal" class="text-lg font-bold text-gray-800">-</div>
                            </div>
                            <div class="bg-white p-3 rounded-lg border">
                                <div class="text-xs text-gray-500">Completed</div>
                                <div id="previewCompleted" class="text-lg font-bold text-green-600">-</div>
                            </div>
                            <div class="bg-white p-3 rounded-lg border">
                                <div class="text-xs text-gray-500">Pending</div>
                                <div id="previewPending" class="text-lg font-bold text-yellow-600">-</div>
                            </div>
                        </div>
                    </div>

                    <!-- Preview Sample Data -->
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Sample Data</h4>
                        <div id="previewTable" class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Date</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Type</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="previewTableBody" class="bg-white divide-y divide-gray-200">
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
                                                @if(isset($report->parameters['transaction_type_id']))
                                                    <div class="mb-1">
                                                        <span class="font-medium">Type:</span>
                                                        {{ optional(\App\Models\TransactionType::find($report->parameters['transaction_type_id']))->type_name ?? 'All Types' }}
                                                    </div>
                                                @endif
                                                @if(isset($report->parameters['transaction_subtype_id']))
                                                    <div class="mb-1">
                                                        <span class="font-medium">Subtype:</span>
                                                        {{ optional(\App\Models\TransactionSubtype::find($report->parameters['transaction_subtype_id']))->subtype_name ?? 'All Subtypes' }}
                                                    </div>
                                                @endif
                                                @if(isset($report->parameters['status']))
                                                    <div class="mb-1">
                                                        <span class="font-medium">Status:</span>
                                                        @switch($report->parameters['status'])
                                                            @case(0)
                                                                <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">
                                                                    Pending
                                                                </span>
                                                                @break
                                                            @case(1)
                                                                <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                                                    Completed
                                                                </span>
                                                                @break
                                                            @case(2)
                                                                <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">
                                                                    Cancelled
                                                                </span>
                                                                @break
                                                            @default
                                                                <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800">
                                                                    All Statuses
                                                                </span>
                                                        @endswitch
                                                    </div>
                                                    @else
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
                                        <a href="{{ route('reports.download', $report) }}" 
                                                class="text-blue-600 hover:text-blue-900 flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                </svg>
                                            Download
                                        </a>
                                            <form action="{{ route('reports.delete', $report) }}" method="POST" 
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
// Wrap the entire script in a function that can be called both on initial load and navigation
function initializeReportEngine() {
    // Get all form elements
    const dateFrom = document.querySelector('input[name="date_from"]');
    const dateTo = document.querySelector('input[name="date_to"]');
    const typeSelect = document.getElementById('transaction_type_id');
    const subtypeSelect = document.getElementById('transaction_subtype_id');
    const statusSelect = document.querySelector('select[name="status"]');
    const subtypeContainer = document.getElementById('subtype_container');

    // Handle subtype visibility
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
        updatePreview(); // Trigger preview update when type changes
    });

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

    // Function to update preview
    async function updatePreview() {
        // Check if we have the minimum required data
        if (!dateFrom.value || !dateTo.value) {
            return; // Don't make API call if dates aren't set
        }

        const loading = document.getElementById('previewLoading');
        loading.classList.remove('hidden');

        // Update preview header
        document.getElementById('previewDateRange').textContent = 
            `Period: ${formatDate(dateFrom.value)} - ${formatDate(dateTo.value)}`;

        // Update filters display
        document.getElementById('previewType').textContent = 
            `Type: ${typeSelect.options[typeSelect.selectedIndex].text}`;
        document.getElementById('previewSubtype').textContent = 
            `Subtype: ${subtypeSelect.value ? subtypeSelect.options[subtypeSelect.selectedIndex].text : 'All Subtypes'}`;
        document.getElementById('previewStatus').textContent = 
            `Status: ${statusSelect.options[statusSelect.selectedIndex].text}`;

        try {
            console.log('Sending preview request with data:', {
                date_from: dateFrom.value,
                date_to: dateTo.value,
                transaction_type_id: typeSelect.value,
                transaction_subtype_id: subtypeSelect.value,
                status: statusSelect.value
            });
            
            const response = await fetch('/api/reports/preview', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    date_from: dateFrom.value,
                    date_to: dateTo.value,
                    transaction_type_id: typeSelect.value,
                    transaction_subtype_id: subtypeSelect.value,
                    status: statusSelect.value
                })
            });

            const responseData = await response.text();
            console.log('Raw response:', responseData);
            
            if (!response.ok) {
                throw new Error(`Network response was not ok: ${response.status} ${response.statusText}`);
            }

            const data = JSON.parse(responseData);

            // Update summary statistics
            document.getElementById('previewTotal').textContent = data.summary.total;
            document.getElementById('previewCompleted').textContent = data.summary.completed;
            document.getElementById('previewPending').textContent = data.summary.pending;

            // Update sample data table
            const tableBody = document.getElementById('previewTableBody');
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
        } catch (error) {
            console.error('Preview update failed:', error);
            // Show error message in preview
            document.getElementById('previewTableBody').innerHTML = `
                <tr>
                    <td colspan="3" class="px-3 py-2 text-center text-red-500">
                        Failed to load preview data: ${error.message}
                    </td>
                </tr>
            `;
        } finally {
            loading.classList.add('hidden');
        }
    }

    // Add debounced event listeners for all form inputs
    let debounceTimer;
    const debounce = (func, delay) => {
        return function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => func(), delay);
        };
    };

    const debouncedUpdate = debounce(updatePreview, 300);

    // Add event listeners
    [dateFrom, dateTo, typeSelect, subtypeSelect, statusSelect].forEach(element => {
        if (element) { // Add null check to prevent errors
            element.addEventListener('change', debouncedUpdate);
            // Also add input event for date fields to catch manual input
            if (element.type === 'date') {
                element.addEventListener('input', debouncedUpdate);
            }
        }
    });

    // Initial preview update if form has pre-filled values
    if (dateFrom && dateTo && dateFrom.value && dateTo.value) {
        updatePreview();
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', initializeReportEngine);

// Listen for turbo:load or similar navigation events if you're using Turbo/Hotwire
document.addEventListener('turbo:load', initializeReportEngine);

// For SPAs or other frameworks, add a custom event listener
document.addEventListener('app:navigation-complete', initializeReportEngine);

// For Laravel Livewire
if (window.Livewire) {
    document.addEventListener('livewire:load', initializeReportEngine);
}
</script>