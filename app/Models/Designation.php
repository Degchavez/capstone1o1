<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    use HasFactory;

    // Define the primary key field
    protected $primaryKey = 'designation_id';  // Corrected from `primary_key` to `primaryKey`
    
    // If the primary key is not auto-incrementing, set incrementing to false
    public $incrementing = true;  // Set to false if `designation_id` is not auto-incrementing
    
    // Specify the table if it does not follow Laravel's naming convention
    protected $table = 'designations';

    // Specify the key type if it's not an integer (e.g., for UUIDs or strings)
    protected $keyType = 'int';  // Change this if your primary key is not an integer (e.g., 'string' for UUIDs)

    // Specify the fillable fields
    protected $fillable = [
        'name',          // Field for storing designation name
        'description',   // Field for storing designation description
    ];
}

