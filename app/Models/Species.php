<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Species extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Relationship: Species has many Breeds.
     */
    public function breeds()
    {
        return $this->hasMany(Breed::class);
    }

    /**
     * Relationship: Species has many Animals.
     */
    public function animals()
    {
        return $this->hasMany(Animal::class);
    }
}
