<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['owner_id', 'category_id'];
    
    public function users()
    {
        return $this->belongsToMany(User::class, 'category_user', 'category_id', 'user_id');
    }
}
