<?php

namespace App\Http\Controllers;
use App\Models\VeterinaryTechnician; // Import the VeterinaryTechnician model

use Illuminate\Http\Request;
use DB; 


class VeterinaryTechnicianController extends Controller
{

    public function loadTechnicians()
    {
        $technicians = VeterinaryTechnician::all();
        return view('admin.technician-index', compact('technicians'));
    }

    public function create()
{
    return view('admin.technician-create'); // Replace with your actual Blade file
}

    
public function store(Request $request)
{
    $request->validate([
        'full_name' => 'required|string|max:255',
        'contact_number' => 'required|string|max:15',
        'email' => 'required|email|unique:veterinary_technicians,email',
    ]);

    VeterinaryTechnician::create($request->all());

    return redirect()->route('admin-technicians')->with('success', 'Technician added successfully!');
}

public function edit($technicianId)
{
    $technician = VeterinaryTechnician::where('technician_id', $technicianId)->firstOrFail();
    return view('admin.technician-edit', compact('technician'));
}

public function update(Request $request, $technicianId)
{
    // Validate the input data
    $request->validate([
        'full_name' => 'required|string|max:255',
        'contact_number' => 'required|string|max:15',
        'email' => 'required|email|unique:veterinary_technicians,email,' . $technicianId . ',technician_id',
    ]);

    // Exclude _token and _method fields
    $data = $request->except(['_token', '_method']);  

    // Find the technician by ID
    $technician = VeterinaryTechnician::where('technician_id', $technicianId)->firstOrFail();

    // Update the technician data with validated data
    VeterinaryTechnician::where('technician_id', $technicianId)->update($data);  // Using the $data array here instead of $request->all()

    // Redirect with success message
    return redirect()->route('admin-technicians')->with('success', 'Technician updated successfully!');
}

public function destroy($technicianId)
{
    // Update associated transactions to remove the technician reference
    DB::table('transactions')->where('technician_id', $technicianId)->update(['technician_id' => null]);

    // Now delete the technician
    VeterinaryTechnician::where('technician_id', $technicianId)->delete();

    return redirect()->route('admin-technicians')->with('success', 'Technician deleted successfully!');
}

    
}
