<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_type_id', 
        'transaction_subtype_id', // Added transaction_subtype_id
        'owner_id', 
        'animal_id', 
        'vet_id', 
        'status', 
        'details',
        'technician_id',
        'vaccine_id',


    ];

    // Define the relationships

    public function transactionType()
    {
        return $this->belongsTo(TransactionType::class, 'transaction_type_id');
    }

    public function transactionSubtype()
    {
        return $this->belongsTo(TransactionSubtype::class, 'transaction_subtype_id');
    }

    public function owner()
    {
        return $this->belongsTo(Owner::class, 'owner_id');
    }

// In Transaction model
public function animal() {
    return $this->belongsTo(Animal::class, 'animal_id', 'animal_id');
}


   // In Transaction model
public function vet()
{
    return $this->belongsTo(User::class, 'vet_id', 'user_id')
                ->where('role', 2); // Ensure the user is a vet
}

// In the Transaction model
public function technician()
{
    return $this->belongsTo(VeterinaryTechnician::class, 'technician_id', 'technician_id');
}
public function vaccine()
{
    return $this->belongsTo(Vaccine::class, 'vaccine_id', 'id');
}

}
