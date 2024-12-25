<?php

namespace App\Http\Controllers;

use App\Models\Vaccine; // Import the Species model
use App\Models\VeterinaryTechnician; // Import the Species model
use App\Models\Breed; // Import the Species model
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
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;



use Illuminate\Http\Request;

class OwnerController extends Controller
{
    public function loadOwnerDashboard()
 
    {
        // Fetch the logged-in user
        $user = Auth::user();

        // Fetch dynamic data
        $animalsOwned = Animal::where('owner_id', $user->owner->owner_id)->count(); // Count animals owned by the user
        $successfulTransactions = Transaction::where('owner_id', $user->owner->owner_id)  // Correct relationship access
        ->where('status', 1)  // Status of 1 for successful transactions
        ->count(); // Count successful transactions

        $pastTransactions = Transaction::where('owner_id', $user->owner->owner_id)
                                       ->orderBy('created_at', 'desc') // Sort by most recent
                                       ->limit(5) // Get only the most recent 5
                                       ->get();

        return view('owner.dashboard', [
            'animalsOwned' => $animalsOwned,
            'successfulTransactions' => $successfulTransactions,
            'pastTransactions' => $pastTransactions
        ]);
    }

    public function showProfile(Request $request, $owner_id)
    {
        // Ensure the $owner_id is valid
        if (!$owner_id) {
            return abort(404, 'Owner ID is missing.');
        }
    
        // Retrieve owner details with relationships, including user info, address, barangay, transactions, and animals
        $owner = Owner::with([
            'user', // Eager load related User model (for complete_name, profile_image, etc.)
            'animals' => function ($query) {
                // Eager load species and breed for animals, ordered by created_at descending
                $query->with(['species', 'breed'])
                      ->orderBy('created_at', 'desc'); // Order animals by newest first
            },
            'transactions' => function ($query) {
            // Eager load transactions and order them by created_at descending (newest first)
            $query->orderBy('created_at', 'desc'); // Order transactions by newest first
        },
            'address' => function ($query) {
                // Eager load address and related barangay
                $query->with('barangay');
            }
        ])
        ->join('users', 'owners.user_id', '=', 'users.user_id') // Join the 'users' table using user_id
        ->leftJoin('transactions', 'owners.owner_id', '=', 'transactions.owner_id') // Join transactions by owner_id
        ->leftJoin('transaction_types', 'transactions.transaction_type_id', '=', 'transaction_types.id') // Join for transaction types
        ->leftJoin('transaction_subtypes', 'transactions.transaction_subtype_id', '=', 'transaction_subtypes.id') // Join for transaction subtypes
        ->leftJoin('addresses', 'users.user_id', '=', 'addresses.user_id') // Left join for addresses
        ->leftJoin('barangays', 'addresses.barangay_id', '=', 'barangays.id') // Left join for barangays
        ->select(
            'owners.owner_id',  // Use owner_id as the primary key
            'owners.civil_status',
            'owners.category',
            'owners.created_at',
            'users.complete_name',  // User information to display in the owner's list
            'users.profile_image',
            'users.contact_no',
            'users.gender',
            'users.email',  // User information to display in the owner's list
            'users.birth_date',
            'users.user_id as user_id',  // This can be used for routing to the profile
            'addresses.street',
            'barangays.barangay_name',
            'transactions.transaction_type_id',
            'transactions.technician_id',
            'transaction_types.type_name',
            'transactions.transaction_subtype_id',
            'transaction_subtypes.subtype_name as transaction_subtype_name',
            'transactions.created_at as transaction_created_at'
        )
        ->where('owners.owner_id', $owner_id) // Get the specific owner by owner_id
        ->first(); // We only need one owner, so use first()
    
        // Check if the owner is found, if not, abort with a 404 error
        if (!$owner) {
            return abort(404, 'Owner profile not found.');
        }
    
        // Search and filter animals
        $query = Animal::with(['species', 'breed'])
            ->where('owner_id', $owner_id)
            ->orderBy('created_at', 'desc'); // Order animals by newest first
    
        // Apply search (by name)
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
    
        // Apply species filter
        if ($request->filled('species_id')) {
            $query->where('species_id', $request->species_id);
        }
    
        // Apply breed filter
        if ($request->filled('breed_id')) {
            $query->where('breed_id', $request->breed_id);
        }
    
        // Paginate results
        $animals = $query->paginate(10); // Adjust the pagination size if needed
    
        // Fetch species and breeds for filters
        $species = Species::all();
        $breeds = Breed::all();
    
        // Check if there are any animals to display
        if ($animals->isEmpty() && !$request->filled('search') && !$request->filled('species_id') && !$request->filled('breed_id')) {
            $message = 'No animals registered for this owner yet.';
        } else if ($animals->isEmpty()) {
            $message = 'No animals match your search or filter criteria.';
        } else {
            $message = null;
        }
    
        // Return the owner profile view with all the necessary data
        return view('admin.owner-profile', compact('owner', 'animals', 'species', 'breeds', 'message'));
    }
    //profile

  
    
    
    public function deleteAnimal($animal_id)
    {
        // Retrieve the animal details from the database
        $animal = DB::table('animals')->where('animal_id', $animal_id)->first();
    
        // Check if the animal exists
        if ($animal) {
            // Delete all transactions associated with the animal
            DB::table('transactions')->where('animal_id', $animal_id)->delete();
            
            // Perform the delete operation for the animal
            DB::table('animals')->where('animal_id', $animal_id)->delete();
        }
    
        // Redirect to the owner's profile with a success message
        return redirect()->route('owners.profile-owner', ['owner_id' => $animal->owner_id])
                         ->with('success', 'Animal and associated transactions deleted successfully.');
    }

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
        
