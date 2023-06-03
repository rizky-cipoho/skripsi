<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyExam
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
        if ($request->route()->parameters != []) {
            $exam = Exam::find($request->route()->parameters['id']);
            if ($exam->user_id == Auth::user()->id) {
                return $next($request);
            }else{
                return redirect()->route('dashboard');
            }
        }else{
            return $next($request);
        }
        
    }
}
