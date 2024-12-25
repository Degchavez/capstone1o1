<x-app-layout>
    <div class="container mx-auto p-6">
        <h2 class="text-xl font-bold mb-6 text-center">Update Animal</h2>
    
        <!-- Error Messages -->
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded mb-6">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    
        <!-- Success Message -->
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif
    
        <!-- Form -->
        <form action="{{ route('owner.updateAnimal', ['owner_id' => $owner_id, 'animal_id' => $animal->animal_id]) }}" 
              method="POST" 
              enctype="multipart/form-data" 
              class="space-y-4">
            @csrf
            @method('PUT')
    
            <!-- Hidden Fields -->
            <input type="hidden" name="owner_id" value="{{ $owner_id }}">
            <input type="hidden" name="animal_id" value="{{ $animal->animal_id }}">
            <input type="hidden" name="is_group" value="{{ $animal->is_group }}">

            <!-- Name -->
            <div>
                <label for="name" id="name-label" class="block text-sm font-medium text-gray-600">
                    {{ $animal->is_group ? 'Group Name' : 'Animal Name' }}
                </label>
                <input type="text" name="name" id="name" 
                       value="{{ old('name', $animal->name) }}" 
                       class="w-full p-2 border border-gray-300 rounded-md" 
                       required>
            </div>
    
            <!-- Species -->
            <div>
                <label for="species_id" class="block text-sm font-medium text-gray-600">Species</label>
                <select name="species_id" id="species_id" 
                        class="w-full p-2 border border-gray-300 rounded-md" 
                        required>
                    <option value="">Select a species</option>
                    @foreach ($species as $specie)
                        <option value="{{ $specie->id }}" 
                                {{ old('species_id', $animal->species_id) == $specie->id ? 'selected' : '' }}>
                            {{ $specie->name }}
                        </option>
                    @endforeach
                </select>
            </div>
    
            <!-- Breed -->
            <div>
                <label for="breed_id" class="block text-sm font-medium text-gray-600">Breed</label>
                <select name="breed_id" id="breed_id" 
                        class="w-full p-2 border border-gray-300 rounded-md" 
                        required>
                    <option value="">Select a breed</option>
                    @foreach ($breeds as $breed)
                        <option value="{{ $breed->id }}" 
                                {{ old('breed_id', $animal->breed_id) == $breed->id ? 'selected' : '' }}>
                            {{ $breed->name }}
                        </option>
                    @endforeach
                </select>
            </div>
    
            <!-- Color -->
            <div>
                <label for="color" class="block text-sm font-medium text-gray-600">Color</label>
                <input type="text" name="color" id="color" value="{{ old('color', $animal->color ?? '') }}"
                       class="w-full p-2 border border-gray-300 rounded-md">
            </div>
            
    
            <!-- Gender (Hidden for Groups) -->
            <div id="gender-container" class="{{ $animal->is_group ? 'hidden' : '' }}">
                <label for="gender" class="block text-sm font-medium text-gray-600">Gender</label>
                <select name="gender" id="gender" class="w-full p-2 border border-gray-300 rounded-md">
                    <option value="Male" {{ old('gender', $animal->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender', $animal->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>
    
            <!-- Group Count (Shown for Groups) -->
            <div id="group-count-container" class="{{ $animal->is_group ? '' : 'hidden' }}">
                <label for="group_count" class="block text-sm font-medium text-gray-600">Number of Animals</label>
                <input type="number" name="group_count" id="group_count" 
                       value="{{ old('group_count', $animal->group_count) }}" 
                       class="w-full p-2 border border-gray-300 rounded-md" 
                       min="1">
            </div>
    
            <!-- Birth Date -->
            <div>
                <label for="birth_date" class="block text-sm font-medium text-gray-600">Birth Date</label>
                <input type="date" name="birth_date" id="birth_date" 
                       value="{{ old('birth_date', optional($animal->birth_date)->format('Y-m-d')) }}" 
                       class="w-full p-2 border border-gray-300 rounded-md">
            </div>
            
            
    
            <!-- Medical Condition -->
            <div>
                <label for="medical_condition" class="block text-sm font-medium text-gray-600">Medical Condition</label>
                <input type="text" name="medical_condition" id="medical_condition" 
                       value="{{ old('medical_condition', $animal->medical_condition) }}" 
                       class="w-full p-2 border border-gray-300 rounded-md">
            </div>
    
            <!-- Photo Uploads -->
            <div class="space-y-4">
                @foreach (['front', 'back', 'left_side', 'right_side'] as $position)
                    <div>
                        <label for="photo_{{ $position }}" class="block text-sm font-medium text-gray-600">
                            {{ ucfirst(str_replace('_', ' ', $position)) }} Photo
                        </label>
                        <input type="file" name="photo_{{ $position }}" id="photo_{{ $position }}" 
                               class="w-full p-2 border border-gray-300 rounded-md" 
                               accept="image/*">
                        @if ($animal->{'photo_' . $position})
                            <img src="{{ asset('storage/' . $animal->{'photo_' . $position}) }}" 
                                 alt="{{ ucfirst($position) }} Photo" 
                                 class="mt-2 w-32 h-32 object-cover">
                        @endif
                    </div>
                @endforeach
            </div>
    
            <!-- Submit Button -->
            <div class="flex justify-between items-center mt-6">
                <button type="submit" class="bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600 transition-colors duration-300">
                    Update Animal
                </button>
                <a href="{{ route('owners.profile-owner', ['owner_id' => $owner_id]) }}" 
                   class="bg-gray-500 text-white p-2 rounded-md hover:bg-gray-600 transition-colors duration-300">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#species_id').change(function() {
                var speciesId = $(this).val();
                if (speciesId) {
                    $.ajax({
                        url: '/get-breeds/' + speciesId,
                        type: 'GET',
                        success: function(data) {
                            $('#breed_id').empty();
                            $('#breed_id').append('<option value="">Select a breed</option>');
                            $.each(data.breeds, function(index, breed) {
                                $('#breed_id').append('<option value="' + breed.id + '">' + breed.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#breed_id').empty();
                    $('#breed_id').append('<option value="">Select a breed</option>');
                }
            });

            // Ensure is_group functionality works on page load
            toggleGroupFields({{ $animal->is_group ? 'true' : 'false' }});

            function toggleGroupFields(isGroup) {
                const genderContainer = $('#gender-container');
                const groupCountContainer = $('#group-count-container');
                const nameLabel = $('#name-label');

                if (isGroup) {
                    nameLabel.text('Group Name');
                    genderContainer.hide();
                    groupCountContainer.show();
                } else {
                    nameLabel.text('Animal Name');
                    genderContainer.show();
                    groupCountContainer.hide();
                }
            }
        });
    </script>
</x-app-layout>
