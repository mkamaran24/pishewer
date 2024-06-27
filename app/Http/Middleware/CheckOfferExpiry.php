<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Offer as OfferModel;
use App\Models\Attachment as AttachModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class CheckOfferExpiry
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Update Expiry Middleware
        $response = $next($request);

        $user = auth('sanctum')->user();

        $msg = null;

        if ($user) {
            $msg = "Offer Expiry Checked";
            $now = Carbon::now();



            $offer_id = DB::table('offers')->select('id')->where('seller_id', $user->id)->orWhere('buyer_id', $user->id)->get();

            if (!empty($offer_id)) {
                foreach ($offer_id as $key => $id) {
                    $check_attach = DB::table('attachments')->where('offer_id', $id->id)->exists();

                    if ($check_attach) {

                        OfferModel::where('offer_expiry', '<=', $now)->where('offer_state', 'inProgress')->where('id', $id->id)->update(['offer_state' => 'Closed']);
                    } else {
                        OfferModel::where('offer_expiry', '<=', $now)->where('id', $id->id)->update(['offer_state' => 'Expired']);
                    }
                }
            } else {
                $msg = "Offers Not Found for this User";
            }
        } else {
            $msg = "Faild to Check Offer Expiry";
        }


        $response->headers->set('X-Custom-Header', $msg);

        return $response;
    }
}
