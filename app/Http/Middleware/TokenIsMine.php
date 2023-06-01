<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Session;
use Illuminate\Support\Facades\Auth;

class TokenIsMine
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
        $session = Session::with([
            'exam'=>function($exam){
                $exam->with('user');
            }
        ])->where('token', $request->route()->parameters['token'])->first();
        if ($session->user_id == Auth::user()->id || $session->exam->user_id == Auth::user()->id) {
            return $next($request);
        }else{
            return redirect()->route('dashboard');
        }
    }
}
