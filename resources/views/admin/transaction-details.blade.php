
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto p-6 lg:p-8">
            <!-- Header Section -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Transaction Details</h1>
                    <p class="mt-2 text-gray-500">Detailed information about transaction #{{ $transaction->transaction_id }}</p>
                </div>
                <a href="{{ route('admin-dashboard') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                    </svg>
                    Back to Dashboard
                </a>
            </div>

            <!-- Main Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <!-- Status Banner -->
                <div class="px-6 py-4 border-b {{ 
                    $transaction->status == 0 ? 'bg-yellow-50 border-yellow-100' : 
                    ($transaction->status == 1 ? 'bg-green-50 border-green-100' : 'bg-red-50 border-red-100') 
                }}">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ 
                        $transaction->status == 0 ? 'text-yellow-800 bg-yellow-100' : 
                        ($transaction->status == 1 ? 'text-green-800 bg-green-100' : 'text-red-800 bg-red-100') 
                    }}">
                        {{ $transaction->status == 0 ? 'Pending' : ($transaction->status == 1 ? 'Completed' : 'Cancelled') }}
                    </span>
                </div>

                <!-- Grid Layout -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Transaction Overview -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Transaction Overview
                            </h3>
                            <dl class="grid grid-cols-2 gap-4">
                                <div class="col-span-1">
                                    <dt class="text-sm text-gray-500">Transaction ID</dt>
                                    <dd class="mt-1 text-sm text-gray-900">#{{ $transaction->transaction_id }}</dd>
                                </div>
                                <div class="col-span-1">
                                    <dt class="text-sm text-gray-500">Transaction Type</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $transaction->transactionType->type_name ?? 'N/A' }}
                                        @if($transaction->transactionSubtype)
                                            - {{ $transaction->transactionSubtype->subtype_name }}
                                        @endif
                                    </dd>
                                </div>
                                <div class="col-span-1">
                                    <dt class="text-sm text-gray-500">Date & Time</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $transaction->created_at->format('M j, Y \a\t g:i A') }}</dd>
                                </div>
                                @if($transaction->transactionSubtype?->id == 8) <!-- Vaccination -->
                                <div class="col-span-2">
                                    <dt class="text-sm text-gray-500">Vaccine Details</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $transaction->vaccine->vaccine_name ?? 'No vaccine selected' }}
                                        @if($transaction->vaccine)
                                            ({{ $transaction->vaccine->manufacturer }})
                                        @endif
                                    </dd>
                                </div>
                                @endif
                                @if($transaction->notes)
                                <div class="col-span-2">
                                    <dt class="text-sm text-gray-500">Additional Notes</dt>
                                    <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $transaction->notes }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>

                        <!-- Animal Details -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Animal Details
                            </h3>
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <img class="h-16 w-16 rounded-lg object-cover" 
                                         src="{{ $transaction->animal->photo_front ? asset('storage/' . $transaction->animal->photo_front) : asset('assets/default-animal.png') }}" 
                                         alt="Animal photo">
                                </div>
                                <div class="space-y-1">
                                    <p class="text-sm font-medium text-gray-900">
                                        <a href="{{ route('animals.profile', $transaction->animal->animal_id) }}" class="hover:text-blue-600">
                                            {{ $transaction->animal->name }}
                                        </a>
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ $transaction->animal->species->name ?? 'Unknown species' }} 
                                        ({{ $transaction->animal->breed->name ?? 'Unknown breed' }})
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        Age: {{ $transaction->animal->age }} years
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Owner Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Owner Information
                            </h3>
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <img class="h-16 w-16 rounded-lg object-cover" 
                                         src="{{ optional($transaction->owner->user)->profile_image ? asset('storage/' . $transaction->owner->user->profile_image) : asset('assets/default-avatar.png') }}" 
                                         alt="Owner profile photo">
                                </div>
                                <div class="space-y-1">
                                    <p class="text-sm font-medium text-gray-900">
                                        <a href="{{ route('owners.profile-owner', $transaction->owner->owner_id) }}" class="hover:text-blue-600">
                                            {{ optional($transaction->owner->user)->complete_name ?? 'N/A' }}
                                        </a>
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ optional($transaction->owner->user)->contact_no ?? 'No contact number' }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ optional($transaction->owner->user->address)->barangay->barangay_name ?? 'No address available' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Staff Information -->
                        <div class="space-y-4">
                            <!-- Veterinarian -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900 mb-2">Attending Veterinarian</h4>
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <img class="h-10 w-10 rounded-full object-cover" 
                                             src="{{ optional($transaction->vet)->profile_image ? asset('storage/' . $transaction->vet->profile_image) : asset('assets/default-avatar.png') }}" 
                                             alt="Veterinarian photo">
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">
                                            @if($transaction->vet)
                                                <a href="{{ route('admin.veterinarian.profile', $transaction->vet->user_id) }}" class="hover:text-blue-600">
                                                    {{ $transaction->vet->complete_name }}
                                                </a>
                                            @else
                                                Not assigned
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ optional($transaction->vet)->contact_no ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Technician -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900 mb-2">Assigned Technician</h4>
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <span class="inline-block h-10 w-10 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                            </svg>
                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $transaction->technician->full_name ?? 'Not assigned' }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ optional($transaction->technician)->contact_number ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
