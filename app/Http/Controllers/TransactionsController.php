<?php

namespace App\Http\Controllers;

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
use App\Models\VeterinaryTechnician;
use App\Models\Vaccine;

use DB; 

use Illuminate\Http\Request;

class TransactionsController extends Controller
{
  // TransactionController.php
  public function edit($id)
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
      return view('admin.transactions-edit', compact(
          'transaction',
          'transactionTypes',
          'vets',
          'technicians',
          'vaccines',
          'transactionSubtypes',
          'subtypesByType'
      ));
  }
  

  
  public function update(Request $request, $transaction_id)
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
      return redirect()->route('animals.profile', ['animal_id' => $animal->animal_id])
                       ->with('success', 'Transaction updated successfully');
  }
  

public function destroy($id)
{
    // Find the transaction by transaction_id (primary key)
    $transaction = Transaction::where('transaction_id', $id)->first(); // Use $id to query transaction_id

    // Check if the transaction exists
    if (!$transaction) {
        return redirect()->route('animals.profile', $transaction->animal_id)->with('error', 'Transaction not found');
    }

    // Store the animal_id before deletion
    $animal_id = $transaction->animal_id;

    // Delete the transaction
    Transaction::where('transaction_id', $id)->delete();

    // Redirect with success message
    return redirect()->route('animals.profile', $animal_id)->with('success', 'Transaction deleted successfully.');
}


public function getTransactionData($transactionId)
{
    $transaction = Transaction::with(['transactionType', 'transactionSubtype', 'vet'])
        ->where('transaction_id', $transactionId)
        ->first();

    if (!$transaction) {
        return response()->json(['error' => 'Transaction not found'], 404);
    }

    return response()->json([
        'transaction_type_id' => $transaction->transaction_type_id,
        'transaction_subtype_id' => $transaction->transaction_subtype_id,
        'vet_id' => $transaction->vet_id,
        'status' => $transaction->status,
        'details' => $transaction->details,
    ]);
}

public function updateStatus(Request $request, $transaction_id)
{
    // Find the transaction by its custom primary key 'transaction_id'
    $transaction = Transaction::where('transaction_id', $transaction_id)->first();
    
    // If the transaction exists, update the status
    if ($transaction) {
        $transaction->status = $request->status; // Update the status with the selected value
        // Update the transaction by explicitly targeting 'transaction_id' as the key
        Transaction::where('transaction_id', $transaction_id)->update(['status' => $request->status]); // Using the update method to target the 'transaction_id' column
    }

    // Redirect back with a success message
    return back()->with('status', 'Transaction status updated successfully!');
}

}
