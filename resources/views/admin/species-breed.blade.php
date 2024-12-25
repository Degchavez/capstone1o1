<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Species and Breeds</h1>

        <!-- Add Species Button -->

        <!-- Flex container for tables -->
        <div class="flex space-x-6">
            
            <!-- Species Table -->
            <div class="flex-1 bg-white shadow-md rounded-lg mb-6">
                
                <h2 class="text-xl font-semibold mb-2">Species</h2>
                <a href="{{ route('species.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 mb-4 inline-block">
                    Add Species
                </a>
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-100 text-gray-600">
                        <tr>
                            <th class="px-4 py-2 text-left">Species Name</th>
                            <th class="px-4 py-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @foreach ($species as $specie)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $specie->name }}</td>
                                <td class="px-4 py-2 flex space-x-2">
                                    <a href="{{ route('species.edit', $specie) }}" class="bg-blue-500 text-white px-3 py-1 rounded-md hover:bg-blue-600 text-sm">Edit</a>
                                    <form action="{{ route('species.destroy', $specie) }}" method="POST" class="inline-block ml-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 text-sm" onclick="return confirm('Are you sure you want to delete this species?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Breeds Table -->
            <div class="flex-1 bg-white shadow-md rounded-lg mb-6">
                <h2 class="text-xl font-semibold mb-2">Breeds</h2>
                
                <!-- Add Breed Button -->
                <a href="{{ route('breeds.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 mb-4 inline-block">
                    Add Breed
                </a>

                <!-- Filter Form -->
                <form id="filterForm" class="mb-4">
                    <div class="flex space-x-4">
                        <!-- Breed Name Filter -->
                        <input type="text" name="name" id="nameFilter" placeholder="Search by breed name" value="{{ request()->input('name') }}" class="px-4 py-2 border rounded-md w-full">

                        <!-- Species Filter -->
                        <select name="species_id" id="speciesFilter" class="px-4 py-2 border rounded-md">
                            <option value="">Select Species</option>
                            @foreach ($species as $specie)
                                <option value="{{ $specie->id }}" {{ request()->input('species_id') == $specie->id ? 'selected' : '' }}>
                                    {{ $specie->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>

                <!-- Breeds Table -->
                <div id="breedsTable" class="mt-4">
                    <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-3 px-4 text-left">Breed Name</th>
                                <th class="py-3 px-4 text-left">Species</th>

                                <th class="py-3 px-4 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="breedTableBody" class="text-gray-700">
                            @foreach ($breeds as $breed)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-2">{{ $breed->name }}</td>
                                    <td class="px-4 py-2">{{ $breed->species->name }}</td>

                                    <td class="px-4 py-2 flex space-x-2">
                                        <a href="{{ route('breeds.edit', $breed) }}" class="bg-blue-500 text-white px-3 py-1 rounded-md hover:bg-blue-600 text-sm">Edit</a>
                                        <form action="{{ route('breeds.destroy', $breed) }}" method="POST" class="inline-block ml-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 text-sm" onclick="return confirm('Are you sure you want to delete this breed?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination Links -->
                    <div class="mt-4">
                        {{ $breeds->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include the JavaScript code -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Function to load filtered breeds automatically when the species is selected
            function loadFilteredBreeds() {
                var name = $('#nameFilter').val();  // Get the breed name filter value
                var species_id = $('#speciesFilter').val();  // Get the selected species ID
    
                // Send an AJAX request with the filter parameters
                $.ajax({
                    url: '{{ route('breeds.index') }}',  // Ensure this route is correct for your breed listing
                    method: 'GET',
                    data: {
                        name: name,
                        species_id: species_id
                    },
                    success: function(response) {
                        // Replace the breeds table body with the filtered content
                        $('#breedTableBody').html(response.breedsHtml);
                        // Update pagination links if needed
                        $('.pagination').html(response.pagination);
                    },
                    error: function(xhr, status, error) {
                        console.log("Error:", error);
                    }
                });
            }
    
            // Trigger the filter when the user changes the species dropdown
            $('#speciesFilter').on('change', function() {
                loadFilteredBreeds();  // Automatically load filtered breeds
            });
    
            // Trigger the filter when the page is first loaded
            loadFilteredBreeds();  // Ensure this loads breeds initially
        });
    </script>
    
</x-app-layout>
