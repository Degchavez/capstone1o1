<?php
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\TransactionSubtypeController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\NewOwnerController;
use App\Http\Controllers\ReController;
use App\Http\Controllers\NewVaccineController;
use App\Http\Controllers\NewBarangayController;
use App\Http\Controllers\NewSpeciesController;
use App\Http\Controllers\NewBreedController;
use App\Http\Controllers\NewTransactionsController;
use App\Http\Controllers\NewTransactionSubtypeController;
use App\Http\Controllers\NewDesignationController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\VeterinaryTechnicianController;
use App\Http\Controllers\VaccineController;
use App\Http\Controllers\BarangayController;
use App\Http\Controllers\SpeciesController;
use App\Http\Controllers\BreedController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\VetController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReceptionistController;
use App\Http\Controllers\AdminController;
use App\Http\Livewire\Pages\Auth\Register;
use App\Models\Breed;
use App\Models\Designation;
use App\Models\TransactionSubtype;
use App\Livewire\EditUser;
use Illuminate\Support\Facades\Route;

//Routes

Route::get('/', function () {
    $veterinarians = \App\Models\User::where('role', 2)->get(); // Assuming role 2 identifies veterinarians
    return view('welcome', compact('veterinarians'));
});

Route::middleware(['auth'])->get('/dashboard', function () {
    if (auth()->user()->role == 0) {
        return redirect()->route('admin-dashboard');
    } elseif (auth()->user()->role == 1) {
        return redirect()->route('owner-dashboard');
    } elseif (auth()->user()->role == 2) {
        return redirect()->route('vet-dashboard');
    } else {
        return redirect()->route('receptionist-dashboard');
    }
})->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Route for transaction details partial (for modal)
Route::get('/admin/transactions/{transactionId}/details-partial', [TransactionsController::class, 'getTransactionDetailsPartial'])
->name('transactions.details-partial');

Route::get('/transaction/{transaction}/pdf', [TransactionsController::class, 'downloadPdf'])
->name('transaction.download.pdf');


