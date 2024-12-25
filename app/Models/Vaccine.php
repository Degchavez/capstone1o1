<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vaccine extends Model
{
    protected $fillable = [
        'vaccine_name',
        'description'
    ];

    public function transactions()
{
    return $this->hasMany(Transaction::class);
}

}

