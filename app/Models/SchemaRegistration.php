<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchemaRegistration extends Model
{
    protected $fillable = [
        'schema_id', 'user_id', 'data', 'status'
    ];

    protected $casts = [
        'data' => 'object'
    ];

    public function schema()
    {
        return $this->belongsTo(Schema::class);
    }

    public function files()
    {
        return $this->hasMany(SchemaRegistrationFile::class);
    }
}
