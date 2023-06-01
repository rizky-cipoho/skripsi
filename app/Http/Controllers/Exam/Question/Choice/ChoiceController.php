<?php

namespace App\Http\Controllers\Exam\Question\Choice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ExamTrait;
use App\Models\Choice;
use App\Models\Question;
use App\Models\Exam;
use App\Models\AnswerKeys;
use App\Models\Choice_attachment;
use App\Traits\ImageTrait;
use Illuminate\Support\Facades\Storage;

class ChoiceController extends Controller
{
    use ImageTrait;
    use ExamTrait;
    public function returnGet($id){
        $question = Question::where('exam_id', $id)->where('remove', null)->with('questionData', function($query){
            $query->where('remove', null);
            $query->with('questionAttachment');
        })->with('choice', function($val){
            $val->where('remove', null);
            $val->with('attachment');
            $val->with([
                'keys'=> function($val2){
                    $val2->where('remove', null);
                }
            ]);
        })->get();
        return [
            'question'=>$question,
            'problem'=>$this->examNotReady($id),
            'exam'=>Exam::find($id)
        ];
    }
    public function addChoice(Request $request, $id){
        // dd($request->all());
        $choice = Choice::create([
            'choice'=>null,
            'question_id'=>$request->post('params'),
            'choice_attachment'=>null,
            'remove'=>null
        ]);
        return $this->returnGet($id);
    }
    public function updateChoice(Request $request, $id){
        $choices = Choice::with(['keys' => function($val){
            $val->where('remove',null);
        }])->where('question_id', $request->post('question_id'))->where('remove', null)->get();
        // dd($choices);
        foreach($choices as $choice){
            $new = Choice::create([
                "choice" => $choice->id == $request->post('id') ? $request->post('choice') : $choice->choice,
                "question_id" => $choice->question_id,
                "choice_attachment" => $choice->choice_attachment,
                "remove" => null,
            ]);
            Choice::find($choice->id)->update([
                'remove'=>'remove'
            ]);
            if ($choice->keys != null) {
                AnswerKeys::create([
                    'choice_id'=>$new->id,
                    'question_id'=>$new->question_id,
                    'remove'=>null
                ]);
                AnswerKeys::find($choice->keys->id)->update([
                    'remove'=>'remove'
                ]);
            }
        }

        return $this->returnGet($id);
    }
    public function choiceAttachment(Request $request, $id){
        $resize = $this->resize($request->file('file'));
        $size = Storage::size('/images/'.$resize->basename);

        $attachment = Choice_attachment::create([
            'filename'=>$resize->basename,
            'path'=>'/images/',
            'type'=>$resize->extension,
            'size'=>$size
        ]);

        $choice = Choice::where('id', $request->post('id'))->update([
            'choice_attachment'=>$attachment->id
        ]);
        return $this->returnGet($id);
    }
    public function removeChoice(Request $request, $id){
        $choice = Choice::where('id', $request->post('id'))->update([
            'remove'=>'remove'
        ]);
        return $this->returnGet($id);

    }
    public function keyChoice(Request $request, $id){
        $choice = Choice::find($request->post('id'));
        if ($choice) {
            $AnswerKeys = AnswerKeys::where('question_id', $choice->question_id)->where('remove', null)->update([
                'remove'=>'remove'
            ]);
            $keysCreate = AnswerKeys::create([
                'choice_id'=>$choice->id,
                'question_id'=>$choice->question_id
            ]);
        }
        return $this->returnGet($id);
    }
    public function removeImage(Request $request, $id){
        $choice = Choice::find($request->post('id'))->update([
            "choice_attachment"=>null
        ]);
        return $this->returnGet($id);
        // dd($choice);
    }
    public function choiceLength(Request $request, $id){
        $exam = Exam::where('id',$id)->first();
        // dd($exam);
        $questions = Question::where('exam_id', $exam->id)->where('remove', null)->get();


        if ($exam->choice < $request->post('length')) {
            foreach($questions as $question){
                $choice = Choice::where('question_id', $question->id)->where('remove', null)->orderBy('id', 'DESC')->get();
                $length = $request->post('length') - $exam->choice;

                for ($i=0; $i < $length; $i++) { 
                    $addChoice = Choice::create([
                        'choice'=>null,
                        'question_id'=>$question->id,
                        'choice_attachment'=>null,
                        'remove'=>null
                    ]);
                }
            }
        }else if($exam->choice > $request->post('length')){
            foreach($questions as $question){
                $choice = Choice::where('question_id', $question->id)->where('remove', null)->orderBy('id', 'DESC')->get();
                $length = $exam->choice - $request->post('length');

                for ($i=0; $i < $length; $i++) { 
                    $remove = Choice::find($choice[$i]->id)->update([
                        'remove'=>'remove'
                    ]);
                }
            }
        }
        Exam::find($id)->update([
            'choice'=>$request->post('length')
        ]);
        
        return $this->returnGet($id);
    }
}
