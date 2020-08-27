<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobCriteria extends Model
{
    protected $fillable = [
        'work_element_id', 'title'
    ];

    public function work_element()
    {
        return $this->belongsTo(WorkElement::class);
    }
}
