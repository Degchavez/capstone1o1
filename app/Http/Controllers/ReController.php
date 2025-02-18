<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail; 
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

class ReController extends Controller
{
    
   public function loadOwnersList(Request $request)
   {
       // Capture filters from the request
       $search = $request->input('search', '');
       $gender = $request->input('gender', ''); // Filter by gender
       $category = $request->input('category', ''); // Filter by category
       $civil_status = $request->input('civil_status', ''); // Filter by civil status
       $barangay_id = $request->input('barangay', ''); // Filter by barangay
   
       // Fetch barangays for the dropdown
       $barangays = Barangay::select('id', 'barangay_name')->orderBy('barangay_name')->get();
   
       // Fetch owners with necessary relationships and filters
       $owners = Owner::with([
               'user',
               'animals.species', // Load species for animals
               'animals.breed',   // Load breed for animals
               'transactions',    // Load transactions
               'address.barangay' // Load address with barangay
           ])
           ->join('users', 'owners.user_id', '=', 'users.user_id')
           ->leftJoin('addresses', 'users.user_id', '=', 'addresses.user_id')
           ->leftJoin('barangays', 'addresses.barangay_id', '=', 'barangays.id')
           ->leftJoin('transactions', 'owners.owner_id', '=', 'transactions.owner_id')
           ->leftJoin('transaction_types', 'transactions.transaction_type_id', '=', 'transaction_types.id')
   
           // Filter: role must be 'Owner' (role = 1)
           ->where('users.role', 1)
   
           // Apply search filter
           ->where(function ($query) use ($search) {
               $query->where('users.complete_name', 'like', '%' . $search . '%')
                     ->orWhere('users.contact_no', 'like', '%' . $search . '%')
                     ->orWhere('addresses.street', 'like', '%' . $search . '%')
                     ->orWhere('barangays.barangay_name', 'like', '%' . $search . '%');
           })
   
          // Apply gender filter
->when($gender, function ($query) use ($gender) {
    return $query->where('users.gender', $gender);
})

// Apply category filter
->when($category, function ($query) use ($category) {
    return $query->where('owners.category', $category);
})

// Apply civil status filter
->when($civil_status, function ($query) use ($civil_status) {
    return $query->where('owners.civil_status', $civil_status);
})

// Apply barangay filter
->when($barangay_id, function ($query) use ($barangay_id) {
    return $query->where('barangays.id', $barangay_id);
})

// Apply date range filter
->when(request('fromDate'), function ($query) {
    return $query->whereDate('owners.created_at', '>=', request('fromDate'));
})
->when(request('toDate'), function ($query) {
    return $query->whereDate('owners.created_at', '<=', request('toDate'));
})

   
           // Select necessary fields
           ->select(
               'owners.owner_id', 
               'owners.civil_status',
               'owners.category',
               'owners.created_at',
               'users.complete_name',
               'users.profile_image',
               'users.contact_no',
               'users.gender',
               'users.birth_date',
               'users.user_id as user_id',
               'addresses.street',
               'barangays.barangay_name',
               DB::raw('GROUP_CONCAT(transactions.transaction_type_id) as transaction_type_ids'),
               DB::raw('GROUP_CONCAT(transaction_types.type_name) as transaction_types'),
               DB::raw('MAX(transactions.created_at) as transaction_created_at')
           )
   
           // Group by owner attributes
           ->groupBy(
               'owners.owner_id',
               'owners.civil_status',
               'owners.category',
               'owners.created_at',
               'users.complete_name',
               'users.profile_image',
               'users.contact_no',
               'users.gender',
               'users.birth_date',
               'users.user_id',
               'addresses.street',
               'barangays.barangay_name'
           )
   
           // Order by creation date
           ->orderBy('users.created_at', 'desc')
   
           // Pagination: 25 items per page
           ->paginate(15);
   
       // Return to the view with compact variables
       return view('receptionist.animal-owners', compact('owners', 'search', 'gender', 'category', 'civil_status', 'barangays', 'barangay_id'));
   }
   
