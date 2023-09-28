<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\User as UserResource;
use App\Models\User as UserModel;
use App\Models\UserTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
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
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
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

        try {

            $user_obj = [];
            $user_trans_obj = [];
            foreach ($request->all() as $db_feild => $req_feild) {
                if ($db_feild == "email") {
                    $user_obj[$db_feild] = $req_feild;
                } elseif ($db_feild == "password") {
                    $hashed_req_field = Hash::make($req_feild);
                    $user_obj[$db_feild] = $hashed_req_field;
                } elseif ($db_feild == "fastpay_acc_num") {
                    $user_obj[$db_feild] = $req_feild;
                } elseif ($db_feild == "phone_number") {
                    $user_obj[$db_feild] = $req_feild;
                } elseif ($db_feild == "fullname") {
                    $user_trans_obj[$db_feild] = $req_feild;
                } elseif ($db_feild == "username") {
                    $user_trans_obj[$db_feild] = $req_feild;
                }
            }

            UserModel::where('id', $id)->update($user_obj);
            UserTranslation::where('user_id', $id)->where('locale', App::getLocale())->update($user_trans_obj);

            // return User API Resource JSON Response //////////////
            return new UserResource(UserModel::find($id));
            ///////////////////////////////////////////////////////

        } catch (\Throwable $th) {
            throw $th;
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
