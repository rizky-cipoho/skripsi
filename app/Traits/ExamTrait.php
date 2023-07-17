<?php 
namespace App\Traits;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\History;
use App\Models\Question;
use App\Models\Lesson;
use App\Models\TimeStart;
use App\Models\Exam;
use Illuminate\Support\Facades\Auth;

trait ExamTrait
{
    public function recommendations(){
        $histories = History::where('user_id', Auth::user()->id)->get();
        $myExam = Exam::where('user_id', Auth::user()->id)->where('remove', null)->get();
        $collecttion = collect([]);
        foreach ($histories as $value) {
            $collecttion->push($value->exam->id);
        }
        foreach ($myExam as $value) {
            $collecttion->push($value->id);
        }
        $collecttion->unique();
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
        if($this->favorite() != "Semuanya"){
            $lesson = Lesson::where('lesson', $this->favorite())->first();
            $result = Exam::whereNotIn('id', $collecttion)
            ->where('lesson_id', $lesson->id)
            ->where('remove', null)
            ->with('attachment')
            ->orderBy('id','DESC')
            ->whereIn('tier',$ages)
            ->with('user')->get();
        }else{
            $result = Exam::whereNotIn('id', $collecttion)
            ->with('attachment')
            ->orderBy('id','DESC')
            ->where('remove', null)
            ->whereIn('tier',$ages)
            ->with('user')->get();
        }
        $data = collect([]);
        $length = 0;

        if (!$result->isEmpty()) {
            foreach($result as $index){
                if ($this->examNotReady($index->id)->isEmpty()) {
                    $data->push($index);
                    if(7<$length){
                        break;
                    }
                    $length++;
                }
            }
        }
        return $data;
    }
    public function favorite(){
        $resultFavorite;
        $favoriteMethod = $this->favoriteProsess(Auth::user()->id);
        if ($favoriteMethod == 0) {
            $resultFavorite = "Semuanya";
        }else{
            $favorite = Lesson::where('id', $favoriteMethod)->first();
            $resultFavorite = $favorite->lesson;
        }
        return $resultFavorite;

    }
    public function favoriteProsess($id){
        $favorites = History::where('user_id', $id)->get();
        $favoriteArr = collect([]);
        foreach($favorites as $favoriteData){
            $favoriteArr->push($favoriteData->exam);
        }
        $favoriteCountBy = $favoriteArr->countBy('lesson_id');
        $favoriteMax = $favoriteCountBy->max();

        $filter = $favoriteCountBy->filter(function($item) use ($favoriteMax){
            return $item == $favoriteMax;
        });
        $keys = $filter->keys();
        $result;
        if ($keys->count() == 0) {
            $result = 0;
        }elseif($keys->count() == 1){
            $result = $keys->implode(',');
        }else{
            $result = $keys->first();
        }

        return (int)$result;
    }
    public function examNotReady($id){
        $problem = collect([]);
        $examId = Exam::with('lesson')->with([
            'time'=>function($time){
                $time->with('startTime');
                $time->where('remove', null);
            }
        ])->find($id);
        $exam = Question::where('exam_id', $id)->where('remove', null)->with('questionData', function($query){
            $query->where('remove', null);
            $query->with('questionAttachment');
        })->with('choice', function($val){
            $val->where('remove', null);
            $val->with('attachment');
            $val->with('keys', function($val2){
                $val2->where('remove', null);
            });
        })->get();
        if($exam->count() < 5){
            $problem->push('setidaknya buat 5 soal atau lebih');
        }
        if($examId->lesson->lesson == 'Lainnya'){
            if ($examId->other == null) {
                $problem->push('Spesifikkan Pelajaran Lainnya');
            }
        }
        if($examId->time->start != null){
            $time = TimeStart::find($examId->time->time_starts_id);
            if ($time->start == null) {
                $problem->push('Tentukan Tanggal Mulai Ujian');
            }
        }

        foreach($exam as $key=>$question){
            foreach($question->questionData as $data){
                if (($data->data == null || $data->data == "") && $data->question_attachment == null) {
                    $problem->push('Soal tidak boleh kosong'.' No.'.$key+1);
                    break;
                }
            }
        }
        foreach($exam as $key=>$question){
            foreach($question->choice as $choice){
                // dump(($choice->choice == null || $data->data == "") && $choice->choice_attachment == null);
                if (($choice->choice == null || $choice->choice == "") && $choice->choice_attachment == null) {
                    $problem->push('Pilihan tidak boleh kosong'.' No.'.$key+1);
                    break;
                } 
            }
            // break;
        }
        foreach($exam as $key=>$question){
            $collect = collect([]);
            foreach($question->choice as $choice){
                $collect->push($choice->keys == null);
            } 
            if (!$collect->contains(false)) {
                $problem->push('Setiap soal harus memiliki satu jawaban yang benar'.' No.'.$key+1);
            }
        }
        return $problem;
    }
}