   public function loadAnimalsList(Request $request)
{
    // Fetch filter inputs from the request
    $search = $request->input('search', '');    // Search query
    $perPage = $request->input('perPage', 25);   // Default to 25 items per page
    $species_id = $request->input('species_id', '');  // Species filter
    $breed_id = $request->input('breed_id', '');      // Breed filter
    $status = $request->input('status', '');          // Status filter
    $fromDate = $request->input('fromDate', '');      // From date filter
    $toDate = $request->input('toDate', '');          // To date filter
    $owner_id = $request->input('owner_id', '');      // Owner filter

    // Fetch Owners, Species, and Breeds for the filter dropdowns
    $owners = Owner::all();
    $species = Species::all();  // Fetch all species for the species dropdown
    $breeds = Breed::all();     // Fetch all breeds for the breed dropdown

    // Build the query with filtering and eager loading
    $query = Animal::with(['owner', 'species', 'breed', 'transactions.vet']) // Eager load related data
        ->when($species_id, function ($q) use ($species_id) {
            $q->where('species_id', $species_id);  // Filter by species
        })
        ->when($breed_id, function ($q) use ($breed_id) {
            $q->where('breed_id', $breed_id);  // Filter by breed
        })
        ->when($status, function ($q) use ($status) {
            $q->where('status', $status);  // Filter by status
        })
        ->when($owner_id, function ($q) use ($owner_id) {
            $q->where('owner_id', $owner_id);  // Filter by owner
        })
        ->when($search, function ($q) use ($search) {
            $q->where(function ($q) use ($search) {
                // Search by animal name
                $q->where('name', 'LIKE', "%{$search}%")
                  // Search for the vet's complete_name from the transaction's vet relationship
                  ->orWhereHas('transactions.vet', function ($q) use ($search) {
                      $q->where('users.complete_name', 'LIKE', "%{$search}%"); // Searching complete_name in users table
                  });
            });
        })
        ->when($fromDate, function ($q) use ($fromDate) {
            $q->whereDate('created_at', '>=', $fromDate);  // Filter by creation date (from)
        })
        ->when($toDate, function ($q) use ($toDate) {
            $q->whereDate('created_at', '<=', $toDate);  // Filter by creation date (to)
        })
        ->orderBy('created_at', 'desc'); // Default ordering by creation date

    // Paginate results
    $animals = $query->paginate($perPage);

    // Return the view with animals, owners, species, and breeds data
    return view('receptionist.animals-table', compact('animals', 'owners', 'species', 'breeds'));
}

public function showRegistrationForm()
{
    $barangays = Barangay::all(); // Get all barangays for selection
    return view('receptionist.add-owners', compact('barangays'));
}

/**
 * Handle the user registration.
 */
public function register(Request $request)
{
    // Validate incoming request
    $request->validate([
        'complete_name' => 'required|string|max:255',
        'contact_no' => 'required|string|max:15',
        'gender' => 'required|string|max:10',
        'birth_date' => ['nullable', 'date', 'before_or_equal:today'],
        'email' => 'required|email|unique:users,email',
        'barangay_id' => 'required|integer|exists:barangays,id',
        'street' => 'required|string|max:255',
        'civil_status' => 'required|string|max:20',
        'category' => 'nullable|string|max:100',
        'permit' => 'nullable|string|max:255',
    ]);

    // Generate a random password
    $randomPassword = Str::random(8);

    // Create the user
    $user = new User();
    $user->complete_name = $request->complete_name;
    $user->contact_no = $request->contact_no;
    $user->gender = $request->gender;
    $user->birth_date = $request->birth_date;
    $user->status = 1;
    $user->email = $request->email;
    $user->role = 1;  // Assuming '1' is for owner
    $user->password = bcrypt($randomPassword);  // Use the random password
    $user->save();

    // Register the address linked to the user
    $address = new Address();
    $address->user_id = $user->user_id;
    $address->barangay_id = $request->barangay_id;
    $address->street = $request->street;
    $address->save();

    // Create the owner record linked to the user
    $owner = new Owner();
    $owner->user_id = $user->user_id;
    $owner->civil_status = $request->civil_status;
    $owner->category = $request->category;
    $owner->permit = 1; 
    $owner->save();

    // Send the password to the user's email
    Mail::to($request->email)->send(new WelcomeEmail($user, $randomPassword));

    // Redirect with a success message
    return redirect()->route('rec-owners')->with('success', 'User and owner registered successfully, and password has been emailed!');
}


public function ownerList_edit($owner_id)
{
    // Fetch the owner details using the `user_id` foreign key
    $owner = Owner::where('user_id', $owner_id)->firstOrFail();

    // Fetch the user with their related address
    $user = User::with('address')->findOrFail($owner_id);

    // Fetch all barangays for the dropdown list
    $barangays = Barangay::all();

    // Pass the data to the view
    return view('receptionist.ownerlist-edit', compact('user', 'barangays', 'owner'));
}



public function ownerList_update(Request $request, $owner_id)
{
    // Validation
    $request->validate([
        'complete_name' => 'required|string|max:100',
        'contact_no' => 'required|string|max:15',
        'gender' => 'required|string|max:10',
        'birth_date' => ['nullable', 'date', 'before_or_equal:today'], // Ensure birthdate is not in the future
        'status' => 'required|integer',
        'email' => 'required|email|max:100|unique:users,email,' . $owner_id . ',user_id',
        'barangay_id' => 'required|exists:barangays,id',
        'street' => 'nullable|string|max:255',
        'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Validate image upload
        'civil_status' => 'nullable|string|max:50', // Additional fields
        'category' => 'nullable|string|max:50',
    ]);

    // Find the user by user_id
    $user = User::findOrFail($owner_id);

    // Find the owner record associated with the user
    $owner = Owner::where('user_id', $user->user_id)->firstOrFail();

    // Handle profile image upload
    if ($request->hasFile('profile_image')) {
        // Delete old image if it exists
        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
        }

        // Store the new image and update the path
        $user->profile_image = $request->file('profile_image')->store('profile_images', 'public');
    }

