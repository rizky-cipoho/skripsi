<?php

namespace App\Http\Middleware;

use App\Models\Session;
use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SessionThere
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $session = Session::where('over', null)->where('user_id', Auth::user()->id)->first();
        // dd($session);
        if ($session != null) {
            return redirect()->route('dataSession', ['id'=>$session->exam_id, 'token'=>$session->token]);
        }
        return $next($request);
    }
}
