<?php

namespace App\Listeners;

use App\Events\FileDeletedEvent;
use Illuminate\Support\Facades\Storage;

class FileDeletedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AppEventsFileDeleted  $event
     * @return void
     */
    public function handle(FileDeletedEvent $event)
    {
        $file = $event->file;
        $upload_path = 'users/files';
        $store = $file->path;
        $path = $upload_path . DIRECTORY_SEPARATOR . $store;
        $delete = Storage::delete($path);
        app('log')->info("[file:delete][{$path}]");
    }
}
