<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\Option;

class StartGame implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $code;
    public $status;
    public $question;
    public $options;
    /**
     * Create a new event instance.
     */
    public function __construct($code, $status, $question)
    {
        $this->code = $code;
        $this->status = $status;
        $this->question = $question;
        $opts = Option::query()->where('question_id', $question['id'])->get();
        $this->options = [];
        $cnt = 0;
        foreach ($opts as $o){
            $this->options[$cnt] = $o['title'];
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
        return new Channel('lobby.' . $this->code);
    }
}
