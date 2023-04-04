<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Traits\ExamTrait;
use App\Models\User;
use App\Models\History;
use App\Models\Lesson;
use App\Models\Exam;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    use ExamTrait;
    public function point(){
        return User::find(Auth::user()->id)->point;
    }
    public function resultExam(){
        return History::where('user_id',Auth::user()->id)->get()->count();
    }
    public function authNow(){
        return User::with('point')->find(Auth::user()->id);
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
        return rand(1, $lesson->count());
    }
    public function seconds(){
        $calculation = $this->calculation(Auth::user()->id);
        $calculation->sortDesc();
        $reject = $this->favoriteProsess(Auth::user()->id);
        $remove = $calculation->reject(function($value, $key) use ($reject){
            return $key == $reject;
        });
        $rejectThen = collect([]);
        foreach($remove as $key => $then){
            $rejectThen->push($key);
        }
        if ($rejectThen->count()<=5) {
            for(;;){
                $index = $this->lessonRand($rejectThen);
                $rejectThen->contains($index) == false ? $rejectThen->push($index) : $this->lessonRand($rejectThen);
                if ($rejectThen->count()>=4) {
                    break;
                }
            }
        }
        $collect = collect([]);
        $userIdArr = collect([Auth::user()->id]);
        $history = History::get();
        foreach($rejectThen as $index){
            $lesson = collect(["lesson"=>"", "data"=>collect([])]);
            $search = Exam::where('lesson_id', $index)
            ->whereNotIn('id', $history->pluck('exam_id'))
            ->whereNotIn('user_id', $userIdArr)
            ->with('attachment')
            ->with('user')
            ->with('lesson')->get();
            $first = Exam::where('lesson_id', $index)
            ->whereNotIn('id', $history->pluck('exam_id'))
            ->with('lesson')
            ->with('user')
            ->with('attachment')->first();
            
            if(!$search->isEmpty()){
                $lesson['data']->push(...$search);
                $lesson['lesson'] = $first->lesson->lesson;
                $collect->push($lesson);
            }
        }
        return $collect;
    }
    public function dashboard(Request $request){
        return Inertia::render('Dashboard',[
            'point' => $this->point(),
            'authNow' => $this->authNow(),
            'resultExam' => $this->resultExam(),
            'favorite' => $this->favorite(),
            'recommendations'=>$this->recommendations(),
            'seconds'=>$this->seconds(),
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
