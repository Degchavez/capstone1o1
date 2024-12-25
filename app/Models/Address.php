<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    // Table name (not strictly necessary if the table follows Laravel's naming conventions)
    protected $table = 'addresses';

    // Fillable fields for mass assignment
    protected $fillable = [
        'user_id',
        'barangay_id',
        'street',
    ];

    // Defining the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class); // No need to specify 'user_id' explicitly
    }

    // Defining the relationship with the Barangay model
    public function barangay()
    {
        return $this->belongsTo(Barangay::class, 'barangay_id', 'id');
    }

    public function owner()
    {
        return $this->belongsTo(Owner::class, 'user_id');
    }
}
