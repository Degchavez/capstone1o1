<?php

namespace App\Http\Controllers;

use App\Models\VeterinaryTechnician; // Import the Species model
use App\Models\Breed; // Import the Species model
use App\Models\Vaccine; // Import the Species model
use App\Models\Technician; // Import the Species model
use App\Models\Designation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth; // Add this at the top of your controller
use Illuminate\Support\Facades\Hash; // Add this at the top of your controller

use App\Models\Transaction; // Import the Species model
use App\Models\TransactionSubtype; // Import the model
use App\Models\Animal; // Import the Species model
use App\Models\TransactionType; // Import the model
use App\Models\Species; // Import the Species model
use App\Models\Owner;
use App\Models\Address;
use App\Models\Barangay;
use App\Models\City;
use App\Models\User;
use DB;

use Illuminate\Http\Request;

class NewOwnerController extends Controller
{
    public function edit($owner_id, $animal_id, Request $request)
    {
        $transaction_id = $request->query('transaction_id');
        
        $animal = Animal::with([
            'species',
            'breed',
            'transactions' => function ($query) {
                $query->with(['transactionType', 'transactionSubtype', 'vet']);
            }
        ])->where('animal_id', $animal_id)->firstOrFail();
        
        $species = Species::all();
        $breeds = Breed::where('species_id', $animal->species_id)->get();
        $vets = User::where('role', 2)->get(); // Veterinarians with role = 2
        $transactionTypes = TransactionType::with('subtypes')->get();
        $technicians = VeterinaryTechnician::all(); // Separate table for technicians
        $vaccines = Vaccine::all();
        
        $oldTransaction = $transaction_id
            ? $animal->transactions->where('transaction_id', $transaction_id)->first()
            : $animal->transactions->first(); // Preload the first transaction if none is selected
        
        return view('owner.animal-update', compact(
            'owner_id',
            'animal',
            'species',
            'breeds',
            'vets',
            'transactionTypes',
            'oldTransaction',
            'technicians',
            'vaccines'
        ));
    }
    
    public function updateAnimal(Request $request, $owner_id, $animal_id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'species_id' => 'required|exists:species,id',
            'breed_id' => 'required|exists:breeds,id',
            'color' => 'nullable|string|max:255', // Validation for color
            'birth_date' => ['nullable', 'date', 'before_or_equal:today'], // Ensure birthdate is not in the future
            'gender' => 'nullable|in:Male,Female',
            'medical_condition' => 'nullable|string|max:255',
            'photo_front' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photo_back' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photo_left_side' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photo_right_side' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_group' => 'nullable|boolean',
            'group_count' => 'nullable|integer|min:1|required_if:is_group,true',
        ]);
    
        $animal = Animal::where('animal_id', $animal_id)->firstOrFail();
    
        // Handle photo uploads
        $photos = [];
        foreach (['photo_front', 'photo_back', 'photo_left_side', 'photo_right_side'] as $photo) {
            if ($request->hasFile($photo)) {
                // Delete the old photo if it exists
                if ($animal->$photo) {
                    \Storage::disk('public')->delete($animal->$photo);
                }
                // Store the new photo
                $photos[$photo] = $request->file($photo)->store('animals/photos', 'public');
            } else {
                $photos[$photo] = $animal->$photo; // Retain the existing photo if no new upload
            }
        }
    
        // Update the animal record
        Animal::where('animal_id', $animal_id)->update([
            'name' => $request->name,
            'species_id' => $request->species_id,
            'breed_id' => $request->breed_id,
            'color' => $request->color,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'medical_condition' => $request->medical_condition,
            'photo_front' => $photos['photo_front'],
            'photo_back' => $photos['photo_back'],
            'photo_left_side' => $photos['photo_left_side'],
            'photo_right_side' => $photos['photo_right_side'],
            'is_group' => $request->is_group,
            'group_count' => $request->is_group ? $request->group_count : null,
        ]);
    
       

    return redirect()->route('owners.profile', ['owner_id' => $owner_id])
                     ->with('success', 'Animal and transaction updated successfully!');
}




public function ownergetBreeds($species_id)
{
    // Get the species by its ID
    $species = Species::find($species_id);
    
    // Check if species exists
    if (!$species) {
        return response()->json(['error' => 'Species not found'], 404);
    }

    // Get breeds related to the species
    $breeds = $species->breeds; // Using the defined relationship

    // Return breeds as JSON
    return response()->json([
        'breeds' => $breeds
    ]);
}



