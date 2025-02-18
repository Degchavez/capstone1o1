<x-app-layout>
    <div class="container mx-auto p-6 bg-white rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Designations</h1>
            <a href="{{ route('recdesignations.create') }}" 
               class="bg-green-500 hover:bg-green-600 text-white font-semibold px-4 py-2 rounded-lg shadow">
                <i class="fas fa-plus-circle"></i> Add Designation
            </a>
        </div>

        <div class="overflow-hidden rounded-lg border border-gray-200 shadow-md">
            <table class="min-w-full bg-white">
                <thead>
                    <tr class="bg-gray-100 text-left text-gray-700 font-semibold uppercase text-sm">
                        <th class="px-6 py-3 border-b">#</th>
                        <th class="px-6 py-3 border-b">Name</th>
                        <th class="px-6 py-3 border-b">Description</th>
                        <th class="px-6 py-3 border-b text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 divide-y">
                    @foreach($designations as $designation)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 font-medium">{{ $designation->name }}</td>
                            <td class="px-6 py-4">{{ $designation->description }}</td>
                            <td class="px-6 py-4 text-center flex justify-center space-x-4">
                                <a href="{{ route('recdesignations.edit', $designation) }}" 
                                   class="text-blue-500 hover:text-blue-700">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                               
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
