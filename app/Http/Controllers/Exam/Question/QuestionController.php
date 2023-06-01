<?php

namespace App\Http\Controllers\Exam\Question;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ExamTrait;
use App\Models\Exam;
use App\Models\Point;
use App\Models\Choice_attachment;
use App\Models\Choice;
use App\Models\Question;
use App\Models\Question_attachment;
use App\Models\QuestionData;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use App\Traits\ImageTrait;

class QuestionController extends Controller
{
    use ExamTrait;
    use ImageTrait;
    public function returnGet($id){
        $question = Question::where('exam_id', $id)->where('remove', null)->with('questionData', function($query){
            $query->where('remove', null);
            $query->with('questionAttachment');
        })->with('choice', function($val){
            $val->where('remove', null);
            $val->with('attachment');
            $val->with('keys', function($val2){
                $val2->where('remove', null);
            });
        })->get();

        return [
            'question'=>$question,
            'problem'=>$this->examNotReady($id),
            'exam'=>Exam::find($id)
        ];
    }
    public function edit($id){
        $exam = Exam::find($id);
        $questionFirst = Question::where('exam_id', $id)->where('remove', null)->with('questionData')->first();
        return Inertia::render('exam/question/edit', [
            'exam'=>$exam,
            'questions'=>$this->returnGet($id),
            'questionId'=>$questionFirst->id,
        ]);
    }
    public function updateText(Request $request, $id){
        // dd($request->all());
        if (QuestionData::where('question_id', $request->post('idQuestion'))->get()->isNotEmpty()) {
            $beforeQuestionData = QuestionData::where('question_id', $request->post('idQuestion'))->update([
                'remove'=>"remove"
            ]);
        }
        if ($request->post('data')['blocks'] == []) {
            $questionData = new QuestionData;
            $questionData->data = '';
            $questionData->type = 'paragraph';
            $questionData->question_id = $request->post('idQuestion');
            $questionData->save();
        }else{
            foreach($request->post('data')['blocks'] as $key=>$dataCount){
                if ($dataCount['type'] == "image") {
                    $image = Question_attachment::where('filename', explode("/", $request->post('data')['blocks'][$key]['data']['file']['url'])[2])->first();
                // $questionAttachment = Question_attachment::where('filename', )
                    $questionData = QuestionData::create([
                        'data'=>null,
                        'type'=>$request->post('data')['blocks'][$key]['type'],
                        'question_id'=>$request->post('idQuestion'),
                        'question_attachment'=>$image->id
                    ]);
                    $center = 'true';
                    switch ($dataCount['data']['withBackground']) {
                        case 'true':
                        $center = 'true';
                        break;
                        case 1:
                        $center = 'true';
                        break;
                        case true:
                        $center = 'true';
                        break;
                        case 'false':
                        $center = 'false';
                        break;
                        case false:
                        $center = 'false';
                        break;
                        case 0:
                        $center = 'false';
                        break;

                    }
                    
                    $center = Question_attachment::find($image->id)->update([
                        'withBackground'=>$center
                    ]);
                // dd($a);

                }else{
                    $questionData = QuestionData::create([
                        'data'=>$request->post('data')['blocks'][$key]['data']['text'],
                        'type'=>$request->post('data')['blocks'][$key]['type'],
                        'question_id'=>$request->post('idQuestion')
                    ]);
                    // dump($questionData);
                } 
            }
        }
// dump(json_decode($this->returnGet($id)));
        return $this->returnGet($id);     
    } 
    public function addQuestion(Request $request, $id){
        $exam = Exam::find($id);
        $question = new Question;
        $question->exam_id = $id;
        $question->save();

        $questionData = new QuestionData;
        $questionData->data = '';
        $questionData->type = 'paragraph';
        $questionData->question_id = $question->id;
        $questionData->save();

        $point = Point::create([
            'point'=>10,
            'question_id'=>$question->id
        ]);

        for ($i=0; $i < $exam->choice ; $i++) { 
            $choice = Choice::create([
                'choice'=>null,
                'question_id'=>$question->id,
                'choice_attachment'=>null,
                'remove'=>null
            ]);
        }
        return $this->returnGet($id);
    }
    public function addQuestionImage(Request $request, $id, $questionId){
    // dd($request->all());
        $upload = $this->resize($request->file("image"));
        // dd($upload);
        $size = Storage::size('/images/'.$upload->basename);

        $createAttachment = Question_attachment::create([
            'filename'=>$upload->basename,
            'path'=>'/images/',
            'type'=>$upload->extension,
            'size'=>$size,
            'withBackground'=>'true'
        ]);
        $create = QuestionData::create([
            "data"=>null,
            "type"=>"image",
            "remove"=>null,
            "question_id"=>$questionId,
            "question_attachment"=>$createAttachment->id
        ]);
        $file = collect([
            "success" => 1,
            "file"=>[
                "url"=>$createAttachment->path.$createAttachment->filename,
            ],
            'withBackground'=>'true'
        ]);
        return [
            'file'=>$file,
            'get'=>$this->returnGet($id)
        ];
    }
    public function removeQuestion(Request $request, $id){
        $question = Question::where('id', $request->post('idQuestion'))->update([
            'remove'=>'remove'
        ]);
        $questionData = QuestionData::where('question_id', $request->post('idQuestion'))->update([
            'remove'=>'remove'
        ]);
        // dd(Question::where('id', $request->post('idQuestion'))->first());
        return $this->returnGet($id);
    }

}
