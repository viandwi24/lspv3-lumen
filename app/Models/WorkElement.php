<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkElement extends Model
{
    protected $fillable = [
        'competency_unit_id', 'title'
    ];

    public function competency_unit()
    {
        return $this->belongsTo(CompetencyUnit::class);
    }

    public function job_criterias()
    {
        return $this->hasMany(JobCriteria::class);
    }
}
