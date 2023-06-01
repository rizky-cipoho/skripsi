<?php

namespace App\Http\Controllers\exam\else;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\User;
use App\Models\Session;
use App\Models\Rand;
use App\Models\Point;
use App\Models\Result;
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
        $session = Session::where('exam_id', $id)->with([
            'history'=>function($history){
                $history->with('user');
                $history->with('question');
            }
        ])->get();
        $history = collect([]);
        $temporary = collect([]);
        $arrValidate = collect([]);
        foreach($session as $data){
            if (!$arrValidate->contains($data->history->user->id)){
                $history->push($data);
                $arrValidate->push($data->history->user->id);
            }
        }
        // $history->sortBy('point')->reverse();
        foreach($history->sortBy('point')->reverse() as $data){
            $temporary->push($data->id);
        }
        $sessionResult = collect([]);
        foreach($temporary as $data){
            $sessionTemporary = Session::with([
                'history'=>function($history){
                    $history->with('user');
                    $history->with('question');
                }
            ])->find($data);
            $sessionResult->push($sessionTemporary);
        }
        // dd($sessionResult);
        $auth = User::find(Auth::user()->id);
        return Inertia::render('exam/examElse/index',[
            'exam'=>$exam,
            'auth'=>$auth,
            'history'=>$sessionResult,
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
            'point'=>0,
            'over'=>null,
            'rate'=>null
        ]);

        // return $this->ExamProsess();
        // return redirect()->route('DataSession', ['id'=>$id, 'session'=>$session->id]);
        return redirect()->route('dataSession', ['id' => $id, 'token'=>$createSession->token]);

        
        // $session = join('', $session);
        // dd($session);
    }
    public function dataSession($id, $session){
        $questionCheck = Session::where('token',$session)->with([
            'history'=>function($history){
                $history->with('question');
            }
        ])->first();
            // dd($questionCheck->history->question->isEmpty());

        if ($questionCheck->history->question->isEmpty()) {
            $create = $this->fisherYatesLCG($id, $session);
        }
        $dataSession = $this->sessionData($id, $session);

        return Inertia::render('exam/examElse/examProsess/index', [
            'data'=>$dataSession
        ]);
    }
    public function getDataTokenChoice(Request $request, $id, $session){
        $choiceHistory = HistoryChoice::find($request->post('choice'));
        $choice = Choice::find($choiceHistory->choice_id);
        $oldAnswer = Answer::where('user_id', Auth::user()->id)->where('history_question_id', $request->post('historyQuestionId'))->first();


        if ($oldAnswer != null) {
            $answer = Answer::find($oldAnswer->id)->update([
                'choice_id'=>$request->post('choice'),
            ]);

        }else{
        // dd(Auth::user()->id);

            $answer = Answer::create([
                'choice_id'=>$request->post('choice'),
                'history_question_id'=>$request->post('historyQuestionId'),
                'user_id'=>Auth::user()->id,
            ]);

        }

        return $this->sessionData($id, $session);
    }
    public function tokenOver(Request $request, $id, $token){
        $session = Session::where('token', $token)->first();
        if ($session->over != 'over') {
            $tokenOver = Session::where('token', $token)->update([
            'over'=>'over'
        ]);
        $tokenData = $this->dataToken($id, $token);

        $historyExam = History::where('exam_id', $session->exam_id)->get();
        $examCount = $historyExam->count();
        $pointExam = 0;
        $true = 0;
        foreach($tokenData->history->question as $data){
            $question = Question::find($data->question->id); 
            $point = Point::where('question_id', $question->id)->first(); 
            if ($data->answer != null) {
                foreach($data->choice as $choice){
                    if ($choice->choice->keys != null) {
                        if ($data->answer->choice_id == $choice->id) {
                            $true++;
                            $result = Result::create([
                                'question_id'=>$data->question_id,
                                'exam_id'=>$session->exam_id,
                                'user_id'=>Auth::user()->id,
                                'session_id'=>$session->id,
                                'truth'=>'true',
                            ]);
                        }else{
                            $result = Result::create([
                                'question_id'=>$data->question_id,
                                'exam_id'=>$session->exam_id,
                                'user_id'=>Auth::user()->id,
                                'session_id'=>$session->id,
                                'truth'=>null,
                            ]);
                        }
                    }
                }

            }else{
                $result = Result::create([
                    'question_id'=>$data->question_id,
                    'exam_id'=>$session->exam_id,
                    'user_id'=>Auth::user()->id,
                    'session_id'=>$session->id,
                    'truth'=>null,
                ]);
            }
            

            $historyValidate = History::where('user_id', Auth::user()->id)->where('exam_id', $session->exam_id)->get();
            // dd($historyValidate);
            if ($historyValidate->count() == 1) {
                if ($examCount >= 1) {

                    $countTrue = collect([]);
                    $countFalse = collect([]);

                    $questionResult = Result::where('question_id', $data->question->id)->get();
                    foreach($questionResult as $unitGenerale){
                        if ($unitGenerale->truth == 'true') {
                            $countTrue->push($unitGenerale);
                        }else{
                            $countFalse->push($unitGenerale);
                        }
                    }
                    $pointNow = 0;

                    $calculationResult = $countTrue->count() + $countFalse->count();
                    $calculation = ($countTrue->count()/$calculationResult)*100;

                    // dump($calculation);
                    // dump("ini calculation");
                    // dump($calculationResult);
                    // dump("ini calculationResult");
                    // dump($countTrue);
                    // dump("ini countTrue");
                    // dump($countFalse);
                    // dump("ini countFalse");
                    // dump($c);
                    // dump("ini c");

                    if (80 <= $calculation) {
                        $pointNow = 1;
                    }else if (60 <= $calculation) {
                        $pointNow = 5;
                    }else if (40 <= $calculation) {
                        $pointNow = 10;
                    }else if (20 <= $calculation) {
                        $pointNow = 15;
                    }else if(20 > $calculation) {
                        $pointNow = 20;
                    }

                    $pointChange = $point->update([
                        'point'=>$pointNow
                    ]);
                }               
            }
        }

        $rate = ($true * 100)/($tokenData->history->exam->question->count());
        $session->update([
            'rate'=>$rate
        ]);
        }
        return redirect()->route('result', [
            'id'=>$id,
            'token'=>$session->token
        ]); 
    }
    public function result(Request $request, $id, $token){
        $tokenData = $this->dataToken($id, $token);
        $session = Session::with([
            'history'=>function($history){
                $history->with([
                    'question'=>function($question){
                        $question->with([
                            'choice'=>function($choice){
                                $choice->with([
                                    'choice'=>function($choiceOriginal){
                                        $choiceOriginal->with('keys');
                                        $choiceOriginal->with('attachment');
                                    }
                                ]);
                            }
                        ]);
                        $question->with([
                            'question'=>function($questionOriginal){
                                $questionOriginal->with('point');
                            }
                        ]);
                        $question->with('answer');
                        $question->with([
                            'questionData'=>function($questionData){
                                $questionData->with([
                                    'questionData'=>function($questionDataOriginal){
                                        $questionDataOriginal->with('questionAttachment');
                                    }
                                ]);
                            }
                        ]);
                    }
                ]);
            }
        ])->where('token', $token)->first();
        $sessionAll = Session::with([
            'history'=>function($history){
                $history->with([
                    'question'=>function($question){
                        $question->with([
                            'choice'=>function($choice){
                                $choice->with([
                                    'choice'=>function($choiceOriginal){
                                        $choiceOriginal->with('keys');
                                        $choiceOriginal->with('attachment');
                                    }
                                ]);
                            }
                        ]);
                        $question->with([
                            'question'=>function($questionOriginal){
                                $questionOriginal->with('point');
                            }
                        ]);
                        $question->with('answer');
                        $question->with([
                            'questionData'=>function($questionData){
                                $questionData->with([
                                    'questionData'=>function($questionDataOriginal){
                                        $questionDataOriginal->with('questionAttachment');
                                    }
                                ]);
                            }
                        ]);
                    }
                ]);
            }
        ])
        ->where('user_id', Auth::user()->id)
        ->where('exam_id', $id)->get();
        $historyValidate = History::where('user_id', Auth::user()->id)->where('exam_id', $session->exam_id)->get();
        if ($historyValidate->count() > 1) {
            if($session->alert == 'remove'){
                return Inertia::render('exam/examElse/examProsess/result',[
                    'histories'=>$tokenData,
                    'session'=>$session,
                    'sessionAll'=>$sessionAll
                ]);
            }else{
                Session::where('token', $token)->update([
                    'alert'=>'remove'
                ]);
                return Inertia::render('exam/examElse/examProsess/result',[
                    'histories'=>$tokenData,
                    'session'=>$session,
                    'alert'=>'Anda telah mengerjakan ujian ini sebelumnya, karena itu anda tidak mendapatkan point kembali dari ujian ini',
                    'sessionAll'=>$sessionAll
                ]);
            }
        }else{
            Session::where('token', $token)->update([
                'alert'=>'remove'
            ]);
            return Inertia::render('exam/examElse/examProsess/result',[
                'histories'=>$tokenData,
                'session'=>$session,
                'sessionAll'=>$sessionAll
            ]);
        }

    }
    public function dataToken($id, $token){
        $tokenData = Session::with([
            'history'=>function($history){
                $history->with([
                    'question'=>function($question){
                        $question->with([
                            'choice'=>function($choice){
                                $choice->with([
                                    'choice'=>function($choiceOriginal){
                                        $choiceOriginal->with('keys');
                                        $choiceOriginal->with('attachment');
                                    }
                                ]);
                            }
                        ]);
                        $question->with([
                            'question'=>function($questionOriginal){
                                $questionOriginal->with('point');
                            }
                        ]);
                        $question->with('answer');
                        $question->with([
                            'questionData'=>function($questionData){
                                $questionData->with([
                                    'questionData'=>function($questionDataOriginal){
                                        $questionDataOriginal->with('questionAttachment');
                                    }
                                ]);
                            }
                        ]);
                    }
                ]);
            }
        ])->where('token', $token)->first();

        return $tokenData;
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
    public function fisherYatesLCG($id, $session){
        $dataSession = Session::where('token', $session)
        ->with([
            'exam'=>function($exam){
                $exam->with([
                    'time'=>function($time){
                        $time->with('startTime');
                    }
                ]);
                $exam->with([
                    'question'=>function($question){
                        $question->where('remove', null);
                        $question->with('point');
                        $question->with([
                            'questionData'=>function($questionData){
                                $questionData->with('questionAttachment');
                                $questionData->where('remove', null);
                            }
                        ]);
                        $question->with([
                            'choice'=>function($choice){
                                $choice->where('remove',null);
                                $choice->with('attachment');
                            }
                        ]);
                    }
                ]);
            }
        ])
        ->with('user')
        ->first();
        $lcgCollection = collect([...$dataSession->exam->question]); 
        $lcgCollectionSeriesArr = collect([]); 
        $series = collect([]);
        $countLength = 5;
        foreach($lcgCollection as $key=>$data){
            $series->push($data);
            if ($series->count() == $countLength) {
                $lcgCollectionSeriesArr->push($series);
                $series = collect([]);
            }
        }
        if ($series->count() != 0) {
            $lcgCollectionSeriesArr->push($series);
            $series = collect([]);
        }

        $arrLCG = $this->lcg($lcgCollectionSeriesArr);

        $arrFisherYates = $this->fisherYates($arrLCG);
        $generateData = $this->generateData($arrFisherYates, $dataSession, $countLength);

        $token = Session::where('token', $session)->first();
        foreach($generateData as $key => $arr){
            foreach($arr[1] as $question){
                $historyQuestion = HistoryQuestion::create([
                    'history_id'=>$token->history_id,
                    'line'=>$question[0],
                    'lineBlock'=>$arr[0],
                    'question_id'=>$question[1]->id,
                ]);
                foreach($question[1]->questionData as $questionData){
                    $historyQuestionData = HistoryQuestionData::create([
                        'questionData_id'=>$questionData->id,
                        'question_id'=>$question[1]->id,
                        'history_question_id'=>$historyQuestion->id,
                    ]);
                }
                foreach($question[2] as $questionChoice){
                    $historyQuestionChoice = HistoryChoice::create([
                        'choice_id'=>$questionChoice[1]->id,
                        'line'=>$questionChoice[0],
                        'question_id'=>$question[1]->id,
                        'history_question_id'=>$historyQuestion->id,
                    ]);
                }
            } 
        }

        return $generateData;
    }
    public function lcg($arr){
        $count = $arr->count();

        $arrLCG = collect([]);
        $question = collect([]);
        $choice = collect([]);
        $result = collect([]);

        $date = (int)date('d', Auth::user()->birth/1000);
        $month = (int)date('m', Auth::user()->birth/1000);
        $year = (int)date('Y', Auth::user()->birth/1000);

        for ($i=0; $i < $count; $i++) { 
            if($i==0){
                $xn = (1*$year+7)%$count;
                $arrLCG->push($xn);
            }else{
                $xn = (1*$xn+7)%$count;
                $arrLCG->push($xn);
            }
        }

        foreach($arr as $key=>$data){
            $questionTemporary = collect([]);
            foreach ($data as $i=>$dataQuestion) {
                $choiceTemporary = collect([]);
                if($i==0){
                    $xnQuestion = (1*4+7)%$data->count();
                    foreach($dataQuestion->choice as $keyChoice=>$dataChoice){
                        if($keyChoice == 0){
                            $xnChoice = (1*$date+7)%$dataQuestion->choice->count();
                            $choiceTemporary->push($xnChoice);
                        }else{
                            $xnChoice = (1*$xnChoice+7)%$dataQuestion->choice->count();
                            $choiceTemporary->push($xnChoice);
                        }
                    }
                    $questionTemporary->push([$xnQuestion,$choiceTemporary]);
                }else{
                    $xnQuestion = (1*$xnQuestion+7)%$data->count();
                    foreach($dataQuestion->choice as $keyChoice=>$dataChoice){
                        if($keyChoice == 0){
                            $xnChoice = (1*$date+7)%$dataQuestion->choice->count();
                            $choiceTemporary->push($xnChoice);
                        }else{
                            $xnChoice = (1*$xnChoice+7)%$dataQuestion->choice->count();
                            $choiceTemporary->push($xnChoice);
                        }
                    }
                    $questionTemporary->push([$xnQuestion,$choiceTemporary]);
                }
            }
            $question->push($questionTemporary);
        }
        // dd($question);

        foreach($arrLCG as $keyLCG=>$LCG){
            $result->push([$LCG,$question[$keyLCG]]);
        }

        return $result;
    }
    public function fisherYates($arr){
        $result = collect([]);

        $countCollect = collect([]);
        $arrCollect = collect([]);
        $arrQuestion = collect([]);
        $arrChoice = collect([]);

        $stepStartArr = 0;
        $lengthArr = $arr->count()-1;
        // $lengthquestion = $arrQuestion->count()-1;


        for ($i=0; $i < $arr->count(); $i++) { 
            $arrCollect->push($i); 
        }
        while ($stepStartArr != $lengthArr) {
            $rand = rand(0,$lengthArr);
            $step1 = $arrCollect[$lengthArr];
            $arrCollect[$lengthArr] = $arrCollect[$rand];
            $arrCollect[$rand] = $step1;

            $lengthArr = $lengthArr-1;
        }

        foreach($arrCollect as $questionData){
            $questionTemporary = collect([]);
            for ($i=0; $i < $arr[$questionData][1]->count(); $i++) {
                $choiceTemporary = collect([]);
            // dd($arr[$questionData][1][$i][1][1]->count());
                for ($j=0; $j < $arr[$questionData][1][$i][1]->count(); $j++) {
                    $choiceTemporary->push($arr[$questionData][1][$i][1][$j]);
                }
                $lengthChoice = $choiceTemporary->count()-1;
                while ($stepStartArr != $lengthChoice) {
                    $rand = rand(0,$lengthChoice);
                    $step1 = $choiceTemporary[$lengthChoice];
                    $choiceTemporary[$lengthChoice] = $choiceTemporary[$rand];
                    $choiceTemporary[$rand] = $step1;
                    $lengthChoice = $lengthChoice-1;
                }
                $questionTemporary->push([$arr[$questionData][1][$i][0], $choiceTemporary]);
            }

            $lengthQuestion = $questionTemporary->count()-1;
            while ($stepStartArr != $lengthQuestion) {
                $rand = rand(0,$lengthQuestion);
                $step1 = $questionTemporary[$lengthQuestion];
                $questionTemporary[$lengthQuestion] = $questionTemporary[$rand];
                $questionTemporary[$rand] = $step1;
                $lengthQuestion = $lengthQuestion-1;
            }
            $arrQuestion->push($questionTemporary);
        }
        foreach($arrCollect as $keyFisher=>$fisherYates){
            $result->push([$fisherYates,$arrQuestion[$fisherYates]]);
        }
        return $result;
    }  
    public function generateData($arr, $datas, $length){
        $arrData = collect([]);
        $questionTemporary = collect([]);

        $questionInTemporary = collect([]);
        foreach($datas->exam->question as $key=>$data){
            $questionInTemporary->push($data);
            if ($questionInTemporary->count() == $length) {
                $question = collect([]);
                foreach($arr[$questionTemporary->count()][1] as $key2=>$questionData){
                    $count = $questionData[0];
                    $choice = collect([]);
                    foreach($questionData[1] as $key3=>$choiceData){
                        $choice->push([$choiceData, $questionInTemporary[$count]->choice[$choiceData]]);
                    }
                    $question->push([$count, $questionInTemporary[$count], $choice]);
                }
                $questionTemporary->push([$questionTemporary->count(), $question]);
                $questionInTemporary = collect([]);
            }
        }

        if ($questionInTemporary->count() != 0) {
            $question = collect([]);
            foreach($arr[$questionTemporary->count()][1] as $key2=>$questionData){
                $count = $questionData[0];
                $choice = collect([]);


                foreach($questionData[1] as $key3=>$choiceData){
                    $choice->push([$choiceData, $questionInTemporary[$count]->choice[$choiceData]]);
                }
                     // dump($questionInTemporary[$count]);
            // dd($choice);       
                $question->push([$count, $questionInTemporary[$count], $choice]);
            }

            $questionTemporary->push([$questionTemporary->count(), $question]);
            $questionInTemporary = collect([]);
        }

        for($i = 0; $i < $questionTemporary->count(); $i++){
            $count = $arr[$i][0];

            $arrData->push($questionTemporary[$count]);
        }

        return $arrData;
    }
}
