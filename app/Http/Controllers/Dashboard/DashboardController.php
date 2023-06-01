<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Traits\ExamTrait;
use App\Models\User;
use App\Models\History;
use App\Models\Lesson;
use App\Models\Session;
use App\Models\Exam;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    use ExamTrait;
    public function resultExam(){
        return History::where('user_id', Auth::user()->id)->get()->count();
    }
    public function authNow(){
        return User::find(Auth::user()->id);
    }
    
    public function calculation($id){
        $favorites = History::where('user_id', $id)->get();
        $favoriteArr = collect([]);
        foreach($favorites as $favoriteData){
            $favoriteArr->push($favoriteData->exam);
        }
        $favoriteCountBy = $favoriteArr->countBy('lesson_id');
        return $favoriteCountBy;
    }
    public function lessonRand($removeData){
        $lesson = Lesson::get();
        return rand(0, $lesson->count()-1);
    }
    public function seconds($recommend){
        $calculation = $this->calculation(Auth::user()->id);
        $calculation->sortDesc();
        $reject = $this->favoriteProsess(Auth::user()->id);
        $remove = $calculation->reject(function($value, $key) use ($reject){
            return $key == $reject;
        });
        $rejectThen = collect([]);

        $lesson = Lesson::where('lesson', $recommend)->first();
        $lessonGet = Lesson::get();
        foreach($remove as $key => $then){
            $rejectThen->push($key);
        }
        if ($rejectThen->count()<=5) {
            for(;;){
                $index = $this->lessonRand($rejectThen);
                $lessonUnit = $lessonGet[$index];

                if ($recommend != 'Semuanya') {
                    $rejectThen->contains($lessonUnit->id) == false && $lesson->id != $lessonUnit->id ? $rejectThen->push($lessonUnit->id) : $this->lessonRand($rejectThen);
                    if ($rejectThen->count()>=4) {
                        break;
                    }
                }else{
                    $rejectThen->contains($lessonUnit->id) == false ? $rejectThen->push($lessonUnit->id) : $this->lessonRand($rejectThen);
                    if ($rejectThen->count()>=4) {
                        break;
                    }
                }
            }
        }
        $collect = collect([]);
        $userIdArr = collect([Auth::user()->id]);
        $history = History::where('user_id', Auth::user()->id)->get();

        $ages = collect([]);
        $now = date('Y');
            // dump((int)$now - 10);
        $sd = collect([]);
        $smp = collect([]);
        $sma = collect([]);
        $nowCountSD = (int)$now - 5;
        $nowCountSMP = (int)$now - 16;
        $nowCountSMA = (int)$now - 14;
        $nowCountMahasiswa = (int)$now - 17;
        for($i = 0;$i < 6; $i++){
            $nowCountSD--;
            $sd->push($nowCountSD);
        }
        for($i = 0;$i < 4; $i++){
            $nowCountSMP++;
            $smp->push($nowCountSMP);
        }
        for($i = 0;$i < 6; $i++){
            $nowCountSMA--;
            $sma->push($nowCountSMA);
        }

        $userY = date('Y', Auth::user()->birth/1000);
        if ($sd->contains($userY)) {
            $ages->push('SD');
            if ($smp->contains($userY)) {
                $ages->push('SMP');
            }
        }elseif($smp->contains($userY)){
            $ages->push('SMP');
            if ($sma->contains($userY)) {
                $ages->push('SMA');
            }
        }elseif($sma->contains($userY)){
            $ages->push('SMA');
            if ($nowCountMahasiswa <= $userY) {
                $ages->push('Mahasiswa');
            }
        }else{
            if ($userY <= $nowCountMahasiswa) {
                $ages->push('Mahasiswa');
            }else{
                $ages->push('SD');
            }
        }

        foreach($rejectThen as $index){
            $lesson = collect(["lesson"=>"", "data"=>collect([])]);
            $search = Exam::where('lesson_id', $index)
            ->where('remove',null)
            ->whereNotIn('id', $history->pluck('exam_id'))
            ->whereNotIn('user_id', $userIdArr)
            ->with('attachment')
            ->with('user')
            ->with('lesson')
            ->whereIn('tier',$ages)->get();
            $first = Exam::where('lesson_id', $index)
            ->whereNotIn('id', $history->pluck('exam_id'))
            ->with('lesson')
            ->with('user')
            ->with('attachment')
            ->first();

            
            if(!$search->isEmpty()){
                $lesson['data']->push(...$search);
                $lesson['lesson'] = $first->lesson->lesson;
                $collect->push($lesson);
            }
        }
        // dd($collect);

        return $collect;
    }
    public function dashboard(Request $request){
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
        ])->where('user_id', Auth::user()->id)->get();
        // dd($session);
        return Inertia::render('Dashboard',[
            'authNow' => $this->authNow(),
            'resultExam' => $this->resultExam(),
            'favorite' => $this->favorite(),
            'recommendations'=>$this->recommendations(),
            'seconds'=>$this->seconds($this->favorite()),
            'session'=>$session,
        ]);
    }
    public function search(Request $request){
        $exam = Exam::where('uni_code', $request->dataSearch)->first();
        if ($exam == null) {
            return response()->json([
                'message'=> "Kode Salah"
            ]);
        }else{
            return $exam;
        }

    }
}
