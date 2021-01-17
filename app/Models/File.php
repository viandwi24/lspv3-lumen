<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'name', 'path', 'type', 'size', 'category'
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'deleted' => \App\Events\FileDeletedEvent::class
    ];

    public function user()
    {
        return $this->belongsToMany(User::class, 'file_users');
    }
}
