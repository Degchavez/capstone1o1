<?php

namespace App\Http\Controllers;
use App\Models\Breed;

use App\Models\Species;
use Illuminate\Http\Request;

class SpeciesController extends Controller
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
            $breedsHtml = view('admin.partials.breeds_table', compact('breeds'))->render();
            $pagination = $breeds->links()->render();
    
            return response()->json([
                'breedsHtml' => $breedsHtml,
                'pagination' => $pagination
            ]);
        }
    
        // If it's not an AJAX request, return the full page
        return view('admin.species-breed', compact('species', 'breeds'));
    }
    

    
    

    public function create()
    {
        return view('admin.create-specie');
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
        return redirect()->route('species.breed')->with('success', 'Species added successfully.');
    }
    

    public function show(Species $species)
    {
        return view('species.show', compact('species'));
    }

    public function edit(Species $species)
    {
        return view('admin.edit-species', compact('species'));
    }

    public function update(Request $request, Species $species)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $species->update($request->all());
        return redirect()->route('species.breed')->with('success', 'Species updated successfully.');
    }

    public function destroy(Species $species)
    {
        // Delete all breeds related to this species
        $species->breeds()->delete();
    
        // Now delete the species
        $species->delete();
    
        // Redirect back with a success message
        return redirect()->route('species.breed')->with('success', 'Species and associated breeds deleted successfully.');
    }
    
}
