<x-app-layout>
    <!-- Table Section -->
    <div class="text-center mt-8">
        <h2 class="text-3xl font-bold text-gray-800 dark:text-white">
            <span style="color: #006400;">Animals</span> Management
        </h2>
        <p class="text-lg text-gray-500 dark:text-gray-300 mt-2">
            Add, edit, or manage users from this section.
        </p>
    </div>

    <div class="max-w-[100rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
        <!-- Card -->
        <div class="flex flex-col">
            <div class="-m-1.5 overflow-x-auto">
                <div class="p-1.5 min-w-full inline-block align-middle">
                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-800 dark:border-neutral-700">
                        
                        <!-- Header -->
                    
                        <!-- End Header -->
                        
                        <!-- Filter Form -->
                        <form method="GET" action="{{ route('rec-animals') }}" class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200 dark:border-neutral-700" id="filterForm">
                            <input 
                                type="text" 
                                name="search" 
                                placeholder="Search animals by name or contact" 
                                class="px-4 py-2 rounded-lg border border-gray-300 dark:bg-neutral-800 dark:border-neutral-600 dark:text-neutral-200" 
                                value="{{ request('search') }}"
                                oninput="document.getElementById('filterForm').submit()"
                            >

                            <select 
                                name="species_id" 
                                class="px-4 py-2 rounded-lg border border-gray-300 dark:bg-neutral-800 dark:border-neutral-600 dark:text-neutral-200"
                                onchange="document.getElementById('filterForm').submit()"
                            >
                                <option value="">--Select Species--</option>
                                @foreach($species as $specie)
                                    <option value="{{ $specie->id }}" {{ request('species_id') == $specie->id ? 'selected' : '' }}>{{ $specie->name }}</option>
                                @endforeach
                            </select>

                            <select 
                                name="breed_id" 
                                class="px-4 py-2 rounded-lg border border-gray-300 dark:bg-neutral-800 dark:border-neutral-600 dark:text-neutral-200"
                                onchange="document.getElementById('filterForm').submit()"
                            >
                                <option value="">--Select Breed--</option>
                                @foreach($breeds as $breed)
                                    <option value="{{ $breed->id }}" {{ request('breed_id') == $breed->id ? 'selected' : '' }}>{{ $breed->name }}</option>
                                @endforeach
                            </select>

                            <select 
                                name="owner_id" 
                                class="px-4 py-2 rounded-lg border border-gray-300 dark:bg-neutral-800 dark:border-neutral-600 dark:text-neutral-200"
                                onchange="document.getElementById('filterForm').submit()"
                            >
                                <option value="">--Select Owner--</option>
                                @foreach($owners as $owner)
                                    <option value="{{ $owner->owner_id }}" {{ request('owner_id') == $owner->owner_id ? 'selected' : '' }}>{{ $owner->user->complete_name }}</option>
                                @endforeach
                            </select>

                            <div class="flex items-center gap-4">
                                <input type="date" name="fromDate" class="form-input w-40 border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-800 dark:text-neutral-200 shadow-sm hover:border-gray-400 transition-all" value="{{ request('fromDate') }}" onchange="this.form.submit()">
                            </div>
                            <h5><b>TO</b></h5>
                            <div class="flex items-center gap-4">
                                <input type="date" name="toDate" class="form-input w-40 border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-800 dark:text-neutral-200 shadow-sm hover:border-gray-400 transition-all" value="{{ request('toDate') }}" onchange="this.form.submit()">
                            </div>
                            <a class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-green-600 text-white hover:bg-green-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none" href="{{ route('rec.add-animal-form') }}">
                                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                                Add Animal
                            </a>

                            <a 
                                href="{{ route('rec-animals') }}" 
                                class="py-2 px-4 rounded-lg bg-gray-500 text-white hover:bg-gray-700"
                            >
                                Reset
                            </a>
                        </form>

                        <!-- Table -->
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                            <thead class="bg-gray-50 dark:bg-neutral-800">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-start">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200"></span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-start">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Animal Name</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-start">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Owner</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-start">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Specie and Breed</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-start">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Latest Veterinarian</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-start">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Latest Transaction</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-start">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Created</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-start">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">Actions</span>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-end"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                                @foreach($animals as $animal)
                                <tr>
                                    <td class="px-6 py-3 text-start">
                                        <img src="{{ asset($animal->photo_front ? 'storage/' . $animal->photo_front : 'assets/default-avatar.png') }}" alt="Animal Photo" class="w-16 h-16 object-cover rounded">
                                    </td>
                                    <td class="px-6 py-3 text-start">
                                        <a href="{{ route('rec.profile', ['animal_id' => $animal->animal_id]) }}" 
                                        class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">
                                            <strong>{{ $animal->name }}</strong>
                                        </a>
                                    </td>
                                    
                                <td class="px-6 py-3 text-start">
    <a href="{{ route('rec.profile-owner', ['owner_id' => $animal->owner->owner_id]) }}" class="text-blue-500 hover:text-blue-700 font-bold">
        {{ $animal->owner->user->complete_name }}
    </a><br>
    {{ $animal->owner->user->contact_no }}<br>
    @if ($animal->owner->user->address && $animal->owner->user->address->barangay)
        {{ $animal->owner->user->address->barangay->barangay_name }}
    @else
        No Barangay Available
    @endif
