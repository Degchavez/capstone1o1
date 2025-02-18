<?php

use App\Models\User;
use App\Models\Owner;
use App\Models\Category;

use App\Models\Barangay;
use App\Models\Designation;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail; 
use App\Models\Address;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $complete_name = '';
    public int $role = 1; // Default role: owner
    public string $contact_no = '';
    public string $gender = '';
    public ?string $birth_date = null; // Nullable for optional field
    public int $status = 1; // Active status by default
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public ?int $designation_id = null; // Nullable, optional designation field

    // Owner-specific fields
    public ?string $civil_status = null;
    public ?string $category = null;
    public ?int $permit = 1; // Active status by default

    // Address-related fields
    public int $barangay_id = 0;
    public string $street = '';

    public $barangays = []; // Barangays list
    public $designations = []; // Designations list
    public $categories = []; // Barangays list

    public array $selectedCategories = [];


    /**
     * Mount function to initialize barangays and designations.
     */
    public function mount()
    {
        // Load all barangays
        $this->barangays = Barangay::all();
        $this->designations = Designation::all();
        $this->categories = Category::all();

    }

/**
 * Handle an incoming registration request.
 */
 public function isConnectedToInternet()
    {
        try {
            // Check by sending a simple HTTP request to a known service (Google in this case)
            $response = Http::get('https://www.google.com');
            return $response->successful(); // Returns true if successful
        } catch (\Exception $e) {
            // If an error occurs, it means there is no internet connection
            return false;
        }
    }
 public function register()
{
    // Check for internet connectivity
    if (!$this->isConnectedToInternet()) {
        // Flash error message
        session()->flash('error', 'No internet connection. Please connect to the internet and try again.');

        // Redirect to admin-users page without modifying the database and stop further execution
        return redirect()->route('admin-users');
    }

    // Validate inputs
    $validated = $this->validate([
        'complete_name' => ['required', 'string', 'max:255'],
        'role' => ['required', 'integer', 'in:1,2,3'], // Validate roles
        'contact_no' => ['nullable', 'string', 'max:15'],
        'gender' => ['required', 'string'],
        'birth_date' => ['nullable', 'date', 'before_or_equal:today'], // Ensure birthdate is not in the future
        'status' => ['required', 'integer'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
        'designation_id' => ['nullable', 'exists:designations,designation_id'],
        'civil_status' => ['nullable', 'required_if:role,1', 'string', 'max:255'],
        'selectedCategories' => ['nullable', 'required_if:role,1', 'array'],  // Make selectedCategories an array when role is 1
        'selectedCategories.*' => ['exists:categories,id'],  // Ensure all selected category IDs exist in the categories table
        'barangay_id' => ['required', 'exists:barangays,id'],
        'street' => ['nullable', 'string', 'max:255'],
    ]);

    // Generate a random password
    $randomPassword = Str::random(8);  // You can adjust the length as needed

    // Hash the password
    $hashedPassword = Hash::make($randomPassword);

    // Wrap user creation and email sending in a try-catch block
    try {
        // Create the user
        $user = User::create([
            'complete_name' => $validated['complete_name'],
            'role' => $validated['role'],
            'contact_no' => $validated['contact_no'],
            'gender' => $validated['gender'],
            'birth_date' => $validated['birth_date'],
            'status' => $validated['status'],
            'email' => $validated['email'],
            'password' => $hashedPassword, // Store hashed password
            'designation_id' => $validated['designation_id'],
        ]);

        // If the role is "Owner," create an Owner record
        if ($validated['role'] === 1) {
    // Create the Owner record
    $owner = Owner::create([
        'user_id' => $user->user_id,
        'civil_status' => $this->civil_status,
        'permit' => $this->permit, // Default permit value
    ]);

    // Attach selected categories to the user (assuming selectedCategories is an array of category IDs)
    if (!empty($this->selectedCategories)) {
        $user->categories()->attach($this->selectedCategories);
    }
}


        // Create the address
        Address::create([
            'user_id' => $user->user_id,
            'barangay_id' => $this->barangay_id,
            'street' => $this->street,
        ]);

        // Send the email with the random password
        Mail::to($validated['email'])->send(new WelcomeEmail($user, $randomPassword));

        // Fire the Registered event
        event(new Registered($user));

        // Redirect and flash a success message
        session()->flash('message', 'User added successfully!');
        return redirect()->route('admin-users');
    } catch (\Exception $e) {
        // Handle the error
        session()->flash('error', 'Cannot add user due to an issue. Please check your details and try again.');
        return;
    }
}
}
?>

