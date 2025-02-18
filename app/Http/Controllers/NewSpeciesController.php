<?php

namespace App\Http\Controllers;
use App\Models\Breed;

use App\Models\Species;
use Illuminate\Http\Request;

class NewSpeciesController extends Controller
{
    public function index(Request $request)
    {
        // Fetch species with their associated breeds
        $species = Species::with('breeds')->get();
        
        // Start the query for breeds
        $query = Breed::query();
        
        // Apply filters if provided
        if ($request->has('name') && $request->input('name') != '') {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }
    
        if ($request->has('species_id') && $request->input('species_id') != '') {
            $query->where('species_id', $request->input('species_id'));
        }
    
        // Paginate breeds
        $breeds = $query->paginate(10);
    
        if ($request->ajax()) {
            // If the request is an AJAX request, return the filtered breeds and pagination
            $breedsHtml = view('receptionist.partials.breeds_table', compact('breeds'))->render();
            $pagination = $breeds->links()->render();
    
            return response()->json([
                'breedsHtml' => $breedsHtml,
                'pagination' => $pagination
            ]);
        }
    
        // If it's not an AJAX request, return the full page
        return view('receptionist.species-breed', compact('species', 'breeds'));
    }
    

    
    

    public function create()
    {
        return view('receptionist.create-specie');
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255|unique:species,name',
        ]);
    
        // Create a new species record
        Species::create([
            'name' => $request->input('name'),
        ]);
    
        // Redirect to species list with success message
        return redirect()->route('recspecies.breed')->with('success', 'Species added successfully.');
    }
    

    public function show(Species $species)
    {
        return view('species.show', compact('species'));
    }

    public function edit($id)
    {
        $species = Species::findOrFail($id);
        return view('receptionist.edit-species', compact('species'));
    }
    
    public function update(Request $request, $id)
    {
        // Validate the incoming request to ensure the 'name' field is required, a string, and doesn't exceed 255 characters.
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);
    
        // Use findOrFail to retrieve the species by its ID or return 404 if not found
        $species = Species::findOrFail($id);
    
        // Update the species model with the validated data
        Species::findOrFail($id)->update([
            'name' => $validatedData['name'],
        ]);
        
        // Redirect to the appropriate route with a success message
        return redirect()->route('recspecies.breed')->with('success', 'Species updated successfully.');
    }
    

    
    
    

    public function destroy(Species $species)
    {
        // Delete all breeds related to this species
        $species->breeds()->delete();
    
        // Now delete the species
        $species->delete();
    
        // Redirect back with a success message
        return redirect()->route('recspecies.breed')->with('success', 'Species and associated breeds deleted successfully.');
    }
    
}
