<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';
    protected $keyType = 'int';
    public $incrementing = true;  // Specify primary key if it's not 'id'

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'complete_name',
        'email',
        'password',
        'address',
        'contact_no',
        'gender',
        'birth_date',
        'status',
        'role',
        'designation_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',  // Laravel 10 supports automatic password hashing
        'birth_date' => 'datetime',
    ];
    
    public function address()
    {
        return $this->hasOne(Address::class, 'user_id', 'user_id');
    }
    
    public function owner()
    {
        return $this->hasOne(Owner::class, 'user_id', 'user_id');
    }

    public function animals()
{
    return $this->hasMany(Animal::class, 'owner_id');
}
// User model
public function transactions()
{
    return $this->hasManyThrough(Transaction::class, Owner::class, 'user_id', 'owner_id', 'user_id', 'owner_id');
}
// In User.php model
public function barangay()
{
    return $this->belongsTo(Barangay::class);
}
public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id', 'designation_id');
    }

    public function treatedAnimals()
{
    return $this->hasManyThrough(Animal::class, Transaction::class, 'vet_id', 'owner_id', 'user_id', 'owner_id');
    // Fetch animals treated by the vet through the transaction model
}

// In User Model
public function vetTransactions()
{
    return $this->hasMany(Transaction::class, 'vet_id', 'user_id');
}


}    
