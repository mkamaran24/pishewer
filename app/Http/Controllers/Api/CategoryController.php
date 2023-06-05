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
use Illuminate\Support\Facades\Storage;
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
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {

        try {

            // add image to storage ////////////////////////////////
            $catge_image = $request->image;
            $new_categ_image = random_int(100000, 999999) . '.' . $catge_image->getClientOriginalExtension();
            Storage::disk('public')->put($new_categ_image, file_get_contents($catge_image));

            // Save to DB ///////////////////////////////////////////
            $categ = new CategoryModel();
            $categ->image = $new_categ_image;
            $categ->save();

            if (is_array($request->categ_trans)) {
                foreach ($request->categ_trans as $key => $ct) {
                    $decoded_ct = json_decode($ct);
                    CategoryTrans::create([
                        'name' => $decoded_ct->name,
                        'locale' => $decoded_ct->locale,
                        'categ_id' => $categ->id
                    ]);
                }
            } else {
                return response()->json([
                    'status' => true,
                    'message' => 'categ_trans is not array'
                ], 500);
            }
            /////////////////////////////////////////////////////////

            // return Job API Resource JSON Response //////////////
            return response()->json([
                'status' => true,
                'message' => 'Object Created'
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

        
           // dd($request->all());

            /////////////////////////////////////

            // Validation of $request should goes here
            ////

            // add image to storage ////////////////////////////////
            $catge_image = $request->image;
            $new_categ_image = random_int(100000, 999999) . '.' . $catge_image->getClientOriginalExtension();
            Storage::disk('public')->put($new_categ_image, file_get_contents($catge_image));

            // Save to DB ///////////////////////////////////////////
            $categ = CategoryModel::find($id);

            $img_path = 'public/' . $categ->image;

            Storage::delete($img_path);

            $categ->image = $new_categ_image;
            $categ->save();

           
            ///
            // $categ_translation = null;
            if (is_array($request->categ_trans)) {
                foreach ($request->categ_trans as $key => $ct) {
                    $decoded_ct = json_decode($ct);
         
                $categ_translation = CategoryTrans::where('locale',$decoded_ct->locale)->where('categ_id',$id)->update(['name'=>$decoded_ct->name]);
                }
                

                return response()->json([
                    'message' => "Done"],200);

            } else {
                return response()->json([
                    'status' => true,
                    'message' => 'categ_trans is not array'
                ], 500);
            }
        

         


            // if (is_array($locals)) {
            //     foreach ($locals as $key => $ct) {
            //         dd($ct);

            //     //  $locals[$key]=$decoded_ct->locale;
            //     //     // CategoryTrans::create([
            //     //     //     'name' => $decoded_ct->name,
            //     //     //     'locale' => $decoded_ct->locale,
            //     //     //     'categ_id' => $categ->id
            //     //     // ]);
            //      } 

            //     }

            
        } catch (\Throwable $th) {
            throw $th;
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
