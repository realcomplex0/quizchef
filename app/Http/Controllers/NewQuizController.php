<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Option;

class NewQuizController extends Controller
{
    public function load_quiz($id){
        return Quiz::with('questions.options')->where('id', $id)->first();
    }
    public function view($id = null){
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        return Inertia::render('Dashboard/NewQuiz', [
            'quiz' => $this->load_quiz($id)
        ]);
    }
    public function update(Request $request, $id = null){
        if(!Auth::check()){
            return redirect()->route('login');
        }
        $user_id = Auth::id();
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'questions' => 'required|array',
            'questions.*.title' => 'required|string',
            'questions.*.id' => 'numeric|min:0',
            'questions.*.options' => 'required|array',
            'questions.*.options.*.title' => 'required|string',
            'questions.*.options.*.id' => 'numeric|min:0'
        ]);
        if($id == null){
            $quiz = new Quiz();
            $quiz->user_id = $user_id;
            $quiz->plays = 0;
            $quiz->questions = rand(0, 10);
        }
        else{
            $quiz = Quiz::findOrFail($id);
        }
        $quiz->title = $validatedData['title'];
        $quiz->save();
        foreach ($validatedData['questions'] as $questionObject) {
            if(!isset($questionObject['id'])){
                $question = new Question();
            }
            else{
                $question = Question::findOrFail($questionObject['id']);
            }
            $question->quiz_id = $quiz->id;
            $question->title = $questionObject['title'];
            $question->index = 0;
            $question->save();
            foreach($questionObject['options'] as $optionObject) {
                if(!isset($optionObject['id'])){
                    $option = new Option();
                }
                else{
                    $option = Option::findOrFail($optionObject['id']);
                }
                $option->question_id = $question->id;
                $option->title = $optionObject['title'];
                $option->index = 0;
                $option->save();
            }
        }
        return redirect()->route('quiz.view', ['id' => $quiz->id]);
    }
    public function destroy_question($id, $question_id){
        if(!Auth::check()){
            return redirect()->route('login');
        }
        try{
            $question = Question::findOrFail($question_id);
            $question->delete();
            return redirect()->route('quiz.view', ['id' => $id]);
        } catch (ModelNotFoundException $exception) {
            return redirect()->route('quiz.view', ['id' => $id]);
        }
    }
    public function destroy($id) {
        if(!Auth::check()){
            return redirect()->route('login');
        }
        $quiz = Quiz::findOrFail($id);
        $quiz->delete();
    }
}
