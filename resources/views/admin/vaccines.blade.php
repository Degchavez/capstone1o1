<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h1 class="text-2xl font-semibold mb-4">Vaccines</h1>

            <!-- Add Vaccine Button -->
            <div class="mb-4">
                <a href="{{ route('vaccines.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Add Vaccine
                </a>
            </div>

            <!-- Vaccine Table -->
            <table class="min-w-full table-auto border-collapse bg-white border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 border text-left text-sm font-medium text-gray-700">#</th>
                        <th class="px-4 py-2 border text-left text-sm font-medium text-gray-700">Vaccine Name</th>
                        <th class="px-4 py-2 border text-left text-sm font-medium text-gray-700">Description</th>
                        <th class="px-4 py-2 border text-center text-sm font-medium text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vaccines as $vaccine)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 border text-sm text-gray-700">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2 border text-sm text-gray-700">{{ $vaccine->vaccine_name }}</td>
                            <td class="px-4 py-2 border text-sm text-gray-700">{{ $vaccine->description }}</td>
                            <td class="px-4 py-2 border text-center">
                                <a href="{{ route('vaccines.edit', $vaccine) }}" class="bg-yellow-500 text-white px-3 py-1 rounded-md hover:bg-yellow-600 text-sm">
                                    Edit
                                </a>
                                <form action="{{ route('vaccines.destroy', $vaccine) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 text-sm" onclick="return confirm('Are you sure you want to delete this vaccine?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
