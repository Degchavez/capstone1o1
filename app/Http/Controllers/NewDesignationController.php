<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use Illuminate\Http\Request;

class NewDesignationController extends Controller
{
    public function index()
    {
        $designations = Designation::all();
        return view('receptionist.designation-index', compact('designations'));
    }

    public function create()
    {
        return view('receptionist.designation-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:designations',
            'description' => 'nullable|string',
        ]);

        Designation::create($request->all());

        return redirect()->route('recdesignation.index')->with('success', 'Designation created successfully.');
    }

    public function edit($id)
    {
        $designation = Designation::findOrFail($id);
        return view('receptionist.designation-edit', compact('designation'));
    }
    

    public function update(Request $request, $id)
    {
        // Find the designation by its designation_id
        $designation = Designation::where('designation_id', $id)->firstOrFail();
    
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255|unique:designations,name,' . $designation->designation_id . ',designation_id',
            'description' => 'nullable|string',
        ]);
    
        Designation::where('designation_id', $id)->update($request->only('name', 'description'));
    
        // Redirect back with success message
        return redirect()->route('recdesignation.index')->with('success', 'Designation updated successfully.');
    }
    

    public function destroy(Designation $designation)
    {
        $designation->delete();
        return redirect()->route('designation.index')->with('success', 'Designation deleted successfully.');
    }
}
