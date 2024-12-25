<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h1 class="text-2xl font-semibold mb-6">Veterinary Technicians</h1>

            <!-- Add Technician Button -->
            <div class="mb-4 flex justify-end">
                <a href="{{ route('rec-technicians.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center space-x-2">
                    <i class="fas fa-plus"></i> <span>Add Technician</span>
                </a>
            </div>

            <!-- Technician Table -->
            <table class="min-w-full bg-white border border-gray-200 shadow-sm rounded-lg">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-4 py-2 text-left font-medium text-gray-700">#</th>
                        <th class="border px-4 py-2 text-left font-medium text-gray-700">Full Name</th>
                        <th class="border px-4 py-2 text-left font-medium text-gray-700">Contact Number</th>
                        <th class="border px-4 py-2 text-left font-medium text-gray-700">Email</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($technicians as $technician)
                        <tr>
                            <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                            <td class="border px-4 py-2">{{ $technician->full_name }}</td>
                            <td class="border px-4 py-2">{{ $technician->contact_number }}</td>
                            <td class="border px-4 py-2">{{ $technician->email }}</td>
                            <td class="border px-4 py-2 text-center">
                                <!-- Edit Button -->
                             
                            
                                <!-- Delete Button -->
                              
                            </td>
                            
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="border px-4 py-2 text-center">No technicians found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
