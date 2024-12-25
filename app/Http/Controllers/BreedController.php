<?php
namespace App\Http\Controllers;

use App\Models\Breed;
use App\Models\Species;
use Illuminate\Http\Request;

class BreedController extends Controller
{
    public function index(Species $species)
{
    // Paginate the breeds (e.g., 10 per page)
    $breeds = $species->breeds()->paginate(10);

    // Pass species and breeds to the view
    return view('admin.species-breed', compact('species', 'breeds'));
}

    public function create()
    {
        $species = Species::all(); // Fetch all species to link breed with species
        return view('admin.breed-create', compact('species'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'species_id' => 'required|exists:species,id',
        ]);

        Breed::create($request->all());

        return redirect()->route('species.breed')->with('success', 'Breed added successfully!');
    }


    public function show(Breed $breed)
    {
        return view('breeds.show', compact('breed'));
    }

    public function edit(Breed $breed)
    {
         $species = Species::all();
    
        return view('admin.breeds-update', compact('breed', 'species'));
    }
    public function update(Request $request, Breed $breed)
    {
        // Validate the input from the request
        $request->validate([
            'name' => 'required|string|max:255',
            'species_id' => 'required|exists:species,id',  // Ensure the species exists in the database
        ]);
    
        // Update the breed with the validated data
        $breed->update([
            'name' => $request->name,
            'species_id' => $request->species_id,  // Update species
        ]);
    
        // Redirect back to the breed list, passing the species associated with this breed
        return redirect()->route('species.breed', $breed->species)->with('success', 'Breed updated successfully.');
    }
    
    

    public function destroy(Breed $breed)
    {
        $breed->delete();
        return redirect()->route('species.breed', $breed->species)->with('success', 'Breed deleted successfully.');
    }
}
