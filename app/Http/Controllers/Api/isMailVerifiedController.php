<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class isMailVerifiedController extends Controller
{
    //
    public function checkVerifiedField(Request $request , $id)
    {


        try {
            $user = User::find($id);
            if ($user->is_email_verfied) {
                return response()->json([
                    'status' => true,
                    'messages' => "Email is verfied"
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'reason' => "Email is not verfied",
                    'message' => "please verify your gmail",
                    'verfication-resend-link' => $request->getSchemeAndHttpHost() . "/api/resend"
                ], 403);
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
