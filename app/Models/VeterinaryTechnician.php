<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VeterinaryTechnician extends Model
{
    use HasFactory;

    protected $table = 'veterinary_technicians';

    protected $fillable = ['full_name', 'contact_number', 'email']; // Add all mass-assignable fields
}
