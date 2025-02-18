<x-app-layout>
    <div class="max-w-6xl mx-auto px-4 py-6">
        <!-- Transaction Edit Form -->
        <div class="bg-white dark:bg-neutral-800 border rounded-lg p-6 shadow-sm">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-neutral-200">Edit Transaction</h2>

            <form method="POST" action="{{ route('transactions.update', $transaction->transaction_id) }}" class="mt-4">
                @csrf
                @method('PUT') <!-- Ensures the correct HTTP method for update -->

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Transaction Type -->
                    <div>
                        <label for="transaction_type_id" class="block text-sm font-medium text-gray-600 dark:text-neutral-400">Transaction</label>
                        <select id="transaction_type_id" name="transaction_type_id" class="w-full border border-gray-300 rounded-lg dark:bg-neutral-800 dark:border-neutral-600 dark:text-neutral-200">
                            <option value="" selected disabled>Select Transaction Type</option>
                            @foreach($transactionTypes as $type)
                                <option value="{{ $type->id }}" {{ old('transaction_type_id', $transaction->transaction_type_id) == $type->id ? 'selected' : '' }}>
                                    {{ $type->type_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Transaction Subtype -->
                    <div>
                        <label for="transaction_subtype_id" class="block text-sm font-medium text-gray-600 dark:text-neutral-400">Transaction Type</label>
                        <select id="transaction_subtype_id" name="transaction_subtype_id" class="form-control">
                            <option value="" disabled>Select a subtype</option>
                            <!-- Subtypes will be populated by JavaScript -->
                        </select>
                    </div>

                    <!-- Veterinarian -->
                    <div>
                        <label for="vet_id" class="block text-sm font-medium text-gray-600 dark:text-neutral-400">Veterinarian</label>
                        <select id="vet_id" name="vet_id" class="w-full border border-gray-300 rounded-lg dark:bg-neutral-800 dark:border-neutral-600 dark:text-neutral-200">
                            @foreach($vets as $vet)
                                <option value="{{ $vet->user_id }}" {{ old('vet_id', $transaction->vet_id) == $vet->user_id ? 'selected' : '' }}>
                                    {{ $vet->complete_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Veterinary Technician -->
                    <div>
                        <label for="technician_id" class="block text-sm font-medium text-gray-600 dark:text-neutral-400">Veterinary Technician</label>
                        <select id="technician_id" name="technician_id" class="w-full border border-gray-300 rounded-lg dark:bg-neutral-800 dark:border-neutral-600 dark:text-neutral-200">
                            <option value="">Select Technician (Optional)</option>
                            @foreach($technicians as $technician)
                                <option value="{{ $technician->technician_id }}" {{ old('technician_id', $transaction->technician_id) == $technician->technician_id ? 'selected' : '' }}>
                                    {{ $technician->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Vaccine -->
                    <div id="vaccine_field" class="hidden">
                        <label for="vaccine_id" class="block text-sm font-medium text-gray-600 dark:text-neutral-400">Vaccine</label>
                        <select id="vaccine_id" name="vaccine_id" class="w-full border border-gray-300 rounded-lg dark:bg-neutral-800 dark:border-neutral-600 dark:text-neutral-200">
                            <option value="">Select Vaccine (Optional)</option>
                            @foreach($vaccines as $vaccine)
                                <option value="{{ $vaccine->id }}" {{ old('vaccine_id', $transaction->vaccine_id) == $vaccine->id ? 'selected' : '' }}>
                                    {{ $vaccine->vaccine_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
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
                    
                </div>

                <div class="mt-4">
                    <label for="details" class="block text-sm font-medium text-gray-600 dark:text-neutral-400">Notes</label>
                    <textarea id="details" name="details" rows="4" class="w-full border border-gray-300 rounded-lg dark:bg-neutral-800 dark:border-neutral-600 dark:text-neutral-200">{{ old('details', $transaction->details) }}</textarea>
                </div>

                <div class="mt-4">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Update Transaction</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const typeSelect = document.getElementById('transaction_type_id');
            const subtypeSelect = document.getElementById('transaction_subtype_id');
            const vaccineField = document.getElementById('vaccine_field');

            // Subtypes data passed from the backend
            const subtypesByType = @json($subtypesByType);
            const selectedSubtypeId = "{{ old('transaction_subtype_id', $transaction->transaction_subtype_id) }}";

            // Populate subtypes based on type
            function populateSubtypes(typeId) {
                subtypeSelect.innerHTML = '<option value="" selected disabled>Select Subtype</option>';
                if (subtypesByType[typeId]) {
                    subtypesByType[typeId].forEach(subtype => {
                        const option = document.createElement('option');
                        option.value = subtype.id;
                        option.textContent = subtype.subtype_name;

                        // Set the selected option if it matches the previously selected subtype
                        if (selectedSubtypeId && selectedSubtypeId == subtype.id) {
                            option.selected = true;
                        }

                        subtypeSelect.appendChild(option);
                    });
                }
            }

            // Show/hide vaccine field based on the selected subtype
            function toggleVaccineField() {
                const selectedSubtypeId = subtypeSelect.value;
                // Assuming 8 is the ID for "Vaccination" subtype
                vaccineField.classList.toggle('hidden', selectedSubtypeId !== '8');
            }

            // Event Listeners
            typeSelect.addEventListener('change', () => {
                populateSubtypes(typeSelect.value);
                toggleVaccineField();
            });

            subtypeSelect.addEventListener('change', toggleVaccineField);

            // Initialize form on page load
            populateSubtypes(typeSelect.value);
            toggleVaccineField();
        });
    </script>

</x-app-layout>