</td>

                                    <td class="px-6 py-3 text-start">
                                        <div class="text-sm text-gray-500 dark:text-neutral-400">
                                            {{ $animal->species->name }}<br>
                                            {{ $animal->breed->name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 text-start">
                                        @if($animal->transactions->isNotEmpty())
                                            @php
                                                $latestTransaction = $animal->transactions->sortByDesc('created_at')->first();
                                            @endphp
                                            @if ($latestTransaction)
                                                <!-- Display Veterinarian Information -->
                                                @if ($latestTransaction->vet)
                                                    <strong>Veterinarian: {{ $latestTransaction->vet->complete_name }}</strong><br>
                                                    Contact: {{ $latestTransaction->vet->contact_no }}<br>
                                                @else
                                                    <p>No veterinarian assigned for the latest transaction.</p>
                                                @endif
                                                
                                                <!-- Display Veterinary Technician Information -->
                                                @if ($latestTransaction->technician)
                                                    <strong>Technician: {{ $latestTransaction->technician->full_name }}</strong><br>
                                                    Contact: {{ $latestTransaction->technician->contact_number }}<br>

                                                @else
                                                    <p>No technician assigned for the latest transaction.</p>
                                                @endif
                                            @else
                                                <p>No details available for the latest transaction.</p>
                                            @endif
                                        @else
                                            <p>No transactions for this animal.</p>
                                        @endif
                                    </td>
                                    
                                    <td class="px-6 py-3 text-start">
                                        @if ($animal->transactions->isNotEmpty())
                                            @php
                                                $latestTransaction = $animal->transactions->sortByDesc('created_at')->first();
                                            @endphp
                                            @if ($latestTransaction->transactionSubtype && $latestTransaction->transactionSubtype->id == 8)
                                                {{ $latestTransaction->transactionSubtype->subtype_name }} - 
                                                {{ $latestTransaction->vaccine ? $latestTransaction->vaccine->vaccine_name : 'No Vaccine Selected' }}
                                            @else
                                                {{ $latestTransaction->transactionSubtype ? $latestTransaction->transactionSubtype->subtype_name : 'N/A' }}
                                            @endif
                                            <br>
                                            Status: {{ ['Pending', 'Completed', 'Canceled'][$latestTransaction->status] ?? 'Unknown' }}
                                        @else
                                            <p>No transactions for this animal.</p>
                                        @endif
                                    </td>
                                    
                                    <td class="px-6 py-3 text-start">
                                        <div class="text-sm text-gray-500 dark:text-neutral-400">
                                            {{ $animal->created_at->format('Y-m-d H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 text-start">
                                        <div class="flex gap-2">
                                            <!-- Update Button -->
                                            <a href="{{ route('rec-animals.edit', ['animal_id' => $animal->animal_id]) }}" 
                                                class="py-1 px-2 text-sm text-white bg-blue-500 rounded hover:bg-blue-600">
                                                Update
                                            </a>
                                        
                                        </div>
                                        
                                    </td>
                                    <td class="px-6 py-3 text-end">
                                        <!-- Actions (e.g., Edit, Delete) -->
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div class="px-6 py-3">
                            {{ $animals->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    
</x-app-layout>
