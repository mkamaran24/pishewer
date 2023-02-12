<?php

namespace App\Http\Controllers;

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



        try {
            //code...
            $passwordReset = ResetPasswordCode::firstWhere('code', $request->code);

            if ($passwordReset->isExpire()) {

                return response(['message' => "OTP Code Expired"], 404);
            }

            return response(['message' => "OTP Code is valid"], 200);

        } catch (\Throwable $th) {
            // throw $th;
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
