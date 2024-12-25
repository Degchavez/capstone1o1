<x-app-layout>
    <div class="container mx-auto p-6">
        <!-- Card Container -->
        <div class="bg-white shadow-lg rounded-lg p-8 max-w-3xl mx-auto">
            <h2 class="text-xl font-bold mb-6 text-center">Add Animal</h2>

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
            <form action="{{ route('admin-animals.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Owner Selection -->
                <div>
                    <label for="owner_id" class="block text-sm font-medium text-gray-600">Select Owner</label>
                    <select name="owner_id" id="owner_id" class="w-full p-3 border border-gray-300 rounded-md" required>
                        <option value="">Select an owner</option>
                        @foreach ($owners as $owner)
                            <option value="{{ $owner->owner_id }}" {{ old('owner_id') == $owner->owner_id ? 'selected' : '' }}>
                                {{ $owner->complete_name }} <!-- Display the complete name of the user -->
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Is Group? -->
                <div>
                    <label for="is_group" class="block text-sm font-medium text-gray-600">Is it a group?</label>
                    <select name="is_group" id="is_group" class="w-full p-3 border border-gray-300 rounded-md" onchange="toggleGroupFields()">
                        <option value="0" {{ old('is_group') == '0' ? 'selected' : '' }}>No</option>
                        <option value="1" {{ old('is_group') == '1' ? 'selected' : '' }}>Yes</option>
                    </select>
                </div>

                <!-- Animal Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-600">Animal Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full p-3 border border-gray-300 rounded-md">
                </div>

                <!-- Individual Animal Fields -->
                <div id="individual-animal-fields">
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-600">Gender</label>
                        <select name="gender" id="gender" class="w-full p-3 border border-gray-300 rounded-md">
                            <option value="">Select Gender</option>
                            <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                </div>

             <!-- Group Animal Fields -->
<div id="group-fields" class="hidden">
    <div>
        <label for="group_count" class="block text-sm font-medium text-gray-600">Group Count</label>
        <input 
            type="number" 
            name="group_count" 
            id="group_count" 
            value="{{ old('group_count', 1) }}" 
            class="w-full p-3 border border-gray-300 rounded-md" 
            min="1"
        >
    </div>
</div>

                <!-- Species -->
                <div>
                    <label for="species_id" class="block text-sm font-medium text-gray-600">Species</label>
                    <select name="species_id" id="species_id" class="w-full p-3 border border-gray-300 rounded-md" required>
                        <option value="">Select a species</option>
                        @foreach ($species as $specie)
                            <option value="{{ $specie->id }}" {{ old('species_id') == $specie->id ? 'selected' : '' }}>{{ $specie->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Breed -->
                <div>
                    <label for="breed_id" class="block text-sm font-medium text-gray-600">Breed</label>
                    <select name="breed_id" id="breed_id" class="w-full p-3 border border-gray-300 rounded-md" required>
                        <option value="">Select a breed</option>
                        @foreach ($breeds as $breed)
                            <option value="{{ $breed->id }}" {{ old('breed_id') == $breed->id ? 'selected' : '' }}>{{ $breed->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Color -->
                <div>
                    <label for="color" class="block text-sm font-medium text-gray-600">Color</label>
                    <input type="text" name="color" id="color" value="{{ old('color') }}" class="w-full p-2 border border-gray-300 rounded-md">
                </div>

                <!-- Birth Date -->
                <div>
                    <label for="birth_date" class="block text-sm font-medium text-gray-600">Birth Date</label>
                    <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}" class="w-full p-3 border border-gray-300 rounded-md">
                </div>

                <!-- Medical Condition -->
                <div>
                    <label for="medical_condition" class="block text-sm font-medium text-gray-600">Medical Condition</label>
                    <textarea name="medical_condition" id="medical_condition" rows="3" class="w-full p-3 border border-gray-300 rounded-md">{{ old('medical_condition') }}</textarea>
                </div>

                <!-- File Uploads -->
                @foreach (['photo_front' => 'Front', 'photo_back' => 'Back', 'photo_left_side' => 'Left Side', 'photo_right_side' => 'Right Side'] as $field => $label)
                    <div>
                        <label for="{{ $field }}" class="block text-sm font-medium text-gray-600">Photo {{ $label }}</label>
                        <input type="file" name="{{ $field }}" id="{{ $field }}" class="w-full p-3 border border-gray-300 rounded-md" onchange="previewImage(event, '{{ $field }}')">
                    </div>
                    <div id="{{ $field }}-preview" class="mt-2">
                        <!-- Image preview will appear here -->
                    </div>
                @endforeach

                <!-- Submit Button -->
                <div class="flex justify-between">
                    <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700">Save Animal</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Function to toggle the display of group fields based on the selection
        function toggleGroupFields() {
            var isGroup = document.getElementById('is_group').value;
            var nameLabel = document.querySelector('label[for="name"]');
            var genderField = document.getElementById('gender');

            if (isGroup == '1') {
                // Update name label to "Group Name"
                nameLabel.textContent = "Group Name";

                // Set gender to "N/A" and hide the field
                genderField.value = "N/A";
                genderField.closest('div').classList.add('hidden');

                // Show group-specific fields and hide individual-specific fields
                document.getElementById('group-fields').classList.remove('hidden');
                document.getElementById('individual-animal-fields').classList.add('hidden');
            } else {
                // Update name label to "Animal Name"
                nameLabel.textContent = "Animal Name";

                // Reset gender visibility and clear its value
                genderField.closest('div').classList.remove('hidden');
                genderField.value = "";

                // Hide group-specific fields and show individual-specific fields
                document.getElementById('group-fields').classList.add('hidden');
                document.getElementById('individual-animal-fields').classList.remove('hidden');
            }
        }

        // Preview the uploaded image before submission
        function previewImage(event, field) {
            var reader = new FileReader();
            reader.onload = function() {
                var preview = document.getElementById(field + '-preview');
                preview.innerHTML = `<img src="${reader.result}" alt="Preview" class="w-32 h-32 object-cover border border-gray-300 rounded-md">`;
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        // Handle the change event for the species field to fetch corresponding breeds
        $(document).ready(function() {
            $('#species_id').change(function() {
                var speciesId = $(this).val();

                if (speciesId) {
                    $.ajax({
                        url: '/get-breeds/' + speciesId,
                        type: 'GET',
                        success: function(data) {
                            $('#breed_id').empty().append('<option value="">Select a breed</option>');
                            $.each(data.breeds, function(index, breed) {
                                $('#breed_id').append('<option value="' + breed.id + '">' + breed.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#breed_id').empty().append('<option value="">Select a breed</option>');
                }
            });
        });
    </script>
</x-app-layout>
