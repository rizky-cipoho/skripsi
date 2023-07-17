<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class BlockProsses
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
        if ($session->over == null) {
            return $next($request);
        }else{
            return redirect()->route('dashboard');
        }
    }
}
