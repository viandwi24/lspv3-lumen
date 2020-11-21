<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompetencyUnit extends Model
{
    protected $fillable = [
        'schema_id', 'title', 'standard_type', 'code'
    ];

    public function schema()
    {
        return $this->belongsTo(Schema::class);
    }

    public function work_elements()
    {
        return $this->hasMany(WorkElement::class);
    }
}
