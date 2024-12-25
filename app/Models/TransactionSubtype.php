<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionSubtype extends Model
{
    protected $fillable = ['transaction_type_id', 'subtype_name'];

    public $timestamps = false;


    // Define relationship with TransactionType
    public function transactionType()
    {
        return $this->belongsTo(TransactionType::class, 'transaction_type_id');
    }

    // Define relationship with Transactions
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'transaction_subtype_id');
    }
}
