<?php

namespace App\Http\Controllers;

use App\Models\VeterinaryTechnician; // Import the Species model
use App\Models\Breed; // Import the Species model
use App\Models\Vaccine; // Import the Species model
use App\Models\Technician; // Import the Species model
use App\Models\Designation;
use Illuminate\Support\Facades\Storage;

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
use App\Models\Category; // Add this import

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function loadAdminDashboard(Request $request)
{
    // Retrieve filter and search inputs
    $search = $request->input('search');
    $statusFilter = $request->input('status');
    $veterinarianFilter = $request->input('veterinarian');
    $technicianFilter = $request->input('technician');

    // Query for transactions with filters
    $transactionsQuery = Transaction::with(['transactionSubtype', 'owner.user', 'animal', 'vet', 'technician']);

    if ($search) {
        $transactionsQuery->whereHas('owner.user', function ($query) use ($search) {
            $query->where('complete_name', 'like', '%' . $search . '%');
        })->orWhereHas('animal', function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        });
    }

    if ($statusFilter) {
        $transactionsQuery->where('status', $statusFilter);
    }

    if ($veterinarianFilter) {
        $transactionsQuery->where('vet_id', $veterinarianFilter);
    }

    if ($technicianFilter) {
        $transactionsQuery->where('technician_id', $technicianFilter);
    }

    $recentTransactions = $transactionsQuery->latest()->paginate(5);

    // Fetch data for filters
    $veterinarians = User::where('role', 2)->get(); // Veterinarians have role = 2
    $technicians = VeterinaryTechnician::all(); // Fetch all technicians
    $statuses = [
        0 => 'Pending',
        1 => 'Completed',
        2 => 'Canceled',
    ];

    // Dashboard statistics
    $totalOwners = User::where('role', 1)->count();
    $successfulTransactions = Transaction::where('status', 1)->count();
    $totalAnimals = Animal::count();
    $lastWeekTransactions = Transaction::whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])
        ->count();

    return view('admin.dashboard', compact(
        'veterinarians',
        'technicians',
        'recentTransactions',
        'statuses',
        'totalOwners',
        'successfulTransactions',
        'totalAnimals',
        'lastWeekTransactions'
    ));
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
    return view('admin.animals-table', compact('animals', 'owners', 'species', 'breeds'));
}

   
   public function loadTransactionsList()
   {
        return view('admin.transactions-table');
   } 


   public function loadVeterinariansList(Request $request)
   {
       $search = $request->input('search', '');  // Search input
       $gender = $request->input('gender', '');  // Filter by gender
       $category = $request->input('category', '');  // Filter by category
       $civil_status = $request->input('civil_status', '');  // Filter by civil status
       $barangay_id = $request->input('barangay', '');  // Filter by barangay
   
       // Fetch barangays and designations for the dropdown
       $barangays = Barangay::select('id', 'barangay_name')->orderBy('barangay_name')->get();
       $designations = Designation::select('designation_id', 'name')->orderBy('name')->get();
       $designation_id = request('designation'); // Assign the designation id from the request

       // Fetch veterinarians (users with role = 2) with necessary relationships and filters
       $veterinarians = User::with([
               'animals.species', // Load species for animals
               'animals.breed',   // Load breed for animals
               'transactions',    // Load transactions
               'address.barangay', // Load address with barangay
               'designation' // Load designation for veterinarian
           ])
           ->leftJoin('addresses', 'users.user_id', '=', 'addresses.user_id')
           ->leftJoin('barangays', 'addresses.barangay_id', '=', 'barangays.id')
           ->leftJoin('transactions', 'users.user_id', '=', 'transactions.vet_id')
           ->leftJoin('transaction_types', 'transactions.transaction_type_id', '=', 'transaction_types.id')
           ->leftJoin('designations', 'users.designation_id', '=', 'designations.designation_id')  // Join designations table
        
           // Filter: Only veterinarians (role = 2)
           ->where('users.role', 2)
   
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
   
           // Apply category filter (specific to veterinarians)
           ->when($category, function ($query) use ($category) {
               return $query->where('users.category', $category);
           })
   
           // Apply civil status filter
           ->when($civil_status, function ($query) use ($civil_status) {
               return $query->where('users.civil_status', $civil_status);
           })
   
           // Apply barangay filter
           ->when($barangay_id, function ($query) use ($barangay_id) {
               return $query->where('barangays.id', $barangay_id);
           })
   
           // Apply date range filter for transactions
           ->when(request('fromDate'), function ($query) {
               return $query->whereDate('transactions.created_at', '>=', request('fromDate'));
           })
           ->when(request('toDate'), function ($query) {
               return $query->whereDate('transactions.created_at', '<=', request('toDate'));
           })
            // Apply designation filter
        ->when($designation_id, function ($query) use ($designation_id) {
            return $query->where('users.designation_id', $designation_id);
        })
   
           // Select necessary fields for veterinarians
           ->select(
               'users.user_id',
               'users.complete_name',
               'users.profile_image',
               'users.contact_no',
               'users.gender',
               'users.birth_date',
               'users.created_at',
               'addresses.street',
               'barangays.barangay_name',
               'designations.name as designation_name', // Include the designation name
               DB::raw('COUNT(transactions.transaction_id) as transaction_count'), // Count transactions for the veterinarian
               DB::raw('MAX(transactions.created_at) as transaction_created_at')
           )
   
           // Group by veterinarian's attributes
           ->groupBy(
               'users.user_id',
               'users.complete_name',
               'users.profile_image',
               'users.contact_no',
               'users.gender',
               'users.birth_date',
               'users.created_at', // Add this line

               'addresses.street',
               'barangays.barangay_name',
               'designations.name'
           )
   
           // Order by creation date
           ->orderBy('users.created_at', 'desc')
   
           // Pagination: 15 items per page
           ->paginate(15);
   
       // Return the view with veterinarians' data, filters, and designations
       return view('admin.vets-table', compact('veterinarians', 'search', 'gender', 'category', 'civil_status', 'barangays', 'barangay_id', 'designations'));
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
       return view('admin.veterinarian-profile', compact(
           'veterinarian', 'transactions', 'technicians', 'transactionTypes', 
           'transactionSubtypes', 'transactionCount', 'animals', 'vet'
       ));
   }
   
   
   public function resetAnimalSpeciesAndBreeds()
   {
       // Set breed_id and species_id to null for all animals
       Animal::query()->update([
           'breed_id' => null,
           'species_id' => null,
       ]);
   
       return redirect()->back()->with('success', 'All animals\' species and breeds have been reset.');
   }

   public function loadUsersList(Request $request)
   {
       // Fetch filter inputs from the request
       $search = $request->input('search', '');
       $perPage = $request->input('perPage', 25); // Default to 25 items per page
       $gender = $request->input('gender', '');
       $role = $request->input('role', '');
       $status = $request->input('status', '');
       $barangay_id = $request->input('barangay_id', ''); // Barangay filter
       $fromDate = $request->input('fromDate', '');
       $toDate = $request->input('toDate', '');
       
       // Fetch Barangays for the filter dropdown
       $barangays = Barangay::all();
       
       // Fetch Owners for the filter dropdown (users with role = 1)
       $owners = User::where('role', 1)->get(); 
   
       // Build query with eager loading for related address and barangay
       $query = User::with(['address.barangay']) // Fix eager loading to load address.barangay
           ->when($role, function ($q) use ($role) {
               $q->where('role', $role);  // Filter by role
           })
           ->when($gender, function ($q) use ($gender) {
               $q->where('gender', $gender);  // Filter by gender
           })
           ->when($status, function ($q) use ($status) {
               $q->where('status', $status);  // Filter by status
           })
           ->when($barangay_id, function ($q) use ($barangay_id) {
               $q->whereHas('address', function ($q) use ($barangay_id) {
                   // Filter by barangay_id in the address
                   $q->where('barangay_id', $barangay_id);
               });
           })
           ->when($search, function ($q) use ($search) {
               $q->where(function ($q) use ($search) {
                   $q->where('complete_name', 'LIKE', "%{$search}%")
                     ->orWhere('email', 'LIKE', "%{$search}%")
                     ->orWhere('contact_no', 'LIKE', "%{$search}%");
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
       $users = $query->paginate($perPage);
       
       // Return the view with users, owners, and barangays data
       return view('admin.users-list', compact('users', 'barangays', 'owners'));
   }
   

   public function destroy(User $user)
   {
       try {
           // Check if the user is an owner
           if ($user->role === 1 && $user->owner) {
               // Delete related transactions
               $user->owner->transactions()->delete();
               
               // Delete related animals
               $user->owner->animals()->delete();
   
               // Delete the owner details
               $user->owner->delete();
           }
   
           // Delete the user
           $user->delete();
   
           return redirect()->back()->with('message', 'User, their owner details, transactions, and animals deleted successfully!');
       } catch (\Exception $e) {
           return redirect()->back()->with('error', 'An error occurred while deleting the user: ' . $e->getMessage());
       }
   }
   
   

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
       return view('admin.animal-owners', compact('owners', 'search', 'gender', 'category', 'civil_status', 'barangays', 'barangay_id'));
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
       return view('admin.add-animal', [
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
   
           return redirect()->route('owners.profile-owner', ['owner_id' => $request->owner_id])
                            ->with('success', 'Animal added successfully.');
       } catch (\Exception $e) {
           // Handle any errors during the insertion
           return back()->withErrors(['error' => $e->getMessage()]);
       }
   }
   
   


public function getBreeds($species_id)
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

    public function getTransactionSubtypes($transactionTypeId)
{
    // Ensure the TransactionType exists, then get the associated subtypes
    $transactionType = TransactionType::with('subtypes')->find($transactionTypeId);

    if ($transactionType) {
        return response()->json([
            'subtypes' => $transactionType->subtypes
        ]);
    }

    return response()->json([
        'subtypes' => []
    ]);
}


   public function loadAddUsers() 
   {
        return view('admin.add-users');
   }

   public function loadAddOwners()
   {
       $role = auth()->user()->role; // Assuming roles are stored in the 'users' table
       $barangays = Barangay::all(); // Load barangay options for the dropdown
   
       return view('admin.add-owners', [
           'role' => $role,
           'barangays' => $barangays,
       ]);
   }
   
   public function owner_edit($id)
   {
       $user = User::with(['address', 'owner'])->findOrFail($id);
       $barangays = Barangay::all();
       $categories = Category::all();
       return view('admin.owner-edit', compact('user', 'barangays', 'categories'));
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
           'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
           'category_id' => 'required|exists:categories,id',
       ]);

       $user = User::findOrFail($id);
       $transaction = Owner::where('user_id', $user->user_id)->first();

       // Handle profile image upload
       if ($request->hasFile('profile_image')) {
           if ($user->profile_image) {
               Storage::disk('public')->delete($user->profile_image);
           }
           $imagePath = $request->file('profile_image')->store('profile_images', 'public');
           $user->profile_image = $imagePath;
       }

       $user->role = 1;
       $user->update($request->only([
           'complete_name',
           'role',
           'contact_no',
           'gender',
           'birth_date',
           'status',
           'email',
       ]));

       // Update address
       $user->address()->updateOrCreate(
           ['user_id' => $user->user_id],
           $request->only(['barangay_id', 'street'])
       );

       // Update owner with category_id
       $user->owner()->updateOrCreate(
           ['user_id' => $user->user_id],
           [
               'civil_status' => $request->civil_status,
               'category_id' => $request->category_id,
               'permit' => 1
           ]
       );

       return redirect()->route('owners.profile-owner', ['owner_id' => $transaction->owner_id])
           ->with('message', 'Profile updated successfully.');
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
       return view('admin.animal-profile', compact(
           'animal', 
           'transactionTypes', 
           'transactionSubtypes', 
           'vets', 
           'transactions', 
           'vaccines', // Pass vaccines to the view
           'technicians' // Pass technicians to the view
       ));
   }
   
   
public function getSubtypes($transactionTypeId)
{
    // Get subtypes where the transaction_type_id matches the provided ID
    $subtypes = TransactionSubtype::where('transaction_type_id', $transactionTypeId)->get();
    
    // Check if any subtypes were found
    if ($subtypes->isEmpty()) {
        return response()->json(['subtypes' => []]);
    }

    // Return the relevant fields (id and subtype_name)
    return response()->json([
        'subtypes' => $subtypes->map(function($subtype) {
            return [
                'id' => $subtype->id,
                'subtype_name' => $subtype->subtype_name
            ];
        })
    ]);
}
public function vet_edit($user_id)
{
    // Fetch veterinarian (user with role = 2)
    $veterinarian = User::with([
            'animals.species',  // Load species for animals
            'animals.breed',    // Load breed for animals
            'transactions',     // Load transactions
            'address.barangay', // Load address with barangay
            'designation'       // Load designation for veterinarian
        ])
        ->where('role', 2)
        ->where('user_id', $user_id)
        ->firstOrFail();

    // Fetch all designations for the dropdown
    $designations = Designation::all();

    // Fetch all barangays for the dropdown
    $barangays = Barangay::orderBy('barangay_name')->get();

    return view('admin.vet-edit', compact('veterinarian', 'designations', 'barangays'));
}

public function vet_update(Request $request, $user_id)
{
    // Validate the input
    $request->validate([
        'complete_name' => 'required|string|max:255',
        'contact_no' => 'required|string|max:20',
        'gender' => 'required|in:Male,Female',
        'birth_date' => ['nullable', 'date', 'before_or_equal:today'], // Ensure birthdate is not in the future
        'designation_id' => 'nullable|exists:designations,designation_id',
        'barangay_id' => 'required|exists:barangays,id',
        'street' => 'nullable|string|max:255',
        'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Find the veterinarian to be updated
    $veterinarian = User::findOrFail($user_id);

    // Update the veterinarian's personal info
    $veterinarian->complete_name = $request->complete_name;
    $veterinarian->contact_no = $request->contact_no;
    $veterinarian->gender = $request->gender;
    $veterinarian->birth_date = $request->birth_date;
    $veterinarian->designation_id = $request->designation_id;

    // Update the address information
    $veterinarian->address->barangay_id = $request->barangay_id;
    $veterinarian->address->street = $request->street;
    $veterinarian->address->save();

    // Update the profile image if a new one is uploaded
    if ($request->hasFile('profile_image')) {
        // Delete old image if it exists
        if ($veterinarian->profile_image) {
            Storage::disk('public')->delete($veterinarian->profile_image);
        }
    
        // Store the new image
        $imagePath = $request->file('profile_image')->store('profile_images', 'public');
        $veterinarian->profile_image = $imagePath;
    }

    // Save the updated veterinarian details
    $veterinarian->save();

    return redirect()-> route('admin.veterinarian.profile', $veterinarian->user_id)->with('success', 'Veterinarian updated successfully');
}

public function showOwnerProfile(Request $request, $owner_id)
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
    return view('owner.owner-profile', compact('owner', 'animals', 'species', 'breeds', 'message'));
}
    
public function updateTechnician(Request $request, $transaction_id)
{
    // Find the transaction by its custom primary key 'transaction_id'
    $transaction = Transaction::where('transaction_id', $transaction_id)->first();

    // If the transaction exists, update the technician_id
    if ($transaction) {
        $transaction->technician_id = $request->technician_id; // Update the technician_id with the selected value
        // Update the transaction by explicitly targeting 'transaction_id' as the key
        Transaction::where('transaction_id', $transaction_id)->update(['technician_id' => $request->technician_id]); // Using the update method to target the 'transaction_id' column
    }

    // Redirect back with a success message
    return back()->with('success', 'Technician updated successfully.');
}

public function updateDetails(Request $request, $transaction_id)
{
    // Validate the input
    $request->validate([
        'details' => 'required|string|max:1000', // Adjust max length as needed
    ]);

    // Find the transaction
    $transaction = Transaction::where('transaction_id', $transaction_id)->first();

    // Update the details field
    if ($transaction) {
     $transaction->details = $request->details; // Update the technician_id with the selected value
     // Update the transaction by explicitly targeting 'transaction_id' as the key
     Transaction::where('transaction_id', $transaction_id)->update(['details' => $request->details]); // Using the update method to target the 'transaction_id' column
 }

    // Redirect with success message
    return redirect()->back()->with('success', 'Transaction details updated successfully.');
}


}
