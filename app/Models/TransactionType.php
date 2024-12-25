<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionType extends Model
{
    use HasFactory;

    protected $fillable = ['type_name']; // Add any necessary fields here

    public function subtypes()
    {
        return $this->hasMany(TransactionSubtype::class, 'transaction_type_id');
    }
}
