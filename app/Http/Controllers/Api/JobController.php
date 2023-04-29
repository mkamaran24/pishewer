<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Job as JobResource;
use App\Models\Addons as AddonModel;
use App\Models\Favorite;
use App\Models\Jobimage;
use App\Models\Jobs;
use App\Models\Keyword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isEmpty;

class JobController extends Controller
{

    public function index()
    {

        try {
            return JobResource::collection(Jobs::withCount('favorites')->get());
        } catch (\Throwable $th) {

            abort(code: 500, message: 'fail to fetch');
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
            'keywords' => 'required',
            'price' => 'required',
            'completein' => 'required',
            'addons' => 'required',
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

                // save $req to DB //////////////////////////////
                $jobs = Jobs::create([
                    'title' => $request->title,
                    'description' => $request->description,
                    'price' => $request->price,
                    'completein' => $request->completein,
                    'user_id' => $request->user_id,
                    'categ_id' => $request->categ_id,
                    'subcateg_id' => $request->subcateg_id
                ]);
                /////////////////////////////////////////////////

                // start of image logics ////////////////////////////////////////////////////////////////
                if ($request->hasFile('image')) {
                    $imgs = $request->file('image');
                    if (is_array($imgs)) {
                        foreach ($imgs as $key => $img) {

                            $new_img_name = random_int(100000, 999999) . $key . '.' . $img->getClientOriginalExtension();

                            // save image name into DB ///////////////////////////////////////////////////////////
                            Jobimage::create([
                                'name' => $new_img_name,
                                'job_id' => $jobs->id
                            ]);
                            ////////////////////////////////////////////////////////////////////////////

                            // save image in laravel Private Storage ///////////////////////////////////
                            Storage::disk('public')->put($new_img_name, file_get_contents($img));
                            /////////////////////////////////////////////////////////////////////////////
                        }
                    } else {
                        $one_img = $request->image;
                        $new_one_img = random_int(100000, 999999) . '.' . $one_img->getClientOriginalExtension();
                        // save image name into DB ///////////////////////////////////////////////////////////
                        Jobimage::create([
                            'name' => $new_one_img,
                            'job_id' => $jobs->id
                        ]);
                        ////////////////////////////////////////////////////////////////////////////
                        // save image in laravel Private Storage ///////////////////////////////////
                        Storage::disk('public')->put($new_one_img, file_get_contents($one_img));
                        /////////////////////////////////////////////////////////////////////////////
                    }
                } else {
                    Jobimage::create([
                        'name' => "File Not Founde",
                        'job_id' => $jobs->id
                    ]);
                }
                // end of Image Logics /////////////////////////////////////////////////////////////////////////////////

                // start of Keword Logic ////////////////////////////////////////////////////////////////////////////
                if (is_array($request->keywords)) {

                    foreach ($request->keywords as $keyword) {
                        Keyword::create([
                            'keyname' => $keyword,
                            'job_id' => $jobs->id
                        ]);
                    }
                } else {
                    Keyword::create([
                        'keyname' => $request->keywords,
                        'job_id' => $jobs->id
                    ]);
                }
                // end of Keyword Logic //////////////////////////////////////////////////////////////////////////////

                // start of addon logic //////////////

                if (is_array($request->addons)) {

                    foreach ($request->addons as $addon) {

                        $decoded_addon = json_decode($addon);

                        AddonModel::create([
                            "title" => $decoded_addon->title,
                            "price" => $decoded_addon->price,
                            "job_id" => $jobs->id
                        ]);
                    }
                } else {
                    return "Addon is not Array";
                }

                //////////////////////////////////////

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

            $job = Jobs::withCount('favorites')->find($id);
            if ($job) {
                return new JobResource($job);
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

    //     // $size = sizeof($request->all());

    //     // return $size;



    //     try {
    //         // Validation of $id should goes here
    //         // Jobs::where('id',$id)->update($request->all());

    //         return $request->all();

    //         /////////////////////////////////////

    //         // Validation of $request should goes here

    //         //Validations Rules //////////////////////////
    //         $rules = array(
    //             'title' => 'required',
    //             'description' => 'required',
    //             'keyword' => 'required',
    //             'price' => 'required',
    //             'completein' => 'required',
    //             'user_id' => 'required',
    //             'categ_id' => 'required',
    //             'subcateg_id' => 'required'
    //         );
    //         /// end of Validation Rules ////////////////////

    //         //Validation Custom Messages
    //         // $messages = array('title'=>'All data required');


    //         // Validator Check //////////////////////////////
    //         $validator = Validator::make($request->all(), $rules);
    //         if ($validator->fails()) {
    //             $messages = $validator->messages();
    //             $errors = $messages->all(); //convert them into one array
    //             return response()->json([
    //                 'status' => false,
    //                 'reason' => 'Validation Fails',
    //                 'messages' => $errors,
    //             ], 422);
    //         }

    //         /////////////////////////////////////

    //         // start of image logic /////
    //         $trim_imgs_path = '';
    //         if ($request->hasFile('image')) {
    //             $imgs = $request->file('image');
    //             $all_imgs_path = '';
    //             if (is_array($imgs)) {
    //                 foreach ($imgs as $key => $img) {
    //                     $new_img_name = random_int(100000, 999999) . $key . '.' . $img->getClientOriginalExtension();
    //                     // save image in laravel Private Storage ///////////////////////////////////
    //                     Storage::disk('public')->put($new_img_name, file_get_contents($img));
    //                     /////////////////////////////////////////////////////////////////////////////
    //                     $all_imgs_path = $all_imgs_path . $new_img_name . ',';
    //                 }
    //                 $trim_imgs_path = substr($all_imgs_path, 0, -1);
    //             } else {
    //                 $one_img = $request->image;
    //                 $trim_imgs_path = random_int(100000, 999999) . '.' . $one_img->getClientOriginalExtension();
    //                 // save image in laravel Private Storage ///////////////////////////////////
    //                 Storage::disk('public')->put($trim_imgs_path, file_get_contents($one_img));
    //                 /////////////////////////////////////////////////////////////////////////////
    //             }
    //         } else {
    //             $trim_imgs_path = "File Not Found";
    //         }


    //         // $all_img_name = '';
    //         // if (is_array($request->image)) {
    //         //     foreach ($request->image as $key => $img) {
    //         //         $all_img_name = $all_img_name . $img . ',';
    //         //     }
    //         //     $trim_all_img_name = substr($all_img_name, 0, -1);
    //         // } else {
    //         //     return "image field should be array";
    //         // }

    //         // end of image logic /////

    //         // start of Keword Logic ////////////////////////////////////////////////////////////////////////////
    //         $converted_keyword = '';
    //         if (is_array($request->keyword)) {
    //             $converted_keyword = implode(',', $request->keyword);
    //         } else {
    //             $converted_keyword = $request->keyword;
    //         }
    //         // end of Keyword Logic //////////////////////////////////////////////////////////////////////////////

    //         // update selected job based on $id ////////////////////
    //         $job = Jobs::find($id);
    //         if ($job) {

    //             $job->title = $request->title;
    //             // $job->image = $trim_all_img_name;
    //             $job->description = $request->description;
    //             $job->keyword = $converted_keyword;
    //             $job->price = $request->price;
    //             $job->completein = $request->completein;
    //             $job->user_id = $request->user_id;
    //             $job->categ_id = $request->categ_id;
    //             $job->subcateg_id = $request->subcateg_id;

    //             $job->save();

    //             // end of updated selected job based on $id ////////////////

    //             // start of addon logic //////////////
    //             if (is_array($request->addons)) {
    //                 foreach ($request->addons as $key => $addonArray) {
    //                     AddonModel::where('job_id', $job->id)
    //                         ->where('id', $addonArray['id'])
    //                         ->update([
    //                             'title' => $addonArray['title'],
    //                             'price' => $addonArray['price'],
    //                             'job_id' => $job->id
    //                         ]);
    //                 }
    //             } else {
    //                 return "Addon is not Array";
    //             }
    //             //////////////////////////////////////

    //             return new JobResource($job);
    //         } else {
    //             return response()->json([
    //                 'status' => false,
    //                 'messages' => "Object Not Found"
    //             ], 404);
    //         }
    //     } catch (\Throwable $th) {
    //         //throw $th;
    //         // abort(code: 500, message: 'fail to update');
    //         //Logs implementation goes down herer
    //         return response()->json([
    //             'status' => false,
    //             'message' => $th->getMessage(),
    //         ], 500);

    //         ////////////////////////////////////////////
    //     }
    // }

    public function updatejob(Request $request, $id)
    {
        try {
            // its working fine
            $req_obj = [];
            foreach ($request->all() as $db_feild => $req_feild) {
                if ($db_feild == "title") {
                    $req_obj[$db_feild] = $req_feild;
                }
                // image and Not Removed Image
                elseif ($db_feild == "image" && !array_key_exists("removed_img", $request->all())) {
                    // start of image logics ////////////////////////////////////////////////////////////////
                    if ($request->hasFile('image')) {
                        $imgs = $request->file('image');
                        if (is_array($imgs)) {
                            foreach ($imgs as $key => $img) {

                                $new_img_name = random_int(100000, 999999) . $key . '.' . $img->getClientOriginalExtension();

                                // save image name into DB ///////////////////////////////////////////////////////////
                                Jobimage::create([
                                    'name' => $new_img_name,
                                    'job_id' => $id
                                ]);
                                ////////////////////////////////////////////////////////////////////////////

                                // save image in laravel Private Storage ///////////////////////////////////
                                Storage::disk('public')->put($new_img_name, file_get_contents($img));
                                /////////////////////////////////////////////////////////////////////////////
                            }
                        } else {
                            $one_img = $request->image;
                            $new_one_img = random_int(100000, 999999) . '.' . $one_img->getClientOriginalExtension();
                            // save image name into DB ///////////////////////////////////////////////////////////
                            Jobimage::create([
                                'name' => $new_one_img,
                                'job_id' => $id
                            ]);
                            ////////////////////////////////////////////////////////////////////////////
                            // save image in laravel Private Storage ///////////////////////////////////
                            Storage::disk('public')->put($new_one_img, file_get_contents($one_img));
                            /////////////////////////////////////////////////////////////////////////////
                        }
                    } else {
                        Jobimage::create([
                            'name' => "File Not Founde",
                            'job_id' => $id
                        ]);
                    }
                    // end of Image Logics /////////////////////////////////////////////////////////////////////////////////
                }
                // Not Image and Removed Image
                elseif (!array_key_exists("image", $request->all()) && $db_feild == "removed_img") {
                    if (is_array($request->removed_img)) {
                        foreach ($request->removed_img as $rmImage) {
                            Jobimage::where("name", $rmImage)->delete();
                            $img_path = "public/" . $rmImage;
                            Storage::delete($img_path);
                        }
                    } else {
                        return "Removed Images is Not Array";
                    }
                }
                // both Image and Remvoed image
                elseif ($db_feild == "image" && array_key_exists("removed_img", $request->all())) {
                    // start of image logics ////////////////////////////////////////////////////////////////
                    if ($request->hasFile('image')) {
                        $imgs = $request->file('image');
                        if (is_array($imgs)) {
                            foreach ($imgs as $key => $img) {

                                $new_img_name = random_int(100000, 999999) . $key . '.' . $img->getClientOriginalExtension();

                                // save image name into DB ///////////////////////////////////////////////////////////
                                Jobimage::create([
                                    'name' => $new_img_name,
                                    'job_id' => $id
                                ]);
                                ////////////////////////////////////////////////////////////////////////////

                                // save image in laravel Private Storage ///////////////////////////////////
                                Storage::disk('public')->put($new_img_name, file_get_contents($img));
                                /////////////////////////////////////////////////////////////////////////////
                            }
                        } else {
                            $one_img = $request->image;
                            $new_one_img = random_int(100000, 999999) . '.' . $one_img->getClientOriginalExtension();
                            // save image name into DB ///////////////////////////////////////////////////////////
                            Jobimage::create([
                                'name' => $new_one_img,
                                'job_id' => $id
                            ]);
                            ////////////////////////////////////////////////////////////////////////////
                            // save image in laravel Private Storage ///////////////////////////////////
                            Storage::disk('public')->put($new_one_img, file_get_contents($one_img));
                            /////////////////////////////////////////////////////////////////////////////
                        }
                    } else {
                        Jobimage::create([
                            'name' => "File Not Founde",
                            'job_id' => $id
                        ]);
                    }
                    // end of Image Logics /////////////////////////////////////////////////////////////////////////////////

                    // start of remove image logic//////////////////////////////////
                    if (is_array($request->removed_img)) {
                        foreach ($request->removed_img as $rmImage) {
                            Jobimage::where("name", $rmImage)->delete();
                            $img_path = "public/" . $rmImage;
                            Storage::delete($img_path);
                        }
                    } else {
                        return "Removed Images is Not Array";
                    }
                    // end of remove image logic ///////////////////////////////////////

                } elseif ($db_feild == "description") {
                    $req_obj[$db_feild] = $req_feild;
                } elseif ($db_feild == "keywords") {
                    // start of keyword Logic ////////////////////////////////////////////////////////////////////////////
                    if (is_array($request->keywords)) {
                        foreach ($request->keywords as $key => $keywordArray) {
                            $decoded_keyword = json_decode($keywordArray);
                            Keyword::where('job_id', $id)
                                ->where('id', $decoded_keyword->id)
                                ->update([
                                    'keyname' => $decoded_keyword->keyname,
                                    'job_id' => $id
                                ]);
                        }
                    } else {
                        return "Keyword is not Array";
                    }
                    // end of keyword Logic //////////////////////////////////////////////////////////////////////////////
                } elseif ($db_feild == "price") {
                    $req_obj[$db_feild] = $req_feild;
                } elseif ($db_feild == "completein") {
                    $req_obj[$db_feild] = $req_feild;
                } elseif ($db_feild == "addons") {
                    // start of addons Logic ////////////////////////////////////////////////////////////////////////////
                    if (is_array($request->addons)) {
                        foreach ($request->addons as $key => $addonArray) {
                            $decoded_addon = json_decode($addonArray);
                            AddonModel::where('job_id', $id)
                                ->where('id', $decoded_addon->id)
                                ->update([
                                    'title' => $decoded_addon->title,
                                    'price' => $decoded_addon->price,
                                    'job_id' => $id
                                ]);
                        }
                    } else {
                        return "Addon is not Array";
                    }
                    // end of addons Logic //////////////////////////////////////////////////////////////////////////////
                } elseif ($db_feild == "user_id") {
                    $req_obj[$db_feild] = $req_feild;
                } elseif ($db_feild == "categ_id") {
                    $req_obj[$db_feild] = $req_feild;
                } elseif ($db_feild == "subcateg_id") {
                    $req_obj[$db_feild] = $req_feild;
                }
            }

            Jobs::where('id', $id)
                ->update($req_obj);

            return new JobResource(Jobs::find($id));
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        // Validation of $id should goes here

        //////////////////////////////////////

        try {

            // Delete image in Storage ///////////////////
            $img_name = DB::table('jobimages')->select('name')->where('job_id', $id)->get();
            foreach ($img_name as $value) {
                $img_path = "public/" . $value->name;
                Storage::delete($img_path);
            }
            // end of Delete image in storage ///////////

            // delete in DB ////////////////////////////
            $job = Jobs::where('id', $id)->delete();
            if ($job) {
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

    public function updatestatus($id)
    {
        try {
            $job = Jobs::find($id);

            $job->status = 1;

            $job->save();

            return response()->json([
                "status" => "success",
                "message" => "Job Approved Successfully"
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;

        }
    }

    public function getjobstatus($id)
    {
        try {
            $status = DB::select('select status from jobs where id = :id', ['id' => $id]);
            return response()->json($status);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getjobsbycateg($id)
    {
        try {
            $jobs = Jobs::where('categ_id', $id)->paginate(3);
            return JobResource::collection($jobs);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function searchjobs(Request $request)
    {

        try {

            $keyword = $request->query('keyword');
            $categ_id = $request->query('category_id');
            $sub_categ_id = $request->query('subcategory_id');
            $min = $request->query('budget_min');
            $max = $request->query('budget_max');
            $dd = $request->query('delivery_time');
            $top_rated_seller = $request->query('top_rated_seller');
            $new_sller = $request->query('new_seller');

            $jobsQuery = Jobs::query();

            // Keywrod 
            if ($keyword != 0) {
                $jobsQuery->whereHas('keywords', function ($query) use ($keyword) {
                    $query->where('keyname', 'like', "%$keyword%");
                });
            }

            // Top rated Seller
            if ($top_rated_seller != 0) {
                $jobsQuery->whereHas('reviews', function ($query) use ($keyword) {
                    $query->where('total_rev', '>=', 4);
                });
            }

            // New Seller
            if ($new_sller != 0) {
                $jobsQuery->where('sold', 1);
            }

            // min and max price
            if ($min != 0 && $max != 0) {
                $jobsQuery->whereBetween('price', [$min, $max]);
            }

            // category
            if ($categ_id != 0) {
                $jobsQuery->where('categ_id', $categ_id);
            }

            // Subcategory
            if ($sub_categ_id != 0) {
                $jobsQuery->where('subcateg_id', $sub_categ_id);
            }

            // Delivery Time
            if ($dd != 0) {
                $jobsQuery->where('completein', '>=', $dd);
            }

            $jobs = $jobsQuery->paginate(10);

            return JobResource::collection($jobs);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function favorite(Request $request, $id)
    {
        try {
            $user = $request->user();
            $fav_status = Favorite::where('user_id', $user->id)->where('job_id', $id)->get();
            if ($fav_status->isEmpty()) {
                // save $req to DB //////////////////////////////
                $fav = new Favorite();
                $fav->job_id = $id;
                $fav->user_id = $user->id;
                $fav->save();
                /////////////////////////////////////////////////

                // return Job API Resource JSON Response //////////////
                return response()->json([
                    'status' => true,
                    'messages' => "Object Created"
                ], 201);
                ///////////////////////////////////////////////////////
            } else {
                return response()->json([
                    "status" => true,
                    "message" => "recorde already exist"
                ], 409);
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function unfavorite(Request $request, $id)
    {

        try {
            
            $user = $request->user();
            $fav_status = Favorite::where('user_id', $user->id)->where('job_id', $id)->delete();
            if ($fav_status) {

                return response()->json([
                    "status" => true,
                    "message" => "Delete Success"
                ], 200);
            } else {


                // return Job API Resource JSON Response //////////////
                return response()->json([
                    'status' => false,
                    'messages' => "recorde not found"
                ], 404);
                ///////////////////////////////////////////////////////
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
