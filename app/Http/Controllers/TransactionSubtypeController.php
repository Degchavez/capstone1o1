<?php

namespace App\Http\Controllers;

use App\Models\TransactionSubtype;
use App\Models\TransactionType;
use Illuminate\Http\Request;

class TransactionSubtypeController extends Controller
{
    public function index()
    {
        // Fetch all Transaction Subtypes with their related Transaction Types
        $transactionSubtypes = TransactionSubtype::with('transactionType')->get();
        return view('admin.subtype-index', compact('transactionSubtypes'));
    }

    public function create()
    {
        // Fetch all Transaction Types for the dropdown
        $transactionTypes = TransactionType::all();
        return view('admin.subtype-create', compact('transactionTypes'));
    }

    public function store(Request $request)
    {
        // Validate the form data
        $request->validate([
            'transaction_type_id' => 'required|exists:transaction_types,id',
            'subtype_name' => 'required|string|max:255|unique:transaction_subtypes',
        ]);

        // Store the new Transaction Subtype
        TransactionSubtype::create([
            'transaction_type_id' => $request->input('transaction_type_id'),
            'subtype_name' => $request->input('subtype_name'),
        ]);

        return redirect()->route('subtype.index')->with('success', 'Transaction Subtype added successfully.');
    }

    public function edit(TransactionSubtype $transactionSubtype)
    {
        // Fetch all Transaction Types for the dropdown
        $transactionTypes = TransactionType::all();
        return view('admin.subtype-edit', compact('transactionSubtype', 'transactionTypes'));
    }

    public function update(Request $request, TransactionSubtype $transactionSubtype)
    {
        // Validate the form data
        $request->validate([
            'transaction_type_id' => 'required|exists:transaction_types,id',
            'subtype_name' => 'required|string|max:255|unique:transaction_subtypes,subtype_name,' . $transactionSubtype->id,
        ]);

        // Update the Transaction Subtype
        $transactionSubtype->update([
            'transaction_type_id' => $request->input('transaction_type_id'),
            'subtype_name' => $request->input('subtype_name'),
        ]);

        return redirect()->route('subtype.index')->with('success', 'Transaction Subtype updated successfully.');
    }

    public function destroy(TransactionSubtype $transactionSubtype)
    {
        // Delete the Transaction Subtype
        $transactionSubtype->delete();
        return redirect()->route('subtype.index')->with('success', 'Transaction Subtype deleted successfully.');
    }
}
