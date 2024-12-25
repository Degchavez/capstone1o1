<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail; 
use Illuminate\Http\Request; // Add this import statement
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules\Password;
use App\Models\Barangay;
use Illuminate\Support\Facades\Storage;
use App\Models\Designation;

use App\Models\Owner;
use App\Models\Address;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;



class UserController extends Controller
{   
    public function settings()
    {
        return view('admin.settings');
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

    public function profile($id)
    {
        $user = User::with('address')->findOrFail($id); // Fetch the user with their address
        $barangays = Barangay::all(); // Fetch all barangays
        return view('admin.profile', compact('user', 'barangays')); // Pass data to the view
    }

    public function navProfile($id)
    {
        $user = User::with('address')->findOrFail($id); // Fetch the user with their address
        $barangays = Barangay::all(); // Fetch all barangays
        return view('admin.user-profile', compact('user', 'barangays')); // Pass data to the view
    }
    public function edit($id)
    {
        $user = User::with('address', 'designation')->findOrFail($id); // Fetch the user with their address and designation
        $barangays = Barangay::all(); // Fetch all barangays
        $designations = Designation::all(); // Fetch all designations
        return view('admin.edit', compact('user', 'barangays', 'designations')); // Pass data to the view
    }
    

    public function update(Request $request, $id)
    {
        $request->validate([
            'complete_name' => 'required|string|max:100',
            'role' => 'required|integer',
            'contact_no' => 'required|string|max:15',
            'gender' => 'required|string|max:10',
            'birth_date' => ['nullable', 'date', 'before_or_equal:today'], // Ensure birthdate is not in the future
            'status' => 'required|integer',
            'email' => 'required|email|max:100|unique:users,email,' . $id . ',user_id',
            'barangay_id' => 'required|exists:barangays,id',
            'street' => 'nullable|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Added validation for image
            'designation_id' => 'nullable|exists:designations,designation_id', // Ensure designation is selected
        ]);
    
        $user = User::findOrFail($id);
    
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
    
        // Update user data
        $user->update($request->only([
            'complete_name',
            'role',
            'contact_no',
            'gender',
            'birth_date',
            'status',
            'email',
            'designation_id', // Update the designation_id
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
    
        return redirect()->route('users.edit-form', ['id' => $user->user_id])
            ->with('message', 'User details updated successfully.');
    }
    

    //Controller for  profile update

    public function profile_edit($id)
    {
        $user = User::with('address')->findOrFail($id); // Fetch the user with their address
        $barangays = Barangay::all(); // Fetch all barangays
        return view('admin.profile-edit', compact('user', 'barangays')); // Pass data to the view
    }

    public function profile_update(Request $request, $id)
    {
        $request->validate([
            'complete_name' => 'required|string|max:100',
            'contact_no' => 'required|string|max:15',
            'gender' => 'required|string|max:10',
            'birth_date' => 'required|date',
            'email' => 'required|email|max:100|unique:users,email,' . $id . ',user_id',
            'barangay_id' => 'required|exists:barangays,id',
            'street' => 'nullable|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Added validation for image
          
    ]);

        $user = User::findOrFail($id);
    
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
    
        // Update user data
        $user->update($request->only([
            'complete_name',
            'contact_no',
            'gender',
            'birth_date',
            'email',
        ]));
    
        // Update the password if provided

    
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
    
        return redirect()->route('users.profile-form', ['id' => $user->user_id])
            ->with('message', 'Profile updated successfully.');
    }
    
    public function resetPassword(User $user)
    {
        try {
            // Generate a random password
            $randomPassword = \Illuminate\Support\Str::random(8);
    
            // Hash the password and update the user's record
            $user->update([
                'password' => bcrypt($randomPassword),
            ]);
    
            // Send the email with the new password
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\ResetPasswordEmail($user, $randomPassword));
    
            // Redirect back with a success message
            return redirect()->back()->with('message', "Password for user {$user->complete_name} has been reset and emailed successfully.");
        } catch (\Exception $e) {
            // Redirect back with an error message in case of failure
            return redirect()->back()->with('error', 'An error occurred while resetting the password.');
        }
    }
    


    public function showRegistrationForm()
    {
        $barangays = Barangay::all(); // Get all barangays for selection
        return view('admin.add-owners', compact('barangays'));
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
        return redirect()->route('admin-owners')->with('success', 'User and owner registered successfully, and password has been emailed!');
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
        return view('admin.ownerlist-edit', compact('user', 'barangays', 'owner'));
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
        return redirect()->route('admin-owners')->with('message', 'Profile updated successfully.');
    }
    
    public function create_vet()
    {
        $designations = Designation::all(); // Get all designations
        $barangays = Barangay::all(); // Get all barangays
        return view('admin.veterinarians-create', compact('designations', 'barangays'));
    }
    
    public function store_vet(Request $request)
{
    $request->validate([
        'complete_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'contact_no' => 'required|string|max:20',
        'gender' => 'required|string',
        'birth_date' => ['nullable', 'date', 'before_or_equal:today'], // Ensure birthdate is not in the future
        'designation_id' => 'required|exists:designations,designation_id',
        'barangay_id' => 'required|exists:barangays,id', // Barangay validation
        'street' => 'required|string|max:255', // Street is required
        'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Profile image validation
    ]);

    // Generate a random password
    $randomPassword = \Illuminate\Support\Str::random(8);

    // Hash the password
    $hashedPassword = bcrypt($randomPassword);

    // Create the veterinarian (user)
    $user = \App\Models\User::create([
        'complete_name' => $request->complete_name,
        'email' => $request->email,
        'contact_no' => $request->contact_no,
        'gender' => $request->gender,
        'birth_date' => $request->birth_date,
        'password' => $hashedPassword, // Store the hashed password
        'role' => 2, // Veterinarian role
        'designation_id' => $request->designation_id,
        'status' => 1, // Set status to 1 (active)
    ]);

    // Save address and associate it with the user and barangay
    \App\Models\Address::create([
        'user_id' => $user->user_id,
        'barangay_id' => $request->barangay_id,
        'street' => $request->street, // Save the street address
    ]);

    // Handle the profile image upload
    if ($request->hasFile('profile_image')) {
        $profileImagePath = $request->file('profile_image')->store('profile_images', 'public');
        $user->profile_image = $profileImagePath;
        $user->save();
    }

    // Send the email with the generated password
    \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\WelcomeEmail($user, $randomPassword));

    return redirect()->route('admin-veterinarians')->with('success', 'Veterinarian registered successfully! Password has been sent to their email.');
}

    public function deleteProfileImage_vet($id)
    {
        $veterinarian = \App\Models\User::findOrFail($id);

        // Delete the image file from storage
        if ($veterinarian->profile_image && \Storage::exists('public/' . $veterinarian->profile_image)) {
            \Storage::delete('public/' . $veterinarian->profile_image);
        }

        // Update the database to null the profile image
        $veterinarian->profile_image = null;
        $veterinarian->save();

        return redirect()->route('veterinarians.index')->with('success', 'Profile image deleted successfully!');
    }
    //edit vet list
    public function edit_veterinarian($id)
{
    $veterinarian = User::findOrFail($id); // Find the veterinarian by ID

    // Fetch necessary data for the form, such as designations
    $designations = Designation::all();

    return view('admin.veterinarians-edit', compact('veterinarian', 'designations'));
}

public function update_veterinarian(Request $request, $id)
{
    $request->validate([
        'complete_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $id . ',user_id',
        'contact_no' => 'required|string|max:20',
        'gender' => 'required|string',
        'birth_date' => ['nullable', 'date', 'before_or_equal:today'], // Ensure birthdate is not in the future
        'designation_id' => 'required|exists:designations,designation_id',
        'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Find the veterinarian by user_id
    $veterinarian = User::where('user_id', $id)->firstOrFail();

    // Update veterinarian details
    $veterinarian->complete_name = $request->complete_name;
    $veterinarian->email = $request->email;
    $veterinarian->contact_no = $request->contact_no;
    $veterinarian->gender = $request->gender;
    $veterinarian->birth_date = $request->birth_date;
    $veterinarian->designation_id = $request->designation_id;

    // Handle the profile image upload
    if ($request->hasFile('profile_image')) {
        $profileImagePath = $request->file('profile_image')->store('profile_images', 'public');
        $veterinarian->profile_image = $profileImagePath;
    }

    // Save the updated veterinarian information
    $veterinarian->save();

    // Redirect with success message
    return redirect()->route('admin-veterinarians')->with('success', 'Veterinarian updated successfully!');
}


public function destroy_veterinarian($user_id)
{
    // Find the veterinarian by their user_id
    $veterinarian = User::findOrFail($user_id);

    // If there is additional logic needed, such as deleting associated records, do it here

    // Delete the veterinarian
    $veterinarian->delete();

    // Redirect or return a response
    return redirect()->route('admin-veterinarians')->with('success', 'Veterinarian deleted successfully.');
}

   }

    
    
    
    
        




