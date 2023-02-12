<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\SendCodeResetPassword;
use App\Models\ResetPasswordCode;
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
        Mail::to($request->email)->send(new SendCodeResetPassword($codeData->code));

        //semd resposne
        return response(['message' => "Reset code has been sent to your gmail"], 200);
    }
}
