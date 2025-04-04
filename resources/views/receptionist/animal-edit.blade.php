<x-app-layout>
    <div class="container mx-auto p-6">
        <!-- Card Container -->
        <div class="bg-white shadow-lg rounded-lg p-8 max-w-3xl mx-auto">
            <h2 class="text-xl font-bold mb-6 text-center">Edit Animal</h2>

            <!-- Error and Success Messages -->
            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-4 rounded mb-6">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="bg-green-100 text-green-700 p-4 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Animal Form -->
            <form action="{{ route('rec.profileupdates', $animal->animal_id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

            

                <!-- Is Group? -->
                <div>
                    <label for="is_group" class="block text-sm font-medium text-gray-600">Is it a group?</label>
                    <select name="is_group" id="is_group" class="w-full p-3 border border-gray-300 rounded-md" onchange="toggleGroupFields()">
                        <option value="0" {{ old('is_group', $animal->is_group) == '0' ? 'selected' : '' }}>No</option>
                        <option value="1" {{ old('is_group', $animal->is_group) == '1' ? 'selected' : '' }}>Yes</option>
                    </select>
                </div>

                <div>
                    <label for="is_vaccinated" class="block text-sm font-medium text-gray-600">Vaccination Status</label>
                    <select name="is_vaccinated" id="is_vaccinated" class="w-full p-2 border border-gray-300 rounded-md">
                        <option value="" {{ old('is_vaccinated', $animal->is_vaccinated) === null ? 'selected' : '' }}>Select Vaccination Status</option>
                        <option value="0" {{ old('is_vaccinated', $animal->is_vaccinated) == '0' ? 'selected' : '' }}>Not Vaccinated</option>
                        <option value="1" {{ old('is_vaccinated', $animal->is_vaccinated) == '1' ? 'selected' : '' }}>Vaccinated</option>
                        <option value="2" {{ old('is_vaccinated', $animal->is_vaccinated) == '2' ? 'selected' : '' }}>No Vaccination Required</option>
                    </select>
                </div>
                

                <!-- Animal Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-600">Animal Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $animal->name) }}" class="w-full p-3 border border-gray-300 rounded-md">
                </div>

                <!-- Individual Animal Fields -->
                <div id="individual-animal-fields">
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-600">Gender</label>
                        <select name="gender" id="gender" class="w-full p-3 border border-gray-300 rounded-md">
                            <option value="Male" {{ old('gender', $animal->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender', $animal->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                </div>

                <!-- Group Animal Fields -->
                <div id="group-fields" class="{{ $animal->is_group ? '' : 'hidden' }}">
                    <div>
                        <label for="group_count" class="block text-sm font-medium text-gray-600">Group Count</label>
                        <input type="number" name="group_count" id="group_count" value="{{ old('group_count', $animal->group_count) }}" class="w-full p-3 border border-gray-300 rounded-md" min="1">
                    </div>
                </div>

                <!-- Species -->
                <div>
                    <label for="species_id" class="block text-sm font-medium text-gray-600">Species</label>
                    <select name="species_id" id="species_id" class="w-full p-3 border border-gray-300 rounded-md" required>
                        @foreach ($species as $specie)
                            <option value="{{ $specie->id }}" {{ old('species_id', $animal->species_id) == $specie->id ? 'selected' : '' }}>
                                {{ $specie->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Breed -->
                <div>
                    <label for="breed_id" class="block text-sm font-medium text-gray-600">Breed</label>
                    <select name="breed_id" id="breed_id" class="w-full p-3 border border-gray-300 rounded-md" required>
                        <option value="">Select a breed</option>
                        @foreach ($breeds as $breed)
                            <option value="{{ $breed->id }}" {{ old('breed_id', $animal->breed_id) == $breed->id ? 'selected' : '' }}>{{ $breed->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="color" class="block text-sm font-medium text-gray-600">Color</label>
                    <input type="text" name="color" id="color" value="{{ old('color', $animal->color ?? '') }}"
                           class="w-full p-2 border border-gray-300 rounded-md">
                </div>
                

                <div>
                    <label for="birth_date" class="block text-sm font-medium text-gray-600">Birth Date</label>
                    <input type="date" name="birth_date" id="birth_date" 
                           value="{{ old('birth_date', optional($animal->birth_date)->format('Y-m-d')) }}" 
                           class="w-full p-2 border border-gray-300 rounded-md">
                </div>
                

                <!-- Medical Condition -->
                <div>
                    <label for="medical_condition" class="block text-sm font-medium text-gray-600">Medical Condition</label>
                    <textarea name="medical_condition" id="medical_condition" rows="3" class="w-full p-3 border border-gray-300 rounded-md">{{ old('medical_condition', $animal->medical_condition) }}</textarea>
                </div>

               <!-- File Uploads -->
@foreach (['front', 'back', 'left_side', 'right_side'] as $field)
<div>
    <label for="photo_{{ $field }}" class="block text-sm font-medium text-gray-600">
        Photo {{ ucfirst(str_replace('_', ' ', $field)) }}
    </label>
    <input type="file" name="photo_{{ $field }}" id="photo_{{ $field }}" 
           class="w-full p-3 border border-gray-300 rounded-md" 
           onchange="previewImage(event, 'photo_{{ $field }}')">
</div>

<!-- Current Photo Preview -->
<div id="photo_{{ $field }}-preview" class="mt-2">
    @if ($animal->{'photo_' . $field})
        <img src="{{ asset('storage/' . $animal->{'photo_' . $field}) }}" 
             alt="{{ ucfirst($field) }} Photo" 
             class="w-32 h-32 object-cover border border-gray-300 rounded-md">
    @else
        <p class="text-gray-500">No image uploaded for this field.</p>
    @endif
</div>
@endforeach


                <!-- Submit Button -->
                <div class="flex justify-between">
                    <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700">Update Animal</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function toggleGroupFields() {
            const isGroup = document.getElementById('is_group').value;
            const groupFields = document.getElementById('group-fields');
            const individualFields = document.getElementById('individual-animal-fields');

            if (isGroup === '1') {
                groupFields.classList.remove('hidden');
                individualFields.classList.add('hidden');
            } else {
                groupFields.classList.add('hidden');
                individualFields.classList.remove('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', toggleGroupFields);

        // Fetch breeds based on selected species
        document.getElementById('species_id').addEventListener('change', function () {
            const speciesId = this.value;

            fetch(`/get-breedz/${speciesId}`)
                .then(response => response.json())
                .then(data => {
                    const breedSelect = document.getElementById('breed_id');
                    breedSelect.innerHTML = '<option value="">Select a breed</option>';
                    data.breeds.forEach(breed => {
                        const option = document.createElement('option');
                        option.value = breed.id;
                        option.textContent = breed.name;
                        breedSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching breeds:', error));
        });
    </script>
</x-app-layout>
