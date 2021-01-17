<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name', 'description'
    ];

    public function schemas()
    {
        return $this->belongsToMany(Schema::class, 'schema_categories');
    }
}
