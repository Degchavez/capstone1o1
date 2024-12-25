<?php
// app/Http/Controllers/BarangayController.php

namespace App\Http\Controllers;

use App\Models\Barangay;
use Illuminate\Http\Request;

class BarangayController extends Controller
{
    // Display a listing of the barangays
    public function loadBarangays()
    {
        // Order barangays alphabetically by barangay_name
        $barangays = Barangay::orderBy('barangay_name', 'asc')->get();
        return view('admin.barangay-index', compact('barangays'));
    }
    

    // Show the form for creating a new barangay
    public function create()
    {
        return view('admin.create-barangay');
    }

    // Store a newly created barangay
    public function store(Request $request)
    {
        $request->validate([
            'barangay_name' => 'required|string|max:255',
        ]);

        Barangay::create($request->all());

        return redirect()->route('barangay.load');
    }

    // Show the form for editing the specified barangay
    public function edit(Barangay $barangay)
    {
        return view('barangays.edit', compact('barangay'));
    }

    // Update the specified barangay
    public function update(Request $request, Barangay $barangay)
    {
        $request->validate([
            'barangay_name' => 'required|string|max:255',
        ]);

        $barangay->update($request->all());

        return redirect()->route('admin.barangay-index');
    }

    // Remove the specified barangay
    public function destroy(Barangay $barangay)
    {
        $barangay->delete();

        return redirect()->route('barangay.load');
    }
}
