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
use App\Events\StartGame;
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

    private function addPlayer($nickname, $lobby_id, $session_id, $is_host){
        $find_session = LobbyPlayer::query()->where('lobby_id', $lobby_id)->where('session_id', session('_token'))->get()->first();
        if ($find_session == null){ // if user with this session doesnt exist yet
            $player = new LobbyPlayer();
            $player->nickname = $nickname;
            $player->lobby_id = $lobby_id;
            $player->session_id = $session_id;
            $player->is_host = $is_host;
            $player->save();
        }
    }
    
    public function create(Request $request)
    {
        $data = $request->validate([
            'id' => 'required'
        ]);
        $user = Auth::user();
        $quiz_id = $data['id'];
        $code = $this->uniqueCodeGenerator();
        if($code == -1){
            return Redirect::route('/');
        }
        $lobby = Lobby::create([
            'code' => $code,
            'quiz_host' => $user->id,
            'quiz_id' => $quiz_id
        ]);
        $quizzes = Quiz::where('id', $quiz_id)->get();
        if($quizzes->isEmpty()){
            return Redirect::route('/');
        }
        $quiz = $quizzes[0];

        $this->addPlayer($user->username, $lobby['id'], session('_token'), true);
        event(new JoinLobby($user->username, $code, $this->getPlayerList($lobby['id'])));

        return Redirect::route('lobby.play')->with(['lobbyId' => $lobby['id'],'lobbyCode'=> $lobby['code'], 'title' => $quiz['title']]);
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

        $this->addPlayer($name, $lobby_id, session('_token'), false);
        event(new JoinLobby($name, $code, $this->getPlayerList($lobby_id)));

        return Redirect::route('lobby.play')->with(['lobbyId' => $lobby['id'],'lobbyCode'=> $lobby['code'], 'title' => $quiz['title']]);
    }

    public function leave(Request $request){
        $data = $request->validate([
            'code' => 'required',
        ]);
        $code = $data['code'];
        $lobbies = Lobby::where('code', $code)->get();
        if($lobbies->isEmpty()){
            return Redirect::route('/');
        }
        $player = LobbyPlayer::query()->where('lobby_id', $lobbies[0]['id'])->where('session_id', session('_token'))->get()->first();
        if ($player != null && $player->is_host){
            Lobby::query()->where('id', $lobbies[0]['id'])->delete();
        }
        LobbyPlayer::query()->where('lobby_id', $lobbies[0]['id'])->where('session_id', session('_token'))->delete();
        event(new JoinLobby('', $code, $this->getPlayerList($lobbies[0]['id'])));
        return Redirect::route('/');
    }

    public function playerView() {
        $lobbyCode = session('lobbyCode', null);
        $title = session('title', null);
        $lobbyId = session('lobbyId', null);
        $find_player = LobbyPlayer::query()->where('lobby_id', $lobbyId)->where('session_id', session('_token'))->get()->first();
        if(!$lobbyCode || !$lobbyId || $find_player == null){
            return Redirect::route('/');
        }
        
        $playerList = $this->getPlayerList($lobbyId);
        return Inertia::render('Lobby/WaitingRoom', [
            'lobbyCode' => $lobbyCode,
            'title' => $title,
            'players' => $playerList,
            'selected_player' => $find_player["id"],
            'is_host' => $find_player["is_host"],
        ]);
    }

    public function startGame(Request $request) {
        $data = $request->validate([
            'code' => 'required'
        ]);
        $code = $data['code'];
        
        $lobbies = Lobby::where('code', $code)->get();
        if($lobbies->isEmpty()){
            return Redirect::route('/');
        }
        $lobby_id = $lobbies[0]['id'];
        $find_player = LobbyPlayer::query()->where('lobby_id', $lobby_id)->where('session_id', session('_token'))->get()->first();
        
        if ($find_player == null || !$find_player->is_host){
            return Redirect::route('/');
        }

        event(new StartGame($data['code']));
        return Redirect::route('game.go')->with(['lobbyCode' => $data['code']]);
    }

    public function gameView() {

        return Inertia::render('Game/GameRoom');
    }

}
