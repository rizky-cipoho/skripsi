<?php

namespace App\Http\Controllers\exam\else;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\User;
use App\Models\Session;
use App\Models\Question;
use App\Models\HistoryQuestionData;
use App\Models\HistoryQuestion;
use App\Models\HistoryChoice;
use App\Traits\ExamTrait;
use App\Models\History;
use App\Models\Pin;
use App\Models\Answer;
use App\Models\Choice;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ExamElseController extends Controller
{
    use ExamTrait;
    public function examInfo($id){
        $exam = Exam::with('lesson')->with([
            'question'=>function($val){
                $val->where('remove', null);
            }
        ])
        ->with('user')
        ->with([
            'time'=>function($time){
                $time->with('startTime');
            }
        ])
        ->find($id);
        $auth = User::find(Auth::user()->id);
        return Inertia::render('exam/examElse/index',[
            'exam'=>$exam,
            'auth'=>$auth,
            'problem'=>$this->examNotReady($id)
        ]);
    }
    public function createSession($id){

        // dd(strtotime(date('d-m-Y H:i:s',strtotime("+10 minutes")))*1000);
        $exam = Exam::with([
            'question'=>function($val){
                $val->where('remove', null);
                $val->with([
                    'questionData'=>function($val2){
                        $val2->where('remove', null);
                        $val2->with('questionAttachment');
                    }
                ]);
                $val->with([
                    'choice'=>function($val3){
                        $val3->with('keys');
                        $val3->with('attachment');
                        $val3->where('remove', null);
                    }

                ]);
            }
        ])->with([
            'time'=>function($time){
                $time->where('remove',null);
                $time->with('startTime');
            }
        ])->find($id);


        $endtoken = null;
        if($exam->time->start != null){
            $minutes = $exam->time->duration * 60 * 1000;
            $endtoken = date('d-m-Y H:i', ($exam->time->startTime->start+$minutes)/1000);
        }else{
            $endtoken = date('d-m-Y H:i', (round(microtime(true) * 1000)+($exam->time->duration * 60 * 1000))/1000);
        }

        $currentMilis = strtotime($endtoken)*1000;

        if ($this->examNotReady($id)->count() != 0) {
            return back()->with('notification', 'Data Ujian ini Belum Lengkap');
        }
        $session = explode("-", Str::uuid());
        $session = join('', $session);

        $history = History::create([
            'exam_id'=>$id,
            'user_id'=>Auth::user()->id,
        ]);

        $createSession = Session::create([
            'token'=>$session,
            'endToken'=>$currentMilis,
            'user_id'=>Auth::user()->id,
            'history_id'=>$history->id,
            'exam_id'=>$id,
            'over'=>null
        ]);

        foreach($exam->question as $key => $question){
            $historyQuestion = HistoryQuestion::create([
                'history_id'=>$history->id
            ]);
            foreach($question->questionData as $questionData){
                $historyQuestionData = HistoryQuestionData::create([
                    'questionData_id'=>$questionData->id,
                    'question_id'=>$question->id,
                    'history_question_id'=>$historyQuestion->id,
                ]);
            }
            foreach($question->choice as $questionChoice){
                $historyQuestionChoice = HistoryChoice::create([
                    'choice_id'=>$questionChoice->id,
                    'question_id'=>$question->id,
                    'history_question_id'=>$historyQuestion->id,
                ]);
            } 
        }
        // return $this->ExamProsess();
        // return redirect()->route('DataSession', ['id'=>$id, 'session'=>$session->id]);
        return redirect()->route('dataSession', ['id' => $id, 'token'=>$createSession->token]);

        
        // $session = join('', $session);
        // dd($session);
    }
    public function dataSession($id, $session){
        $dataSession = $this->sessionData($id, $session);
        return Inertia::render('exam/examElse/examProsess/index', [
            'data'=>$dataSession
        ]);
    }
    public function getDataTokenChoice(Request $request, $id, $session){
        // dd($request->all());
        $choiceHistory = HistoryChoice::find($request->post('choice'));
        $choice = Choice::find($choiceHistory->choice_id);
        $oldAnswer = Answer::where('user_id', Auth::user()->id)->where('history_question_id', $request->post('historyQuestionId'))->first();

        if ($oldAnswer != null) {
            $answer = Answer::find($oldAnswer->id)->update([
                'choice_id'=>$request->post('choice'),
            ]);
        }else{
            $answer = Answer::create([
                'choice_id'=>$request->post('choice'),
                'history_question_id'=>$request->post('historyQuestionId'),
                'user_id'=>Auth::user()->id
            ]);
        }
        return $this->sessionData($id, $session);
    }
    public function tokenOver($id, $token){
        $session = Session::where('token', $token)->first();
        $tokenOver = Session::where('token', $token)->update([
            'over'=>'over'
        ]);

       return redirect()->route('dashboard'); 
    }
    public function sessionData($id, $session){
        $dataSession = Session::where('token', $session)
        ->with([
            'exam'=>function($exam){
                $exam->with([
                    'time'=>function($time){
                        $time->with('startTime');
                    }
                ]);
            }
        ])
        ->with('user')
        ->with([
            'history'=>function($history){
                $history->with([
                    'question'=>function($question){
                        $question->with([
                            'questionData'=>function($questionData){
                                $questionData->with([
                                    'questionData'=>function($questionDataOriginal){
                                        $questionDataOriginal->with('questionAttachment');
                                    }
                                ]);
                            }
                        ]);
                        $question->with([
                            'choice'=>function($choice){
                                $choice->with([
                                    'choice'=>function($choiceOriginal){
                                        $choiceOriginal->with('attachment');
                                    }
                                ]);
                            }
                        ]);
                        $question->with('answer');
                        $question->with('pin');
                    }
                ]);
            }
        ])
        ->first();
        return $dataSession;
    }
    public function tokenPin(Request $request, $id, $session){
        // dd($request->post('val')['id']);
        $searchPin = Pin::where('history_question_id', $request->post('val')['id'])->first();
        if ($searchPin == null) {
            $pin = Pin::create([
                'history_question_id'=>$request->post('val')['id'],
                'remove'=>null
            ]);
        }else{
            $pin = Pin::find($searchPin->id)->delete();
        }
        return $this->sessionData($id, $session);
    }
}
