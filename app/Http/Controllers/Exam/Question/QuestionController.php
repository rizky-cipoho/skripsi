<?php

namespace App\Http\Controllers\Exam\Question;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Question_attachment;
use App\Models\QuestionData;
use Inertia\Inertia;
use App\Traits\ImageTrait;

class QuestionController extends Controller
{
    use ImageTrait;
    public function edit($id){
        $exam = Exam::find($id);
        $question = Question::where('exam_id', $id)->where('remove', null)->with('questionData', function($query){
            $query->where('remove', null);
            $query->with('questionAttachment');
        })->get();
        $questionFirst = Question::where('exam_id', $id)->where('remove', null)->with('questionData')->first();
        return Inertia::render('exam/question/edit', [
            'exam'=>$exam,
            'questions'=>$question,
            'questionId'=>$questionFirst->id
        ]);
    }
    public function updateText(Request $request, $id){
        if (QuestionData::where('question_id', $request->post('idQuestion'))->get()->isNotEmpty()) {
            $beforeQuestionData = QuestionData::where('question_id', $request->post('idQuestion'))->update([
                'remove'=>"remove"
            ]);
        }
        // dump($beforeQuestionData);
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
                // dd($image);
                // $questionAttachment = Question_attachment::where('filename', )
                    $questionData = QuestionData::create([
                        'data'=>null,
                        'type'=>$request->post('data')['blocks'][$key]['type'],
                        'question_id'=>$request->post('idQuestion'),
                        'question_attachment'=>$image->id
                    ]);
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
        


        $question = Question::where('exam_id', $id)->where('remove', null)->with('questionData', function($query){
            $query->where('remove', null);
            $query->with('questionAttachment');
        })->get();
        // dd($question);

        return $question;     
    } 
    public function addQuestion(Request $request, $id){
        $question = new Question;
        $question->exam_id = $id;
        $question->save();

        $questionData = new QuestionData;
        $questionData->data = '';
        $questionData->type = 'paragraph';
        $questionData->question_id = $question->id;
        $questionData->save();
        $question = Question::where('exam_id', $id)->where('remove', null)->with('questionData', function($query){
            $query->with('questionAttachment');
            $query->where('remove', null);
        })->get();
        return $question;
    }
    public function addQuestionImage(Request $request, $id, $questionId){
        $upload = $this->upload($request->file("image"));
        // dd($upload);
        $createAttachment = Question_attachment::create([...$upload]);
        $create = QuestionData::create([
            "data"=>null,
            "type"=>"image",
            "remove"=>null,
            "question_id"=>$questionId,
            "question_attachment"=>$createAttachment->id
        ]);
        $move = $request->file("image")->move(explode("/", $upload["path"])[1], $upload["filename"]);
        // $moveExplotano =dd($move);
        $file = collect([
            "success" => 1,
            "file"=>[
                "url"=>"/".explode("\\",$move)[0]."/".explode("\\",$move)[1],
            ]
        ]);
        return $file;
    }
    public function removeQuestion(Request $request, $id){
        $question = Question::where('id', $request->post('idQuestion'))->update([
            'remove'=>'remove'
        ]);
        $questionData = QuestionData::where('question_id', $request->post('idQuestion'))->update([
            'remove'=>'remove'
        ]);
        return Question::where('exam_id', $id)->where('remove', null)->with('questionData', function($query){
            $query->with('questionAttachment');
            $query->where('remove', null);
        })->get();
    }
    public function getQuestion($id){
        return Question::where('exam_id', $id)->where('remove', null)->with('questionData', function($query){
            $query->with('questionAttachment');
            $query->where('remove', null);
        })->get();
    }
}
