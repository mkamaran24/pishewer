<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
// use App\Models\ResetPasswordCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, $otpCode)
    {
        //
        try {
            //code...
            $data = $request->validate([
                'password' => 'required',
            ]);

            $user_email = DB::select('select email from reset_password_codes where code = ?', [$otpCode]);

            if ($user_email) {
                # code...
                $user = User::firstWhere('email', $user_email[0]->email);

                $user->password = Hash::make($request->password);

                $user->save();

                return response()->json([
                    "status" => true,
                    "message" => "Password changed successfully"
                ], 200);
            } else {
                # code...
                return response()->json([
                    "status" => false,
                    "message" => "otpCode not found"
                ], 404);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
