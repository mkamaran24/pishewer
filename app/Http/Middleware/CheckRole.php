<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckRole
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
        $user = auth('sanctum')->user();
        // return response()->json(["data" => $user->role], 200);
        $check_role = DB::table('users')->where('id', $user->id)->where("role", 1)->exists();
        if (!$check_role) {
            return response()->json([
                "message" => "This server could not verify that you are authorized to access the requested resource."
            ], 401);
        }
        return $next($request);
    }
}