        return view('admin.animal-update', compact(
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
    
       

    return redirect()->route('owners.profile-owner', ['owner_id' => $owner_id])
                     ->with('success', 'Animal and transaction updated successfully!');
}



    public function getBreedsBySpecies(Request $request)
{
    $speciesId = $request->species_id;
    $breeds = Breed::where('species_id', $speciesId)->get();

    return response()->json($breeds);
}


// Method to show the form to add a transaction
public function addTransactionForm($owner_id)
{
    // Fetch the owner and their animals
    $owner = Owner::with('animals.species', 'animals.breed')->findOrFail($owner_id);

    // Fetch all transaction types and subtypes
    $transactionTypes = TransactionType::with('subtypes')->get(); // Fetch transaction types with their subtypes
    $vets = User::where('role', 2)->get(); // Get all veterinarians (role = 2)
    $animals = Owner::find($owner_id)
                    ->animals()
                    ->orderBy('created_at', 'desc') // Order animals by the latest first
                    ->get(); // Get all animals for the owner
    $technicians = VeterinaryTechnician::all(); // Fetch all veterinary technicians
    $vaccines = Vaccine::all(); // Fetch all veterinary technicians



    // Pass the data to the view
    return view('admin.add-transaction', [
        'transactionTypes' => $transactionTypes,
        'vets' => $vets,
        'animals' => $animals,
        'owner' => $owner, // Add the owner variable here
        'owner_id' => $owner_id,
        'technicians' => $technicians, // Include the technicians here
        'vaccines' => $vaccines, // Include the technicians here


    ]);
}


public function storeTransaction(Request $request, $owner_id)
{
    // Validate the form input
    $request->validate([
        'animal_id' => 'required|exists:animals,animal_id', // Ensure animal exists
        'transaction_type_id' => 'required|exists:transaction_types,id', // Ensure valid transaction type
        'transaction_subtype_id' => 'required|exists:transaction_subtypes,id', // Ensure valid transaction subtype
        'vet_id' => 'required|exists:users,user_id,role,2', // Ensure veterinarian is valid (role 2)
        'technician_id' => 'nullable|exists:veterinary_technicians,technician_id',
        'status' => 'required|in:0,1,2', // Ensure valid status
        'details' => 'nullable|string|max:1000', // Optional details
        'Vaccine_id' => 'nullable|exists:vaccines,id',

       

    ]);

    try {
        // Prepare the data for the transaction
        $transactionData = [
            'owner_id' => $owner_id, // Link to the owner
            'animal_id' => $request->animal_id, // Link to the selected animal
            'transaction_type_id' => $request->transaction_type_id, // Selected transaction type
            'transaction_subtype_id' => $request->transaction_subtype_id, // Selected transaction subtype
            'vet_id' => $request->vet_id, // Link to the selected veterinarian
            'technician_id' => $request->technician_id,
            'status' => $request->status, // Transaction status
            'details' => $request->details, // Transaction details
            'vaccine_id' => $request->vaccine_id,

        ];

        // Save the transaction record
        Transaction::create($transactionData);

        // Redirect back to the owner's profile page with success message
        return redirect()->route('owners.profile-owner', ['owner_id' => $owner_id])
                         ->with('success', 'Transaction added successfully.');
    } catch (\Exception $e) {
        // Handle any errors during the insertion
        return back()->withErrors(['error' => $e->getMessage()]);
    }
}

public function deleteTransaction($transaction_id)
{
    // Find the transaction by its custom primary key (transaction_id)
    $transaction = Transaction::where('transaction_id', $transaction_id)->firstOrFail();

    // Find the associated owner based on the transaction's data (e.g., `owner_id`)
    $owner = Owner::where('owner_id', $transaction->owner_id)->firstOrFail();

    // Delete the transaction
    Transaction::where('transaction_id', $transaction_id)->delete();

    // Redirect with a success message
    return redirect()
        ->route('owner.transactions', ['owner_id' => $owner->owner_id])
        ->with('success', 'Transaction deleted successfully.');
}


public function editTransactionForm($transaction_id)
{
    // Fetch the transaction by its ID
    $transaction = Transaction::where('transaction_id', $transaction_id)->firstOrFail();

    // Fetch necessary data
    $transactionTypes = TransactionType::with('subtypes')->get(); // Get transaction types and subtypes
    $vets = User::where('role', 2)->get(); // Get veterinarians (role = 2)
    $animals = Animal::where('owner_id', $transaction->owner_id)
    ->orderBy('created_at', 'desc')  // Order animals by the creation date (newest first)
    ->get(); // Get animals of the same owner
$technicians = VeterinaryTechnician::all(); // Get all technicians
    $vaccines = Vaccine::all(); // Get all technicians


    // Pass data to the view, including the technician_id
    return view('admin.edit-transaction', [
        'transaction' => $transaction,
        'transactionTypes' => $transactionTypes,
        'vets' => $vets,
        'animals' => $animals,
        'owner_id' => $transaction->owner_id, // Add owner_id to the view data
        'technicians' => $technicians,
        'vaccines' => $vaccines, // Add owner_id to the view data
        'selectedVaccineId' => $transaction->vaccine_id, // Pass technician_id to view
        'selectedTechnicianId' => $transaction->technician_id, // Pass technician_id to view
    ]);
}

public function updateTransaction(Request $request, $transaction_id)
{
    // Validate the form input
    $validated = $request->validate([
        'transaction_type_id' => 'required|exists:transaction_types,id',
        'transaction_subtype_id' => 'required|exists:transaction_subtypes,id',
        'animal_id' => 'required|exists:animals,animal_id',
        'vet_id' => 'required|exists:users,user_id,role,2',
        'created_at' => 'required|date|before_or_equal:today',
        'details' => 'nullable|string|max:1000',
        'status' => 'nullable|integer|in:0,1,2',
        'technician_id' => 'nullable|exists:veterinary_technicians,technician_id',
        'vaccine_id' => 'nullable|exists:vaccines,id',

    ]);

    // Find the transaction by its ID
    $transaction = Transaction::where('transaction_id', $transaction_id)->firstOrFail();

    // If a technician ID is provided, use it; otherwise, retain the existing technician ID
    $technician_id = $validated['technician_id'] ?? $transaction->technician_id;

    $vaccine_id = $validated['vaccine_id'] ?? $transaction->vaccine_id;

    // Update the transaction details
    Transaction::where('transaction_id', $transaction_id)->update([
        'transaction_type_id' => $validated['transaction_type_id'],
        'transaction_subtype_id' => $validated['transaction_subtype_id'],
        'animal_id' => $validated['animal_id'],
        'vet_id' => $validated['vet_id'],
        'created_at' => $validated['created_at'],
        'details' => $validated['details'] ?? $transaction->details, // Retain old details if not updated
        'status' => $validated['status'] ?? $transaction->status, // Retain old status if not updated
        'technician_id' => $technician_id, // Update technician_id if provided
        'vaccine_id' => $vaccine_id, // Update technician_id if provided

    ]);

    // Redirect back with a success message
    return redirect()->route('owners.profile-owner', ['owner_id' => $transaction->owner_id])
                     ->with('success', 'Transaction updated successfully.');
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
    return view('admin.owner-transactions', [
        'owner_id' => $owner_id,
        'transactions' => $transactions,
        'owner' => $owner,
        'transactionTypes' => $transactionTypes,
        'transactionSubtypes' => $transactionSubtypes
    ]);
}


public function getTransactionData($transactionId)
{
    $transaction = Transaction::find($transactionId);
    
    if ($transaction) {
        return response()->json([
            'transaction_type_id' => $transaction->transaction_type_id,
            'transaction_subtype_id' => $transaction->transaction_subtype_id,
            'vet_id' => $transaction->vet_id,
            'status' => $transaction->status,
            'details' => $transaction->details,
            'subtypes' => $transaction->transactionType->subtypes, // Example: assuming transactionType has a relation to subtypes
        ]);
    }

    return response()->json(['error' => 'Transaction not found']);
}

// Controller method to get subtypes by transaction type
public function getSubtypesForType($transactionTypeId)
{
    $transactionType = TransactionType::with('subtypes')->find($transactionTypeId);

    if (!$transactionType) {
        return response()->json(['error' => 'Transaction type not found'], 404);
    }

    // Return the subtypes
    return response()->json([
        'subtypes' => $transactionType->subtypes,
    ]);
}

public function removeTransaction($transaction_id)
{
    // Find the transaction by its custom primary key (transaction_id)
    $transaction = Transaction::where('transaction_id', $transaction_id)->firstOrFail();

    // Find the associated owner based on the transaction's data (e.g., `owner_id`)
    $owner = Owner::where('owner_id', $transaction->owner_id)->firstOrFail();

    // Delete the transaction
    Transaction::where('transaction_id', $transaction_id)->delete();

    // Redirect with a success message
    return redirect()->route('owners.profile-owner', ['owner_id' => $transaction->owner_id])
    ->with('success', 'Transaction updated successfully.');
}

public function getSubtypes($transactionTypeId)
{
    $subtypes = TransactionSubtype::where('transaction_type_id', $transactionTypeId)->get();
    
    // Check if any subtypes were found
    if ($subtypes->isEmpty()) {
        return response()->json(['subtypes' => []]);
    }

    // Return only the needed fields (e.g., id and subtype_name)
    return response()->json([
        'subtypes' => $subtypes->map(function($subtype) {
            return [
                'id' => $subtype->id,
                'subtype_name' => $subtype->subtype_name
            ];
        })
    ]);
}


public function owner_editprofile($id)
   {
       $user = User::with('address')->findOrFail($id); // Fetch the user with their address
       $barangays = Barangay::all(); // Fetch all barangays
       return view('owner.owner-edit', compact('user', 'barangays')); // Pass data to the view
   }

   public function owner_updateprofile(Request $request, $id)
   {
       $request->validate([
           'complete_name' => 'required|string|max:100',
           'contact_no' => 'required|string|max:15',
           'gender' => 'required|string|max:10',
           'birth_date' => 'required|date',
           'status' => 'required|integer',
           'email' => 'required|email|max:100|unique:users,email,' . $id . ',user_id',
           'barangay_id' => 'required|exists:barangays,id',
           'street' => 'nullable|string|max:255',
           'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Added validation for image
       ]);

       $user = User::findOrFail($id);

       $transaction = Owner::where('user_id', $user->user_id)->first();

       // Handle profile image upload if a file is provided
       if ($request->hasFile('profile_image')) {
           // Delete old image if it exists
           if ($user->profile_image) {
               Storage::disk('public')->delete($user->profile_image);
           }
       
           // Store the new image
           $imagePath = $request->file('profile_image')->store('profile_images', 'public');
           $user->profile_image = $imagePath;
       }
       
        // Set the role to 1 explicitly
    $user->role = 1;
       // Update user data
       $user->update($request->only([
           'complete_name',
           'role',
           'contact_no',
           'gender',
           'birth_date',
           'status',
           'email',
       ]));

       // Update or create the address
       $user->address()->updateOrCreate(
           ['user_id' => $user->user_id], // Condition to find the address
           $request->only(['barangay_id', 'street']) // Address fields to update
       );

       // Update or create the owner's data
       $user->owner()->updateOrCreate(
           ['user_id' => $user->user_id], // Match condition
           $request->only(['civil_status', 'category']) + ['permit' => 1] // Data to update, with permit added
       );
       return redirect()->route('owners.profile', ['owner_id' => $transaction->owner_id])
       ->with('message', 'Profile updated successfully.');

   }


    
   public function createAddAnimalForm  ($owner_id)
   {
       // Fetch necessary data
       $owner = User::where('role', 1)->whereHas('owner', function ($query) use ($owner_id) {
        $query->where('owner_id', $owner_id);
    })->first(); // Assuming one owner for a given owner_id
    
       $species = Species::all(); // Get all species
       $breeds = Breed::all(); // Get all breeds
       $vets = User::where('role', 2)->get(); // Get all veterinarians (role = 2)
       $transactionTypes = TransactionType::with('subtypes')->get(); // Fetch transaction types with their subtypes
       $technicians = VeterinaryTechnician::all(); // Fetch all veterinary technicians
       $vaccines = Vaccine::all(); // Fetch all veterinary technicians

   
       // Pass the data to the view
       return view('owner.add-animal', [
           'species' => $species,
           'breeds' => $breeds,
           'vets' => $vets,
           'transactionTypes' => $transactionTypes,
           'owner_id' => $owner_id,
           'technicians' => $technicians, // Include the technicians here
           'vaccines' => $vaccines, // Include the technicians here
           'owner' => $owner, // Get all species

           

       ]);
   }
   
   public function storeAnimal  (Request $request)
   {
       // If is_group is false, set group_count to 1 before validation
       if (!$request->is_group) {
           $request->merge(['group_count' => 1]);
       }
   
       // If is_group is true, set gender to "N/A"
       if ($request->is_group) {
           $request->merge(['gender' => 'N/A']);
       }
   
       // Validate the form input
       $request->validate([
           'name' => 'required|string|max:255',  // Name is now required, but will be "N/A" if is_group is true
           'owner_id' => 'required|exists:owners,owner_id',
           'species_id' => 'required|exists:species,id',
           'breed_id' => 'required|exists:breeds,id',
           'color' => 'nullable|string|max:255', // Validation for color
           'birth_date' => ['nullable', 'date', 'before_or_equal:today'], // Ensure birthdate is not in the future
           'gender' => 'nullable|in:Male,Female,N/A',
           'medical_condition' => 'nullable|string|max:255',
           'photo_front' => 'nullable|image|max:2048',
           'photo_back' => 'nullable|image|max:2048',
           'photo_left_side' => 'nullable|image|max:2048',
           'photo_right_side' => 'nullable|image|max:2048',
           'is_group' => 'required|boolean', // Add validation for is_group
           'group_count' => 'required|integer|min:1', // Add validation for group_count (required)
       ]);
   
       // Prepare the data for the animal
       $data = $request->only([
           'name',
           'owner_id',
           'species_id',
           'breed_id',
           'color', // Include the color field
           'birth_date',
           'gender',
           'medical_condition',
           'is_group', // Include is_group in the data
           'group_count', // Include group_count in the data
       ]);
   
       // Handle file uploads for photos
       foreach (['photo_front', 'photo_back', 'photo_left_side', 'photo_right_side'] as $photo) {
           if ($request->hasFile($photo)) {
               $data[$photo] = $request->file($photo)->store('animal_photos', 'public');
           } else {
               $data[$photo] = null;
           }
       }
   
       try {
           // Save the animal record
           $animal = Animal::create($data); // Animal is created, now we have its animal_id
   
           return redirect()->route('owners.profile', ['owner_id' => $request->owner_id])
                            ->with('success', 'Animal added successfully.');
       } catch (\Exception $e) {
           // Handle any errors during the insertion
           return back()->withErrors(['error' => $e->getMessage()]);
       }
   }
   

}