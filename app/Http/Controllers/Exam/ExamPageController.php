<?php

namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Models\Exam;
use App\Traits\ExamTrait;
use App\Models\Lesson;
use App\Models\History;
use App\Models\Choice;
use App\Models\Time;
use App\Models\TimeStart;
use App\Models\Question;
use App\Models\QuestionData;
use App\Models\Exam_Attachment;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ExamRequest;

use Illuminate\Http\Request;

class ExamPageController extends Controller
{
    use ExamTrait;
    public function examReturn($id){
        $exam = Exam::with('lesson')->with('question', function($question){
            $question->where('remove', null);
            $question->with('questionData', function($questionData){
                $questionData->with('questionAttachment');
                $questionData->where('remove', null);
            });
            $question->with('choice', function($choice){
                $choice->with('keys');
                $choice->with('attachment');
                $choice->where('remove', null);
            });
        })->with([
            'time'=>function($time){
                $time->with('startTime');
                $time->where('remove', null);
            }
        ])->find($id);

        return $exam;
    }
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

        $time = Time::create([
            'duration'=>30,
            'time_start_id'=>null,
            'start'=>null,
            'remove'=>null,
        ]);
        $add = Exam::create([
            'exam'=>$request->examName,
            'uni_code'=>$uniCode,
            'lesson_id'=>$lesson->id,
            'time_id'=>$time->id,
            'choice'=>2,
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
        for ($i=0; $i < 2; $i++) { 
            $choice = Choice::create([
                "question_id"=>$question->id,
                "choice"=>null,
                "remove"=>null,
                "choice_attachment"=>null
            ]);
        }
        $get = Exam::where('user_id', Auth::user()->id)->with('attachment')->with('lesson')->orderBy('id', 'DESC')->get();
        return $get;
    }
    public function selected($id){
        $exam = $this->examReturn($id);
        // dd($exam);
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
            'lessonOther'=>$lessonOther,
            'problem'=>$this->examNotReady($id)
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
        }else{
            $updateExam = Exam::find($id)->update([
                'lesson_id'=>$request->id,
                'other'=>null,
            ]);
        }
        $now = Exam::with('lesson')->find($id);
        return [
            'now'=>$this->examReturn($id),
            'problem'=>$this->examNotReady($id)
        ];
    }
    public function examKey(Request $request, $id){
        $request->validate([
            'val'=>"max:50"
        ]);
        $exam = Exam::find($id);

        if($request->post('val') == ""){
            $exam->update([
                'key'=>null
            ]);
        }else{
            $exam->update([
                'key'=>$request->post('val')
            ]);
        }
        return $exam;
    }
    public function timeDuration(Request $request, $id){
        $exam = Exam::find($id);

        $time = Time::find($exam->time_id);
        $timeUpdate = Time::find($exam->time_id)->update([
            'remove'=>'remove'
        ]);

        $create = Time::create([
            'duration'=>$request->post('val'),
            'time_starts_id'=>$time->time_starts_id,
            'start'=>$time->start,
            'remove'=>null,
        ]);

        $exam->update([
            'time_id'=>$create->id
        ]);

        return [
            'exam'=>$this->examReturn($id),
            'problem'=>$this->examNotReady($id)
        ];
    }
    public function startExamCheckBox(Request $request, $id){
        $exam = Exam::find($id);

        $time = Time::with('startTime')->find($exam->time_id);
        $timeUpdate = Time::find($exam->time_id)->update([
            'remove'=>'remove'
        ]);
        $a = $request->post('val') ? 'checked' : null;
        
        if ($request->post('val')) {
            $timeStart = TimeStart::create([
                'start'=>$time->time_starts_id != null ? $time->start->start : null 
            ]);
            $create = Time::create([
                'duration'=>$time->duration,
                'time_starts_id'=>$timeStart->id,
                'start'=>$a,
                'remove'=>null,
            ]);
        }else{
            $create = Time::create([
                'duration'=>$time->duration,
                'time_starts_id'=>null,
                'start'=>$a,
                'remove'=>null,
            ]);
        }
        $exam->update([
            'time_id'=>$create->id
        ]);
        return [
            'exam'=>$this->examReturn($id),
            'problem'=>$this->examNotReady($id)
        ];
    }
    public function timeStart(Request $request, $id){
        // dd($request->all());
        $explode = explode("T", $request->post('val'));
        $start = collect($explode)->join(" ");

        $exam = Exam::find($id);
        $time = Time::find($exam->time_id);
        $timeStart = TimeStart::find($time->time_starts_id)->update([
            'start'=>strtotime($start)*1000
        ]);

        return [
            'exam'=>$this->examReturn($id),
            'problem'=>$this->examNotReady($id)
        ];
    }
}
