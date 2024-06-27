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
use App\Models\Question;
use App\Models\Option;
use App\Events\JoinLobby;
use App\Events\StartGame;
use App\Events\UpdatePlayer;
use Inertia\Inertia;

class LobbyController extends Controller
{
    private function uniqueCodeGenerator(){
        $minCode = 1;
        $maxCode = 1000;

        $maxAttempts = 100;
        for($i = 0; $i < $maxAttempts; $i++){
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

    private function getQuestionList($quiz_id){
        return Question::where('quiz_id', $quiz_id)->get();
    }

    private function findPlayer($lobby_id){
        return LobbyPlayer::query()->where('lobby_id', $lobby_id)->where('session_id', session('_token'))->get()->first();
    }

    private function addPlayer($nickname, $lobby_id, $is_host){ 
        $find_session = $this->findPlayer($lobby_id);
        if ($find_session == null){ // if user with this session doesnt exist yet
            $player = new LobbyPlayer();
            $player->nickname = $nickname;
            $player->lobby_id = $lobby_id;
            $player->session_id = session('_token');
            $player->is_host = $is_host;
            $player->score = 0;
            $player->save();
        }
    }

    private function getLobbyInfo($code){
        $lobbies = Lobby::where('code', $code)->get();
        if($lobbies->isEmpty()) return -1;
        $lobby = $lobbies[0];
        $quiz_id = $lobby['quiz_id'];
        $quizzes = Quiz::where('id', $quiz_id)->get();
        if($quizzes->isEmpty()) return -1;
        $quiz = $quizzes[0];
        return ['lobby' => $lobby, 'quiz' => $quiz];
    }

    private function getNextQuestion($code){ // and increment next question counter
        $info = $this->getLobbyInfo($code);
        $questions = $this->getQuestionList($info['quiz']['id']);
        $next_ind = $info['lobby']['next_question'];
        Lobby::query()->where('id', $info['lobby']['id'])->increment('next_question');
        if ($next_ind >= count($questions)) return -1;
        return $questions[$next_ind];
    }

    private function getCurrentQuestion($code){
        $info = $this->getLobbyInfo($code);
        $questions = $this->getQuestionList($info['quiz']['id']);
        $next_ind = $info['lobby']['next_question'];
        return $questions[$next_ind-1];
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

        // delete all other quizes that this user is hosting
        $prev_lobbies = Lobby::query()->where('quiz_host', $user->id)->get(); 
        Lobby::query()->where('quiz_host', $user->id)->delete();
        foreach($prev_lobbies as $lob) event(new JoinLobby('', $lob['code'], $this->getPlayerList($lob['id'])));

        $lobby = Lobby::create([
            'code' => $code,
            'quiz_host' => $user->id,
            'quiz_id' => $quiz_id,
            'status' => 0,
            'next_question' => 0,
        ]);
        $quizzes = Quiz::where('id', $quiz_id)->get();
        if($quizzes->isEmpty()){
            return Redirect::route('/');
        }
        $quiz = $quizzes[0];

        $this->addPlayer($user->username, $lobby['id'], true);
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

        $info = $this->getLobbyInfo($code);
        if ($info == -1) return Redirect::route('/');

        $this->addPlayer($name, $info["lobby"]["id"], false);
        event(new JoinLobby($name, $code, $this->getPlayerList($info["lobby"]["id"])));
        
        return Redirect::route('lobby.play')->with(['lobbyId' => $info["lobby"]["id"],'lobbyCode'=> $code, 'title' => $info['quiz']['title']]);
    }

    public function leave(Request $request){
        $data = $request->validate([
            'code' => 'required',
        ]);
        $code = $data['code'];
        $info = $this->getLobbyInfo($code);
        if ($info == -1) return Redirect::route('/');

        $player = $this->findPlayer($info["lobby"]["id"]);
        if ($player != null && $player->is_host){ // if host leaves
            Lobby::query()->where('id', $info["lobby"]["id"])->delete();
        }

        LobbyPlayer::query()->where('lobby_id', $info['lobby']['id'])->where('session_id', session('_token'))->delete();
        event(new JoinLobby('', $code, $this->getPlayerList($info["lobby"]["id"])));
        return Redirect::route('/');
    }

    public function playerView() {
        $lobbyCode = session('lobbyCode', null);
        $title = session('title', null);
        $lobbyId = session('lobbyId', null);
        $player = $this->findPlayer($lobbyId);

        if(!$lobbyCode || !$lobbyId || $player == null) return Redirect::route('/');
        
        $playerList = $this->getPlayerList($lobbyId);
        return Inertia::render('Lobby/WaitingRoom', [
            'lobbyCode' => $lobbyCode,
            'title' => $title,
            'players' => $playerList,
            'selected_player' => $player["id"],
            'is_host' => $player["is_host"],
        ]);
    }

    public function startGame(Request $request) {
        $data = $request->validate([
            'code' => 'required'
        ]);
        $code = $data['code'];
        $info = $this->getLobbyInfo($code);
        if ($info == -1) return Redirect::route('/');

        $player = $this->findPlayer($info["lobby"]["id"]);
        
        if ($player == null || !$player->is_host){
            return Redirect::route('/');
        }
        $nxt_question = $this->getNextQuestion($code);
        if (gettype($nxt_question) == 'object'){
            event(new StartGame($data['code'], 1, $nxt_question));
        }
        return Redirect::route('lobby.play')->with(['lobbyId' => $info["lobby"]["id"],'lobbyCode'=> $code, 'title' => $info["quiz"]["title"]]);
    }

    public function submitAnswer(Request $request){
        $data = $request->validate([
            'code' => 'required',
            'answer_index' => 'required',
        ]);
        $code = $data['code'];
        $ans_ind = $data['answer_index'];
        $info = $this->getLobbyInfo($code);
        $player = $this->findPlayer($info['lobby']['id']);
        $question = $this->getCurrentQuestion($code);

        $options = Option::query()->where('question_id', $question['id'])->get();
        if ($options[$ans_ind]['correct'] == 1) {
            LobbyPlayer::query()->where('id', $player['id'])->increment('score', 1);
        }
        
        event(new UpdatePlayer($player['id'], $question));

        return Redirect::route('lobby.play')->with(['lobbyId' => $info["lobby"]["id"],'lobbyCode'=> $code, 'title' => $info["quiz"]["title"]]);
    }

}
