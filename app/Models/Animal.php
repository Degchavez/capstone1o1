<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    // The table associated with the model (if it's not the default plural form)
    protected $table = 'animals'; // This is optional if your table name follows Laravel's conventions

    protected $casts = [
        'birth_date' => 'date',
    ];
    
    protected $fillable = [
        'is_group',
        'group_count',
        'name',
        'owner_id',
        'species_id',
        'breed_id',
        'birth_date',
        'gender',
        'medical_condition',
        'photo_front',
        'photo_back',
        'photo_left_side',
        'photo_right_side',
        'color',
        'is_vaccinated',

    ];

    // Relationship with the Owner model
 // In Animal.php model
public function owner()
{
    return $this->belongsTo(Owner::class, 'owner_id', 'owner_id');
}

    // Relationship with the Species model
    public function species()
    {
        return $this->belongsTo(Species::class);
    }

    // Relationship with the Breed model
    public function breed()
    {
        return $this->belongsTo(Breed::class);
    }

    // Optionally, if you want to get the full URL for the photos, you can define a method:
    public function getPhotoUrl($photo)
    {
        return url('storage/' . $this->$photo); // Assumes the photos are stored in the public disk under 'storage'
    }

    public function transactions()
{
    return $this->hasMany(Transaction::class, 'animal_id', 'animal_id');
}

}
