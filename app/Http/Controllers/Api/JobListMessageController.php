<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\JobListMessage as JLR;
use App\Models\JobListMessage as JLM;
use App\Http\Resources\Message as MSR;
use App\Models\FriendList;
use App\Models\Message as MSM;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class JobListMessageController extends Controller
{

    public function store(Request $request)
    {

        //Validations Rules //////////////////////////
        $rules = array(
            'user_id' => 'required',
            'friend_id' => 'required'
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

                $is_friend = FriendList::where('user_id', $request->user_id)->where('friend_id', $request->friend_id)->exists();



                if ($is_friend) {

                    return response()->json([
                        "message" => "Objects Already Exist",
                        "ftc_code" => FriendList::where('user_id', $request->user_id)->where('friend_id', $request->friend_id)->value('ftc_code')
                    ], 200);
                }

                // creating ftc_code with seconds ////////////////////////////////////
                $ftc_code = time();
                //////////////////////////////////////////////////////////////////////

                // Save to DB ///////////////////////////////////////////

                // User A
                FriendList::create([
                    'user_id' => $request->user_id,
                    'friend_id' => $request->friend_id,
                    'ftc_code' => $ftc_code
                ]);

                // User B
                FriendList::create([
                    'user_id' => $request->friend_id,
                    'friend_id' => $request->user_id,
                    'ftc_code' => $ftc_code
                ]);

                /////////////////////////////////////////////////////////

                // send mail to congrats new freind list ////////////////

                $email = DB::table('users')->where('id', $request->friend_id)->value('email');

                Mail::send('email.newFriendList', [], function ($message) use ($email) {
                    $message->to($email);
                    $message->subject('New Friend List Mail');
                });

                // end of send mail to congrats new freind list //////////

                // return Job API Resource JSON Response //////////////
                return response()->json([
                    "message" => "Object Created",
                    'ftc_code' => (string)$ftc_code
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

    // public function show($id)
    // {
    //     try {

    //         $friend_list = FriendList::find($id);
    //         return $friend_list;
    //     } catch (\Throwable $th) {
    //         //throw $th;
    //     }
    // }

    public function getuserjoblist($userid)
    {
        try {

            // $f = User::query()
            // ->join('user_translations', 'users.id', '=', 'user_translations.user_id')
            // ->where('users.id', $userid)
            // ->with(['friendlists' => function ($query) {
            //     $query->where('friend_id', '!=', 58);
            // }, 'friendlists.fromUser'])
            // ->get();

            // return $f;

            // $all_friend_list = FriendList::query()
            // ->join('user_translations as ut1', 'friend_lists.user_id', '=', 'ut1.user_id')
            // ->join('user_translations as ut2', 'friend_lists.friend_id', '=', 'ut2.user_id')
            // ->select('ut1.username')
            // ->where('friend_lists.user_id', 58)
            // ->where('friend_lists.friend_id', '!=', 58)
            // ->get();

            $all_friend_list = FriendList::select(['id', 'friend_id', 'user_id', 'ftc_code', 'created_at'])->where('user_id', $userid)->where('friend_id', '!=', $userid)->get();

            return JLR::collection($all_friend_list);

            // $getJBL = DB::table('job_list_messages')->select('id')->where('seller_id', $userid)->orWhere('buyer_id',$userid)->get();

            // $getJBL = JLM::where('seller_id', $userid)->orWhere('buyer_id', $userid)->get();

            // foreach ($getJBL as $jbl) {
            //     $jbl->user_id = $userid;
            //     // Update other columns as needed
            //     $jbl->save(); // Save the changes to the database
            // }

            // dd($getJBL[0]->id);

            // $getunreadmsg = Message::where('job_list_msg_id', 2)->where('sender_id',$userid)->orWhere('recever_id',$userid)->count();

            // $getSeller = DB::table('users')->select('email','fullname')->where('id',$getJBL->seller_id)->get();

            // return response()->json([
            //     'seller' => $getJBL
            // ],200);



            // return JLR::collection($getJBL);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function textmessagesperjoblist($ftc_code, $userid)
    {
        try {

            $status_msgs = MSM::where('ftm_code', $ftc_code)->where('recever_id', $userid)->get();
            foreach ($status_msgs as $update_status) {
                $update_status->status = 1;
                $update_status->resp_time = Carbon::now();
                // Update other columns as needed
                $update_status->save(); // Save the changes to the database
            }
            $txt_msg = MSM::where('ftm_code', $ftc_code)->get();
            return MSR::collection($txt_msg);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
