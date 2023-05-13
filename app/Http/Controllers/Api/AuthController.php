<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User as UserModel;
use App\Models\UserVerify;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use App\Mail\VerificationEmail;
use App\Models\UserTranslation;
use Illuminate\Support\Facades\App;

class AuthController extends Controller
{
    //

    public function createUser(Request $request)
    {
        //Validations Rules //////////////////////////
        $rules = array(
            'email' => 'required',
            'fullname' => 'required',
            'username' => 'required',
            'fastpay_acc_num' => 'required',
            'phone_number' => 'required',
        );
        /// end of Validation Rules ////////////////////

        //Validation Custom Messages
        // $messages = array('title'=>'All data required');

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all(); //convert them into one array
            return response()->json([
                'status' => false,
                'reason' => 'Validation Fails',
                'messages' => $errors,
            ], 422);
        } else {
            # put data into DB
            try {
                $hashed_pass = array("password" => Hash::make($request->password));

                $user = UserModel::create(array_merge($request->except(['password','username','fullname']), $hashed_pass));

                UserTranslation::create([
                    'username' => $request->username,
                    'fullname' => $request->fullname,
                    'locale' => App::getLocale(),
                    'user_id' => $user->id
                ]);

                $token = Str::random(64);

                UserVerify::create([
                    'user_id' => $user->id,
                    'token' => $token
                ]);

                // Mail::to($request->email)->send(new VerificationEmail("Ahmed", "account/verify/"));

                Mail::send('email.emailVerificationEmail', ['token' => $token], function ($message) use ($request) {
                    $message->to($request->email);
                    $message->subject('Reset Password Mail');
                });

                // return User API Resource JSON Response //////////////
                return response()->json([
                    'status' => true,
                    'messages' => "Object Created",
                    "mail message" => "Mail Verfication Sent To Your Gmail",
                    "verify mail link" => $request->getSchemeAndHttpHost() . "api/account/verify/" . $token,
                    'token' => $user->createToken("API TOKEN")->plainTextToken
                ], 201);
                ///////////////////////////////////////////////////////

            } catch (\Throwable $th) {
                //throw $th;
                // abort(code: 500, message: 'fail to create');
                return response()->json([
                    'status' => false,
                    'message' => $th->getMessage(),
                ], 500);
            }
        }
    }

    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = UserModel::where('email', $request->email)->first();

            return response()->json([
                'status' => true,
                'user_id' => (string)$user->id,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function verifyAccount($token)
    {
        $verifyUser = UserVerify::where('token', $token)->first();


        if (!is_null($verifyUser)) {
            $user = $verifyUser->user;
            if (!$user->email_verified_at) {
                $verifyUser->user->email_verified_at = now();
                $verifyUser->user->is_email_verfied = true;
                $verifyUser->user->save();
            }
            return redirect('https://pishewer.com/verifiedMesage.php');
        }
    }
}
