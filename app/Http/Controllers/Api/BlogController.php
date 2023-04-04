<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Blog as ResourceBlog;
use App\Models\Blog as ModelsBlog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{

    public function index()
    {
        //
        try {
            return ResourceBlog::collection(ModelsBlog::paginate(5));
        } catch (\Throwable $th) {
            //throw $th;
        }
    }


    public function store(Request $request)
    {
        //Validations Rules //////////////////////////
        $rules = array(
            'title' => 'required',
            'image' => 'required',
            'body' => 'required',
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
                $new_img_name = "";
                // start of image logics ////////////////////////////////////////////////////////////////
                if ($request->hasFile('image')) {
                    $img = $request->file('image');
                    $new_img_name = random_int(100000, 999999) . '.' . $img->getClientOriginalExtension();
                    // save image in laravel Private Storage ///////////////////////////////////
                    Storage::disk('public')->put($new_img_name, file_get_contents($img));
                    /////////////////////////////////////////////////////////////////////////////

                } else {
                    $new_img_name = "file not found";
                }
                // end of Image Logics /////////////////////////////////////////////////////////////////////////////////

                // save $req to DB //////////////////////////////
                $jobs = ModelsBlog::create([
                    'image' => $new_img_name,
                    'title' => $request->title,
                    'body' => $request->body,
                    'user_id' => $request->user_id,
                    'blog_category_id' => $request->blog_category_id
                ]);
                /////////////////////////////////////////////////

                // return Job API Resource JSON Response //////////////
                return response()->json([
                    'status' => true,
                    'messages' => "Object Created"
                ], 201);
                ///////////////////////////////////////////////////////


            } catch (\Throwable $th) {
                //throw $th;
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
        try {
            // Validation of $id should goes here


            /////////////////////////////////////

            $blog = ModelsBlog::find($id);
            if ($blog) {
                return new ResourceBlog($blog);
            } else {
                return response()->json([
                    'status' => false,
                    'messages' => "Object Not Found"
                ], 404);
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    // public function update(Request $request, $id)
    // {
    //     //
    // }

    public function updateblog(Request $request, $id)
    {
        //
        //Validations Rules //////////////////////////
        $rules = array(
            'title' => 'required',
            'image' => 'required',
            'body' => 'required',
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

                $blog_image = $request->image;
                $new_blog_image = random_int(100000, 999999) . '.' . $blog_image->getClientOriginalExtension();
                ////////////////////////////////////////////////////////////////////////////
                // save image in laravel Private Storage ///////////////////////////////////
                Storage::disk('public')->put($new_blog_image, file_get_contents($blog_image));
                /////////////////////////////////////////////////////////////////////////////

                // remove old image from storage/////////////////////////////////////////////
                $img_name = DB::table('blogs')->select('image')->where('id', $id)->get();
                $img_path = "public/" . $img_name;
                Storage::delete($img_path);
                /////////////////////////////////////////////////////////////////////////////

                // update $req to DB //////////////////////////////
                $blog = ModelsBlog::find($id);

                $blog->title = $request->title;
                $blog->image = $new_blog_image;
                $blog->body = $request->body;


                $blog->save();
                /////////////////////////////////////////////////

                // return Job API Resource JSON Response //////////////
                return new ResourceBlog($blog);
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

    public function destroy($id)
    {
        //
        try {

            // remove old image from storage/////////////////////////////////////////////
            $img_name = DB::table('blogs')->select('image')->where('id', $id)->get();
            $img_path = "public/" . $img_name;
            Storage::delete($img_path);
            /////////////////////////////////////////////////////////////////////////////

            // delete in DB ////////////////////////////
            $blog = ModelsBlog::where('id', $id)->delete();
            if ($blog) {
                # code...
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
            // end of delete in DB //////////////
        } catch (\Throwable $th) {
            //throw $th;
            //Logs implementation goes down herer


            ////////////////////////////////////////////
        }
    }
}
