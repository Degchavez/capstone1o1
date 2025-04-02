<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-[96rem] mx-auto">
        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-5">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-slate-800 font-bold">Animals</h1>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <!-- Add animal button -->
                <a class="btn bg-indigo-500 hover:bg-indigo-600 text-white" href="{{ route('rec.add-animal-form') }}">
                    <svg class="w-4 h-4 fill-current opacity-50 shrink-0" viewBox="0 0 16 16">
                        <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                    </svg>
                    <span class="hidden xs:block ml-2">Add Animal</span>
                </a>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white border border-slate-200 rounded-sm shadow-lg">
            <!-- Search and filters -->
            <div class="border-b border-slate-200">
                <form class="p-4" method="GET" action="{{ route('rec-animals') }}" id="filterForm">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                        <!-- Search field -->
                        <div>
                            <label class="block text-sm font-medium mb-1" for="search">Search</label>
                            <input id="search" name="search" class="form-input w-full" type="text" placeholder="Search by name…" value="{{ request('search') }}" oninput="document.getElementById('filterForm').submit()">
                        </div>
                        <!-- Species -->
                        <div>
                            <label class="block text-sm font-medium mb-1" for="species">Species</label>
                            <select id="species" name="species_id" class="form-select w-full" onchange="document.getElementById('filterForm').submit()">
                                <option value="">All Species</option>
                                @foreach($species as $specie)
                                    <option value="{{ $specie->id }}" {{ request('species_id') == $specie->id ? 'selected' : '' }}>{{ $specie->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Breed -->
                        <div>
                            <label class="block text-sm font-medium mb-1" for="breed">Breed</label>
                            <select id="breed" name="breed_id" class="form-select w-full" onchange="document.getElementById('filterForm').submit()">
                                <option value="">All Breeds</option>
                                @foreach($breeds as $breed)
                                    <option value="{{ $breed->id }}" {{ request('breed_id') == $breed->id ? 'selected' : '' }}>{{ $breed->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Owner -->
                        <div>
                            <label class="block text-sm font-medium mb-1" for="owner">Owner</label>
                            <select id="owner" name="owner_id" class="form-select w-full" onchange="document.getElementById('filterForm').submit()">
                                <option value="">All Owners</option>
                                @foreach($owners as $owner)
                                    <option value="{{ $owner->owner_id }}" {{ request('owner_id') == $owner->owner_id ? 'selected' : '' }}>{{ $owner->user->complete_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Date range -->
                        <div>
                            <label class="block text-sm font-medium mb-1">Date Range</label>
                            <div class="flex items-center space-x-2">
                                <input type="date" name="fromDate" class="form-input w-full text-sm" value="{{ request('fromDate') }}" onchange="this.form.submit()">
                                <span class="text-slate-400">-</span>
                                <input type="date" name="toDate" class="form-input w-full text-sm" value="{{ request('toDate') }}" onchange="this.form.submit()">
                            </div>
                        </div>
                        <!-- Reset button -->
                        <div class="flex items-end">
                            <a href="{{ route('rec-animals') }}" class="btn border-slate-200 hover:border-slate-300 text-slate-600">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 16 16">
                                    <path d="M7.2 11.2c-.85 0-1.65-.33-2.25-.93-.6-.6-.93-1.4-.93-2.25 0-.85.33-1.65.93-2.25.6-.6 1.4-.93 2.25-.93.85 0 1.65.33 2.25.93.6.6.93 1.4.93 2.25 0 .85-.33 1.65-.93 2.25-.6.6-1.4.93-2.25.93zm0-8c-1.36 0-2.64.53-3.6 1.49-.96.96-1.49 2.24-1.49 3.6 0 1.36.53 2.64 1.49 3.6.96.96 2.24 1.49 3.6 1.49 1.36 0 2.64-.53 3.6-1.49.96-.96 1.49-2.24 1.49-3.6 0-1.36-.53-2.64-1.49-3.6-.96-.96-2.24-1.49-3.6-1.49z"/>
                                </svg>
                                <span class="ml-2">Reset</span>
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <!-- Table header -->
                    <thead class="text-xs font-semibold uppercase text-slate-500 bg-slate-50 border-t border-b border-slate-200">
                        <tr>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <span class="font-semibold text-left">Photo</span>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <span class="font-semibold text-left">Name</span>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <span class="font-semibold text-left">Owner</span>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <span class="font-semibold text-left">Species & Breed</span>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <span class="font-semibold text-left">Latest Veterinarian</span>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <span class="font-semibold text-left">Latest Transaction</span>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <span class="font-semibold text-left">Vaccination</span>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <span class="font-semibold text-left">Created</span>
                            </th>
                            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                <span class="font-semibold text-left">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <!-- Table body -->
                    <tbody class="text-sm divide-y divide-slate-200">
                        @foreach($animals as $animal)
                            <tr>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="w-10 h-10 shrink-0">
                                        <img class="w-10 h-10 rounded-full" src="{{ asset($animal->photo_front ? 'storage/' . $animal->photo_front : 'assets/default-avatar.png') }}" alt="Animal photo">
                                    </div>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <a href="{{ route('rec.profile', ['animal_id' => $animal->animal_id]) }}" class="font-medium text-indigo-500 hover:text-indigo-600">{{ $animal->name }}</a>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="font-medium">
                                        <a href="{{ route('rec.profile-owner', ['owner_id' => $animal->owner->owner_id]) }}" class="text-indigo-500 hover:text-indigo-600">
                                            {{ $animal->owner->user->complete_name }}
                                        </a>
                                    </div>
                                    <div class="text-slate-500 text-xs">{{ $animal->owner->user->contact_no }}</div>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="font-medium text-slate-800">{{ $animal->species->name ?? 'N/A' }}</div>
                                    <div class="text-slate-500 text-xs">{{ $animal->breed->name ?? 'N/A' }}</div>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    @if($animal->transactions->isNotEmpty())
                                        @php $latestTransaction = $animal->transactions->sortByDesc('created_at')->first(); @endphp
                                        @if($latestTransaction->vet)
                                            <div class="font-medium">
                                                <a href="{{ route('rec.veterinarian.profile', $latestTransaction->vet->user_id) }}" class="text-indigo-500 hover:text-indigo-600">
                                                    {{ $latestTransaction->vet->complete_name }}
                                                </a>
                                            </div>
                                            <div class="text-slate-500 text-xs">{{ $latestTransaction->vet->contact_no }}</div>
                                        @else
                                            <div class="text-slate-500 text-xs">No veterinarian assigned</div>
                                        @endif
                                    @else
                                        <div class="text-slate-500 text-xs">No transactions</div>
                                    @endif
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    @if($animal->transactions->isNotEmpty())
                                        <div class="text-left font-medium text-slate-800">{{ $latestTransaction->transactionSubtype->subtype_name ?? 'N/A' }}</div>
                                        <div class="text-slate-500 text-xs">Status: {{ ['Pending', 'Completed', 'Canceled'][$latestTransaction->status] ?? 'Unknown' }}</div>
                                    @else
                                        <div class="text-slate-500 text-xs">No transactions</div>
                                    @endif
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    @if($animal->is_vaccinated == 1)
                                        <div class="inline-flex font-medium rounded-full text-center px-2.5 py-0.5 bg-emerald-100 text-emerald-600">Vaccinated</div>
                                    @elseif($animal->is_vaccinated == 2)
                                        <div class="inline-flex font-medium rounded-full text-center px-2.5 py-0.5 bg-slate-100 text-slate-600">Not Required</div>
                                    @else
                                        <div class="inline-flex font-medium rounded-full text-center px-2.5 py-0.5 bg-rose-100 text-rose-600">Not Vaccinated</div>
                                    @endif
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                    <div class="text-slate-500">{{ $animal->created_at->format('M d, Y') }}</div>
                                    <div class="text-slate-500 text-xs">{{ $animal->created_at->format('h:i A') }}</div>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                    <a href="{{ route('rec-animals.edit', ['animal_id' => $animal->animal_id]) }}" class="text-indigo-500 hover:text-indigo-600 rounded-full">
                                        <span class="sr-only">Edit</span>
                                        <svg class="w-8 h-8 fill-current" viewBox="0 0 32 32">
                                            <path d="M19.7 8.3c-.4-.4-1-.4-1.4 0l-10 10c-.2.2-.3.4-.3.7v4c0 .6.4 1 1 1h4c.3 0 .5-.1.7-.3l10-10c.4-.4.4-1 0-1.4l-4-4zM12.6 22H10v-2.6l6-6 2.6 2.6-6 6zm7.4-7.4L17.4 12l1.6-1.6 2.6 2.6-1.6 1.6z"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $animals->links() }}
        </div>
    </div>
</x-app-layout>
