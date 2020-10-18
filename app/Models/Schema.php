<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schema extends Model
{
    protected $fillable = [
        'title', 'description', 'code', 'status'
    ];

    public function competency_units()
    {
        return $this->hasMany(CompetencyUnit::class);
    }

    public function assessors()
    {
        return $this->belongsToMany(User::class, 'schema_assessors');
    }

    public function places()
    {
        return $this->belongsToMany(Place::class, 'schema_places');
    }
}
