<?php

namespace App\Http\Controllers\History;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Session;

class HistoryController extends Controller
{
    public function history(){
        // $temporary = Session::where('user_id', Auth::user()->id)->with('history')->get();
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
        ->where('user_id', Auth::user()->id)->get();
        $reverse = collect([]);
        foreach($session->reverse() as $data){
            $reverse->push($data);
        }
        // dd($reverse);
        return Inertia::render('history/index',[
            'session'=>$reverse
        ]);
    }

}
