<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Review as ResourcesReview;
use App\Models\ReplyReview;
use App\Models\Review as ModelReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function store(Request $request)
    {

        //Validations Rules //////////////////////////
        $rules = array(
            'service_quality' => 'required',
            'commun_followup' => 'required',
            'panctual_delevery' => 'required',
            'description' => 'required',
            'buyer_id' => 'required',
            'job_id' => 'required'

        );
        /// end of Validation Rules ////////////////////

        // Validator Check //////////////////////////////
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

                // save $req to DB //////////////////////////////
                ModelReview::create([
                    'service_quality' => $request->service_quality,
                    'commun_followup' => $request->commun_followup,
                    'panctual_delevery' => $request->panctual_delevery,
                    'description' => $request->description,
                    'user_id' => $request->buyer_id,
                    'job_id' => $request->job_id
                ]);
                /////////////////////////////////////////////////

                // return Job API Resource JSON Response //////////////
                return response()->json([
                    'status' => true,
                    'messages' => "Object Created"
                ], 201);
                ///////////////////////////////////////////////////////


            } catch (\Throwable $th) {
                // abort(code: 500, message: 'fail to create');
                //throw $th;
                return response()->json([
                    'status' => false,
                    'message' => $th->getMessage(),
                ], 500);
            }
        }
    }

    public function getallrev()
    {

        try {
            //code...
            return ResourcesReview::collection(ModelReview::all());
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }

        
    }

    public function getrevperjob($id)
    {

        try {
            //code...
            return ResourcesReview::collection(ModelReview::where('job_id',$id)->get());
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }

        
    }
}