Route::group(['middleware' => 'admin'],function(){

    //Dashboard
    Route::get('/admin/dashboard',[AdminController::class,'loadAdminDashboard'])
    ->name('admin-dashboard');

    //Users List
    Route::get('/admin/users',[AdminController::class,'loadUsersList'])
    ->name('admin-users');

    //Owners List
    Route::get('/admin/owners',[AdminController::class,'loadOwnersList'])
    ->name('admin-owners');

    //Animals List
    Route::get('/admin/animals/',[AdminController::class,'loadAnimalsList'])
    ->name('admin-animals');

    //Transactions List
    Route::get('/admin/transactions',[AdminController::class,'loadTransactionsList'])
    ->name('admin-transactions');

    //Veterinarians List
    Route::get('/admin/veterinarians',[AdminController::class,'loadVeterinariansList'])
    ->name('admin-veterinarians');


    //Create Users
    Route::get('/admin/create/users',[AdminController::class,'loadAddUsers']);

    //Delete Users
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroy'])->name('users.destroy');

    // Edit User Route
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit-form');
    Route::put('/users/{id}/update', [UserController::class, 'update'])->name('users.update');

   
    // Profile Update Routes (Make sure the paths are distinct)
    Route::prefix('users')->group(function () {
    Route::get('/{id}/editprofile', [UserController::class, 'profile_edit'])->name('users.profile-edit-form');
    Route::put('/{id}/updateprofile', [UserController::class, 'profile_update'])->name('users.profile-update');
        
    });
    //reset password
    Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    
    //user profile
    Route::get('/users/{id}/profile', [UserController::class, 'profile'])->name('users.profile-form');

    //navigation profile
    Route::get('/users/{id}/nav-profile', [UserController::class, 'navProfile'])->name('users.nav-profile');


    //owner profile
// In your routes file (web.php)
    Route::get('/owners/{owner_id}/user_profile', [OwnerController::class, 'showProfile'])->name('owners.profile-owner');
    //owner transactions
    Route::get('owner/{owner_id}/transactions', [OwnerController::class, 'showTransactions'])->name('owner.transactions');


    //add animal
    Route::get('/owners/{owner_id}/add-animal', [AdminController::class, 'showAddAnimalForm'])->name('owner.addAnimalForm');
    Route::post('/owners/{owner_id}/add-animal', [AdminController::class, 'store'])->name('owner.addAnimal');

//GET Breeds
    Route::get('/get-breeds/{species_id}', [AdminController::class, 'getBreeds'])->name('getBreeds');

    //get transaction types
    Route::get('/get-transaction-subtypes/{transactionTypeId}', [AdminController::class, 'getTransactionSubtypes']);


    Route::delete('owner/animal/{animal_id}/delete', [OwnerController::class, 'deleteAnimal'])->name('owner.deleteAnimal');

    // Define the update route with the owner_id and animal_id parameters
    Route::get('/owner/{owner_id}/animal/{animal_id}/edit', [OwnerController::class, 'edit'])->name('owner.editAnimal');

    Route::put('/owner/{owner_id}/animal/{animal_id}/update', [OwnerController::class, 'updateAnimal'])->name('owner.updateAnimal');

    //add transaction
    Route::get('/owner/{owner_id}/add-transaction', [OwnerController::class, 'addTransactionForm'])->name('owner.addTransactionForm');
    Route::post('/owner/{owner_id}/add-transaction', [OwnerController::class, 'storeTransaction'])->name('owner.addTransaction');

    //delete transaction
    Route::delete('/transaction/{transaction_id}', [OwnerController::class, 'deleteTransaction'])->name('transaction.delete');

    //edit transaction
    Route::get('/transaction/{transaction_id}/edit', [OwnerController::class, 'editTransactionForm'])->name('owner.editTransactionForm');
    Route::put('/transaction/{transaction_id}/update', [OwnerController::class, 'updateTransaction'])->name('owner.updateTransaction');

    //GET TRANSACTION
    Route::get('/get-transaction/{transaction_id}', [OwnerController::class, 'getTransaction']);

    Route::get('/get-subtypes-for-type/{transactionTypeId}', [OwnerController::class, 'getSubtypesForType']);

    Route::prefix('admin')->group(function () {
    Route::get('/users/{id}/editprofile', [AdminController::class, 'owner_edit'])->name('admin.owner-edit-form');
    Route::put('/users/{id}/updateprofile', [AdminController::class, 'owner_update'])->name('admin.owner-form-update');
    Route::delete('/removeTransaction/{transaction_id}', [OwnerController::class, 'removeTransaction'])->name('transaction.remove');

    
    //Animal Profile
    Route::get('/animals/{animal_id}/profile', [AdminController::class, 'showAnimalProfile'])->name('animals.profile');

    //Get subtypes
    Route::get('/get-subtypes/{transactionTypeId}', [AdminController::class, 'getSubtypes']);
    //storetransactions
    Route::post('/transactions/store/{animal_id}', [AnimalController::class, 'store'])->name('transactions.store');


    //Animals-transaction talbe and profile
    Route::delete('/transactions/{transaction_id}', [TransactionsController::class, 'destroy'])->name('transactions.destroy');
    Route::get('transactions/{transaction_id}/edit', [TransactionsController::class, 'edit'])->name('transactions.edit');
    Route::put('transactions/{transaction_id}', [TransactionsController::class, 'update'])->name('transactions.update');
    Route::get('/animals/{animal_id}/edit', [AnimalController::class, 'edit'])->name('animals.edit');
    Route::put('/animals/{animal_id}', [AnimalController::class, 'update'])->name('animals.update');
    Route::delete('/animals/{animal_id})', [AnimalController::class, 'destroy'])->name('animals.delete');
    Route::get('/animals/add', [AnimalController::class, 'showAddAnimalForm'])->name('animals.add-animal-form');
    Route::post('/animals', [AnimalController::class, 'animalStore'])->name('admin-animals.store');
//edit animals table
    Route::get('/admin/animals/{animal_id}/edit', [AnimalController::class, 'showEditAnimalForm'])->name('admin-animals.edit');

    // Update an animal
    Route::put('/admin/animals/{animal_id}', [AnimalController::class, 'animalUpdate'])->name('admin-animals.update');

//API
Route::get('/breeds/{species_id}', function ($species_id) {
    $breeds = Breed::where('species_id', $species_id)->get();
    return response()->json($breeds);

    Route::get('/get-transaction-data/{transactionId}', [TransactionsController::class, 'getTransactionData']);

   
});

Route::get('/transaction-subtypes/{transaction_type_id}', function ($transaction_type_id) {
    $subtypes = TransactionSubtype::where('transaction_type_id', $transaction_type_id)->get();
    return response()->json($subtypes);
});
});
//register Owner
Route::get('register-owner', [UserController::class, 'showRegistrationForm'])->name('register-owner');
Route::post('register-owner', [UserController::class, 'register'])->name('register-owner.submit');

//OwnerList Edit
Route::get('owner/{owner_id}/edit', [UserController::class, 'ownerList_edit'])->name('ownerList.edit');
Route::put('owner/{owner_id}/update', [UserController::class, 'ownerList_update'])->name('ownerList.update');

 //Settings

 Route::get('/users/settings', [UserController::class, 'settings'])->name('users.settings');
 Route::post('/users/settings/change-password', [UserController::class, 'changePassword'])->name('users.change-password');
 
 //Delete Image
 Route::delete('/users/{user}/delete-image', [UserController::class, 'deleteImage'])->name('users.delete-image');
// Route for viewing veterinarian's profile
Route::get('admin/veterinarians/{user_id}', [AdminController::class, 'showVeterinarianProfile'])->name('admin.veterinarian.profile');

//vet update transaction s tatus
// In routes/web.php
Route::put('/transaction/{transaction_id}/update-status', [TransactionsController::class, 'updateStatus'])->name('updateStatus');

//edit vet from profile
Route::get('/admin/vets/{user_id}/edit', [AdminController::class, 'vet_edit'])->name('vets.edit');
Route::put('/admin/vets/{user_id}', [AdminController::class, 'vet_update'])->name('vets.update');

//vet list crud
  // Route for showing the veterinarian registration form
  Route::get('/veterinarians/register', [UserController::class, 'create_vet'])->name('veterinarians.create');
    
  // Route for storing a new veterinarian
  Route::post('/veterinarians', [UserController::class, 'store_vet'])->name('veterinarians.store');

  // Route for deleting a profile image
  Route::delete('/veterinarians/{veterinarian}/image', [UserController::class, 'deleteProfileImage_vet'])->name('veterinarians.deleteImage');

    //EDIT vet list
    // Route to show the edit form for a veterinarian
Route::get('/admin/veterinarians/{id}/edit', [UserController::class, 'edit_veterinarian'])->name('admin-veterinarians.edit');

// Route to update the veterinarian's details
Route::put('/admin/veterinarians/{id}', [UserController::class, 'update_veterinarian'])->name('admin-veterinarians.update');
Route::delete('/admin/veterinarians/{user_id}', [UserController::class, 'destroy_veterinarian'])->name('admin-veterinarians.destroy');

//Technicians Index

Route::get('/technicians/index',[VeterinaryTechnicianController::class,'loadTechnicians'])
->name('admin-technicians');


//technicians create
Route::get('veterinary-technicians/create', [VeterinaryTechnicianController::class, 'create'])->name('veterinary-technicians.create');

//technician store
Route::post('veterinary-technicians/store', [VeterinaryTechnicianController::class, 'store'])->name('veterinary-technicians.store');

// Route to edit a technician
Route::get('veterinary-technicians/{technician}/edit', [VeterinaryTechnicianController::class, 'edit'])->name('veterinary-technicians.edit');
Route::put('veterinary-technicians/{technician}', [VeterinaryTechnicianController::class, 'update'])->name('veterinary-technicians.update');

// Route to delete a technician
Route::delete('veterinary-technicians/{technician}', [VeterinaryTechnicianController::class, 'destroy'])->name('veterinary-technicians.destroy');

Route::resource('vaccines', VaccineController::class);
Route::get('/admin/vaccines', [VaccineController::class, 'loadVaccines'])->name('vaccines.load');

Route::resource('barangays', BarangayController::class);
Route::get('/admin/barangays', [BarangayController::class, 'loadBarangays'])->name('barangay.load');
Route::get('/barangays/create', [BarangayController::class, 'create'])->name('create-barangay');


Route::resource('species', SpeciesController::class);
Route::resource('breeds', BreedController::class);

Route::get('/admin/species', [SpeciesController::class, 'index'])->name('species.breed');

Route::resource('transaction-subtypes', TransactionSubtypeController::class);
Route::get('/admin/subtypes', [TransactionSubtypeController::class, 'index'])->name('subtype.index');

Route::resource('designations', DesignationController::class);
Route::get('/admin/designation', [DesignationController::class, 'index'])->name('designation.index');

Route::put('/update-tech/{transaction_id}', [AdminController::class, 'updateTechnician'])->name('updateTech');

Route::put('/admin/{transaction_id}/update-dets', [AdminController::class, 'updateDetails'])->name('update.dets');



});

