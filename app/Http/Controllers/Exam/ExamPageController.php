<?php

namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Models\Exam;
use App\Traits\ExamTrait;
use App\Models\Lesson;
use App\Models\History;
use App\Models\Point;
use App\Models\Choice;
use App\Models\Time;
use App\Models\TimeStart;
use App\Models\Session;
use App\Models\Question;
use App\Models\QuestionData;
use App\Models\Exam_attachment;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ExamRequest;
use App\Traits\ImageTrait;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SessionExport;

class ExamPageController extends Controller
{
    use ImageTrait;
    use ExamTrait;
    public function examReturn($id){
        $exam = Exam::with('lesson')->with('attachment')->with('question', function($question){
            $question->where('remove', null);
            $question->with('point');
            $question->with('questionData', function($questionData){
                $questionData->with('questionAttachment');
                $questionData->where('remove', null);
            });
            $question->with('choice', function($choice){
                $choice->with([
                    'keys'=>function($keys){
                        $keys->where('remove', null);
                    }
                ]);
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
        $exam = Exam::where('user_id', Auth::user()->id)->where('remove',null)->with('attachment')->with('lesson')->orderBy('id', 'DESC')->get();
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
            'tier'=>'SMA',
            'user_id'=>Auth::user()->id,
        ]);
        $attachment = Exam_attachment::create([
            'exam_id' => $add->id,
            'filename'=>"rasberry.jpg",
            'path'=>"/image/",
            'size'=>"4000",
            'type'=>".jpg",
            'image' => "/image/rasberry.jpg",
        ]);
        $question = Question::create([
            'exam_id'=>$add->id
        ]);
        $point = Point::create([
            'point'=>10,
            'question_id'=>$question->id
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
        $get = Exam::where('user_id', Auth::user()->id)->where('remove', null)->with('attachment')->with('lesson')->orderBy('id', 'DESC')->get();
        return $get;
    }
    public function selected($id){
        $exam = $this->examReturn($id);
        $recomendation = $this->recommendations();
        $lessonNotIn = collect(['Lainnya']);
        $lesson = Lesson::whereNotIn('lesson', $lessonNotIn)->get();
        $lessonOther = Lesson::where('lesson', 'Lainnya')->first();
        $historyCount = History::where('exam_id', $exam->id)->count();
        $session = Session::with([
            'history'=>function($history){
                $history->with([
                    'question'=>function($question){
                        $question->with([
                            'choice'=>function($choice){
                                $choice->with([
                                    'choice'=>function($choiceOriginal){
                                        $choiceOriginal->with([
                                            'keys'=>function($keys){
                                                $keys->where('remove', null);
                                            }
                                        ]);
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
                        $question->with([
                            'answer'=>function($answer){
                                $answer->with([
                                    'choiceHistory'=>function($choice){
                                        $choice->with('choice');
                                    }
                                ]);
                            }
                        ]);
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
        ])->where('exam_id', $id)->get();
        return Inertia::render('exam/examSelected',[
            'exam'=>$exam,
            'recommendations'=>$recomendation,
            'lesson'=>$lesson,
            'historyCount'=>$historyCount,
            'lessonOther'=>$lessonOther,
            'problem'=>$this->examNotReady($id),
            'session'=>$session
        ]);
    }
    public function changeExamName(ExamRequest $request, $id){
        $request->validate([
            'examName'=>'required'
        ]);
        $update = Exam::find($id)->update([
            'exam'=>$request->examName
        ]);
        $now = $this->examReturn($id);
        return $now;
    }
    public function changeExamLesson(ExamRequest $request, $id){
        $lesson = Lesson::find($request->id);
        if ($request->other != null && $lesson->lesson == 'Lainnya') {
            $request->validate([
                'other'=>'required'
            ]);
            // dd($request->all());

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
    public function myExamHistory($id){
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
        ])
        ->with('exam')
        ->with('user')
        ->where('exam_id',$id)->get();
        // dd($session);
        return Inertia::render('exam/examHistory', [
            'session'=>$session,
            'exam'=>Exam::find($id),
        ]);
    }
    public function tier(Request $request, $id){
        $tier = exam::find($id)->update([
            'tier'=>$request->post('val')
        ]);

        return [
            'exam'=>$this->examReturn($id),
            'problem'=>$this->examNotReady($id)
        ];
    }
    public function changeExamDescription(Request $request, $id){
        $exam = Exam::find($id)->update([
            'description'=>$request->post('val')
        ]);
        return [
            'exam'=>$this->examReturn($id),
            'problem'=>$this->examNotReady($id)
        ];
    }
    public function changeExamImage(Request $request, $id){
        $upload = $this->resize($request->file);
        $size = Storage::size('/images/'.$upload->basename);

        $exam = Exam::with('attachment')->find($id);

        if ($exam->attachment != null) {
            $attachment = Exam_attachment::find($exam->attachment->id)->update([
                'filename'=>$upload->basename,
                'path'=>'/images/',
                'type'=>$upload->extension,
                'size'=>$size,
            ]);
        }else{
            $attachment = Exam_attachment::create([
                'filename'=>$upload->basename,
                'path'=>'/images/',
                'type'=>$upload->extension,
                'size'=>$size,
                'exam_id'=>$id,
            ]);
        }
        return [
            'exam'=>$this->examReturn($id),
            'problem'=>$this->examNotReady($id)
        ];
    }
    public function examRemove($id){
        $exam = Exam::find($id)->update([
            'remove'=>'remove'
        ]);
        return redirect()->route('myExam');
    }
    public function minimum(Request $request, $id){
        $exam = Exam::find($id)->update([
            'minimum'=>$request->post('minimum')
        ]);
        return [
            'exam'=>$this->examReturn($id),
            'problem'=>$this->examNotReady($id)
        ];
    }
    public function detected(Request $request, $id){
        $request->post('val');
        $exam = Exam::find($id)->update([
            'detected'=> $request->post('val') ? 'true' : null
        ]);

        return [
            'exam'=>$this->examReturn($id),
            'problem'=>$this->examNotReady($id)
        ];
    }
    public function excel($id){
        // dd("asdsad");
        return Excel::download(new SessionExport($id), 'Nilai.xlsx');
    }
}
