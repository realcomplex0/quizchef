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
use App\Models\LobbyPlayer;

class UpdateLobby implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $code;
    public $op;
    public $info;
    /**
     * Create a new event instance.
     */
    public function __construct($code, $op, $data)
    {   
        $this->code = $code;
        $this->op = $op;
        $this->info = [];
        if ($op == 0){ // send corrent options
            $opts = Option::query()->where('question_id', $data['question']['id'])->get();
            $this->info['optionsAnswer'] = [];
            $cnt = 0;
            foreach ($opts as $o){
                $this->info['optionsAnswer'][$cnt] = $o['correct'];
                $cnt++;
            }
        } else if ($op == 1){ // update player answered count
            $this->info['answer_count'] = LobbyPlayer::query()->where('lobby_id', $data['lobby_id'])->where('has_answered', 1)->count();
        } else if ($op == 2){ // go to scoreboard
            $this->info['scoreboard'] = $data['scoreboard'];
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
