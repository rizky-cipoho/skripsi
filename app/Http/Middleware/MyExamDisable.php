<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Exam;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MyExamDisable
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
        $exam = Exam::find($request->route()->parameters['id']);
        if ($exam->user_id == Auth::user()->id) {
            return redirect()->route('dashboard');
        }
        return $next($request);
    }
}
