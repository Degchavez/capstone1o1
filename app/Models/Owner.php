<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'owners';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'owner_id';

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = true;

    /**
     * The data type of the primary key.
     */
    protected $keyType = 'int';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'civil_status',
        'category',
        'permit',
    ];

    /**
     * Define the relationship with the `User` model.
     */
// In Owner model
public function user()
{
    return $this->belongsTo(User::class, 'user_id', 'user_id');
}

    public function animals()
{
    return $this->hasMany(Animal::class, 'owner_id');
}

public function transactions()
    {
        return $this->hasMany(Transaction::class, 'owner_id'); // Assuming the foreign key is owner_id in the transactions table
    }
    public function address()
    {
        return $this->hasOne(Address::class, 'user_id', 'user_id'); // Assuming the foreign key is user_id
    }
}
