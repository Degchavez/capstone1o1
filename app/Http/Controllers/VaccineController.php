<?php

namespace App\Http\Controllers;
use App\Models\Vaccine; // Import the Species model

use Illuminate\Http\Request;

class VaccineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function loadVaccines()
    {
        $vaccines = Vaccine::all(); // Get all vaccines
        return view('admin.vaccines', compact('vaccines')); // Return index view with vaccines
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.vaccines-create'); // Show create view
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'vaccine_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        // Create the vaccine
        Vaccine::create($request->all());

        return redirect()->route('vaccines.load')->with('success', 'Vaccine created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vaccine $vaccine)
    {
        return view('admin.edit-vaccine', compact('vaccine'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vaccine $vaccine)
    {
        // Validate the request
        $request->validate([
            'vaccine_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        // Update the vaccine
        $vaccine->update($request->all());

        return redirect()->route('vaccines.load')->with('success', 'Vaccine updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vaccine $vaccine)
    {
        // Delete the vaccine
        $vaccine->delete();

        return redirect()->route('vaccines.load')->with('success', 'Vaccine deleted successfully.');
    }
}
