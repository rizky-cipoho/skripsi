<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Exam;

class ExamRemove
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
        if ($request->route()->parameters == []) {
            return $next($request);
        }else{
            $exam = Exam::find($request->route()->parameters['id']);
            if ($exam->remove == null) {
                return $next($request);
            }else{
                return back();
            }
        }
    }
}
