<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Category as CategoryResource;
use App\Models\Category as CategoryModel;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class CategoryController extends Controller
{

    public function index()
    {
        //
        try {

            return CategoryResource::collection(CategoryModel::all());
        } catch (\Throwable $th) {
            // abort(code: 500, message: 'fail to fetch');
            //throw $th;
            // return response()->json([
            //     'status'=>false,
            //     'message'=>$th->getMessage(),
            // ],500);
        }
    }

    public function store(Request $request)
    {
        //
        //Validations Rules //////////////////////////
        $rules = array(
            'name' => 'required',
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
                $sub_categ = CategoryModel::create([
                    'name' => $request->name,
                ]);
                /////////////////////////////////////////////////////////

                // return Job API Resource JSON Response //////////////
                return new CategoryResource($sub_categ);
                ///////////////////////////////////////////////////////

            } catch (\Throwable $th) {
                abort(code: 500, message: 'fail to create');
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
            $categ = CategoryModel::find($id);
            if ($categ) {
                return new CategoryResource($categ);
            } else {
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
            // Validation of $id should goes here


            /////////////////////////////////////

            // Validation of $request should goes here

            //Validations Rules //////////////////////////
            $rules = array(
                'name' => 'required',
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

            $categ = CategoryModel::find($id);
            if ($categ) {
                $categ->update([
                    'name' => $request->name,
                ]);
                return new CategoryResource($categ);
            } else {
                return response()->json([
                    'status' => false,
                    'messages' => "Object Not Found"
                ], 404);
            }
        } catch (\Throwable $th) {
            //throw $th;
            // abort(code: 500, message: 'fail to update');
            //Logs implementation goes down herer


            ////////////////////////////////////////////
        }
    }

    public function destroy($id)
    {
        //
        try {
            $categ = CategoryModel::where('id', $id)->delete();
            if ($categ) {
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
            abort(code: 500, message: 'fail to delete');
            //Logs implementation goes down herer


            ////////////////////////////////////////////
        }
    }
}
