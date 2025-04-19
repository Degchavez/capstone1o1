
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto p-6 lg:p-8">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Transaction Details</h1>
                <p class="mt-2 text-gray-500">Detailed information about transaction #{{ $animal->animal_id }}</p>
            </div>
            <div class="flex space-x-4">
                <a href="#" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17v3a2 2 0 002 2h14a2 2 0 002-2v-3M3 7v3a2 2 0 002 2h14a2 2 0 002-2V7"/>
                    </svg>
                    Download PDF
                </a>

            </div>
        </div>

        <!-- Main Card -->
        
        <div class="p-1 bg-gray-100">
            <div class="w-80 h-48 bg-white rounded-lg shadow-xl relative overflow-hidden border border-gray-300 font-sans text-[0.6rem]">
                <!-- Header Section -->
                <div class="bg-green-800 text-white py-1 px-2 flex justify-between items-center">
                    <div class="flex items-center gap-1">
                        <img src="{{ asset('assets/1.jpg') }}" class="w-5 h-5 rounded-full border border-yellow-400 object-cover">
                        <div>
                            <p class="text-[0.65rem] font-semibold leading-tight">City Veterinary Office</p>
                            <p class="text-[0.5rem] text-yellow-200 leading-tight">Valencia City Bukidnon</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-[0.5rem] text-yellow-200 uppercase">Animal ID</p>
                        <p class="text-[0.5rem] font-mono">{{ strtoupper($animal->animal_id) }}</p>
                    </div>
                </div>
        
                <!-- Main Content -->
                <div class="grid grid-cols-3 h-[calc(100%-1.8rem)] p-1">
                    <!-- Left Column - Photo -->
                    <div class="border-r border-gray-200 pr-1">
                        <div class="w-full aspect-square border border-gray-300 rounded overflow-hidden">
                            @if($animal->photo_front)
                                <img src="{{ asset('storage/' . $animal->photo_front) }}" alt="{{ $animal->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                    </div>
        
                    <!-- Right Column - Information -->
                    <div class="col-span-2 pl-1 relative">
                        <!-- QR Code in Upper Right Corner -->
                        <div class="absolute top-0 right-0 w-14 h-14 bg-white p-0.5 rounded-bl border border-gray-300">
                            {!! QrCode::size(48)->margin(0)->color(22, 82, 58)->generate(route('animal.id', $animal->animal_id)) !!}
                        </div>
        
                        <!-- Animal Details -->
                        <div class="pr-14"> <!-- Padding to avoid QR overlap -->
                            <div class="flex items-baseline gap-0.5 mb-0.5">
                                <h2 class="text-[0.7rem] font-bold uppercase tracking-tight text-gray-800 truncate">{{ $animal->name }}</h2>
                                <span class="text-gray-500">({{ $animal->gender ? $animal->gender[0] : '-' }})</span>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-x-0.5 gap-y-0.5">
                                <div class="col-span-2 flex items-center gap-0.5">
                                    <span class="font-semibold text-green-800">{{ $animal->species->name ?? 'Species N/A' }}</span>
                                    @if($animal->breed)
                                    <span class="text-yellow-600 bg-yellow-100 px-0.5 rounded">{{ $animal->breed->name }}</span>
                                    @endif
                                </div>
                                
                                <div>
                                    <p class="text-gray-500">Color:</p>
                                    <p class="font-medium">{{ $animal->color ?? 'N/A' }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-gray-500">Age/DOB:</p>
                                    <p class="font-medium">
                                        @if($animal->birth_date)
                                            {{ (int)\Carbon\Carbon::parse($animal->birth_date)->diffInYears(now()) }}y
                                            ({{ $animal->birth_date->format('m/d/y') }})
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                </div>
                                
                                <div>
                   
                                    <p class="text-gray-500">Owner:</p>
                                </div>
                                
                                <div>
                                    <p class="text-gray-500">Status</p>
                                    <p class="font-medium">
                                        @if($animal->is_vaccinated == 1)
                                            <span class="text-green-600">Vax</span>
                                        @elseif($animal->is_vaccinated == 2)
                                            <span class="text-yellow-600">Part</span>
                                        @else
                                            <span class="text-red-600">No Vax</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
        
                        <!-- Owner Information -->
                        <div class="pt-0.5 border-t border-gray-200 mt-0.5">
                            <div class="flex gap-0.5 items-center">
                                <div class="w-5 h-5 bg-gray-200 rounded-full overflow-hidden shrink-0">
                                    @if(optional($animal->owner->user)->profile_image)
                                        <img src="{{ asset('storage/' . $animal->owner->user->profile_image) }}" class="w-full h-full object-cover" alt="Owner">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-500">
                                            <svg viewBox="0 0 20 20" fill="currentColor" class="w-2.5 h-2.5">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="overflow-hidden">
                                    <p class="font-bold truncate">{{ optional($animal->owner->user)->complete_name ?? 'Owner N/A' }}</p>
                                    <p class="text-gray-600 truncate">{{ optional($animal->owner->user)->contact_no ?? 'Contact N/A' }}</p>
                                  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        
                <!-- Footer -->
                <div class="absolute bottom-0 w-full bg-gray-100 py-0.5 px-1 flex justify-between items-center border-t border-gray-300">
                    <span class="font-mono text-gray-600 truncate">Reg: {{ $animal->created_at->format('m/d/y') }}</span>
                    <span class="text-green-800 font-medium">Valid: {{ now()->addYear()->format('m/y') }}</span>
                </div>
        
                <!-- Security Features -->
                <div class="absolute bottom-8 right-1 rotate-45 text-gray-200 text-[0.4rem] font-bold tracking-widest opacity-50">
                    OFFICIAL
                </div>
            </div>
        </div>
    </div>
</div>
