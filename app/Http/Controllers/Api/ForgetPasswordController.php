<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\SendCodeResetPassword;
use App\Models\ResetPasswordCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ForgetPasswordController extends Controller
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
        $data = $request->validate([
            'email' => 'required',
        ]);

        $isEmail_Exist = User::where('email', $request->email)->exists();

        if ($isEmail_Exist) {
            // Delete all old code that user send before.
            ResetPasswordCode::where('email', $request->email)->delete();

            // Generate random code
            $gen_code = mt_rand(100000, 999999);

            // save info to DB
            $codeData = new ResetPasswordCode();
            $codeData->email = $request->email;
            $codeData->code = $gen_code;
            $codeData->created_at = now();
            $codeData->save();

            //Sent Mail with Code 
            // Mail::to($request->email)->send(new SendCodeResetPassword($codeData->code));

            Mail::send('email.send-code-reset-password', ['code' => $gen_code], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Reset Password Mail');
            });

            //semd resposne
            return response()->json([
                'message' => "Reset code has been sent to your gmail"
            ], 200);
        } else {
            //semd resposne
            return response()->json([
                'message' => "email not found",
                'response_code' => "404"
            ], 404);
        }
    }
}