Route::group(['middleware' => 'owner'], function () {
    Route::get('/owner/dashboard', [OwnerController::class, 'loadOwnerDashboard'])->name('owner-dashboard');

    Route::get('/owner/{owner_id}/owner_profile', [AdminController::class, 'showOwnerProfile'])->name('owners.profile');

    Route::get('/user/{id}/viewedit', [OwnerController::class, 'owner_editprofile'])->name('owner.owner-edit-form');
    Route::put('/user/{id}/viewupdate', [OwnerController::class, 'owner_updateprofile'])->name('owner.owner-form-update');

    Route::get('/owner/{owner_id}/create-animal', [OwnerController::class, 'createAddAnimalForm'])->name('owner.createAnimalForm');
    Route::post('/owner/{owner_id}/create-animal', [OwnerController::class, 'storeAnimal'])->name('owner.createAnimal');

    // Rename route name to avoid conflict
    Route::get('/get-breed/{species_id}', [NewOwnerController::class, 'ownergetBreeds'])->name('owner.getBreeds');

    Route::get('/owners/{owner_id}/animals/{animal_id}/editAnimal', [NewOwnerController::class, 'edit'])->name('owner.NeweditAnimal');

    Route::put('/owners/{owner_id}/animals/{animal_id}/updateAnimal', [NewOwnerController::class, 'updateAnimal'])->name('owner.NewupdateAnimal');

    Route::get('owners/{owner_id}/transaction', [NewOwnerController::class, 'showTransactions'])->name('owner.Newtransactions');

    Route::get('/owners/settings', [NewOwnerController::class, 'settings'])->name('owners.settings');
 Route::post('/owners/setting/change-password', [NewOwnerController::class, 'changePassword'])->name('owners.change-password');

 Route::get('/animal/{animal_id}/profiles', [NewOwnerController::class, 'showAnimalProfile'])->name('newanimals.profile');

 Route::get('/animal/{animal_id}/edits', [NewOwnerController::class, 'Newedit'])->name('newanimals.edit');
 Route::put('/animal/{animal_id}', [NewOwnerController::class, 'update'])->name('newanimals.update');




});

