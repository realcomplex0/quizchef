<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Option;

class UpdatePlayer implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $id;
    public $optionsAnswer;
    /**
     * Create a new event instance.
     */
    public function __construct($id, $question)
    {   
        $this->id = $id;
        $opts = Option::query()->where('question_id', $question['id'])->get();
        $this->optionsAnswer = [];
        $cnt = 0;
        foreach ($opts as $o){
            $this->optionsAnswer[$cnt] = $o['correct'];
            $cnt++;
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
