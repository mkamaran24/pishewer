<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Category as CategoryResource;
use App\Http\Resources\Subcategory as ResourcesSubcategory;
use App\Models\Category as CategoryModel;
use App\Models\CategoryTrans;
use App\Models\Subcategory;
use App\Models\SubcategoryTrans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;


class CategoryController extends Controller
{

    public function index()
    {
        //
        try {

            $locale =  App::getLocale();
            

            // return CategoryTrans::with('subcategorytrans')->where('locale','en')->get();
            // return CategoryModel::with('categorytrans')->where('locale','en')->get();
            // return CategoryModel::whereHas('categorytrans', function($query) {
            //     $query->where('locale', 'en');
            // })->get();

            // $category =  CategoryModel::join('category_trans', 'categories.id', '=', 'category_trans.categ_id')
            //     ->where('category_trans.locale', $locale)
            //     ->get(['categories.*', 'category_trans.name','category_trans.locale']);

            // $categories = CategoryModel::with(['categorytrans' => function ($query) use ($locale){
            //     $query->where('locale', $locale);
            // }])->get();

            $categories = CategoryModel::with('categorytrans')->get();
            return CategoryResource::collection($categories);

        } catch (\Throwable $th) {
            // abort(code: 500, message: 'fail to fetch');
            //throw $th;
            return response()->json([
                'status'=>false,
                'message'=>$th->getMessage(),
            ],500);
        }
    }

    public function store(Request $request)
    {

        try {

            // Save to DB ///////////////////////////////////////////
            $categ = new CategoryModel();
            $categ->save();

            foreach ($request->all() as $key => $req) {

                CategoryTrans::create([
                    'name'=>$req['name'],
                    'locale'=>$req['locale'],
                    'categ_id'=>$categ->id
                ]);
                
            }


            /////////////////////////////////////////////////////////

            // return Job API Resource JSON Response //////////////
            return response()->json([
                'status' => true,
                'message' => 'Object Created'
            ]);
            ///////////////////////////////////////////////////////

        } catch (\Throwable $th) {
            // abort(code: 500, message: 'fail to create');
            // //throw $th;
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }

        //Validations Rules //////////////////////////
        // $rules = array(
        //     'name' => 'required',
        // );
        /// end of Validation Rules ////////////////////

        // Validator Check /////////////////////////////
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
            # put data to DB after Succes Validation

        // }
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

    public function getSubcategory($id)
    {

        try {

            $locale = App::getLocale();
            
            $subcateg_trans = Subcategory::with(['subcategorytrans' => function ($query) use ($locale) {
                $query->where('locale', $locale);
            }])->get();

            return ResourcesSubcategory::collection($subcateg_trans);

        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

}