<div class="max-w-4xl mx-auto p-8 bg-white rounded-lg shadow-lg">
    <!-- Error Message for No Internet -->
    @if (session()->has('error'))
        <div class="text-center mb-6">
            <p class="text-lg text-red-500">{{ session('error') }}</p>
        </div>
    @endif
    <!-- Title -->
    <div class="text-center mb-6">
        <h2 class="text-3xl font-semibold text-gray-800">User Registration Form</h2>
        <p class="text-lg text-gray-500">Please fill in the details below to create a new account.</p>
    </div>

    <!-- Logo -->
    <div class="text-center mb-8">
        <a href="/">
            <img class="h-20 w-auto mx-auto" src="{{ asset('assets/1.jpg') }}" alt="Your Logo">
        </a>
    </div>

    <form wire:submit.prevent="register" class="space-y-8">
        <!-- Complete Name -->
        <div>
            <x-input-label for="complete_name" :value="__('Full Name')" class="text-lg font-semibold text-gray-800"/>
            <x-text-input wire:model="complete_name" id="complete_name" class="block mt-1 w-full border border-gray-300 rounded-lg p-4 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" type="text" required autofocus />
            <x-input-error :messages="$errors->get('complete_name')" class="mt-2 text-sm text-red-500" />
        </div>

        <!-- Role -->
        <div>
            <x-input-label for="role" :value="__('Role')" class="text-lg font-semibold text-gray-800"/>
            <select wire:model="role" id="role" class="block mt-1 w-full border border-gray-300 rounded-lg p-4 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required wire:change="$refresh">
                <option value="#">Select Role</option>
                <option value="1">Animal Owner</option>
                <option value="2">Veterinarian</option>
                <option value="3">Veterinary Receptionist</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2 text-sm text-red-500" />
        </div>

        @if ($role == 2)
        <div>
            <x-input-label for="designation_id" :value="__('Designation')" class="text-lg font-semibold text-gray-800" />
            <select wire:model="designation_id" id="designation_id" class="block mt-1 w-full border border-gray-300 rounded-lg p-4 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                <option value="">Select Designation</option>
                @foreach($designations as $designation)
                    <option value="{{ $designation->designation_id }}">{{ $designation->name }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('designation_id')" class="mt-2 text-sm text-red-500" />
        </div>
        @endif

        <!-- Barangay Selection -->
        <div>
            <x-input-label for="barangay_id" :value="__('Barangay')" class="text-lg font-semibold text-gray-800"/>
            <select wire:model="barangay_id" id="barangay_id" class="block mt-1 w-full border border-gray-300 rounded-lg p-4 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                <option value="">Select Barangay</option>
                @foreach($barangays as $barangay)
                    <option value="{{ $barangay->id }}">{{ $barangay->barangay_name }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('barangay_id')" class="mt-2 text-sm text-red-500" />
        </div>

        <!-- Street Name -->
        <div>
            <x-input-label for="street" :value="__('Street Name')" class="text-lg font-semibold text-gray-800"/>
            <x-text-input wire:model="street" id="street" class="block mt-1 w-full border border-gray-300 rounded-lg p-4 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" type="text" required />
            <x-input-error :messages="$errors->get('street')" class="mt-2 text-sm text-red-500" />
        </div>

        <!-- Contact Number -->
        <div>
            <x-input-label for="contact_no" :value="__('Contact Number')" class="text-lg font-semibold text-gray-800"/>
            <x-text-input wire:model="contact_no" id="contact_no" class="block mt-1 w-full border border-gray-300 rounded-lg p-4 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" type="text" />
            <x-input-error :messages="$errors->get('contact_no')" class="mt-2 text-sm text-red-500" />
        </div>

        <!-- Gender -->
        <div>
            <x-input-label for="gender" :value="__('Gender')" class="text-lg font-semibold text-gray-800"/>
            <select wire:model="gender" id="gender" class="block mt-1 w-full border border-gray-300 rounded-lg p-4 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
            <x-input-error :messages="$errors->get('gender')" class="mt-2 text-sm text-red-500" />
        </div>

        <!-- Birth Date -->
        <div>
            <x-input-label for="birth_date" :value="__('Birth Date')" class="text-lg font-semibold text-gray-800"/>
            <x-text-input wire:model="birth_date" id="birth_date" class="block mt-1 w-full border border-gray-300 rounded-lg p-4 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" type="date" />
            <x-input-error :messages="$errors->get('birth_date')" class="mt-2 text-sm text-red-500" />
        </div>

        <!-- Owner-Specific Fields -->
        @if ($role == 1)
            <div>
                <x-input-label for="civil_status" :value="__('Civil Status')" class="text-lg font-semibold text-gray-800"/>
                <select wire:model="civil_status" id="civil_status" class="block mt-1 w-full border border-gray-300 rounded-lg p-4 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="">Select Civil Status</option>
                    <option value="Married">Married</option>
                    <option value="Separated">Separated</option>
                    <option value="Single">Single</option>
                    <option value="Widow">Widow</option>
                </select>
                <x-input-error :messages="$errors->get('civil_status')" class="mt-2 text-sm text-red-500" />
            </div>

    <!-- Categories Selection -->
<div>
    <x-input-label for="category" :value="__('Categories')" class="text-lg font-semibold text-gray-800" />
    <div class="space-y-2">
        @foreach($categories as $category)
            <div class="flex items-center">
                <input 
                    type="checkbox" 
                    id="category_{{ $category->id }}" 
                    wire:model="selectedCategories" 
                    value="{{ $category->id }}" 
                    class="mr-2"
                />
                <label for="category_{{ $category->id }}" class="text-gray-800">{{ $category->name }}</label>
            </div>
        @endforeach
    </div>
    <x-input-error :messages="$errors->get('selectedCategories')" class="mt-2 text-sm text-red-500" />
</div>

        
        

        @endif

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-lg font-semibold text-gray-800"/>
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full border border-gray-300 rounded-lg p-4 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" type="email" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-500" />
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-center mt-8">
            <x-primary-button class="bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 focus:outline-none text-white font-semibold rounded-lg px-6 py-3 shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                {{ __('Add User') }}
            </x-primary-button>
        </div>
    </form>
</div> 
