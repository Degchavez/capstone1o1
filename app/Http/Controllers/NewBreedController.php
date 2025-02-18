<?php
namespace App\Http\Controllers;

use App\Models\Breed;
use App\Models\Species;
use Illuminate\Http\Request;

class NewBreedController extends Controller
{
    public function index(Species $species)
{
    // Paginate the breeds (e.g., 10 per page)
    $breeds = $species->breeds()->paginate(10);

    // Pass species and breeds to the view
    return view('receptionist.species-breed', compact('species', 'breeds'));
}

    public function create()
    {
        $species = Species::all(); // Fetch all species to link breed with species
        return view('receptionist.breed-create', compact('species'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'species_id' => 'required|exists:species,id',
        ]);

        Breed::create($request->all());

        return redirect()->route('recspecies.breed')->with('success', 'Breed added successfully!');
    }


    public function show(Breed $breed)
    {
        return view('breeds.show', compact('breed'));
    }

    public function edit($id)
    {
        $breed = Breed::findOrFail($id);
        $species = Species::all(); // Assuming species data is needed for dropdown.

        return view('receptionist.breeds-update', compact('breed', 'species'));
    }

    /**
     * Update the specified breed in storage.
     */
    public function update(Request $request, $id)
    {
        $breed = Breed::findOrFail($id);

        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'species_id' => 'required|exists:species,id',
        ]);

        // Update breed data
        $breed->update($request->only('name', 'species_id'));

        // Redirect back with success message
        return redirect()->route('recspecies.breed', $breed)
            ->with('success', 'Breed updated successfully.');
    }
    
    

    public function destroy(Breed $breed)
    {
        $breed->delete();
        return redirect()->route('species.breed', $breed->species)->with('success', 'Breed deleted successfully.');
    }
}
