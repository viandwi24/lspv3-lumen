<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchemaFile extends Model
{
    protected $fillable = [
        'schema_id', 'name', 'format',
    ];

    protected $casts = [
        'format' => 'array'
    ];

    public function schema()
    {
        return $this->belongsTo(Schema::class);
    }
}
