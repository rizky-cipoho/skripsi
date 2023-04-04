<?php 
namespace App\Traits;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\History;
use App\Models\Lesson;
use App\Models\Exam;
use Illuminate\Support\Facades\Auth;

trait ExamTrait
{
public function recommendations(){
        $histories = History::where('user_id', Auth::user()->id)->get();
        $myExam = Exam::where('user_id', Auth::user()->id)->get();
        $collecttion = collect([]);
        foreach ($histories as $value) {
            $collecttion->push($value->exam->id);
        }
        foreach ($myExam as $value) {
            $collecttion->push($value->id);
        }
        $collecttion->unique();
        if($this->favorite() != "Semuanya"){
            $lesson = Lesson::where('lesson', $this->favorite())->first();
            $result = Exam::whereNotIn('id', $collecttion)->where('lesson_id', $lesson->id)->with('attachment')->orderBy('id','DESC')->get();
        }else{
            $result = Exam::whereNotIn('id', $collecttion)->with('attachment')->orderBy('id','DESC')->get();
        }
        $data = collect([]);
        $length = 0;
        foreach($result as $index){
            $data->push($index);
            if($length>4){
                break;
            }
            $length++;
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
}