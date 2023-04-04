<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlogComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class BlogCommentController extends Controller
{
    //
    public function store(Request $request)
    {
        //Validations Rules //////////////////////////
        $rules = array(
            'body' => 'required',
            'blog_id' => 'required',
            'user_id' => 'required',
            'parent_id' => 'required',
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
                $blog_comment = new BlogComment();
                $blog_comment->body = $request->body;
                $blog_comment->blog_id = $request->blog_id;
                $blog_comment->user_id = $request->user_id;
                $blog_comment->parent_id = $request->parent_id;
                $blog_comment->save();
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
}
