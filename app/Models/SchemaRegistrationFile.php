<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchemaRegistrationFile extends Model
{
    protected $fillable = [
        'schema_registration_id', 'name', 'path', 'type', 'size'
    ];
}
