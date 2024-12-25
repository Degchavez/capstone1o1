<x-app-layout>
    <div class="container mx-auto p-8 shadow-lg rounded-xl">
        <!-- Main Card Wrapper -->
        <div class="bg-white p-8 rounded-lg shadow-xl hover:shadow-2xl transition-all max-w-4xl mx-auto">
            
            <!-- Flex Container for Header Buttons -->
            <div class="flex justify-between items-center mb-6">
                <!-- Go Back Button -->
              
                
                <!-- Edit Profile Button -->
                <a href="{{ route('owner.owner-edit-form', ['id' => $owner->user_id]) }}" 
                   class="bg-yellow-500 text-white px-4 py-2 rounded-lg shadow hover:bg-yellow-600 transition-colors duration-300">
                    Edit Profile
                </a>
            </div>
            
            <!-- Profile Section -->
            <div class="flex flex-col items-center text-center">
                <!-- Profile Image -->
                <img class="w-36 h-36 object-cover rounded-full border-4 border-green-500 shadow-lg hover:scale-105 transition-transform duration-300" 
                     src="{{ $owner->profile_image ? Storage::url($owner->profile_image) : asset('assets/default-avatar.png') }}" 
                     alt="Profile Image">
                
                <!-- Profile Name -->
                <h1 class="text-3xl font-bold text-gray-800 mt-4">{{ $owner->complete_name }}</h1>
                
                <!-- Address -->
                <p class="text-sm text-gray-600 mt-2">
                    <i class="fas fa-map-marker-alt"></i> {{ $owner->street }}, {{ $owner->barangay_name }}
                </p>
                
                <!-- Member Since -->
                <p class="text-sm text-gray-500 mt-1">
                    <strong>Member Since:</strong> {{ $owner->created_at->format('F d, Y') }}
                </p>
            </div>
            
            <!-- Horizontal Divider -->
            <hr class="my-6 border-t-2 border-green-600">
            
            <!-- Contact Information -->
            <div class="grid grid-cols-2 gap-4 text-left">
                <p class="text-sm text-gray-700">
                    <strong>Email:</strong> {{ $owner->email }}
                </p>
                <p class="text-sm text-gray-700">
                    <strong>Contact No:</strong> {{ $owner->contact_no }}
                </p>
                <p class="text-sm text-gray-700">
                    <strong>Gender:</strong> {{ $owner->gender }}
                </p>
                <p class="text-sm text-gray-700">
                    <strong>Age:</strong> {{ \Carbon\Carbon::parse($owner->birth_date)->age }} yrs
                </p>
                <p class="text-sm text-gray-700">
                    <strong>Civil Status:</strong> {{ $owner->civil_status }}
                </p>
                <p class="text-sm text-gray-700">
                    <strong>Category:</strong> {{ $owner->category }}
                </p>
            </div>
        </div>
