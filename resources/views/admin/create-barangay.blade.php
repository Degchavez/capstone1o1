<!-- resources/views/barangays/create.blade.php -->

<x-app-layout>
    <div class="container">
        <h1>Create Barangay</h1>
        <form action="{{ route('barangays.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="barangay_name">Barangay Name</label>
                <input type="text" name="barangay_name" id="barangay_name" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Save</button>
        </form>
    </div>
</x-app-layout>
