<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Breed extends Model
{

    use HasFactory;

    // Define the table name if it doesn't follow Laravel's naming convention
    // protected $table = 'breeds';

    // Specify the fillable attributes

    protected $fillable = [
        'name',
        'species_id', // Assuming breeds belong to a species
    ];

    public function species()
    {
        return $this->belongsTo(Species::class);
    }

    public function animals()
    {
        return $this->hasMany(Animal::class);
    }
}
