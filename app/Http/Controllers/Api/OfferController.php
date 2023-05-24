<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Addon;
use App\Http\Resources\Offer as ResourceOffer;
use App\Http\Resources\Offer\Job;
use App\Models\Jobs;
use App\Models\Offer as ModelsOffer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


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
            return Job::collection(Jobs::where('user_id',$user_id)->get());
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
            $all_offers = ModelsOffer::where('seller_id',$user_id)->orWhere('buyer_id',$user_id)->get();
            return ResourceOffer::collection($all_offers);
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

                // save $req to DB //////////////////////////////
                $offer = new ModelsOffer();
                $offer->title = $request->title;
                $offer->price = $request->price;
                $offer->delivery_period = $request->delivery_period;
                $offer->seller_id = $request->seller_id;
                $offer->buyer_id = $request->buyer_id;
                $offer->job_id = $request->job_id;
                $offer->save();
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


    public function show($id)
    {
        try {
            // Validation of $id should goes here


            /////////////////////////////////////

            $offer = ModelsOffer::find($id);
            if ($offer) {
                return new ResourceOffer($offer);
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


    public function update($id)
    {
        //
        try {
            $offer = ModelsOffer::find($id);
            // $offer->status = 1;
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


    public function destroy($id)
    {
        //
    }
}
