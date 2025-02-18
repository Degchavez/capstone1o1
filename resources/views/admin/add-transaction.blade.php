<x-app-layout>
    <div class="container mx-auto my-8 p-6 bg-white shadow-lg rounded-lg border border-gray-300">
        <h1 class="text-2xl font-bold text-center mb-6">Add a Transaction for Owner: {{ $owner->user->complete_name }}</h1>

        <!-- Display Success Message -->
        @if(session('success'))
            <div class="alert alert-success mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md">
                {{ session('success') }}
            </div>
        @endif

        <!-- Transaction Form -->
        <form action="{{ route('owner.addTransaction', $owner_id) }}" method="POST">
            @csrf

            <!-- Animal Selection -->
            <div class="mb-4">
                <label for="animal_id" class="block text-sm font-medium text-gray-700">Select Animal</label>
                <select name="animal_id" id="animal_id" class="w-full p-2 border border-gray-300 rounded-md">
                    <option value="">Select Animal</option>

                    @foreach($animals as $animal)
                    <option value="{{ $animal->animal_id }}">
                        {{ $animal->name }} - 
                        {{ $animal->species ? $animal->species->name : 'Species not specified' }} - 
                        {{ $animal->breed ? $animal->breed->name : 'Breed not specified' }}
                    </option>
                @endforeach
                
                </select>
                @error('animal_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Transaction Type -->
            <select name="transaction_type_id" id="transaction_type_id" class="w-full p-2 border border-gray-300 rounded-md">
                <option value="">Select Transaction</option>
                @foreach($transactionTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->type_name }}</option>
                @endforeach
            </select>
            

            <!-- Transaction Subtype -->
            <select name="transaction_subtype_id" id="transaction_subtype_id" class="w-full p-2 border border-gray-300 rounded-md">
                <option value="">Select Transaction Type</option>
            </select>

            <!-- Transaction Vaccine -->
           <!-- Vaccine Dropdown (only show if 'Vaccination' subtype is selected) -->
<div id="vaccine_dropdown" style="display: none;">
    <label for="vaccine_id" class="block text-sm font-medium text-gray-600">Select Vaccine</label>
    <select name="vaccine_id" id="vaccine_id" class="w-full p-3 border border-gray-300 rounded-md">
        <option value="">Select a Vaccine</option>
        @foreach ($vaccines as $vaccine)
            <option value="{{ $vaccine->id }}" {{ old('vaccine_id') == $vaccine->id ? 'selected' : '' }}>
                {{ $vaccine->vaccine_name }}
            </option>
        @endforeach
    </select>
</div>

            <!-- Veterinarian Selection -->
            <div class="mb-4">
                <label for="vet_id" class="block text-sm font-medium text-gray-700">Select Veterinarian</label>
                <select name="vet_id" id="vet_id" class="w-full p-2 border border-gray-300 rounded-md">
                    <option value="">Select Veterinarian</option>

                    @foreach($vets as $vet)
                        <option value="{{ $vet->user_id }}">{{ $vet->complete_name }}</option>
                    @endforeach
                </select>
                @error('vet_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <!-- Technician Selection -->   
            <div>
                <label for="technician_id" class="block text-sm font-medium text-gray-600">Select Technician</label>
                <select name="technician_id" id="technician_id" class="w-full p-2 border border-gray-300 rounded-md">
                    <option value="">Select Technician</option>
                    @foreach($technicians as $technician)
                        <option value="{{ $technician->technician_id }}" {{ old('technician_id') == $technician->technician_id ? 'selected' : '' }}>
                            {{ $technician->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            

            <!-- Transaction Date -->
      {{--      <div class="mb-4">
                <label for="transaction_date" class="block text-sm font-medium text-gray-700">Transaction Date</label>
                <input type="date" name="transaction_date" id="transaction_date" class="w-full p-2 border border-gray-300 rounded-md" >
                @error('transaction_date')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div> --}}

            <!-- Status -->
            <div class="mb-4" hidden>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status" class="w-full p-2 border border-gray-300 rounded-md" required>
                    <option value="0">Pending</option>
                    <option value="1">Completed</option>
                    <option value="2">Cancelled</option>
                </select>
                @error('status')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            

            <!-- Details -->
            <div class="mb-4">
                <label for="details" class="block text-sm font-medium text-gray-700">Details</label>
                <textarea name="details" id="details" class="w-full p-2 border border-gray-300 rounded-md" rows="4"></textarea>
                @error('details')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex justify-between items-center mt-6">
                <button type="submit" class="bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600 transition-colors duration-300">
                    Add Transaction
                </button>

                <a href="{{ route('owners.profile-owner', ['owner_id' => $owner_id]) }}" class="bg-gray-500 text-white p-2 rounded-md hover:bg-gray-600 transition-colors duration-300">
                    Cancel
                </a>
            </div>
        </form>
    </div>
    <script>
        document.getElementById('transaction_type_id').addEventListener('change', function () {
            const transactionTypeId = this.value;
            const subtypeSelect = document.getElementById('transaction_subtype_id');
            const transactionData = @json($transactionTypes);
    
            // Clear existing options
            subtypeSelect.innerHTML = '<option value="">Select Transaction Type</option>';
    
            if (transactionTypeId) {
                // Find the selected transaction type's subtypes
                const selectedType = transactionData.find(type => type.id == transactionTypeId);
    
                if (selectedType && selectedType.subtypes) {
                    selectedType.subtypes.forEach(subtype => {
                        const option = document.createElement('option');
                        option.value = subtype.id;
                        option.textContent = subtype.subtype_name;
                        subtypeSelect.appendChild(option);
                    });
    
                    // Automatically trigger change event to toggle vaccine dropdown
                    subtypeSelect.dispatchEvent(new Event('change'));
                }
            }
        });
    
        document.getElementById('transaction_subtype_id').addEventListener('change', function () {
            const selectedSubtypeId = this.value;
            toggleVaccineDropdown(selectedSubtypeId);
        });
    
        function toggleVaccineDropdown(subtypeId) {
            // Assuming "8" is the ID for "Vaccination" in your transaction_subtypes table
            const vaccineDropdown = document.getElementById('vaccine_dropdown');
            if (subtypeId == '8') {
                vaccineDropdown.style.display = 'block'; // Show vaccine dropdown
            } else {
                vaccineDropdown.style.display = 'none'; // Hide vaccine dropdown
            }
        }
    
        // Trigger subtype and vaccine dropdown visibility on page load if needed
        document.addEventListener('DOMContentLoaded', function () {
            const initialSubtypeId = document.getElementById('transaction_subtype_id').value;
            toggleVaccineDropdown(initialSubtypeId);
        });
    </script>
    
    
</x-app-layout>