<br>
            <!-- Profile Sections Layout (Animals and Transactions) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Left Column: Animals Section -->
                <div class="space-y-6 lg:col-span-1">
                    <div class="bg-gray-50 p-6 rounded-lg shadow-md mb-6">
                        <h2 class="text-xl font-semibold text-black-600 mb-4">Animals Registered</h2>
                        
                        <!-- Add Animal Button -->
                        <a href="{{ route('owner.createAnimalForm', ['owner_id' => $owner->owner_id]) }}"
                            class="bg-green-500 text-white px-6 py-3 rounded-lg shadow-md hover:bg-green-600 transition-colors text-lg font-semibold mb-4 inline-block">
                            Add Animal
                        </a>
                    
                        <!-- Search and Filters Form (Automatically Submits on Change) -->
                        <form method="GET" action="{{ route('owners.profile', ['owner_id' => $owner->owner_id]) }}" class="space-y-4" id="filterForm">
                            <div class="flex space-x-4">
                                <!-- Search Input -->
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name"
                                    class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 w-full lg:w-1/3" 
                                    onchange="submitForm()">
                                  
                                <!-- Species Filter -->
                                <select name="species_id" class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 w-full lg:w-1/3" onchange="submitForm()">
                                    <option value="">Select All Species</option>
                                    @foreach($species as $specie)
                                        <option value="{{ $specie->id }}" {{ request('species_id') == $specie->id ? 'selected' : '' }}>
                                            {{ $specie->name }}
                                        </option>
                                    @endforeach
                                </select>
                    
                                <!-- Breed Filter -->
                                <select name="breed_id" class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 w-full lg:w-1/3" onchange="submitForm()">
                                    <option value="">Select All Breed</option>
                                    @foreach($breeds as $breed)
                                        <option value="{{ $breed->id }}" {{ request('breed_id') == $breed->id ? 'selected' : '' }}>
                                            {{ $breed->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Reset Button -->
                        
                        </form>
                    
                        <!-- Animals Table -->
                        @if($animals->isNotEmpty())
                        <div class="space-y-6 lg:col-span-1" id="animals-section">
                            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
                                    <thead class="bg-green-100">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Image</th>
                                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Name</th>
                                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Species</th>
                                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Breed</th>
                                          
                                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($animals as $animal)
                                            <tr class="hover:bg-gray-100 transition-all">
                                                <td class="px-6 py-3 text-sm">
                                                    <img 
                                                        src="{{ $animal->photo_front ? asset('storage/' . $animal->photo_front) : asset('assets/default-avatar.png') }}" 
                                                        alt="{{ $animal->name }}" 
                                                        class="w-12 h-12 rounded-full">
                                                </td>
                                                <td class="px-6 py-3 text-gray-800">
                                                    <b>
                                                        <a href="{{ route('newanimals.profile', ['animal_id' => $animal->animal_id]) }}" class="text-blue-600 hover:text-blue-800">
                                                            {{ $animal->name }}
                                                            @if ($animal->is_group)
                                                                ({{$animal->group_count}}
                                                             )
                                                            @endif
                                                        </a>
                                                        
                                                    </b>
                                                </td>
                                                
                                                                                                <td class="px-6 py-3 text-gray-600">{{ $animal->species->name }}</td>
                                                <td class="px-6 py-3 text-gray-600">{{ $animal->breed->name }}</td>
                                       
                                              
                                                     <td class="px-6 py-3 text-sm">

                                                        <div class="flex flex-col space-y-2">     
                                                            <!-- Update Button -->
                                                            <a href="{{ route('owner.NeweditAnimal', ['owner_id' => $owner->owner_id, 'animal_id' => $animal->animal_id]) }}" 
                                                               class="text-blue-500 hover:text-blue-700 px-4 py-2 border border-blue-500 rounded-md">
                                                                Update
                                                            </a>
                                                        
                                                            <!-- Delete Button -->
                                                         
                                                        </div>
                                                        
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                    
                                <!-- Pagination Controls -->
                                <div class="mt-6">
                                    {{ $animals->links() }}                                </div>
                            </div>
                        @else
                            <p class="text-sm text-gray-600 text-center mt-4">No animals registered yet. Try adjusting the filters or adding an animal.</p>
                        @endif
                    </div>
                </div>

               <!-- Right Column: Transactions Section (Feed Style) -->
               <div class="space-y-6 lg:col-span-1">
                <div class="bg-gray-50 p-6 rounded-lg shadow-md mb-6">
                 
                    <h2 class="text-xl font-semibold text-black-600 mb-4">Recent Transactions</h2>
                    
                    @if($owner->transactions->isNotEmpty())
                        <div class="space-y-4">
                            @foreach($owner->transactions->take(4) as $transaction)

                          <!-- Delete Button -->
                        
                             
                        </form>
            


                                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition-all border-l-4 
                                            @if($transaction->status == 0) border-yellow-500 
                                            @elseif($transaction->status == 1) border-green-500 
                                            @elseif($transaction->status == 2) border-red-500 
                                            @else border-gray-500 @endif">
                                    <div class="flex items-center space-x-4">
                                        <!-- Transaction Icon/Avatar -->
                                        <div class="w-14 h-14 bg-gray-200 rounded-full overflow-hidden">

                                            <img src="{{ $transaction->animal && $transaction->animal->photo_front ? asset('storage/' . $transaction->animal->photo_front) : asset('assets/default-avatar.png') }}" 
                                            alt="Animal Avatar" class="w-full h-full object-cover">
                                       
                                        </div>
                                        <div class="flex-1">
                                            <!-- Transaction Description -->
                                            <p class="text-lg font-semibold text-gray-800">
                                                @if ($transaction->transactionSubtype && $transaction->transactionSubtype->id == 8)
                                                    {{ $transaction->transactionSubtype->subtype_name }} - 
                                                    {{ $transaction->vaccine ? $transaction->vaccine->vaccine_name : 'No Vaccine Selected' }}
                                                @else
                                                    {{ $transaction->transactionSubtype ? $transaction->transactionSubtype->subtype_name : 'N/A' }}
                                                @endif
                                            </p>
                                                                                            <p class="text-sm text-gray-600">
                                                <strong>Name:</strong> {{ $transaction->animal ? $transaction->animal->name : 'N/A' }}
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                <strong>Species:</strong> {{ $transaction->animal && $transaction->animal->species ? $transaction->animal->species->name : 'N/A' }}
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                <strong>Breed:</strong> {{ $transaction->animal && $transaction->animal->breed ? $transaction->animal->breed->name : 'N/A' }}
                                            </p>

                                           
                                            
                                            <p class="text-sm text-gray-600">
                                                <strong>Owner:</strong> {{ $owner->complete_name }} 
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                <strong>Veterinarian:</strong> {{ $transaction->vet ? $transaction->vet->complete_name : 'N/A' }}
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                <strong>Technician:</strong> {{ $transaction->technician ? $transaction->technician->full_name : 'N/A' }}
                                            </p>
                                            
                                        </div>
                                        <!-- Status & Date -->
                                        <div class="text-sm flex flex-col items-end space-y-1">
                                            <p class="text-sm text-gray-600">
                                                <strong>Details:</strong> {{ $transaction->details ?? 'N/A' }}
                                            </p>
                                            <p class="font-medium 
                                                @if($transaction->status == 0) text-yellow-500 
                                                @elseif($transaction->status == 1) text-green-500 
                                                @elseif($transaction->status == 2) text-red-500 
                                                @else text-gray-500 @endif">
                                                @if($transaction->status == 0) Pending 
                                                @elseif($transaction->status == 1) Completed 
                                                @elseif($transaction->status == 2) Canceled 
                                                @else Unknown @endif
                                            </p>
                                            <p class="text-xs text-gray-500">{{ $transaction->created_at->format('F d, Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!-- View All Transactions Button -->
                        <div class="text-center mt-4">
                            <a href="{{ route('owner.Newtransactions', ['owner_id' => $owner->owner_id]) }}" 
                               class="bg-yellow-500 text-white py-2 px-6 rounded-lg hover:bg-yellow-800 transition-colors font-medium">
                                View All Transactions
                            </a>
                        </div>
                    @else
                        <p class="text-sm text-gray-600 text-center">No transactions available.</p>
                    @endif
                </div>
            </div>
        </div>

    </div>

               
            </div>

        </div>

    </div>

    <script>
        function submitForm() {
            document.getElementById('filterForm').submit();
        }
    
        function resetFilters() {
            const form = document.getElementById('filterForm');
            form.reset();
            submitForm(); // Automatically apply filters after reset
        }
    
        // Scroll to the animals section after page load if filters exist
        window.addEventListener('load', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('search') || urlParams.has('species_id') || urlParams.has('breed_id')) {
                const animalsSection = document.getElementById('animals-section');
                if (animalsSection) {
                    animalsSection.scrollIntoView({ behavior: 'smooth' });
                }
            }

            
        });

        

    </script>
    
</x-app-layout>