    // Update the user record
    $user->update([
        'complete_name' => $request->complete_name,
        'contact_no' => $request->contact_no,
        'gender' => $request->gender,
        'birth_date' => $request->birth_date,
        'status' => $request->status,
        'email' => $request->email,
        'role' => 1, // Explicitly set the role
    ]);

    // Update or create the user's address
    $user->address()->updateOrCreate(
        ['user_id' => $user->user_id], // Match by user_id
        [
            'barangay_id' => $request->barangay_id,
            'street' => $request->street,
        ]
    );

    // Update or create the owner data
    $user->owner()->updateOrCreate(
        ['user_id' => $user->user_id], // Match by user_id
        [
            'civil_status' => $request->civil_status,
            'category' => $request->category,
            'permit' => 1, // Set permit to 1 explicitly
        ]
    );

    // Redirect back with success message
    return redirect()->route('rec-owners')->with('message', 'Profile updated successfully.');
}



public function destroy(User $user)
{
    try {
        // Delete associated owner details if they exist
        if ($user->owner) {
            $user->owner->delete(); // Delete the related owner record
        }

        // Now delete the user
        $user->delete(); // Soft delete or hard delete depending on your model

        return redirect()->back()->with('message', 'User and their owner details deleted successfully!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'An error occurred while deleting the user: ' . $e->getMessage());
    }
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
    return view('receptionist.owner-profile', compact('owner', 'animals', 'species', 'breeds', 'message'));
}

public function owner_edit($id)
{
    $user = User::with('address')->findOrFail($id); // Fetch the user with their address
    $barangays = Barangay::all(); // Fetch all barangays
    return view('receptionist.owner-edit', compact('user', 'barangays')); // Pass data to the view
}

public function owner_update(Request $request, $id)
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
    return redirect()->route('rec.profile-owner', ['owner_id' => $transaction->owner_id])
    ->with('message', 'Profile updated successfully.');

}

