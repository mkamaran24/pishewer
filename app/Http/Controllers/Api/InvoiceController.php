<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Invoice;
use App\Models\Invoice as ModelsInvoice;
use App\Models\Offer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{


    public function index()
    {
        try {
            $all_invoice = ModelsInvoice::paginate(9);
            return Invoice::collection($all_invoice);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getInvoices($seller_id)
    {
        try {


            // change invoice status based on offer state
            $is_Closed = DB::table('offers')->select(['offer_state', 'seller_id'])->where('seller_id', $seller_id)->where('offer_state', 'Closed')->exists();

            if ($is_Closed) {
                $offers = DB::table('offers')->select(['id', 'offer_expiry'])->where('seller_id', $seller_id)->where('offer_state', 'Closed')->get();

                foreach ($offers as $key => $offer) {
                    $notBlocked = DB::table('invoices')->where('offer_id', $offer->id)->where('status', 'Blocked')->doesntExist();
                    // dd($notBlocked);
                    if ($notBlocked) {
                        // dd("Yes");
                        ModelsInvoice::where('offer_id', $offer->id)->where('status', 'Pending')->update(['status' => 'Blocked']);
                    } else {
                        $expiryDate = Carbon::parse($offer->offer_expiry)->addDays(14);
                        $remainingDays = $expiryDate->diffInDays(Carbon::now());

                        if (Carbon::now() > $expiryDate || $remainingDays == 0) {
                            // dd("Yess");
                            ModelsInvoice::where('offer_id', $offer->id)->where('status', 'Blocked')->update(['status' => 'Withdraw']);
                        }
                    }
                }
            }

            ////////////////////////////////////////////////////

            $total_balance = 0;
            $blocked_balance = 0;
            $withdraw_balance = 0;
            $paid_balance = 0;

            // $total_balance
            $total_amounts = DB::table('invoices')->select('offer_amount')->where('seller_id', $seller_id)->get();
            foreach ($total_amounts as $key => $amount) {
                $total_balance = $total_balance + $amount->offer_amount;
            }
            ///////////////////////



            // $blocked balance
            $blocked_amounts = DB::table('invoices')->select('offer_amount')->where('status', 'Blocked')->where('seller_id', $seller_id)->get();
            foreach ($blocked_amounts as $key => $amount) {
                $blocked_balance = $blocked_balance + $amount->offer_amount;
            }
            ///////////////////////



            // $withdraw_balance
            $withdraw_amounts = DB::table('invoices')->select('offer_amount')->where('status', 'Withdraw')->where('seller_id', $seller_id)->get();
            foreach ($withdraw_amounts as $key => $amount) {
                $withdraw_balance = $withdraw_balance + $amount->offer_amount;
            }
            //////////////////////



            // $paid_balance
            $paid_amounts = DB::table('invoices')->select('offer_amount')->where('status', 'Paid')->where('seller_id', $seller_id)->get();
            foreach ($paid_amounts as $key => $amount) {
                $paid_balance = $paid_balance + $amount->offer_amount;
            }
            //////////////////////



            return response()->json([
                'total_balance' => $total_balance,
                'blocked_balances' => $blocked_balance,
                'withdraw_balances' => $withdraw_balance,
                'paid_balances' => $paid_balance,
                'invoice_detail' => Invoice::collection(ModelsInvoice::where('seller_id', $seller_id)->get())
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }


    public function update($invoice_id)
    {
        try {
            $invoice = ModelsInvoice::find($invoice_id);
            if ($invoice) {
                $invoice->update([
                    'status' => 'Paid'
                ]);
                return new Invoice($invoice);
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

    public function block($invoice_id)
    {
        try {
            $invoice = ModelsInvoice::find($invoice_id);
            if ($invoice) {
                $invoice->update([
                    'status' => 'Blocked'
                ]);
                return new Invoice($invoice);
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
}
