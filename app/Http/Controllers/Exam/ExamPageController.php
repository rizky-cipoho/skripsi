<?php

namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Models\Exam;
use App\Traits\ExamTrait;
use App\Models\Lesson;
use App\Models\History;
use App\Models\Question;
use App\Models\QuestionData;
use App\Models\Exam_Attachment;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ExamRequest;

use Illuminate\Http\Request;

class ExamPageController extends Controller
{
    use ExamTrait;
    public function myExam(){
        $lesson = Lesson::get();
        $exam = Exam::where('user_id', Auth::user()->id)->with('attachment')->with('lesson')->orderBy('id', 'DESC')->get();
        return Inertia::render('exam/exam',[
            "exams"=>$exam,
            "lessons"=>$lesson
        ]);
    }
    public function index($id){
        $check = Exam::where('id', $id)->first();
        return $check == null ? abort(404) : Inertia::render('exam/exam');
    }
    public function add(ExamRequest $request){
        $request->validate([
            'examName'=>'required'
        ]);
        $exam = Exam::get()->pluck('uni_code');
        $uniCode = "";
        for(;;){
            $uniCode = $request->uniCode(6);
            if ($exam->contains($uniCode) == false) {
                break;
            }
        }
        $lesson = Lesson::where('lesson', 'Lainnya')->first();
        $add = Exam::create([
            'exam'=>$request->examName,
            'uni_code'=>$uniCode,
            'lesson_id'=>$lesson->id,
            'user_id'=>Auth::user()->id,
        ]);
        $attachment = Exam_Attachment::create([
            'exam_id' => $add->id,
            'filename'=>"rasberry",
            'path'=>"/image/",
            'size'=>"4000",
            'type'=>".jpg",
            'image' => "/image/rasberry.jpg",
        ]);
        $question = Question::create([
            'exam_id'=>$add->id
        ]);
                $questionData = QuestionData::create([
            'question_id'=>$question->id,
            'type'=>'paragraph',
            'data'=>""
        ]);
        $get = Exam::where('user_id', Auth::user()->id)->with('attachment')->with('lesson')->orderBy('id', 'DESC')->get();
        return $get;
    }
    public function selected($id){
        $exam = Exam::with('lesson')->find($id);
        $recomendation = $this->recommendations();
        $lessonNotIn = collect(['Lainnya']);
        $lesson = Lesson::whereNotIn('lesson', $lessonNotIn)->get();
        $lessonOther = Lesson::where('lesson', 'Lainnya')->first();
        $historyCount = History::where('exam_id', $exam->id)->count();
        // $historyCount == 0 ? $historyCount = 0 : $historyCount;
        return Inertia::render('exam/examSelected',[
            'exam'=>$exam,
            'recommendations'=>$recomendation,
            'lesson'=>$lesson,
            'historyCount'=>$historyCount,
            'lessonOther'=>$lessonOther
        ]);
    }
    public function changeExamName(ExamRequest $request, $id){
        $request->validate([
            'examName'=>'required'
        ]);
        $update = Exam::find($id)->update([
            'exam'=>$request->examName
        ]);
        $now = Exam::find($id);
        return $now;
    }
    public function changeExamLesson(ExamRequest $request, $id){
        $lesson = Lesson::find($request->id);
        if ($request->other != null && $lesson->lesson == 'Lainnya') {
            $request->validate([
                'other'=>'required'
            ]);
            $updateExam = Exam::find($id)->update([
                'lesson_id'=>$request->id,
                'other'=>$request->other
            ]);
            $now = Exam::with('lesson')->find($id);
            return $now;
        }else{
            $updateExam = Exam::find($id)->update([
                'lesson_id'=>$request->id,
                'other'=>null,
            ]);
            $now = Exam::with('lesson')->find($id);
            return $now;
        }
    }
}
