<x-app-layout>
    <div class="container mx-auto my-8 p-6 bg-white shadow-lg rounded-lg border border-gray-300">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <!-- Card Wrapper -->
                <div class="card shadow-lg border-0 rounded-lg">
                    <!-- Card Header -->
                    <div class="card-header bg-primary text-black text-center py-4">
                        <h4 class="mb-0">
                            <i class="bi bi-pencil-square"></i> Edit Transaction
                        </h4>
                        <p class="mb-0"><small>Animal: <strong>{{ $transaction->animal->name }}</strong></small></p>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body p-4">
                        <!-- Success Message -->
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Edit Transaction Form -->
                        <form action="{{ route('rec.updateTransaction', $transaction->transaction_id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Animal Selection -->
                                <div class="mb-4">
                                    <label for="animal_id" class="block text-sm font-medium text-gray-700">Select Animal</label>
                                    <select name="animal_id" id="animal_id" class="w-full p-2 border border-gray-300 rounded-md" required>
                                        <option value="">Select animal</option>
                                        @foreach($animals as $animal)
                                            <option value="{{ $animal->animal_id }}" {{ old('animal_id', $transaction->animal_id) == $animal->animal_id ? 'selected' : '' }}>
                                                {{ $animal->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('animal_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                            <!-- Transaction Type and Subtype -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="transaction_type_id" class="block text-sm font-medium text-gray-700">Transaction</label>
                                    <select name="transaction_type_id" id="transaction_type_id" class="w-full p-2 border border-gray-300 rounded-md" required>
                                        <option value="">Select a Transaction</option>

                                        @foreach($transactionTypes as $type)
                                            <option value="{{ $type->id }}" {{ old('transaction_type_id', $transaction->transaction_type_id) == $type->id ? 'selected' : '' }}>
                                                {{ $type->type_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('transaction_type_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="transaction_subtype_id" class="block text-sm font-medium text-gray-700">Transaction Type</label>
                                    <select name="transaction_subtype_id" id="transaction_subtype_id" class="w-full p-2 border border-gray-300 rounded-md" required>

                                        <!-- Subtypes will be populated dynamically -->
                                    </select>
                                    @error('transaction_subtype_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div id="vaccine_dropdown" style="display: none;">
                                <label for="vaccine_id" class="block text-sm font-medium text-gray-600">Select Vaccine</label>
                                <select name="vaccine_id" id="vaccine_id" class="w-full p-3 border border-gray-300 rounded-md">
                                    <option value="">Select a Vaccine</option>
                                    @foreach ($vaccines as $vaccine)
                                        <option value="{{ $vaccine->id }}" {{ old('vaccine_id', $selectedVaccineId ?? '') == $vaccine->id ? 'selected' : '' }}>
                                            {{ $vaccine->vaccine_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Veterinarian Selection -->
                            <div class="mb-4">
                                <label for="vet_id" class="block text-sm font-medium text-gray-700">Select Veterinarian</label>
                                <select name="vet_id" id="vet_id" class="w-full p-2 border border-gray-300 rounded-md" required>
                                    @foreach($vets as $vet)
                                        <option value="{{ $vet->user_id }}" {{ old('vet_id', $transaction->vet_id) == $vet->user_id ? 'selected' : '' }}>
                                            {{ $vet->complete_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('vet_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <!--technician -->
                            <div>
                                <label for="technician_id" class="block text-sm font-medium text-gray-600">Select Technician</label>
                                <select name="technician_id" id="technician_id" class="w-full p-2 border border-gray-300 rounded-md">
                                    <option value="">Select Technician</option>
                                    @foreach ($technicians as $technician)
                                        <option value="{{ $technician->technician_id }}" 
                                            {{ old('technician_id', $selectedTechnicianId) == $technician->technician_id ? 'selected' : '' }}>
                                            {{ $technician->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            

                            <!-- Transaction Date -->
                            <div class="mb-4">
                                <label for="created_at" class="block text-sm font-medium text-gray-700">Transaction Date</label>
                                <input type="date" name="created_at" id="created_at" class="w-full p-2 border border-gray-300 rounded-md" 
                                    value="{{ old('created_at', $transaction->created_at ? $transaction->created_at->format('Y-m-d') : '') }}" required>
                                @error('created_at')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Details -->
                            <div class="mb-4" hidden>
                                <label for="details" class="block text-sm font-medium text-gray-700">Details</label>
                                <textarea name="details" id="details" class="w-full p-2 border border-gray-300 rounded-md" rows="4">{{ old('details', $transaction->details) }}</textarea>
                                @error('details')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="mb-4" hidden>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status" class="w-full p-2 border border-gray-300 rounded-md">
                                    <option value="0" {{ old('status', $transaction->status) == 0 ? 'selected' : '' }}>Pending</option>
                                    <option value="1" {{ old('status', $transaction->status) == 1 ? 'selected' : '' }}>Completed</option>
                                    <option value="2" {{ old('status', $transaction->status) == 2 ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                @error('status')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-between items-center mt-6">
                                <button type="submit" class="bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600 transition-colors duration-300 ">
                                    Update Transaction
                                </button>

                                <a href="{{ route('owners.profile-owner', ['owner_id' => $owner_id]) }}" class="bg-gray-500 text-white p-2 rounded-md hover:bg-gray-600 transition-colors duration-300">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for dynamic transaction subtypes -->
    <script>
       document.addEventListener('DOMContentLoaded', function () {
    const transactionTypeSelect = document.getElementById('transaction_type_id');
    const transactionSubtypeSelect = document.getElementById('transaction_subtype_id');
    const vaccineDropdown = document.getElementById('vaccine_dropdown');

    function loadSubtypes(transactionTypeId) {
        const transactionData = @json($transactionTypes->toArray());
        const selectedType = transactionData.find(type => type.id == transactionTypeId)?.subtypes || [];

        transactionSubtypeSelect.innerHTML = '<option value="">Select Transaction Subtype</option>';
        selectedType.forEach(subtype => {
            const option = document.createElement('option');
            option.value = subtype.id;
            option.textContent = subtype.subtype_name;
            option.selected = subtype.id == '{{ $transaction->transaction_subtype_id }}'; // Preselect existing subtype
            transactionSubtypeSelect.appendChild(option);
        });

        toggleVaccineDropdown(transactionSubtypeSelect.value); // Check vaccine visibility
    }

    function toggleVaccineDropdown(subtypeId) {
        if (subtypeId == '8') { // Assuming '8' is the ID for "Vaccination"
            vaccineDropdown.style.display = 'block';
        } else {
            vaccineDropdown.style.display = 'none';
        }
    }

    // Initialize subtypes and vaccine visibility
    if (transactionTypeSelect.value) {
        loadSubtypes(transactionTypeSelect.value);
    }

    transactionTypeSelect.addEventListener('change', () => {
        loadSubtypes(transactionTypeSelect.value);
    });

    transactionSubtypeSelect.addEventListener('change', () => {
        toggleVaccineDropdown(transactionSubtypeSelect.value);
    });
});

        
    </script>
</x-app-layout>
