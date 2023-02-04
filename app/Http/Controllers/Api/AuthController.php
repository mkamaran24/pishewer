<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User as UserModel;

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

                $user = UserModel::create(array_merge($request->except(['password']), $hashed_pass));
                // return User API Resource JSON Response //////////////
                return response()->json([
                    'status' => true,
                    'messages' => "Object Created",
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
}
