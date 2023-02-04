<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\User as UserResource;
use App\Models\User as UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{

    public function index()
    {
        try {
            //code...
            return UserResource::collection(UserModel::paginate(9));
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function show($id)
    {
        try {
            $user = UserModel::find($id);
            if ($user) {
                # code...
                return new UserResource($user);
            } else {
                # code...
                return response()->json([
                    'status' => false,
                    'messages' => "Object Not Found"
                ], 404);
            }
        } catch (\Throwable $th) {
            //throw $th;
            abort(code: 500, message: 'fail to find object');
        }
    }


    public function update(Request $request, $id)
    {
        //Validations Rules //////////////////////////
        // $rules = array(
        //     'email' => 'required',
        //     'fullname' => 'required',
        //     'username' => 'required',
        //     'fastpay_acc_num' => 'required',
        //     'phone_number' => 'required',
        // );
        /// end of Validation Rules ////////////////////


        //Validation Custom Messages
        // $messages = array('title'=>'All data required');

        // $validator = Validator::make($request->all(), $rules);
        // if ($validator->fails()) {
        //     $messages = $validator->messages();
        //     $errors = $messages->all(); //convert them into one array
        //     return response()->json([
        //         'status' => false,
        //         'reason' => 'Validation Fails',
        //         'messages' => $errors,
        //     ], 422);
        // } else {
        # put data into DB
        try {
            // $hashed_pass = array("password" => Hash::make($request->password));
            // array_merge($request->except(['password']), $hashed_pass)
            UserModel::where('id', $id)->update($request->all());
            // return User API Resource JSON Response //////////////
            return new UserResource(UserModel::find($id));
            ///////////////////////////////////////////////////////

        } catch (\Throwable $th) {
            //throw $th;
            // abort(code: 500, message: 'fail to create');
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
        // }
    }


    public function destroy($id)
    {
        try {
            $user = UserModel::where('id', $id)->delete();
            if ($user) {
                return response()->json([
                    'status' => true,
                    'messages' => "Delete Success",
                    "data" => []
                ], 200);
            } else {
                # code...
                return response()->json([
                    'status' => false,
                    'messages' => "Object Not Found"
                ], 404);
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
