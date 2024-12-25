<?php
// app/Http/Controllers/BarangayController.php

namespace App\Http\Controllers;

use App\Models\Barangay;
use Illuminate\Http\Request;

class NewBarangayController extends Controller
{
    // Display a listing of the barangays
    public function loadBarangays()
    {
        // Order barangays alphabetically by barangay_name
        $barangays = Barangay::orderBy('barangay_name', 'asc')->get();
        return view('receptionist.barangay-index', compact('barangays'));
    }
    

    // Show the form for creating a new barangay
    public function create()
    {
        return view('receptionist.create-barangay');
    }

    // Store a newly created barangay
    public function store(Request $request)
    {
        $request->validate([
            'barangay_name' => 'required|string|max:255',
        ]);
    
        // Check if the barangay already exists
        $existingBarangay = Barangay::where('barangay_name', $request->barangay_name)->first();
    
        if ($existingBarangay) {
            return redirect()->back()->withErrors(['barangay_name' => 'Barangay already exists.'])->withInput();
        }
    
        // Create a new barangay
        Barangay::create($request->all());
    
        return redirect()->route('newbarangay.load')->with('success', 'Barangay created successfully.');
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

    public function destroy($id)
    {
        // Find the barangay by ID
        $barangay = Barangay::findOrFail($id);
    
        // Delete the barangay
        $barangay->delete();
    
        // Redirect with success message
        return redirect()->route('newbarangay.load')->with('success', 'Barangay deleted successfully.');
    }
    
}
