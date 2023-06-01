<?php

namespace App\Http\Controllers\Rank;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Lesson;
use App\Models\Session;
use App\Models\User;

class RankCurrentController extends Controller
{
    public function rankCurrent(){
        $lesson = Lesson::get();

        $user = User::get();

        $generale = collect([]);
        $generaleTemporary = collect();
        foreach($user as $person){
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
            ->with([
                'exam'=>function($exam){
                    $exam->with('lesson');
                }
            ])
            ->with('user')
            ->where('user_id', $person->id)->get();
            $pointGenerale = 0;
            $examArr = collect([]);
            foreach($session as $unit){
                if ($examArr->contains($unit->exam_id) == false) {
                    $examArr->push($unit->exam_id);
                    foreach($unit->history->question as $questionTemporary){
                        if ($questionTemporary->answer == null) {
                            continue;
                        }
                        foreach($questionTemporary->choice as $choiceTemporary){
                            if ($choiceTemporary->choice->keys != null) {
                                if ($questionTemporary->answer->choice_id == $choiceTemporary->id) {
                                    $pointGenerale = $pointGenerale + (int)$questionTemporary->question->point->point;
                                }
                            }
                        }
                    }
                }else{
                    continue;
                }

            }
            // dd($examArr);

            $generaleTemporary->push(['point'=>$pointGenerale, 'name'=>$person]);
            if ($generale->count() == 20) {
                break;
            }
        }
        foreach($generaleTemporary->sortBy('point')->reverse() as $keys=>$unit){
            if ($keys == 20) {
               break;
           }
           $generale->push($unit);
       }





       $locale = collect();

       foreach($lesson as $lessonData){
        $resultLesson = collect();
        $temporary = collect();
        foreach($user as $person){
            $sessionTemporary = Session::with([
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
            ->with([
                'exam'=>function($exam)use($lessonData){
                    $exam->where('lesson_id', $lessonData->id);
                    $exam->with('lesson');
                }
            ])
            ->with('user')
            ->where('user_id', $person->id)->get();

            if ($sessionTemporary->isEmpty()) {
                continue;
            }

            $userLesson = collect([]);
            $point = 0;
            $examArrLocale = collect([]);
            foreach($sessionTemporary as $examTemporary){
                if ($examArrLocale->contains($examTemporary->exam_id) == false) {
                    $examArrLocale->push($examTemporary->exam_id); 
                    if($examTemporary->exam != null){
                        foreach($examTemporary->history->question as $questionTemporary){
                            if ($questionTemporary->answer == null) {
                                continue;
                            }
                            foreach($questionTemporary->choice as $choiceTemporary){
                                if ($choiceTemporary->choice->keys != null) {
                                    if ($questionTemporary->answer->choice_id == $choiceTemporary->id) {
                                        $point = $point + (int)$questionTemporary->question->point->point;
                                    }
                                }
                            }
                        }
                    }else{
                        continue;
                    }
                }else{
                    continue;
                }
            }
            $temporary->push(['point'=>$point, 'name'=>$person]);
        }

        foreach($temporary->sortBy('point')->reverse() as $keys=>$unit){
            if ($keys == 20) {
               break;
           }
           $resultLesson->push($unit);
       }
       $locale->push(['lesson'=>$lessonData->lesson, 'result'=>$resultLesson]);
   }
   return Inertia::render('rank/index',[
    'lesson'=>$lesson,
    'generale'=>$generale,
    'locale'=>$locale,
]);
}
}
