<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TestMid
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
        $year = ['1956', '1970', '1972'];
        if (in_array('1956', $year)) {
            return response()->json(["message" => "this is Second Middleware"], 401);
        }
        return $next($request);
    }
}
