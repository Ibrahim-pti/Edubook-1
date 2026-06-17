<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicEvent extends Model
{
    protected $fillable = [
        'title',
        'description',
        'date',
        'duration_days',
        'category',
        'icon',
    ];

    protected $casts = [
        'date' => 'date',
        'duration_days' => 'integer',
    ];
}
