<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Order as ResourceOrder;
use App\Models\Invoice;
use App\Models\Offer;
use App\Models\Order as ModelsOrder;
use App\Models\OfferAddon as AddonModel;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{

    public function index()
    {
        //
        return ResourceOrder::collection(ModelsOrder::simplePaginate(10));
    }


    public function store(Request $request)
    {
        //Validations Rules //////////////////////////
        $rules = array(
            'fastpay_number' => 'required',
            'total_price' => 'required',
            'offer_price' => 'required',
            'total_addon_price' => 'required',
            'comision_fee' => 'required',
            'buyer_id' => 'required',
            'offer_id' => 'required'
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

                // dd($request->offer_id);

                // save $req to DB //////////////////////////////
                $order = new ModelsOrder();
                $order->fastpay_number = $request->fastpay_number;
                $order->total_price = $request->total_price;
                $order->offer_price = $request->offer_price;
                $order->total_addon_price = $request->total_addon_price;
                $order->comision_fee = $request->comision_fee;
                $order->buyer_id = $request->buyer_id;
                $order->offer_id = $request->offer_id;
                $order->save();
                /////////////////////////////////////////////////

                // start of addon logic //////////////

                if (is_array($request->addons)) {

                    foreach ($request->addons as $addon) {

                        AddonModel::create([
                            "title" => $addon['title'],
                            "price" => $addon['price'],
                            "offer_id" => $order->offer_id
                        ]);
                    }
                } else {
                    return "Addon is not Array";
                }

                // update offer state from payment to Pendingpayment ////////////////////////////////////
                Offer::where('id', $request->offer_id)->update(['offer_state' => 'Pendingpayment']);
                /////////////////////////////////////////////////////////////////////////////////////////


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

            $order = ModelsOrder::where('buyer_id', $id)->get();
            if (!$order->isEmpty()) {
                return  ResourceOrder::collection($order);
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


    public function update($payment_id)
    {
        try {
            $now = Carbon::now();
            // $custom_now = $now->addHours(3);

            $order = ModelsOrder::find($payment_id);
            if ($order) {
                $offer = Offer::find($order->offer_id);

                // Add days to the expiry date
                $numberOfDays = $offer->delivery_period; // Example: adding 7 days


                $offer->offer_expiry = $now->addDays($numberOfDays);
                $order->status = 1;
                $offer->offer_state = "inProgress";
                $order->save();
                $offer->save();
                //

                Invoice::create([
                    'offer_id' => $order->offer_id,
                    'seller_id' => $offer->seller_id,
                    'offer_amount' => $order->total_price,
                    'status' => 'Pending'
                ]);

                return new ResourceOrder($order);
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


    public function destroy($id)
    {
        //
    }
}