Route::group(['middleware' => 'vet'], function () {

    Route::prefix('reports')->group(function () {
        Route::get('/', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
        Route::post('/transactions', [App\Http\Controllers\ReportController::class, 'generateTransactionReport'])->name('reports.transactions');
        Route::post('/animal-health', [App\Http\Controllers\ReportController::class, 'generateAnimalHealthReport'])->name('reports.animal-health');
        Route::get('/download/{report}', [App\Http\Controllers\ReportController::class, 'download'])->name('reports.download');
        Route::delete('/reports/{report}', [App\Http\Controllers\ReportController::class, 'delete'])->name('reports.delete');
    // ... existing code ...

    });
    // Replace or add this route outside of any route groups
Route::post('/api/reports/preview', [App\Http\Controllers\ReportController::class, 'preview'])
->middleware('auth')
->name('reports.preview');


Route::get('vet/veterinarian/{user_id}', [VetController::class, 'showVeterinarianProfile'])->name('vet.veterinarian.profile');

Route::put('/update-technician/{transaction_id}', [VetController::class, 'updateTechnician'])->name('updateTechnician');

Route::get('/transactions/{transaction_id}/details', [VetController::class, 'showDetails'])->name('transactions.details');

Route::put('/transactions/{transaction_id}/update-details', [VetController::class, 'updateDetails'])->name('update.details');

Route::get('/vet/vts/{user_id}/edit', [VetController::class, 'vet_edit'])->name('newvets.edit');
Route::put('/vet/vts/{user_id}', [VetController::class, 'vet_update'])->name('newvets.update');

Route::get('/vet/settings', [VetController::class, 'settings'])->name('vet.settings');
Route::post('/vet/setting/change-password', [VetController::class, 'changePassword'])->name('vet.change-password');

Route::put('/vet/{transaction_id}/update-vet-status', [VetController::class, 'updateStatus'])->name('vet.updateStatus');

Route::get('/vets/{owner_id}/owner_profile', [VetController::class, 'showProfile'])->name('vet.profile-owner');

Route::get('/vet/{animal_id}/animal_profile', [VetController::class, 'showAnimalProfile'])->name('vet.profile');

//RECEPTIONIST NAV

});

Route::group(['middleware' => 'receptionist'], function () {
    
    Route::prefix('rec_reports')->group(function () {
        Route::get('/receptionist/reports', [App\Http\Controllers\VetReportController::class, 'index'])
        ->name('receptionist.reports');
    
    // API for report previews
    Route::post('/api/receptionist/reports/preview', [App\Http\Controllers\VetReportController::class, 'preview'])
        ->name('receptionist.reports.preview');
    
     Route::get('/transactions/reports', [App\Http\Controllers\VetReportController::class, 'transactionReportView'])
        ->name('receptionist.view.transactions');

    Route::post('/transactions/reports', [App\Http\Controllers\VetReportController::class, 'generateRecTransactionReport'])
        ->name('receptionist.generate.transactions');
    Route::post('/receptionist/reports/owners', [App\Http\Controllers\VetReportController::class, 'generateOwnerReport'])
        ->name('receptionist.reports.owners');
    
    Route::post('/receptionist/reports/animals', [App\Http\Controllers\VetReportController::class, 'generateAnimalReport'])
        ->name('receptionist.reports.animals');
    
    Route::post('/receptionist/reports/vaccinations', [App\Http\Controllers\ReportController::class, 'generateVaccinationReport'])
        ->name('receptionist.reports.vaccinations');
    
    Route::post('/receptionist/reports/users', [App\Http\Controllers\ReportController::class, 'generateUserReport']);

});

    Route::get('/rec/owners',[ReController::class,'loadOwnersList'])
    ->name('rec-owners');

    Route::get('/rec/animals/',[ReController::class,'loadAnimalsList'])
    ->name('rec-animals');

    Route::get('reg-owner', [ReController::class, 'showRegistrationForm'])->name('reg-owner');
    Route::post('reg-owner', [ReController::class, 'register'])->name('reg-owner.submit');

    Route::get('rec/{owner_id}/edit', [ReController::class, 'ownerList_edit'])->name('ownerRec.edit');
    Route::put('rec/{owner_id}/update', [ReController::class, 'ownerList_update'])->name('ownerRec.update');

    Route::delete('/rec/users/{user}', [ReController::class, 'destroy'])->name('rec.destroy');

    Route::get('/rec/{owner_id}/user_profile', [ReController::class, 'showProfile'])->name('rec.profile-owner');

    Route::get('/rec/{id}/editprofile', [ReController::class, 'owner_edit'])->name('rec.owner-edit-form');
    Route::put('/rec/{id}/updateprofile', [ReController::class, 'owner_update'])->name('rec.owner-form-update');
    Route::delete('/recTransaction/{transaction_id}', [ReController::class, 'removeTransaction'])->name('transaction.rec');

    Route::get('/rec/{owner_id}/add-animal', [ReController::class, 'showAddAnimalForm'])->name('rec.addAnimalForm');
    Route::post('/rec/{owner_id}/add-animal', [ReController::class, 'store'])->name('rec.addAnimal');

    Route::get('/get-breedz/{species_id}', [ReController::class, 'RecgetBreeds'])->name('rec.getBreeds');

    Route::get('/rec/{owner_id}/animal/{animal_id}/edit', [ReController::class, 'edit'])->name('rec.editAnimal');

Route::put('/rec/{owner_id}/animalz/{animal_id}/updatez', [ReController::class, 'updateAnimal'])->name('rec.updateAnimal');

Route::get('/rec/{owner_id}/add-transaction', [ReController::class, 'addTransactionForm'])->name('rec.addTransactionForm');
Route::post('/rec/{owner_id}/add-transaction', [ReController::class, 'storeTransaction'])->name('rec.addTransaction');

Route::get('/rec/{transaction_id}/editz', [ReController::class, 'editTransactionForm'])->name('rec.editTransactionForm');
Route::put('/rec/{transaction_id}/updatez', [ReController::class, 'updateTransaction'])->name('rec.updateTransaction');

Route::get('rec/{owner_id}/transaction', [ReController::class, 'showTransactions'])->name('rec.transactions');

Route::get('/recanimals/add', [ReController::class, 'showAddAnimalForms'])->name('rec.add-animal-form');
Route::post('/recanimals', [ReController::class, 'animalStore'])->name('rec-animals.store');

Route::get('/rec/animals/{animal_id}/edit', [ReController::class, 'showEditAnimalForm'])->name('rec-animals.edit');

// Update an animal
Route::put('/rec/animals/{animal_id}', [ReController::class, 'animalUpdate'])->name('rec-animals.update');

Route::get('/rec/{animal_id}/animalprofile', [ReController::class, 'showAnimalProfile'])->name('rec.profile');

Route::get('/rec/{animal_id}/editAnimal', [ReController::class, 'edit_animal'])->name('rec.edit');
Route::put('/rec/animal/{animal_id}', [ReController::class, 'update'])->name('rec.profileupdates');

Route::get('rec/{transaction_id}/recedit', [ReController::class, 'recedit'])->name('rectransactions.edit');
Route::put('rec/{transaction_id}', [ReController::class, 'recupdate'])->name('rectransactions.update');

Route::post('/rectransactions/store/{animal_id}', [ReController::class, 'recstore'])->name('rectransactions.store');

Route::get('/rectechnicians/index',[ReController::class,'loadTechnicians'])
->name('rec-technicians');

Route::get('rec-tech/reccreate', [ReController::class, 'createTech'])->name('rec-technicians.create');
Route::post('rec-tech/recstore', [ReController::class, 'storeTech'])->name('rec-tech.store');

Route::resource('newvaccines', NewVaccineController::class);
Route::get('/rec/newvaccines', [NewVaccineController::class, 'loadVaccines'])->name('recvaccines.load');

Route::resource('newbarangays', NewBarangayController::class);
Route::get('/admin/newbarangays', [NewBarangayController::class, 'loadBarangays'])->name('newbarangay.load');
Route::get('/newbarangays/create', [NewBarangayController::class, 'create'])->name('newcreate-barangay');
Route::delete('/newbarangays/{barangay}', [NewBarangayController::class, 'destroy'])->name('destroy-barangay');

Route::resource('newspecies', NewSpeciesController::class);
Route::resource('newbreeds', NewBreedController::class);

Route::get('/rec/newspecies', [NewSpeciesController::class, 'index'])->name('recspecies.breed');
Route::put('/newspecies/{species}', [NewSpeciesController::class, 'update'])->name('newspecies.updates');
Route::put('/newbreeds/{breeds}', [NewBreedController::class, 'update'])->name('newbreeds.updates');// Edit route
Route::get('/newbreeds/{breed}/edit', [NewBreedController::class, 'edit'])->name('newbreeds.edits');
    Route::put('/newbreeds/{breeds}', [NewBreedController::class, 'update'])->name('newbreeds.updates');


    Route::resource('rec-subtypes', NewTransactionSubtypeController::class);
    Route::get('/rec/subtypes', [NewTransactionSubtypeController::class, 'index'])->name('recsubtype.index');

    Route::resource('recdesignations', NewDesignationController::class);
    Route::get('/rec/designation', [NewDesignationController::class, 'index'])->name('recdesignation.index');

    Route::get('/rec/settings', [ReController::class, 'settings'])->name('rec.settings');
Route::post('/rec/set/change-password', [ReController::class, 'changePassword'])->name('rec.change-password');

Route::get('rec/veterinarian/{user_id}', [ReController::class, 'showVeterinarianProfile'])->name('rec.veterinarian.profile');


}); 

Route::get('/vet/dashboard',[VetController::class,'loadVetDashboard'])
->name('vet-dashboard')
->middleware('vet');

Route::get('/receptionist/dashboard',[ReceptionistController::class,'loadReceptionistDashboard'])
->name('receptionist-dashboard')
->middleware('receptionist');


require __DIR__.'/auth.php';
