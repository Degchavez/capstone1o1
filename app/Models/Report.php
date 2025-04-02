<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'report_type',
        'file_path',
        'date_from',
        'date_to',
        'parameters',
        'status',
        'generated_by'
    ];

    protected $casts = [
        'parameters' => 'json',
        'date_from' => 'datetime',
        'date_to' => 'datetime'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function generator()
    {
        return $this->belongsTo(User::class, 'generated_by', 'user_id');
    }

    public function veterinarian()
    {
        // Assuming veterinarians are users with role = 'veterinarian'
        return $this->belongsTo(User::class, 'vet_id', 'user_id')
                    ->where('role', 'veterinarian');
    }
}