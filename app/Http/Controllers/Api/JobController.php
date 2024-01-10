<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Hero\JobTrans as HeroJobTrans;
use App\Http\Resources\Job as JobResource;
use App\Models\Addons as AddonModel;
use App\Models\Favorite;
use App\Models\Jobimage;
use App\Models\Jobs;
use App\Models\JobTrans;
use App\Models\Keyword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use function PHPUnit\Framework\isEmpty;



class JobController extends Controller
{
    // public static bool $is_admin;

    // public function __construct()
    // {
    //     $user = auth('sanctum')->user();
    //     $check_role = DB::table('users')->where('id', $user->id)->where("role", 1)->exists();
    //     JobController::$is_admin = $check_role;
    // }

    public function index()
    {
        try {
            $user = auth('sanctum')->user();

            if ($user) {
                $check_role = DB::table('users')->where('id', $user->id)->where("role", 1)->exists();
                if ($check_role) {
                    return JobResource::collection(Jobs::withCount('favorites')->paginate(10));
                }
            }
            return JobResource::collection(Jobs::withCount('favorites')->where('status', 1)->paginate(10));
        } catch (\Throwable $th) {
            throw $th; //this throwble should be used for logs details
        }
    }

    public function store(Request $request)
    {

        //Validations Rules //////////////////////////
        $rules = array(
            'title' => 'required',
            'image' => 'required',
            'description' => 'required',
            'price' => 'required',
            'completein' => 'required',
            // 'addons' => 'required',
            'user_id' => 'required',
            'categ_id' => 'required'
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

                // getting langs collection from .env file and converting it to array
                $langs = explode(',', Config::get('app.langs'));


                // save $req to DB //////////////////////////////
                $jobs = Jobs::create([
                    'user_id' => $request->user_id,
                    'categ_id' => $request->categ_id
                ]);

                foreach ($langs as $lang) {
                    DB::insert('insert into job_trans (title,description,price,completein,locale,job_id) values (?,?,?,?,?,?)', ([
                        $request->title,
                        $request->description,
                        $request->price,
                        $request->completein,
                        $lang,
                        $jobs->id
                    ]));
                    // JobTrans::create([
                    //     'title' => $request->title,
                    //     'description' => $request->description,
                    //     'price' => $request->price,
                    //     'completein' => $request->completein,
                    //     'locale' => $lang,
                    //     'job_id' => $jobs->id
                    // ]);
                }



                /////////////////////////////////////////////////

                // start of image logics ////////////////////////////////////////////////////////////////
                if ($request->hasFile('image')) {
                    $imgs = $request->file('image');
                    if (is_array($imgs)) {
                        $withoutSpaces = str_replace(' ', '', now());
                        // Remove symbols using regular expression
                        $withoutSymbols = preg_replace('/[^a-zA-Z0-9]/', '', $withoutSpaces);
                        foreach ($imgs as $key => $img) {

                            $new_img_name = $withoutSymbols . $key . '.' . $img->getClientOriginalExtension();

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
                        $withoutSpaces = str_replace(' ', '', now());
                        // Remove symbols using regular expression
                        $withoutSymbols = preg_replace('/[^a-zA-Z0-9]/', '', $withoutSpaces);
                        $one_img = $request->image;
                        $new_one_img = $withoutSymbols .  '.' . $one_img->getClientOriginalExtension();
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
                if (!isEmpty($request->keywords)) {
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
                }
                // end of Keyword Logic //////////////////////////////////////////////////////////////////////////////

                // start of addon logic //////////////

                if (is_array($request->addons)) {

                    foreach ($request->addons as $addon) {

                        $decoded_addon = json_decode($addon);
                        if (!empty($decoded_addon->title)) {
                            $numbersOnly = preg_replace("/[^0-9]/", "", $decoded_addon->price);

                            AddonModel::create([
                                "title" => $decoded_addon->title,
                                "price" => $numbersOnly,
                                "job_id" => $jobs->id
                            ]);
                        }
                    }
                }

                //////////////////////////////////////

                // return Job API Resource JSON Response //////////////
                return response()->json([
                    'status' => true,
                    'messages' => "Object Created"
                ], 201);
                ///////////////////////////////////////////////////////


            } catch (\Throwable $th) {
                throw $th;
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

            // admin logic part ////

            $user = auth('sanctum')->user();

            if ($user) {
                $check_role = DB::table('users')->where('id', $user->id)->where("role", 1)->exists();
                if ($check_role) { // admin
                    $job = Jobs::withCount('favorites')->find($id);
                } else { // Normal user with auth 
                    $job = Jobs::withCount('favorites')->where('status', 1)->find($id);
                }
            } else { // Normal user without auth 
                $job = Jobs::withCount('favorites')->where('status', 1)->find($id);
            }

            // end admin logic part //////

            if ($job) {
                return new JobResource($job);
            } else {
                return response()->json([
                    'status' => false,
                    'messages' => "Object Not Found"
                ], 404);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updatejob(Request $request, $id)
    {
        try {
            // its working fine
            $req_obj = [];
            $job_trans_obj = [];
            foreach ($request->all() as $db_feild => $req_feild) {
                if ($db_feild == "title") {
                    $job_trans_obj[$db_feild] = $req_feild;
                } elseif ($db_feild == "image") {
                    // start of image logics ////////////////////////////////////////////////////////////////
                    if ($request->hasFile('image')) {
                        $imgs = $request->file('image');
                        if (is_array($imgs)) {
                            $withoutSpaces = str_replace(' ', '', now());
                            // Remove symbols using regular expression
                            $withoutSymbols = preg_replace('/[^a-zA-Z0-9]/', '', $withoutSpaces);
                            // Delete image in Storage and DB ///////////////////
                            $img_name = DB::table('jobimages')->select('name')->where('job_id', $id)->get();
                            foreach ($img_name as $value) {
                                $img_path = "public/" . $value->name;
                                Storage::delete($img_path);
                            }
                            Jobimage::where('job_id', $id)->delete();
                            // end of Delete image in storage and DB ///////////
                            // add image to DB
                            foreach ($imgs as $key => $img) {

                                $new_img_name = $withoutSymbols . $key . '.' . $img->getClientOriginalExtension();

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
                            $withoutSpaces = str_replace(' ', '', now());
                            // Remove symbols using regular expression
                            $withoutSymbols = preg_replace('/[^a-zA-Z0-9]/', '', $withoutSpaces);
                            $new_one_img = $withoutSymbols . '.' . $one_img->getClientOriginalExtension();
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
                } elseif ($db_feild == "description") {
                    $job_trans_obj[$db_feild] = $req_feild;
                } elseif ($db_feild == "keywords") {
                    // we should remove old data and add new data, and if we recevie empty keywords we will remove all keywords from DB
                    // start of keyword Logic ////////////////////////////////////////////////////////////////////////////
                    if (is_array($request->keywords)) {

                        // when getting an empty request
                        if (isEmpty($request->keywords)) {
                            Keyword::where('job_id', $id)->delete();
                            continue;
                        } else {
                            Keyword::where('job_id', $id)->delete();
                            foreach ($request->keywords as $keyword) {
                                Keyword::create([
                                    'keyname' => $keyword,
                                    'job_id' => $id
                                ]);
                            }
                        }
                    } else {
                        return "Keyword is not Array";
                    }
                    // end of keyword Logic //////////////////////////////////////////////////////////////////////////////
                } elseif ($db_feild == "price") {
                    $job_trans_obj[$db_feild] = $req_feild;
                } elseif ($db_feild == "completein") {
                    $job_trans_obj[$db_feild] = $req_feild;
                } elseif ($db_feild == "addons") {
                    // rebwar will send you addon data as post data not update, it means customer updated or not updated, rebwar will send the data, and you should remove old data
                    // start of addons Logic ////////////////////////////////////////////////////////////////////////////
                    if (is_array($request->addons)) {
                        AddonModel::where('job_id', $id)->delete();
                        foreach ($request->addons as $addon) {
                            $decoded_addon = json_decode($addon);
                            if (!empty($decoded_addon->title)) {
                                $numbersOnly = preg_replace("/[^0-9]/", "", $decoded_addon->price);
                                AddonModel::create([
                                    "title" => $decoded_addon->title,
                                    "price" => $numbersOnly,
                                    "job_id" => $id
                                ]);
                            }
                        }
                    } else {
                        return "Addon is not Array";
                    }
                    // end of addons Logic //////////////////////////////////////////////////////////////////////////////
                } elseif ($db_feild == "categ_id") {
                    $req_obj[$db_feild] = $req_feild;
                }
            }
            Jobs::where('id', $id)->update($req_obj);
            JobTrans::where('job_id', $id)->where('locale', App::getLocale())->update($job_trans_obj);
            return new JobResource(Jobs::find($id));
        } catch (\Throwable $th) {
            throw $th;
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
            throw $th;
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
            throw $th;
        }
    }
    public function unAppprove($id)
    {
        try {
            $job = Jobs::find($id);

            $job->status = 0;

            $job->save();

            return response()->json([
                "status" => "success",
                "message" => "Job Status Rejected Successfully"
            ], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getjobstatus($id)
    {
        try {
            $status = DB::select('select status from jobs where id = :id', ['id' => $id]);
            return response()->json($status);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getjobsbycateg($id)
    {
        try {

            $jobs = Jobs::where('categ_id', $id)->where('status', 1)->paginate(3);


            return JobResource::collection($jobs);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function searchjobs(Request $request)
    {

        try {

            $categ_id = $request->query('category_id');
            $budget_range = $request->query('budget_range');
            $dd = $request->query('delivery_time');
            $top_rated_seller = $request->query('top_rated_seller');
            $new_sller = $request->query('new_seller');
            $search = $request->query('search');

            $jobsQuery = Jobs::query();

            $min = null;
            $max = null;
            if ($budget_range == 1) {
                $min = 5000;
                $max = 100000;
                $jobsQuery->whereHas('jobtrans', function ($query) use ($min, $max) {
                    $query->whereBetween('price', [$min, $max]);
                });
            } elseif ($budget_range == 2) {
                $min = 100000;
                $max = 400000;
                $jobsQuery->whereHas('jobtrans', function ($query) use ($min, $max) {
                    $query->whereBetween('price', [$min, $max]);
                });
            } elseif ($budget_range == 3) {
                $min = 400000;
                $max = 800000;
                $jobsQuery->whereHas('jobtrans', function ($query) use ($min, $max) {
                    $query->whereBetween('price', [$min, $max]);
                });
            } elseif ($budget_range == 4) {
                $max = 800000;
                $jobsQuery->whereHas('jobtrans', function ($query) use ($max) {
                    $query->where('price', '>=', $max);
                });
            } else {
                // All
                $jobsQuery->whereHas('jobtrans', function ($query) {
                    $query->where('price', '>=', 0);
                });
            }

            // Top rated Seller
            if ($top_rated_seller != 0) {
                $jobsQuery->whereHas('reviews', function ($query) {
                    $query->where('total_rev', '>=', 4);
                });
            }

            // New Seller
            if ($new_sller != 0) {
                $jobsQuery->where('sold', 1);
            }

            // category
            if ($categ_id != 0) {
                $jobsQuery->where('categ_id', $categ_id);
            }

            // Delivery Time
            if ($dd != 0) {
                $jobsQuery->whereHas('jobtrans', function ($query) use ($dd) {
                    $query->where('completein', '>=', $dd);
                });
            }

            if ($search != null) {
                $jobsQuery->whereHas('jobtrans', function ($query) use ($search) {
                    $query->where('title', 'LIKE', "%{$search}%");
                });
            }
            $user = auth('sanctum')->user();
            if ($user) {
                $check_role = DB::table('users')->where('id', $user->id)->where("role", 1)->exists();
            } else {
                $check_role = false;
            }

            if ($check_role) {
                $jobs = $jobsQuery->paginate(10);
            } else {
                $jobs = $jobsQuery->where('status', 1)->paginate(10);
            }
            return JobResource::collection($jobs);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function search_hero(Request $request)
    {
        try {
            $user = auth('sanctum')->user();
            $keyword = $request->query('keyword'); // The search keyword entered by the user
            $jobs_id = JobTrans::whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($keyword) . '%'])->pluck('job_id');

            if ($user) {
                $check_role = DB::table('users')->where('id', $user->id)->where("role", 1)->exists();
            } else {
                $check_role = false;
            }

            if ($check_role) {
                if ($keyword == "Unapproved") { // get approved jobs
                    return JobResource::collection(Jobs::where('status', 0)->paginate(10));
                } elseif ($keyword == "Approved") { // get un-approved jobs 
                    return JobResource::collection(Jobs::where('status', 1)->paginate(10));
                } else { // get jobs per keywords for search in title
                    $jobs = Jobs::whereIn('id', $jobs_id)->paginate(10);
                }
            } else { // search for normal user
                $jobs = Jobs::whereIn('id', $jobs_id)->where('status', 1)->paginate(10);
            }
            return JobResource::collection($jobs);
        } catch (\Throwable $th) {
            throw $th;
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
            throw $th;
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
            throw $th;
        }
    }

    public function getJobsperUser($user_id)
    {
        try {
            $jobs_per_user = Jobs::withCount('favorites')->where('user_id', $user_id)->get();
            return JobResource::collection($jobs_per_user);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updatelang(Request $request, $job_id)
    {
        try {
            JobTrans::create([
                'title' => $request->title,
                'description' => $request->description,
                'price' => $request->price,
                'completein' => $request->completein,
                'locale' => $request->locale,
                'job_id' => $job_id
            ]);

            // return Job API Resource JSON Response //////////////
            return response()->json([
                'status' => true,
                'messages' => "Object Created"
            ], 201);
            ///////////////////////////////////////////////////////

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function featured()
    {
        try {


            $jobs = Jobs::inRandomOrder()->take(8)->where('status', 1)->get();

            // $jobs = Jobs::inRandomOrder()->groupBy('categ_id')->limit(8)->get();
            return JobResource::collection($jobs);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
