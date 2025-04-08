<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FileImportProgress implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $progress;
    public $fileName;

    public function __construct($progress, $fileName)
    {
        $this->progress = $progress;
        $this->fileName = $fileName;
    }

    public function broadcastOn()
    {
        return new Channel('file-import-channel'); // You can change the channel name
    }

    public function broadcastAs()
    {
        return 'file-import-progress'; // This is the event name
    }
}
