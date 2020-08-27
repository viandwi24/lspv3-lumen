<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompetencyUnit extends Model
{
    protected $fillable = [
        'title', 'standart_type', 'code'
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
