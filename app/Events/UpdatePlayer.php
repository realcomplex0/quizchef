<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdatePlayer implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $id;
    public $op;
    public $info;
    /**
     * Create a new event instance.
     */
    public function __construct($id, $op, $data)
    {   
        $this->id = $id;
        $this->op = $op;
        $this->info = [];
        if ($op == 0){ // confirm they have submitted their answer
            $this->info['answer_index'] = $data['answer_index'];
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): Channel
    {
        return new Channel('player.' . $this->id);
    }
}