public function showTransactions($owner_id, Request $request)
{
    // Retrieve the owner by ID
    $owner = Owner::find($owner_id);

    // If the owner does not exist, return a 404 response
    if (!$owner) {
        return abort(404, 'Owner not found');
    }

    // Retrieve the search term and status filter from the request
    $search = $request->input('search');
    $status = $request->input('status');

    $transactions = $owner->transactions()
    ->join('animals', 'transactions.animal_id', '=', 'animals.animal_id')
    ->join('species', 'animals.species_id', '=', 'species.id')
    ->join('breeds', 'animals.breed_id', '=', 'breeds.id')
    ->leftJoin('users as vets', 'transactions.vet_id', '=', 'vets.user_id')
    ->leftJoin('veterinary_technicians', 'transactions.technician_id', '=', 'veterinary_technicians.technician_id') // Join with veterinary_technicians
    ->leftJoin('vaccines', 'transactions.vaccine_id', '=', 'vaccines.id') // Join with vaccines table
    ->select(
        'transactions.*',
        'animals.name as animal_name',
        'species.name as species_name',
        'breeds.name as breed_name',
        'vets.complete_name as vet_name',
        'veterinary_technicians.full_name as technician_name',
        'vaccines.vaccine_name as vaccine_name' // Select the vaccine name
    )
    ->when($request->transaction_type, function ($query, $transaction_type) {
        return $query->whereHas('transactionType', function ($q) use ($transaction_type) {
            $q->where('type_name', $transaction_type);
        });
    })
    ->when($request->transaction_subtype, function ($query, $transaction_subtype) {
        return $query->whereHas('transactionSubtype', function ($q) use ($transaction_subtype) {
            $q->where('subtype_name', $transaction_subtype);
        });
    })
    ->when($status !== null, function ($query) use ($status) {
        return $query->where('transactions.status', $status);
    })
    ->when($search, function ($query, $search) {
        return $query->where(function ($query) use ($search) {
            $query->where('animals.name', 'like', "%{$search}%")
                  ->orWhere('vets.complete_name', 'like', "%{$search}%")
                  ->orWhere('breeds.name', 'like', "%{$search}%")
                  ->orWhere('species.name', 'like', "%{$search}%")
                  ->orWhere('veterinary_technicians.full_name', 'like', "%{$search}%")
                  ->orWhere('vaccines.vaccine_name', 'like', "%{$search}%"); // Add search filter for vaccine name
        });
    })
    ->orderBy('transactions.created_at', 'desc') // Order by newest first

    ->paginate(10);

    // Map transactions to include status labels and colors
    $transactions->transform(function ($transaction) {
        $statusMapping = [
            0 => ['label' => 'Pending', 'color' => 'text-yellow-500'],
            1 => ['label' => 'Completed', 'color' => 'text-green-500'],
            2 => ['label' => 'Canceled', 'color' => 'text-red-500'],
        ];

        $transaction->statusData = $statusMapping[$transaction->status] ?? [
            'label' => 'Unknown',
            'color' => 'text-gray-500',
        ];

        return $transaction;
    });

    // Log the status data for debugging
    \Log::info('Transactions status data: ' . json_encode($transactions->pluck('statusData')->toArray()));

    // Fetch transaction types and subtypes for the dropdowns
    $transactionTypes = TransactionType::all();
    $transactionSubtypes = TransactionSubtype::all();

    // Return the view with the transactions and owner
    return view('owner.owner-transactions', [
        'owner_id' => $owner_id,
        'transactions' => $transactions,
        'owner' => $owner,
        'transactionTypes' => $transactionTypes,
        'transactionSubtypes' => $transactionSubtypes
    ]);
}

public function settings()
{
    return view('owner.settings');
}

