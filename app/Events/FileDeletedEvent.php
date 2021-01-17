<?php

namespace App\Events;

use App\Models\File;
use Illuminate\Queue\SerializesModels;

class FileDeletedEvent extends Event
{
    use SerializesModels;

    /**
     * The file instance.
     *
     * @var \App\Models\File
     */
    public $file;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(File $file)
    {
        $this->file = $file;
    }
}
