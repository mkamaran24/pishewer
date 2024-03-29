<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message as MSG;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\Message as ModelsMessage;


class MessageController extends Controller
{

    public function store(Request $request)
    {
        //
        //Validations Rules //////////////////////////
        $rules = array(
            'text_mesg' => 'required',
            'sender_id' => 'required',
            'recever_id' => 'required',
            'ftc_code' => 'required'
        );
        /// end of Validation Rules ////////////////////

        // Validator Check /////////////////////////////
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
            # put data to DB after Succes Validation
            try {



                // Save to DB ///////////////////////////////////////////
                MSG::create([
                    'text_msg' => $request->text_mesg,
                    'sender_id' => $request->sender_id,
                    'recever_id' => $request->recever_id,
                    'ftm_code' => $request->ftc_code,
                    'msg_time' => Carbon::now()
                ]);
                /////////////////////////////////////////////////////////

                // return Job API Resource JSON Response //////////////
                return response()->json([
                    "message" => "Object Created"
                ], 201);
                ///////////////////////////////////////////////////////

            } catch (\Throwable $th) {
                // abort(code: 500, message: 'fail to create');
                // //throw $th;
                return response()->json([
                    'status' => false,
                    'message' => $th->getMessage(),
                ], 500);
            }
        }
        //// end of Validator Check ///////////////////////
    }

    public function show($id)
    {
    }

    public function total_unread_msg($user_id)
    {
        try {
            $t_u_m = ModelsMessage::where('status', '=', false)->where('recever_id', $user_id)->count();
            return response()->json([
                'total_unread_messages' => $t_u_m
            ], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
