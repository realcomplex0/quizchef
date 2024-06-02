<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use App\Models\Quiz;
use App\Models\Lobby;
use App\Models\LobbyPlayer;
use App\Events\JoinLobby;
use Inertia\Inertia;

class LobbyController extends Controller
{
    private function uniqueCodeGenerator(){
        $minCode = 1;
        $maxCode = 100;

        $maxAttempts = 100;
        for($i = 0; $i < $maxAttempts; $i ++  ){
            $randomCode = rand($minCode, $maxCode);

            if(!Lobby::where('code', $randomCode)->exists()){
                return $randomCode;
            }
        }
        return -1;
    }
    
    private function getPlayerList($lobby_id) {
        $players = LobbyPlayer::where('lobby_id', $lobby_id)->get();
        return $players;
    }
    
    public function create(Request $request)
    {
        $data = $request->validate([
            'id' => 'required'
        ]);
        $user = Auth::user();
        $quiz_id = $data['id'];
        $unique_code = $this->uniqueCodeGenerator();
        if($unique_code == -1){
            return Redirect::route('/');
        }
        $lobby = Lobby::create([
            'code' => $unique_code,
            'quiz_host' => $user->id,
            'quiz_id' => $quiz_id
        ]);
        $quizzes = Quiz::where('id', $quiz_id)->get();
        if($quizzes->isEmpty()){
            return Redirect::route('/');
        }
        return Redirect::route('lobby.host')->with(['lobbyId' => $lobby['id'], 'lobbyCode' => $lobby['code'], 'title' => $quizzes[0]['title']]) ;
    }

    public function join(Request $request) { 
        $data = $request->validate([
            'code' => 'required',
            'name' => 'required'
        ]);
        $name = $data['name'];
        $code = $data['code'];
        $lobbies = Lobby::where('code', $code)->get();
        if($lobbies->isEmpty()){
            return Redirect::route('/');
        }
        $lobby = $lobbies[0];
        $lobby_id = $lobby['id'];
        $quiz_id = $lobby['quiz_id'];
        $quizzes = Quiz::where('id', $quiz_id)->get();
        if($quizzes->isEmpty()){
            return Redirect::route('/'); // TODO: send error message
        }
        $quiz = $quizzes[0];
        $player = new LobbyPlayer();
        $player->nickname = $name;
        $player->lobby_id = $lobby_id;
        $player->save();
        event(new JoinLobby($name, $code, $this->getPlayerList($lobby_id)));
        return Redirect::route('lobby.play')->with(['lobbyId' => $lobby['id'],'lobbyCode'=> $lobby['code'], 'title' => $quiz['title']]);
    }
    
    public function hostView() {
        $lobbyCode = session('lobbyCode', null);
        $title = session('title', null);
        $lobbyId = session('lobbyId', null);
        if(!$lobbyCode || !$lobbyId){
            return Redirect::route('/');
        }
        $playerList = $this->getPlayerList($lobbyId);
        return Inertia::render('Lobby/WaitingRoom', [
            'lobbyCode' => $lobbyCode,
            'title' => $title,
            'players' => $playerList]);
    }

    public function playerView() {
        $lobbyCode = session('lobbyCode', null);
        $title = session('title', null);
        $lobbyId = session('lobbyId', null);
        if(!$lobbyCode || !$lobbyId){
            return Redirect::route('/');
        }
        $playerList = $this->getPlayerList($lobbyId);
        return Inertia::render('Lobby/WaitingRoom', [
            'lobbyCode' => $lobbyCode,
            'title' => $title,
            'players' => $playerList]);
    }
}
