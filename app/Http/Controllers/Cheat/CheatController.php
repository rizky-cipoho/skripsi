<?php

namespace App\Http\Controllers\Cheat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cheat;
use App\Models\session;

class CheatController extends Controller
{
    public function cheat($id, $token){
        $session = Session::where('token', $token)->first();

        $cheat = Cheat::create([
            'session_id'=>$session->id
        ]);
    }
}
