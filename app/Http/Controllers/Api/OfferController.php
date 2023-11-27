<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Addon;
use App\Http\Resources\Offer as ResourceOffer;
use App\Http\Resources\Offer\Job;
use App\Models\Attachment;
use App\Models\Jobs;
use App\Models\Offer as ModelsOffer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OfferController extends Controller
{

    public function index()
    {
        try {
            return ResourceOffer::collection(ModelsOffer::all());
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getAlljobs($user_id)
    {
        try {
            return Job::collection(Jobs::where('user_id', $user_id)->where('status', 1)->get());
        } catch (\Throwable $th) {
            //throw $th;
        }
    }




    // public function storeExistjobs(Request $request)
    // {
    //     //Validations Rules //////////////////////////
    //     $rules = array(
    //         'title' => 'required',
    //         'price' => 'required',
    //         'delivery_period' => 'required',
    //         'seller_id' => 'required',
    //         'buyer_id' => 'required',
    //         'job_id' => 'required',
    //     );
    //     /// end of Validation Rules ////////////////////

    //     // Validator Check //////////////////////////////
    //     $validator = Validator::make($request->all(), $rules);
    //     if ($validator->fails()) {
    //         $messages = $validator->messages();
    //         $errors = $messages->all(); //convert them into one array
    //         return response()->json([
    //             'status' => false,
    //             'reason' => 'Validation Fails',
    //             'messages' => $errors,
    //         ], 422);
    //     } else {
    //         # put data to DB after Succes Validation
    //         try {

    //             // save $req to DB //////////////////////////////
    //             $offer = new ModelsOffer();
    //             $offer->title = $request->title;
    //             $offer->price = $request->price;
    //             $offer->delivery_period = $request->delivery_period;
    //             $offer->seller_id = $request->seller_id;
    //             $offer->buyer_id = $request->buyer_id;
    //             $offer->job_id = $request->job_id;
    //             $offer->save();
    //             /////////////////////////////////////////////////

    //             // return Job API Resource JSON Response //////////////
    //             return response()->json([
    //                 'status' => true,
    //                 'messages' => "Object Created"
    //             ], 201);
    //             ///////////////////////////////////////////////////////


    //         } catch (\Throwable $th) {
    //             // abort(code: 500, message: 'fail to create');
    //             //throw $th;
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => $th->getMessage(),
    //             ], 500);
    //         }
    //     }
    // }


    public function OffersperUsers($user_id)
    {
        try {
            $all_offers = ModelsOffer::where('seller_id', $user_id)->orWhere('buyer_id', $user_id)->get();

            $specific_property_count = $all_offers->countBy('offer_state');

            return response()->json([
                'count_states' => $specific_property_count,
                'offers_detail' => ResourceOffer::collection($all_offers)
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }


    public function store(Request $request)
    {
        //Validations Rules //////////////////////////
        $rules = array(
            'title' => 'required',
            'price' => 'required',
            'delivery_period' => 'required',
            'seller_id' => 'required',
            'buyer_id' => 'required',
            'job_id' => 'required',
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

                // generate the offer code
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $code = substr(str_shuffle($characters), 0, 8);
                $isUnique = DB::table('offers')->where('offer_code', $code)->doesntExist();

                // checking if its Unique or not
                while (!$isUnique) {
                    $code = substr(str_shuffle($characters), 0, 8);
                    $isUnique = DB::table('offers')->where('offer_code', $code)->doesntExist();
                }

                // save $req to DB //////////////////////////////
                $offer = new ModelsOffer();
                $offer->title = $request->title;
                $offer->price = $request->price;
                $offer->offer_code = $code;
                $offer->delivery_period = $request->delivery_period;
                $offer->seller_id = $request->seller_id;
                $offer->buyer_id = $request->buyer_id;
                $offer->job_id = $request->job_id;
                $offer->save();
                /////////////////////////////////////////////////

                // return Job API Resource JSON Response //////////////
                return response()->json([
                    'status' => true,
                    'messages' => "Object Created",
                    'offer_code' => $offer->offer_code
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


    public function show($user_id, $offer_code)
    {
        try {
            // Validation of $id should goes here


            /////////////////////////////////////

            $offer = ModelsOffer::where('offer_code', $offer_code)->where('seller_id', $user_id)->orWhere('buyer_id', $user_id)->get();

            if ($offer) {
                return ResourceOffer::collection($offer);
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


    public function accept($id)
    {
        //
        try {

            $offer = ModelsOffer::find($id);
            $offer->offer_state = "payment";
            $offer->save();
            $commission_fee = $offer->price * 0.05;
            $total_price = $offer->price + $commission_fee;
            return response()->json([
                'status' => true,
                'messages' => "Offer Accepted Successfully",
                'data' => [
                    'offer_id' => (string)$offer->id,
                    'offer_title' => $offer->title,
                    // 'seller_name' => $offer->user->username,
                    'addons' => Addon::collection($offer->job->addons),
                    'offer_price' => $offer->price,
                    'commission_percentage' => "0.05",
                    'commission_fee' => (string) $commission_fee,
                    'total_price' => (string) $total_price
                ]
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function reject($id)
    {
        try {
            $offer = ModelsOffer::find($id);
            // $offer->status = 1;
            $offer->offer_state = "rejected";
            $offer->save();
            return response()->json([
                'status' => true,
                'messages' => "Offer Rejected Successfully"
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function cancel($id)
    {
        try {
            $offer = ModelsOffer::find($id);
            // $offer->status = 1;
            $offer->offer_state = "canceled";
            $offer->save();
            return response()->json([
                'status' => true,
                'messages' => "Offer Canceled Successfully"
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }


    public function destroy($id)
    {
        //
    }

    public function upload(Request $request, $offer_id)
    {
        try {

            //Validations Rules //////////////////////////
            $rules = array(
                'zip' => 'required|mimes:zip|max:2048'
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

                    // put zip file to public folder

                    // get zip file from reuest
                    $zip_file = $request->file('zip');

                    // Generate a new name for the zip file
                    $newZipName = time() . '_' . $zip_file->getClientOriginalName();

                    // Store the zip file in the public disk
                    Storage::disk('public')->put($newZipName, file_get_contents($zip_file));


                    //////////////////////////////////////

                    // save $req to DB //////////////////////////////
                    Attachment::create([
                        'zipfile' => $newZipName,
                        'offer_id' => $offer_id
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
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function download($attach_id)
    {
        try {



            $attachment = Attachment::find($attach_id);

            if ($attachment && $attachment->zipfile) {
                $zipfile = $attachment->zipfile;

                if (Storage::exists('public/', $zipfile)) {
                    $zip_path = 'app/public/' . $zipfile;
                    return response()->download(storage_path($zip_path));
                } else {
                    // ZIP file does not exist
                    return response()->json([
                        'status' => false,
                        'messages' => 'ZIP file does not exist',
                    ], 404);
                }
            } else {
                // Attachment not found or ZIP file column is empty
                return response()->json([
                    'status' => false,
                    'messages' => 'Attachment not found or ZIP file column is empty',
                ], 404);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    // public function testdownload()
    // {
    //     try {

    //         $zip_path = storage_path('app/public/1696705329_Peshang_Des_1_bold_ (1).zip');
    //         return response()->download($zip_path);
    //     } catch (\Throwable $th) {
    //         //throw $th;
    //     }
    // }
}
