<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Job as JobResource;
use App\Models\Jobs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{

    public function index()
    {

        try {
            return JobResource::collection(Jobs::all());
        } catch (\Throwable $th) {

            abort(code:500,message:'fail to fetch');
           
            //throw $th; this throwble should be used for logs details
            // return response()->json([
            //     'status' => false,
            //     'message' => $th->getMessage(),
            // ], 500);
        }
    }

    public function store(Request $request)
    {
        //Validations Rules //////////////////////////
        $rules = array(
            'title' => 'required',
            'image' => 'required',
            'description' => 'required',
            'keyword' => 'required',
            'price' => 'required',
            'completein' => 'required',
            'user_id' => 'required',
            'categ_id' => 'required',
            'subcateg_id' => 'required'
        );
        /// end of Validation Rules ////////////////////

        //Validation Custom Messages
        // $messages = array('title'=>'All data required');


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
                $jobs = Jobs::create($request->all());
                return new JobResource($jobs);
            } catch (\Throwable $th) {
                abort(code:500,message:'fail to create');
                // //throw $th;
                // return response()->json([
                //     'status' => false,
                //     'message' => $th->getMessage(),
                // ], 500);
            }
        }
        //// end of Validator Check ///////////////////////

    }

    public function show($id)
    {
        
        try {
            // Validation of $id should goes here


            /////////////////////////////////////

            $job = Jobs::find($id);
            if ($job) {return new JobResource($job);}
            else{
                return response()->json([
                    'status' => false,
                    'messages' => "Object Not Found"
                ], 404);
            }

        } catch (\Throwable $th) {
            //throw $th;
            abort(code:500,message:'fail to find object');
        }
    }

    public function update(Request $request, $id)
    {
        
        try {
            // Validation of $id should goes here


            /////////////////////////////////////

            // Validation of $request should goes here

            //Validations Rules //////////////////////////
            $rules = array(
                'title' => 'required',
                'image' => 'required',
                'description' => 'required',
                'keyword' => 'required',
                'price' => 'required',
                'completein' => 'required',
                'user_id' => 'required',
                'categ_id' => 'required',
                'subcateg_id' => 'required'
            );
            /// end of Validation Rules ////////////////////

            //Validation Custom Messages
            // $messages = array('title'=>'All data required');


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

            }

            /////////////////////////////////////

            $job = Jobs::find($id);
            if ($job) {
                $job->update([
                    'title' => $request->title,
                    'image' => $request->image,
                    'description' => $request->description,
                    'keyword' => $request->keyword,
                    'price' => $request->price,
                    'completein' => $request->completein,
                    'user_id' => $request->user_id,
                    'categ_id' => $request->categ_id,
                    'subcateg_id' => $request->subcateg_id
                ]);
                return new JobResource($job);
            }
            else{
                return response()->json([
                    'status' => false,
                    'messages' => "Object Not Found"
                ], 404);
            }

        } catch (\Throwable $th) {
            //throw $th;
            abort(code:500,message:'fail to update');
            //Logs implementation goes down herer


            ////////////////////////////////////////////
        }
    }

    public function destroy($id)
    {
        // Validation of $id should goes here

        //////////////////////////////////////

        try {
            //code...
            $job = Jobs::where('id',$id)->delete();
            if ($job) {
                # code...
                return response()->json([
                    'status' => true,
                    'messages' => "Delete Success",
                    "data"=>[]
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
            abort(code:500,message:'fail to delete');
            //Logs implementation goes down herer


            ////////////////////////////////////////////
        }
        

    }
    
}