public function showAddAnimalForm($owner_id)
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
       return view('receptionist.add-animal', [
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
   
   public function store(Request $request)
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
           'is_vaccinated' => 'required|in:0,1,2', // Add validation for is_vaccinated

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
           'is_vaccinated', // Include is_vaccinated in the data

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
   
           return redirect()->route('rec.profile-owner', ['owner_id' => $request->owner_id])
                            ->with('success', 'Animal added successfully.');
       } catch (\Exception $e) {
           // Handle any errors during the insertion
           return back()->withErrors(['error' => $e->getMessage()]);
       }
   }

   public function RecgetBreeds($species_id)
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
        
        return view('receptionist.animal-update', compact(
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
            'is_vaccinated' => 'required|in:0,1,2', // Add validation for is_vaccinated

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
            'group_count' => $request->is_group ? $request->group_count : 1,
            'is_vaccinated' => $request->is_vaccinated,

        ]);
    
       

    return redirect()->route('rec.profile-owner', ['owner_id' => $owner_id])
                     ->with('success', 'Animal and transaction updated successfully!');
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
    return view('receptionist.add-transaction', [
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
        return redirect()->route('rec.profile-owner', ['owner_id' => $owner_id])
                         ->with('success', 'Transaction added successfully.');
    } catch (\Exception $e) {
        // Handle any errors during the insertion
        return back()->withErrors(['error' => $e->getMessage()]);
    }
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
    return view('receptionist.edit-transaction', [
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
    return redirect()->route('rec.profile-owner', ['owner_id' => $transaction->owner_id])
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
    return view('receptionist.owner-transactions', [
        'owner_id' => $owner_id,
        'transactions' => $transactions,
        'owner' => $owner,
        'transactionTypes' => $transactionTypes,
        'transactionSubtypes' => $transactionSubtypes
    ]);
}

public function showAddAnimalForms()
{
    // Fetch all species and breeds
    $species = Species::all();
    $breeds = Breed::all();

    // Fetch all owners (users with role 1)
    $owners = User::where('role', 1)
                  ->join('owners', 'owners.user_id', '=', 'users.user_id')
                  ->select('users.user_id', 'owners.owner_id', 'users.complete_name')
                  ->get(); // Ensure you're getting the 'complete_name' as well

    // Fetch all veterinarians (users with role 2)
    $vets = User::where('role', 2)->get();

    // Fetch all transaction types and subtypes
    $transactionTypes = TransactionType::all();
    $transactionSubtypes = TransactionSubtype::all(); // Ensure this fetches all subtypes
    $technicians = VeterinaryTechnician::all();
    $vaccines = Vaccine::all();

    // Return the view with the necessary data
    return view('receptionist.plus-animal', compact(
        'species',
        'breeds',
        'owners',
        'vets',
        'transactionTypes',
        'transactionSubtypes',
        'technicians',
        'vaccines',
    ));
}

public function animalStore(Request $request)
{
    // Validate the form input
    $request->validate([
        'name' => 'required_if:is_group,false|string|max:255', // Required for individual animals
        'owner_id' => 'required|exists:owners,owner_id',
        'species_id' => 'required|exists:species,id',
        'breed_id' => 'required|exists:breeds,id',
        'group_count' => 'required_if:is_group,true|integer|min:1',
        'birth_date' => ['nullable', 'date', 'before_or_equal:today'], // Ensure birthdate is not in the future
        'gender' => 'nullable|required_if:is_group,false|in:Male,Female', // Gender required for individual animals
        'medical_condition' => 'nullable|string|max:255',
        'color' => 'required|string|max:100', // New color field, required for both
        'photo_front' => 'nullable|image|max:2048',
        'photo_back' => 'nullable|image|max:2048',
        'photo_left_side' => 'nullable|image|max:2048',
        'photo_right_side' => 'nullable|image|max:2048',
        'is_group' => 'required|boolean', // Determine if it's a group
        'is_vaccinated' => 'required|in:0,1,2', // Add validation for is_vaccinated

    ]);
    
    // Prepare the animal data
    $data = $request->only([
        'name','color', 'owner_id', 'species_id', 'breed_id', 'birth_date', 'gender', 'medical_condition', 'is_group', 'group_count','is_vaccinated',
    ]);

    // Convert is_group to boolean
    $data['is_group'] = $request->is_group == '1';

    // Handle group-specific logic
    if ($data['is_group']) {
        $data['gender'] = null; // Groups do not have a gender
        $data['group_count'] = max(1, $data['group_count'] ?? 1); // Ensure group_count is at least 1
    } else {
        $data['group_count'] = 1; // Individual animals do not have a group count
    }

    // Handle photo uploads
    foreach (['photo_front', 'photo_back', 'photo_left_side', 'photo_right_side'] as $photo) {
        if ($request->hasFile($photo)) {
            $data[$photo] = $request->file($photo)->store('animal_photos', 'public');
        }
    }

    try {
        // Save the animal record and get the created instance
        $animal = Animal::create([
            'name' => $data['name'], // Use default for groups
            'color' => $data['color'], // Use default for groups
            'owner_id' => $data['owner_id'],
            'species_id' => $data['species_id'],
            'breed_id' => $data['breed_id'],
            'birth_date' => $data['birth_date'],
            'gender' => $data['gender'], // Null for groups
            'medical_condition' => $data['medical_condition'],
            'is_group' => $data['is_group'],
            'group_count' => $data['group_count'] ?? 1, // Null for individual animals
            'is_vaccinated' => $data['is_vaccinated'],
            'photo_front' => $data['photo_front'] ?? null,
            'photo_back' => $data['photo_back'] ?? null,
            'photo_left_side' => $data['photo_left_side'] ?? null,
            'photo_right_side' => $data['photo_right_side'] ?? null,
        ]);

        // Redirect with success message
        return redirect()->route('rec-animals')
        ->with('message', 'Animal added successfully.');
    

    } catch (\Exception $e) {
        // Handle exceptions and return the error message
        return back()->withErrors(['error' => $e->getMessage()]);
    }
}

public function showEditAnimalForm($animal_id)
{
    // Fetch the existing animal by ID
    $animal = Animal::where('animal_id', $animal_id)->firstOrFail();

    // Fetch all species and breeds
    $species = Species::all();
    $breeds = Breed::all();

    // Fetch all owners (users with role 1)
    $owners = User::where('role', 1)
                  ->join('owners', 'owners.user_id', '=', 'users.user_id')
                  ->select('users.user_id', 'owners.owner_id', 'users.complete_name')
                  ->get(); // Ensure you're getting the 'complete_name' as well

    // Fetch all veterinarians (users with role 2)
    $vets = User::where('role', 2)->get();

    // Fetch all transaction types and subtypes
    $transactionTypes = TransactionType::all();
    $transactionSubtypes = TransactionSubtype::all(); // Ensure this fetches all subtypes

    // Fetch the related transaction (assuming the latest or the relevant one)
    $transaction = Transaction::where('animal_id', $animal_id)->latest()->first();

    // Return the view with the necessary data
    return view('receptionist.edit-animal-form', compact(
        'animal',
        'species',
        'breeds',
        'owners',
        'vets',
        'transactionTypes',
        'transactionSubtypes',
        'transaction'
    ));
}


public function animalUpdate(Request $request, $animal_id)
{
    // Determine the validation rules based on is_group
    $validationRules = [
        'owner_id' => 'required|exists:owners,owner_id',
        'species_id' => 'required|exists:species,id',
        'breed_id' => 'required|exists:breeds,id',
        'birth_date' => ['nullable', 'date', 'before_or_equal:today'], // Ensure birthdate is not in the future
        'color' => 'nullable|string|max:255', // Add validation for color
        'medical_condition' => 'nullable|string|max:255',
        'photo_front' => 'nullable|image|max:2048',
        'photo_back' => 'nullable|image|max:2048',
        'photo_left_side' => 'nullable|image|max:2048',
        'photo_right_side' => 'nullable|image|max:2048',
        'is_group' => 'required|boolean', // Add validation for is_group
        'is_vaccinated' => 'required|in:0,1,2', // Add validation for is_vaccinated

    ];

    // Apply conditional validation based on 'is_group'
    if ($request->is_group == 0) {
        // If it's not a group, name and gender are required
        $validationRules['name'] = 'required|string|max:255';
        $validationRules['gender'] = 'required|in:Male,Female';
        $validationRules['group_count'] = 'nullable'; // Don't require group_count for non-groups
    } else {
        // If it's a group, name and gender are not required
        $validationRules['name'] = 'nullable|string|max:255'; // Allow name to be null
        $validationRules['gender'] = 'nullable|in:Male,Female'; // Allow gender to be null
        $validationRules['group_count'] = 'required|integer|min:1'; // Require group_count for groups
    }

    // Validate the form input
    $request->validate($validationRules);

    // Fetch the existing animal record
    $animal = Animal::where('animal_id', $animal_id)->firstOrFail();

    // Prepare the animal data
    $data = $request->only([
        'name', 'owner_id', 'species_id', 'breed_id', 'birth_date', 'gender', 'medical_condition', 'is_group', 'group_count', 'color', 'is_vaccinated',
    ]);

    // Handle photo uploads and update the existing photos
    foreach (['photo_front', 'photo_back', 'photo_left_side', 'photo_right_side'] as $photo) {
        if ($request->hasFile($photo)) {
            // Store new photo and update the animal record
            $data[$photo] = $request->file($photo)->store('animal_photos', 'public');
        } else {
            // Retain old photo if not replaced
            $data[$photo] = $animal->{$photo}; // Retain the old photo if the user doesn't upload a new one
        }
    }

    // If it's a group, handle the group-specific logic
    if ($data['is_group'] == 1) {
        // Set a default value for name (or empty string) when it's a group
        $data['name'] = $data['name'] ?? 'Group'; // Use 'Group' or leave it empty
        $data['gender'] = null; // Groups do not have a gender
    }

    try {
        // Update the animal record
        Animal::where('animal_id', $animal_id)->update($data);

        // Redirect with success message
        return redirect()->route('rec-animals')
            ->with('message', 'Animal updated successfully.');

    } catch (\Exception $e) {
        // Handle exceptions and return the error message
        return back()->withErrors(['error' => $e->getMessage()]);
    }
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
    return view('receptionist.animal-profile', compact(
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
public function edit_animal($animal_id)
{
    // Fetch the animal by its ID
    $animal = Animal::where('animal_id', $animal_id)->firstOrFail();


    // Fetch all species, breeds, and owners for dropdown selections
    $species = Species::all();
    $breeds = Breed::all();
    $owners = Owner::all();
    $vaccines = Vaccine::all();

    // Return the view with the data to populate the form
    return view('receptionist.animal-edit', compact('animal', 'species', 'breeds', 'owners', 'vaccines'));
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
        'is_vaccinated' => 'required|in:0,1,2', // Add validation for is_vaccinated

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
        'is_vaccinated' => $request->is_vaccinated,

    ];

    // Conditional logic for name and group-specific fields
    if ($request->is_group) {
        $attributes['name'] = $request->name; // Set default group name
        $attributes['gender'] = null; // Groups don't have genders
        $attributes['group_count'] = max($request->group_count, 1); // Ensure group count is at least 1
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
    return redirect()->route('rec.profile', ['animal_id' => $animal->animal_id])
        ->with('message', 'Animal updated successfully.');
}

public function recedit($id)
  {
      // Find the transaction or fail using transaction_id
      $transaction = Transaction::with('transactionSubtype')->where('transaction_id', $id)->firstOrFail();
  
      // Retrieve all transaction types
      $transactionTypes = TransactionType::all();
  
      // Retrieve all veterinarians (role 2 = Veterinarian)
      $vets = User::where('role', 2)->get();
  
      // Retrieve all veterinary technicians (model set up correctly)
      $technicians = VeterinaryTechnician::all();
  
      // Retrieve all vaccines
      $vaccines = Vaccine::all();
  
      // Retrieve all transaction subtypes
      $transactionSubtypes = TransactionSubtype::all();
  
      // Group subtypes by transaction type
      $subtypesByType = $transactionSubtypes->groupBy('transaction_type_id');
      
      // Return the edit view with the transaction and other necessary data
      return view('receptionist.transactions-edit', compact(
          'transaction',
          'transactionTypes',
          'vets',
          'technicians',
          'vaccines',
          'transactionSubtypes',
          'subtypesByType'
      ));
  }
  

  
  public function recupdate(Request $request, $transaction_id)
  {
      // Validate the input data
      $request->validate([
          'transaction_type_id' => 'required|exists:transaction_types,id',
          'transaction_subtype_id' => 'required|exists:transaction_subtypes,id',
          'vet_id' => 'required|exists:users,user_id,role,2',
          'technician_id' => 'nullable|exists:veterinary_technicians,technician_id', // Technician ID from veterinary_technicians
          'vaccine_id' => 'nullable|exists:vaccines,id', // Vaccine ID from vaccines table
          'status' => 'required|in:pending,completed,cancelled',
          'details' => 'nullable|string',
      ]);
  
      // Map status string to integer if needed
      $statusMap = [
          'pending' => 0,
          'completed' => 1,
          'cancelled' => 2,
      ];
  
      $statusValue = $statusMap[$request->status] ?? 0; // Default to 0 (pending) if not found
  
      // Update the transaction using the update method (Directly updating using the primary key)
      Transaction::where('transaction_id', $transaction_id)->update([
          'transaction_type_id' => $request->transaction_type_id,
          'transaction_subtype_id' => $request->transaction_subtype_id,
          'vet_id' => $request->vet_id,
          'technician_id' => $request->technician_id, // Associate technician
          'vaccine_id' => $request->vaccine_id, // Associate vaccine
          'status' => $statusValue,
          'details' => $request->details,
      ]);
  
      // Retrieve the animal associated with this transaction
      $transaction = Transaction::where('transaction_id', $transaction_id)->first();
      $animal = $transaction->animal; // Assuming the relation is set up correctly
  
      // Redirect to the transaction index or show page with a success message
      return redirect()->route('rec.profile', ['animal_id' => $animal->animal_id])
                       ->with('success', 'Transaction updated successfully');
  }

  public function recstore(Request $request, $animal_id)
  {
      // Validate incoming request
      $request->validate([
          'transaction_type_id' => 'required|exists:transaction_types,id',
          'transaction_subtype_id' => 'required|exists:transaction_subtypes,id',
          'vet_id' => 'required|exists:users,user_id', // Vet must be a valid user
          'technician_id' => 'nullable|exists:veterinary_technicians,technician_id', // Technician ID now from VeterinaryTechnician model
          'vaccine_id' => 'nullable|exists:vaccines,id', // Vaccine optional
          'notes' => 'nullable|string',
      ]);
  
      // Ensure the animal exists by animal_id
      $animal = Animal::where('animal_id', $animal_id)->first();
      if (!$animal) {
          return redirect()->route('animals.index')->with('error', 'Animal not found.');
      }
  
      // Create a new transaction
      $transaction = new Transaction();
      $transaction->animal_id = $animal_id;  // Use animal_id to associate with the transaction
      $transaction->transaction_type_id = $request->transaction_type_id;
      $transaction->transaction_subtype_id = $request->transaction_subtype_id;
      $transaction->owner_id = $animal->owner_id; // Assuming you get owner_id from the animal
      $transaction->vet_id = $request->vet_id;
      $transaction->technician_id = $request->technician_id;  // Associate technician from VeterinaryTechnician model
      $transaction->vaccine_id = $request->vaccine_id;  // Associate vaccine if provided
      $transaction->details = $request->notes;
      $transaction->status = 0; // Default status (Pending)
      $transaction->save();
  
      // Redirect or return response
      return redirect()->route('rec.profile', ['animal_id' => $animal_id])
          ->with('success', 'Transaction added successfully!');
  }

  public function loadTechnicians()
  {
      $technicians = VeterinaryTechnician::all();
      return view('receptionist.technician-index', compact('technicians'));
  }

  public function createTech()
  {
      return view('receptionist.technician-create'); // Replace with your actual Blade file
  }

     
public function storeTech(Request $request)
{
    $request->validate([
        'full_name' => 'required|string|max:255',
        'contact_number' => 'required|string|max:15',
        'email' => 'required|email|unique:veterinary_technicians,email',
    ]);

    VeterinaryTechnician::create($request->all());

    return redirect()->route('rec-technicians')->with('success', 'Technician added successfully!');
}


public function settings()
{
    return view('receptionist.settings');
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

    public function showVeterinarianProfile(Request $request, $user_id)
    {
        // Fetch query parameters for filters
        $search = $request->input('search');
        $status = $request->input('status');
        $transactionType = $request->input('transaction_type');
        $transactionSubtype = $request->input('transaction_subtype');
        $date = $request->input('date');
    
        // Fetch veterinarian profile
        $veterinarian = User::with([
            'vetTransactions.animal.species',
            'vetTransactions.animal.breed',
            'vetTransactions.transactionType',
            'animals.species',
            'animals.breed',
            'address.barangay',
            'designation',
        ])
        ->where('users.user_id', $user_id)
        ->where('users.role', 2)
        ->first();
    
        // Build the transactions query
        $transactions = Transaction::with([
            'animal.owner.user',
            'animal.species',
            'animal.breed',
            'transactionType',
            'transactionSubtype',
            'technician',
        ])->where('vet_id', $user_id);
    
        // Apply filters
        if ($search) {
            $transactions->whereHas('animal.owner.user', function ($query) use ($search) {
                $query->where('complete_name', 'like', "%$search%");
            })->orWhereHas('animal', function ($query) use ($search) {
                $query->where('name', 'like', "%$search%");
            });
        }
    
        if ($status !== null) {
            $transactions->where('status', $status);
        }
    
        if ($transactionType) {
            $transactions->where('transaction_type_id', $transactionType);
        }
    
        if ($transactionSubtype) {
            $transactions->where('transaction_subtype_id', $transactionSubtype);
        }
    
        if ($date) {
            $transactions->whereDate('created_at', $date);
        }
    
        // Transaction Count
        $transactionCount = $transactions->count();
        // Paginate the transactions
        $transactions = $transactions->orderBy('created_at', 'desc')->paginate(10);
    
        // Fetch other needed data
        $technicians = VeterinaryTechnician::all();
        $transactionTypes = TransactionType::all();
        $transactionSubtypes = TransactionSubtype::all();
        $vet = User::findOrFail($user_id);
        $animals = Animal::whereIn('animal_id', $transactions->pluck('animal_id'))->get();
    
        // Return the view with the filtered transactions
        return view('receptionist.veterinarian-profile', compact(
            'veterinarian', 'transactions', 'technicians', 'transactionTypes', 
            'transactionSubtypes', 'transactionCount', 'animals', 'vet'
        ));
    }
}
