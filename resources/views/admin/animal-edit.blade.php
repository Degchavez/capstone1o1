<x-app-layout>
    <div class="max-w-6xl mx-auto px-4 py-6">
        <div class="bg-white dark:bg-neutral-800 border rounded-lg p-6 shadow-sm">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-neutral-200">Edit Animal</h1>

            <form method="POST" action="{{ route('animals.update', $animal->animal_id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Is Group -->
                    <div>
                        <label for="is_group" class="block text-sm font-medium text-gray-600 dark:text-neutral-400">Is Group</label>
                        <select id="is_group" name="is_group" 
                                class="w-full border border-gray-300 rounded-lg dark:bg-neutral-800 dark:border-neutral-600 dark:text-neutral-200" 
                                onchange="toggleFields()">
                            <option value="0" {{ old('is_group', $animal->is_group) == 0 ? 'selected' : '' }}>No</option>
                            <option value="1" {{ old('is_group', $animal->is_group) == 1 ? 'selected' : '' }}>Yes</option>
                        </select>
                    </div>

                    <!-- Group Count -->
                    <div id="group_count_field" class="{{ old('is_group', $animal->is_group) == 1 ? '' : 'hidden' }}">
                        <label for="group_count" class="block text-sm font-medium text-gray-600 dark:text-neutral-400">Group Count</label>
                        <input type="number" id="group_count" name="group_count" value="{{ old('group_count', $animal->group_count) }}" 
                               class="w-full border border-gray-300 rounded-lg dark:bg-neutral-800 dark:border-neutral-600 dark:text-neutral-200" min="1">
                    </div>

                    <!-- Name -->
                    <div id="name_field">
                        <label for="name" id="name_label" class="block text-sm font-medium text-gray-600 dark:text-neutral-400">
                            {{ old('is_group', $animal->is_group) == 1 ? 'Group Name' : 'Complete Name' }}
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $animal->name) }}" 
                               class="w-full border border-gray-300 rounded-lg dark:bg-neutral-800 dark:border-neutral-600 dark:text-neutral-200">
                    </div>

                    <!-- Species -->
                    <div>
                        <label for="species_id" class="block text-sm font-medium text-gray-600 dark:text-neutral-400">Species</label>
                        <select id="species_id" name="species_id" 
                                class="w-full border border-gray-300 rounded-lg dark:bg-neutral-800 dark:border-neutral-600 dark:text-neutral-200">
                            @foreach($species as $specie)
                                <option value="{{ $specie->id }}" {{ old('species_id', $animal->species_id) == $specie->id ? 'selected' : '' }}>{{ $specie->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Breed -->
                    <div>
                        <label for="breed_id" class="block text-sm font-medium text-gray-600 dark:text-neutral-400">Breed</label>
                        <select id="breed_id" name="breed_id" 
                                class="w-full border border-gray-300 rounded-lg dark:bg-neutral-800 dark:border-neutral-600 dark:text-neutral-200">
                            @foreach($breeds as $breed)
                                <option value="{{ $breed->id }}" data-species="{{ $breed->species_id }}" {{ old('breed_id', $animal->breed_id) == $breed->id ? 'selected' : '' }}>{{ $breed->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Birth Date -->
                    <div>
                        <label for="birth_date" class="block text-sm font-medium text-gray-600 dark:text-neutral-400">Birth Date</label>
                        <input type="date" id="birth_date" name="birth_date" 
                               value="{{ old('birth_date', $animal->birth_date ? $animal->birth_date->format('Y-m-d') : '') }}" 
                               class="w-full border border-gray-300 rounded-lg dark:bg-neutral-800 dark:border-neutral-600 dark:text-neutral-200">
                    </div>

                    <!-- Gender -->
                    <div id="gender_field" class="{{ old('is_group', $animal->is_group) == 0 ? '' : 'hidden' }}">
                        <label for="gender" class="block text-sm font-medium text-gray-600 dark:text-neutral-400">Gender</label>
                        <select id="gender" name="gender" 
                                class="w-full border border-gray-300 rounded-lg dark:bg-neutral-800 dark:border-neutral-600 dark:text-neutral-200">
                            <option value="male" {{ old('gender', $animal->gender) == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $animal->gender) == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>

                    <!-- Color -->
                    <div>
                        <label for="color" class="block text-sm font-medium text-gray-600 dark:text-neutral-400">Color</label>
                        <input type="text" id="color" name="color" value="{{ old('color', $animal->color) }}" 
                               class="w-full border border-gray-300 rounded-lg dark:bg-neutral-800 dark:border-neutral-600 dark:text-neutral-200">
                    </div>

                    <!-- Medical Condition -->
                    <div>
                        <label for="medical_condition" class="block text-sm font-medium text-gray-600 dark:text-neutral-400">Medical Condition</label>
                        <textarea id="medical_condition" name="medical_condition" 
                                  class="w-full border border-gray-300 rounded-lg dark:bg-neutral-800 dark:border-neutral-600 dark:text-neutral-200">{{ old('medical_condition', $animal->medical_condition) }}</textarea>
                    </div>
                </div>

                <!-- Photo Fields -->
                @foreach (['photo_front', 'photo_back', 'photo_left_side', 'photo_right_side'] as $photo)
                    <div>
                        <label for="{{ $photo }}" class="block text-sm font-medium text-gray-600 dark:text-neutral-400">{{ ucfirst(str_replace('_', ' ', $photo)) }}</label>
                        @if ($animal->{$photo})
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $animal->{$photo}) }}" alt="{{ ucfirst($photo) }}" class="w-32 h-32 object-cover rounded-md">
                            </div>
                        @endif
                        <input type="file" id="{{ $photo }}" name="{{ $photo }}" 
                               class="w-full border border-gray-300 rounded-lg dark:bg-neutral-800 dark:border-neutral-600 dark:text-neutral-200">
                    </div>
                @endforeach

                <div class="mt-6">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md">Update Animal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript to toggle fields dynamically -->
    <script>
        function toggleFields() {
            const isGroup = document.getElementById('is_group').value;
            const groupCountField = document.getElementById('group_count_field');
            const genderField = document.getElementById('gender_field');
            const nameLabel = document.getElementById('name_label');

            if (isGroup === "1") {
                groupCountField.classList.remove('hidden');
                genderField.classList.add('hidden');
                nameLabel.textContent = "Group Name";
            } else {
                groupCountField.classList.add('hidden');
                genderField.classList.remove('hidden');
                nameLabel.textContent = "Complete Name";
            }
        }

        document.addEventListener('DOMContentLoaded', toggleFields);
        document.getElementById('is_group').addEventListener('change', toggleFields);
    </script>
</x-app-layout>
