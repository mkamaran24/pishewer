<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ResetPasswordCode;
use Illuminate\Http\Request;

class CodeCheckController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        //
        try {
            //code...
            $passwordReset = ResetPasswordCode::firstWhere('code', $request->code);

            if ($passwordReset) {
                # code...
                if ($passwordReset->isExpire()) {

                    return response()->json([
                        "status"=>false,
                        "message"=>"OTP code has been expired"
                    ],404);
                }
    
                return response()->json([
                    "status"=>true,
                    "message"=>"OTP code is valid and not expired yet"
                ],200);
            } else {
                # code...
                return response()->json([
                    "status"=>false,
                    "message"=>"OTP code does not exist"
                ],404);
            }
            

        } catch (\Throwable $th) {
            // throw $th;
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