public function deleteImage($id)
{
    $user = User::findOrFail($id);

    if ($user->profile_image) {
        // Delete the file from storage
        Storage::delete($user->profile_image);

        // Remove the path from the database
        $user->profile_image = null;
        $user->save();
    }

    return back()->with('status', 'Profile image deleted successfully!');
}


    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Check if current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        // Update the password
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('status', 'Password updated successfully!');
    }


    
   public function showAnimalProfile(Request $request, $animal_id)
   {
       // Fetch the animal with its related data using eager loading
       $animal = Animal::with([
           'owner.user',        // Load owner and related user details
           'species',           // Load species details
           'breed',             // Load breed details
           'transactions' => function ($query) use ($request) {
               // Apply filters to the transactions query
               if ($request->has('search_date') && $request->search_date != '') {
                   $query->whereDate('created_at', $request->search_date);
               }
   
               if ($request->has('transaction_type') && $request->transaction_type != '') {
                   $query->where('transaction_type_id', $request->transaction_type);
               }
   
               if ($request->has('transaction_status') && $request->transaction_status != '') {
                   $query->where('status', $request->transaction_status);
               }
   
               // Order by newest transaction first (sort by created_at descending)
               $query->latest();
   
               // Load the related models for each transaction
               $query->with([
                   'transactionType',  // Load transaction types
                   'vet',               // Load veterinarians (vets)
                   'transactionSubtype' => function ($subQuery) {
                       $subQuery->with('transactionType');  // Load related transaction type for subtypes
                   }
               ]);
           }
       ])
       ->where('animal_id', $animal_id) // Find the animal by animal_id
       ->firstOrFail(); // Ensure it exists or return a 404 error
   
       // Fetch additional data for transaction forms (if needed for dropdowns, etc.)
       $transactionTypes = TransactionType::all(); // Get all transaction types
       $transactionSubtypes = TransactionSubtype::all(); // Get all transaction subtypes
       $vets = User::where('role', 2)->get(); // Get all veterinarians (role 2 = Veterinarian)
       
       // Fetch vaccines and technicians
       $vaccines = Vaccine::all();  // Fetch all vaccines
       $technicians = VeterinaryTechnician::all();  // Fetch all veterinary technicians
   
       // Paginate the transactions for the given animal
       $transactions = $animal->transactions()
           ->orderBy('created_at', 'desc')
           ->paginate(10); // Apply pagination to transactions (10 per page)
   
       // Return the view with the necessary data
       return view('owner.animal-profile', compact(
           'animal', 
           'transactionTypes', 
           'transactionSubtypes', 
           'vets', 
           'transactions', 
           'vaccines', // Pass vaccines to the view
           'technicians' // Pass technicians to the view
       ));
   }

    // Show the form for editing the animal's details
    public function Newedit($animal_id)
    {
        // Fetch the animal by its ID
        $animal = Animal::where('animal_id', $animal_id)->firstOrFail();


        // Fetch all species, breeds, and owners for dropdown selections
        $species = Species::all();
        $breeds = Breed::all();
        $owners = Owner::all();
        $vaccines = Vaccine::all();

        // Return the view with the data to populate the form
        return view('owner.animal-edit', compact('animal', 'species', 'breeds', 'owners', 'vaccines'));
    }
    public function update(Request $request, $animal_id)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required_if:is_group,false|string|max:255', // Required if not a group
            'species_id' => 'required|exists:species,id',
            'breed_id' => 'required|exists:breeds,id',
            'birth_date' => 'nullable|date',
            'gender' => 'required_if:is_group,false|in:male,female', // Required if not a group
            'medical_condition' => 'nullable|string',
            'photo_front' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'photo_back' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'photo_left_side' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'photo_right_side' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_group' => 'required|boolean',
            'group_count' => 'required_if:is_group,true|integer|min:1', // Required if group
            'color' => 'nullable|string|max:255', // Color validation
        ]);
    
        // Fetch the existing animal record
        $animal = Animal::where('animal_id', $animal_id)->firstOrFail();
    
        // Prepare the attributes to be updated
        $attributes = [
            'species_id' => $request->species_id,
            'breed_id' => $request->breed_id,
            'birth_date' => $request->birth_date,
            'medical_condition' => $request->medical_condition,
            'is_group' => $request->is_group,
            'color' => $request->color,
        ];
    
        // Conditional logic for name and group-specific fields
        if ($request->is_group) {
            $attributes['name'] = $request->name; // Set default group name
            $attributes['gender'] = null; // Groups don't have genders
            $attributes['group_count'] = $request->group_count; // Assign group count
        } else {
            $attributes['name'] = $request->name; // Use the provided name for individuals
            $attributes['gender'] = $request->gender; // Assign gender for individuals
            $attributes['group_count'] = null; // Reset group count for individuals
        }
    
        // Handle file uploads for photos
        foreach (['photo_front', 'photo_back', 'photo_left_side', 'photo_right_side'] as $photo) {
            if ($request->hasFile($photo)) {
                if ($animal->{$photo}) {
                    \Storage::disk('public')->delete($animal->{$photo}); // Delete existing photo
                }
                $attributes[$photo] = $request->file($photo)->store('photos', 'public');
            }
        }
    
        // Perform the update with the prepared attributes
        Animal::where('animal_id', $animal_id)->update($attributes);
    
        // Redirect to the animal's profile with a success message
        return redirect()->route('newanimals.profile', ['animal_id' => $animal->animal_id])
            ->with('success', 'Animal updated successfully.');
    }
    
}