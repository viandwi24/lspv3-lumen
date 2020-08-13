<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'name', 'announcement', 'agenda', 'date'
    ];

    protected $casts = [
        'agenda' => 'array',
        'date' => 'date'
    ];
}
