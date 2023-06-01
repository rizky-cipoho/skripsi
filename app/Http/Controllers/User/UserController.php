<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\User_attachment;
use App\Models\Session;
use Illuminate\Support\Facades\Auth;
use App\Traits\ImageTrait;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    use ImageTrait;
    public function setting(){
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
        ])->where('user_id', Auth::user()->id)->get();
        $user = User::with('attachment')->find(Auth::user()->id);
        return Inertia::render('User/setting',[
            'session'=>$session,
            'user'=>$user
        ]);
    }
    public function settingName(Request $request){
        $user = User::find(Auth::user()->id)->update([
            'name'=>$request->post('name')
        ]);

        return $request->post('name');
    }
    public function settingDescription(Request $request){
        $user = User::find(Auth::user()->id)->update([
            'description'=>$request->post('description')
        ]);

        return $request->post('description');
    }
    public function settingSchool(Request $request){
        $user = User::find(Auth::user()->id)->update([
            'school'=>$request->post('school')
        ]);

        return $request->post('school');
    }
    public function settingImage(Request $request){
        $upload = $this->resize($request->file);
        $size = Storage::size('/images/'.$upload->basename);

        $user = User::with('attachment')->find(Auth::user()->id);

        if ($user->attachment != null) {
            $attachment = User_attachment::find($user->attachment->id)->update([
                'filename'=>$upload->basename,
                'path'=>'/images/',
                'type'=>$upload->extension,
                'size'=>$size,
            ]);
        }else{
            $attachment = User_attachment::create([
                'filename'=>$upload->basename,
                'path'=>'/images/',
                'type'=>$upload->extension,
                'size'=>$size,
                'user_id'=>$user->id,
            ]);
        }
        // $now = User::with('attachment')->find(Auth::user()->id);
        return [
            'user'=>User::with('attachment')->find(Auth::user()->id),
        ];
    }
}
