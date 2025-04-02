<x-app-layout>
    <div class="container mx-auto p-6">
        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Generate Reports</h1>
            <p class="text-gray-600">Generate detailed reports for your veterinary practice</p>
        </div>

        <!-- Report Generation Forms -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Transaction Report Form -->
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
                            <label class="inline-flex items-center">
                                <input type="radio" name="format" value="excel" class="form-radio text-blue-600">
                                <span class="ml-2">Excel</span>
                            </label>
                        </div>
                    </div>

                    <button type="submit" 
                        class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Generate Report
                    </button>
                </form>
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
                                                @endif
                                            @else
                                                <span class="text-gray-500">No filters applied</span>
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
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('transaction_type_id');
    const subtypeContainer = document.getElementById('subtype_container');
    const subtypeSelect = document.getElementById('transaction_subtype_id');

    typeSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const subtypes = selectedOption.dataset.subtypes ? JSON.parse(selectedOption.dataset.subtypes) : [];

        if (this.value && subtypes.length > 0) {
            // Clear previous options
            subtypeSelect.innerHTML = '<option value="">All Subtypes</option>';
            
            // Add new options
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
    });
});
</script>