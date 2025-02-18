<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Species and Breeds</h1>

        @if(session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
        <!-- Flex container for tables -->
        <div class="flex space-x-6">
            
            <!-- Species Table -->
            <div class="flex-1 bg-white shadow-md rounded-lg mb-6">
                
                <h2 class="text-xl font-semibold mb-2">Species</h2>
                <a href="{{ route('newspecies.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 mb-4 inline-block">
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
                                    <a href="{{ route('newspecies.edit', $specie) }}" class="bg-blue-500 text-white px-3 py-1 rounded-md hover:bg-blue-600 text-sm">Edit</a>
                                  
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
                <a href="{{ route('newbreeds.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 mb-4 inline-block">
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
                                        <a href="{{ route('newbreeds.edits', $breed) }}" class="bg-blue-500 text-white px-3 py-1 rounded-md hover:bg-blue-600 text-sm">Edit</a>
                                     
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
        $(document).ready(function () {
            // Automatically reload the page when a new species is selected
            $('#speciesFilter').on('change', function () {
                $('#filterForm').submit();
            });
        });
    </script>
    
    
</x-app-layout>
