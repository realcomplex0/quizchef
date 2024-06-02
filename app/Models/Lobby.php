<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\LobbyPlayer;

class Lobby extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'quiz_host', 'quiz_id'];
    public function players () 
    {
        return $this->hasMany(LobbyPlayer::class);
    }
}