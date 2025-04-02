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

class VetController extends Controller
{


    public function loadVetDashboard()
    {
        // Fetch the logged-in veterinarian
        $user = Auth::user();
        
        // Fetch dynamic data
        $assignedTransactions = Transaction::where('vet_id', $user->user_id)->count(); // Count transactions assigned to the vet
        $successfulTransactions = Transaction::where('vet_id', $user->user_id)
            ->where('status', 1) // Status of 1 for successful transactions
            ->count(); // Count successful transactions
        
        $recentTransactions = Transaction::where('vet_id', $user->user_id)
            ->orderBy('created_at', 'desc') // Sort by most recent
            ->limit(5) // Get only the most recent 5
            ->get();
    
        // Prepare report data (example: number of transactions in the last month)
        $lastMonthTransactions = Transaction::where('vet_id', $user->user_id)
            ->whereBetween('created_at', [now()->subMonth(), now()])
            ->count();
    
        // Fetch other relevant data (e.g., pending transactions, or specific date range)
        $pendingTransactions = Transaction::where('vet_id', $user->user_id)
            ->where('status', 0) // Status of 0 for pending transactions
            ->count();
    
        return view('vet.dashboard', [
            'assignedTransactions' => $assignedTransactions,
            'successfulTransactions' => $successfulTransactions,
            'recentTransactions' => $recentTransactions,
            'lastMonthTransactions' => $lastMonthTransactions,
            'pendingTransactions' => $pendingTransactions,
        ]);
    }
    
    
    public function showVeterinarianProfile(Request $request, $user_id)
    {
        // Fetch query parameters for filters
        $search = $request->input('search');
        $status = $request->input('status');
        $transactionType = $request->input('transaction_type');
        $transactionSubtype = $request->input('transaction_subtype');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
    
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
    
        if ($startDate) {
            $transactions->whereDate('created_at', '>=', $startDate);
        }
    
        // Apply the end date filter if it exists
        if ($endDate) {
            $transactions->whereDate('created_at', '<=', $endDate);
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
        return view('vet.veterinarian-profile', compact(
            'veterinarian', 'transactions', 'technicians', 'transactionTypes', 
            'transactionSubtypes', 'transactionCount', 'animals', 'vet'
        ));
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

    return view('vet.vet-edit', compact('veterinarian', 'designations', 'barangays'));
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

    return redirect()-> route('vet.veterinarian.profile', $veterinarian->user_id)->with('success', 'Veterinarian updated successfully');
}
   

public function settings()
{
    return view('vet.settings');
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

   
public function updateStatus(Request $request, $transaction_id)
{
    // Find the transaction by its custom primary key 'transaction_id'
    $transaction = Transaction::where('transaction_id', $transaction_id)->first();
    
    // If the transaction exists, proceed
    if ($transaction) {
        // Update the status of the transaction
        Transaction::where('transaction_id', $transaction_id)->update(['status' => $request->status]);

        // Check if the transaction_subtype_id is 8 (vaccination) and the status is 1 (completed)
        if ($transaction->transaction_subtype_id == 8 && $request->status == 1) {
            // Find the related animal using the animal_id from the transaction
            $animal = Animal::where('animal_id', $transaction->animal_id)->first();

            // Update the is_vaccinated field in the animals table
            if ($animal) {
                Animal::where('animal_id', $transaction->animal_id)->update(['is_vaccinated' => 1]);
            }
        }
    }

    // Redirect back with a success message
    return back()->with('message', 'Transaction status updated successfully!');
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
    return view('vet.owner-profile', compact('owner', 'animals', 'species', 'breeds', 'message'));
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
    return view('vet.animal-profile', compact(
        'animal', 
        'transactionTypes', 
        'transactionSubtypes', 
        'vets', 
        'transactions', 
        'vaccines', // Pass vaccines to the view
        'technicians' // Pass technicians to the view
    ));
}

}
