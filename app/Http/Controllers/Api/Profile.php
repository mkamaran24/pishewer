<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Job;
use App\Http\Resources\Profile as ResourcesProfile;
use App\Http\Resources\Review as ResourcesReview;
use App\Http\Resources\ViewProfile\AllJobProfile;
use App\Models\City;
use App\Models\Jobs;
use App\Models\Message;
use App\Models\Profile as ModelsProfile;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\Cast\Double;

class Profile extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //Validations Rules //////////////////////////
        $rules = array(
            'title' => 'required',
            'description' => 'required',
            'skills' => 'required',
            'langs' => 'required',
            'certification' => 'required',
            'city_id' => 'required',
            'age' => 'required',
            'gender' => 'required',
            'user_id' => 'required'
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

                $one_natid = $request->nationalid;
                $image_profile = $request->imageprofile;
                $new_one_natid = random_int(100000, 999999) . '.' . $one_natid->getClientOriginalExtension();
                $new_image_profile = 'IMG_Profile' . random_int(100000, 999999) . '.' . $image_profile->getClientOriginalExtension();
                // set NatID into Global String VAR ///////////////////////////////////////////////////////////
                // $Global_Natid = $new_one_natid . ',';
                ////////////////////////////////////////////////////////////////////////////
                // save image in laravel Private Storage ///////////////////////////////////
                Storage::disk('public')->put($new_one_natid, file_get_contents($one_natid));

                Storage::disk('public')->put($new_image_profile, file_get_contents($image_profile));
                /////////////////////////////////////////////////////////////////////////////

                // start of NationalID logics ////////////////////////////////////////////////////////////////
                // if ($request->hasFile('nationalid')) {
                //     $natinal_id = $request->file('nationalid');
                //     if (is_array($natinal_id)) {
                //         foreach ($natinal_id as $key => $nat_id) {

                //             $new_natid_name = random_int(100000, 999999) . $key . '.' . $nat_id->getClientOriginalExtension();

                //             // convert NatID from Array to String Logic ///////////////////////////////////////////////////////////
                //             $Global_Natid = $Global_Natid . $new_natid_name . ',';
                //             ////////////////////////////////////////////////////////////////////////////

                //             // save image in laravel Private Storage ///////////////////////////////////
                //             Storage::disk('public')->put($new_natid_name, file_get_contents($nat_id));
                //             /////////////////////////////////////////////////////////////////////////////
                //         }
                //     } else {
                //         $one_natid = $request->nationalid;
                //         $new_one_natid = random_int(100000, 999999) . '.' . $one_natid->getClientOriginalExtension();
                //         // set NatID into Global String VAR ///////////////////////////////////////////////////////////
                //         $Global_Natid = $new_one_natid . ',';
                //         ////////////////////////////////////////////////////////////////////////////
                //         // save image in laravel Private Storage ///////////////////////////////////
                //         Storage::disk('public')->put($new_one_natid, file_get_contents($one_natid));
                //         /////////////////////////////////////////////////////////////////////////////
                //     }
                // } else {
                //     return "File Not Found";
                // }
                // end of NationalID Logics /////////////////////////////////////////////////////////////////////////////////

                // save $req to DB //////////////////////////////
                $profile = ModelsProfile::create([
                    'title' => $request->title,
                    'description' => $request->description,
                    'skills' => implode(',', $request->skills),
                    'langs' => implode(',', $request->langs),
                    'certification' => $request->certification,
                    'nationalid' => $new_one_natid,
                    "imageprofile" => $new_image_profile,
                    'city_id' => $request->city_id,
                    'age' => $request->age,
                    'gender' => $request->gender,
                    'user_id' => $request->user_id
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        try {
            //code...
            $profile = ModelsProfile::where('user_id',$id)->get();
            // return isset($profile[0]->profiletranslation[0]->title);
            if ($profile) {
                $city_name = City::where('id',$profile[0]->city_id)->get();
                $user = User::where('id',$id)->get();
                $jobs = Jobs::where('user_id', $id)->get();
                $messages = DB::select('select msg_time,resp_time from messages where recever_id = ? AND status = ?', [$id, 1]);

                $avg_resp_time = null;
                if ($messages) {
                    // avg_resp time logic ///////////////////////////////////////////
                    //////////////////////////////////////////////////////////////////
                    $seconds = array();
                    for ($i = 0; $i < count($messages); $i++) {
                        $seconds[$i] = strtotime($messages[$i]->resp_time) - strtotime($messages[$i]->msg_time);
                    }


                    $avg_resp_time_in_sec = array_sum($seconds) / count($seconds);

                    if ($avg_resp_time_in_sec < 3600 && $avg_resp_time_in_sec >= 60) {
                        $avg_resp_time_in_min = $avg_resp_time_in_sec / 60;
                        $avg_resp_time = round($avg_resp_time_in_min)  . "minute";
                    } elseif ($avg_resp_time_in_sec >= 3600) {
                        $avg_resp_time_in_min = $avg_resp_time_in_sec / 60;
                        $avg_resp_time_in_hour = $avg_resp_time_in_min / 60;
                        $avg_resp_time = round($avg_resp_time_in_hour) . "hour";
                    } else {
                        $avg_resp_time = round($avg_resp_time_in_sec) . "second";
                    }
                }
                // end of avg resp time log //////////////////////////////////////
                /////////////////////////////////////////////////////////////////

                // get all review of all jobs logic /////////////////////////////////////////////
                //////////////////////////////////////////////////////////////////////////////////
                $job_reviews = [];
                foreach ($jobs as $job) {
                    $reviews = Review::where('job_id', $job->id)->get();
                    $job_reviews[] = $reviews;
                }
                $reviews_collection = collect($job_reviews)->flatten();

                $total_rev_collection = array();

                for ($i = 0; $i < count($reviews_collection); $i++) {
                    $total_rev = ($reviews_collection[$i]->service_quality + $reviews_collection[$i]->commun_followup + $reviews_collection[$i]->panctual_delevery) / 3;
                    $total_rev_collection[$i] = floor($total_rev);
                }
                // end of get all review of all jobs logic /////////////////////////////////////////////
                //////////////////////////////////////////////////////////////////////////////////


                $new_date = Carbon::createFromFormat('Y-m-d H:i:s', $user[0]->created_at)->format('d-m-Y');
                

                $delimiters = ['-', ',', '٬','،']; // Array of delimiters
                $escapedDelimiters = array_map('preg_quote', $delimiters);
                $pattern = implode('|', $escapedDelimiters);
                
                return response()->json([
                    "locale" => App::getLocale(),
                    "image_profile" => $profile[0]->imageprofile,
                    "username" => isset($user[0]->usertranslations[0]->username) ? $user[0]->usertranslations[0]->username : null,
                    "title" => (isset($profile[0]->profiletranslation[0]->title)) ? $profile[0]->profiletranslation[0]->title : null ,
                    "location" => isset($city_name[0]->citytranslations[0]->cityname) ? $city_name[0]->citytranslations[0]->cityname : null,
                    "member_since" => $new_date,
                    "avg_response_time" => $avg_resp_time,
                    "description" => isset($profile[0]->profiletranslation[0]->description) ? $profile[0]->profiletranslation[0]->description : null,
                    "skills" => isset($profile[0]->profiletranslation[0]->skills) ? preg_split("/$pattern/u", $profile[0]->profiletranslation[0]->skills) : null,
                    "certification" => isset($profile[0]->profiletranslation[0]->certification) ? $profile[0]->profiletranslation[0]->certification : null,
                    "total_review_number" => count($reviews_collection),
                    "all_rev_avg_stars" => (count($total_rev_collection) != 0) ? round(array_sum($total_rev_collection) / count($total_rev_collection), 1) : 0,
                    "reviews" => ResourcesReview::collection($reviews_collection),
                    "Jobs" => AllJobProfile::collection($jobs),
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => "Object Not Found",
                ], 404);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function updateprofile(Request $request, $id)
    {
        //
        //Validations Rules //////////////////////////
        $rules = array(
            'title' => 'required',
            'description' => 'required',
            'skills' => 'required',
            'langs' => 'required',
            'certification' => 'required',
            'city_id' => 'required',
            'age' => 'required',
            'gender' => 'required',
            'user_id' => 'required'
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

                $one_natid = $request->nationalid;
                $image_profile = $request->imageprofile;
                $new_one_natid = random_int(100000, 999999) . '.' . $one_natid->getClientOriginalExtension();
                $new_image_profile = 'IMG_Profile' . random_int(100000, 999999) . '.' . $image_profile->getClientOriginalExtension();
                // set NatID into Global String VAR ///////////////////////////////////////////////////////////
                // $Global_Natid = $new_one_natid . ',';
                ////////////////////////////////////////////////////////////////////////////
                // save image in laravel Private Storage ///////////////////////////////////
                Storage::disk('public')->put($new_one_natid, file_get_contents($one_natid));

                Storage::disk('public')->put($new_image_profile, file_get_contents($image_profile));
                /////////////////////////////////////////////////////////////////////////////

                // $Global_Natid = '';
                // // start of NationalID logics ////////////////////////////////////////////////////////////////
                // if ($request->hasFile('nationalid')) {
                //     $natinal_id = $request->file('nationalid');
                //     if (is_array($natinal_id)) {
                //         foreach ($natinal_id as $key => $nat_id) {

                //             $new_natid_name = random_int(100000, 999999) . $key . '.' . $nat_id->getClientOriginalExtension();

                //             // convert NatID from Array to String Logic ///////////////////////////////////////////////////////////
                //             $Global_Natid = $Global_Natid . $new_natid_name . ',';
                //             ////////////////////////////////////////////////////////////////////////////

                //             // save image in laravel Private Storage ///////////////////////////////////
                //             Storage::disk('public')->put($new_natid_name, file_get_contents($nat_id));
                //             /////////////////////////////////////////////////////////////////////////////
                //         }
                //     } else {
                //         $one_natid = $request->nationalid;
                //         $new_one_natid = random_int(100000, 999999) . '.' . $one_natid->getClientOriginalExtension();
                //         // set NatID into Global String VAR ///////////////////////////////////////////////////////////
                //         $Global_Natid = $new_one_natid . ',';
                //         ////////////////////////////////////////////////////////////////////////////
                //         // save image in laravel Private Storage ///////////////////////////////////
                //         Storage::disk('public')->put($new_one_natid, file_get_contents($one_natid));
                //         /////////////////////////////////////////////////////////////////////////////
                //     }
                // } else {
                //     return "File Not Found";
                // }
                // end of NationalID Logics /////////////////////////////////////////////////////////////////////////////////

                // update $req to DB //////////////////////////////
                $profile = ModelsProfile::find($id);

                $profile->title = $request->title;
                $profile->description = $request->description;
                $profile->skills = implode(',', $request->skills);
                $profile->langs = implode(',', $request->langs);
                $profile->certification = $request->certification;
                $profile->nationalid = $new_one_natid;
                $profile->imageprofile = $new_image_profile;
                $profile->city_id = $request->city_id;
                $profile->age = $request->age;
                $profile->gender = $request->gender;
                $profile->user_id = $request->user_id;

                $profile->save();
                /////////////////////////////////////////////////

                // return Job API Resource JSON Response //////////////
                return new ResourcesProfile($profile);
                ///////////////////////////////////////////////////////


            } catch (\Throwable $th) {
                // abort(code: 500, message: 'fail to create');
                //throw $th;
                // return response()->json([
                //     'status' => false,
                //     'message' => $th->getMessage(),
                // ], 500);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
