<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\AddonController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\City;
use App\Http\Controllers\Api\Profile;
use App\Http\Controllers\Api\SubcategoryController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BlogCategory;
use App\Http\Controllers\Api\BlogCommentController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\CodeCheckController;
use App\Http\Controllers\Api\ForgetPasswordController;
use App\Http\Controllers\Api\FreelanceCommunityController;
use App\Http\Controllers\Api\HeroController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\isMailVerifiedController;
use App\Http\Controllers\Api\JobListMessageController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\OfferController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ReplyReviewController;
use App\Http\Controllers\Api\ResetPasswordController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Resources\Job;
use App\Models\Jobs;
use App\Models\Offer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

// test MK Laptop

//My PC 

// My Laptop again

//pc master

// testststststststststts

// test kwi bram

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Auth Routes ///////////////////////////
Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);
Route::get('auth/google/redirect', [AuthController::class, 'redirect']);
Route::get('auth/google/call-back', [AuthController::class, 'callback']);
//////////////////////////////////////////

// Email Verfied Middleware //////////////
Route::get('isEmailVerfied/{id}', [isMailVerifiedController::class, 'checkVerifiedField']);
//////////////////////////////////////////

// Reset Password Routes /////////////////
Route::post('auth/forgetpassword', [ForgetPasswordController::class, '__invoke']);
Route::post('auth/resetcode/check', [CodeCheckController::class, '__invoke']);
Route::post('auth/password/reset/{otpcode}', [ResetPasswordController::class, '__invoke']);
///////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Middleware Filter for Private Access //////////////////////////////////////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->group(function () {

    // private home page / hero section /////////////////////////////////////////////////////
    Route::get('hero', [HeroController::class, "index"]);
    Route::post('hero', [HeroController::class, "store"]);
    Route::post('hero/{id}', [HeroController::class, "updateimage"]);
    Route::delete('hero/{id}', [HeroController::class, "destroy"]);
    // end of private home page / hero section //////////////////////////////////////////////

    // Private Jobs /////////////////////////////////////////////////////////////////////////
    Route::get('jobs', [JobController::class, "index"]);
    Route::get('jobs/{job_id}', [JobController::class, "show"]);
    Route::get('jobs/user/{user_id}', [JobController::class, "getJobsperUser"]);
    Route::post('jobs', [JobController::class, "store"]);
    Route::post('jobs/{id}', [JobController::class, "updatejob"]);
    Route::delete('jobs/{job_id}', [JobController::class, "destroy"]);
    Route::post('jobs/{job_id}/favorite', [JobController::class, "favorite"]);
    Route::delete('jobs/{job_id}/unfavorite', [JobController::class, "unfavorite"]);
    Route::get('jobs/updatestatus/{jobid}', [JobController::class, "updatestatus"]);
    Route::get('jobs/getjobstatus/{jobid}', [JobController::class, "getjobstatus"]);
    Route::post('jobs/translation/{jobid}', [JobController::class, "updatelang"]);
    ///////////////////////////////////////////////////////////////////////////////////////////

    // Private User Route ////////////////////////////////
    Route::get('users', [UserController::class, "index"]);
    Route::get('users/{user_id}', [UserController::class, "show"]);
    Route::put('users/{user_id}', [UserController::class, "update"]);
    Route::delete('users/{user_id}', [UserController::class, "destroy"]);
    // end of User Route /////////////////////////

    // Private Category Route ////////////////////
    Route::post('categories', [CategoryController::class, "store"]);
    // Route::get('categories/{categ_id}', [CategoryController::class, "show"]);
    Route::post('categories/{categ_id}', [CategoryController::class, "update"]);
    Route::delete('categories/{categ_id}', [CategoryController::class, "destroy"]);
    Route::put('categories/popular', [CategoryController::class, "popular"]);
    Route::put('categories/unpopular', [CategoryController::class, "unpopular"]);
    // end of Private Category Route ////////////

    // Private Subcategory Route (Cancled) //////////////////////////////////////////////////////////////
    // Route::post('subcategories/{categ_id}', [SubcategoryController::class, "store"]);
    // Route::get('subcategories/{subcateg_id}', [SubcategoryController::class, "show"]);
    // Route::put('subcategories/{subcateg_id}', [SubcategoryController::class, "update"]);
    // Route::delete('subcategories/{subcateg_id}', [SubcategoryController::class, "destroy"]);
    // end of Private Subcategory Route (Cancled)//////////////////////////////////////////////////////////

    // Private Profile Route /////////////////////////////////
    Route::get('profiles', [Profile::class, "index"]);
    Route::post('profiles', [Profile::class, "store"]);
    Route::delete('profiles', [Profile::class, "destroy"]);
    Route::post('profiles/{id}', [Profile::class, "updateprofile"]);
    // Route::put('profiles/translate/{profile_id}',[Profile::class, "translate"]);
    // end of Private Profile Route ///////////////////////////

    // Private City Route /////////////////////////////////////
    Route::get('city', [City::class, "index"]);
    Route::post('city', [City::class, "store"]);
    Route::post('city/{id}', [City::class, "update"]);
    // end of Private City Route //////////////////////////////

    // Private Blog Category //////////////////////////////////
    Route::get('blogcategory', [BlogCategory::class, "index"]);
    Route::post('blogcategory', [BlogCategory::class, "store"]);
    Route::put('blogcategory/{id}', [BlogCategory::class, "update"]);
    Route::delete('blogcategory/{id}', [BlogCategory::class, "destroy"]);
    // end of Private Blog categiry ROute //////////////////////

    // Private Blog Route //////////////////////////////////////
    Route::get('blogs', [BlogController::class, "index"]);
    Route::post('blogs', [BlogController::class, "store"]);
    Route::delete('blogs/{blog_id}', [BlogController::class, "destroy"]);
    Route::get('blogs/{blog_id}', [BlogController::class, "show"]);
    Route::post('updateblog/{id}', [BlogController::class, "updateblog"]);
    // end of Private Blog Route ////////////////////////////////

    // Private Blog Comment Route ///////////////////////////////
    Route::post('blogs/comment', [BlogCommentController::class, "store"]);
    //////////////////////////////////////////////////////

    // Private Offer & Order Route ///////////////////////////////////////
    Route::get('offers', [OfferController::class, "index"]);
    Route::get('offers/getexistjobs/{user_id}', [OfferController::class, "getAlljobs"]);
    // Route::post('offers/existjob', [OfferController::class, "storeExistjobs"]);
    Route::post('offers', [OfferController::class, "store"]);
    Route::get('offers/getoffersperuser/{user_id}', [OfferController::class, "OffersperUsers"]);
    Route::get('offers/detail/{user_id}/{offer_code}', [OfferController::class, "show"]);
    Route::put('offers/accept/{offer_id}', [OfferController::class, "accept"]);
    Route::put('offers/reject/{offer_id}', [OfferController::class, "reject"]);
    Route::put('offers/cancel/{offer_id}', [OfferController::class, "cancel"]);
    Route::post('offers/upload/{offer_id}', [OfferController::class, "upload"]);

    //
    Route::get('offers/payment/getall', [OrderController::class, "index"]);
    Route::get('offers/payment/{buyer_id}', [OrderController::class, "show"]);
    Route::post('offers/payment', [OrderController::class, "store"]);
    Route::put('offers/payment/accept/{payment_id}', [OrderController::class, "update"]);

    //
    Route::get('invoice', [InvoiceController::class, "index"]);
    Route::get('invoice/{seller_id}', [InvoiceController::class, "getInvoices"]);
    Route::put('invoice/{invoice_id}', [InvoiceController::class, "update"]);
    // end of Private Offer Route /////////////////////////////////

    // Private Order Route ////////////////////////////////////////
    // Route::get('orders', [OrderController::class, "index"]);
    // Route::post('orders', [OrderController::class, "store"]);
    // Route::get('orders/{id}', [OrderController::class, "show"]);
    // Route::put('orders/{id}', [OrderController::class, "update"]);
    // end Private Order Route /////////////////////////////////////

    // Private Messages Route //////////////////////////////////////
    Route::post('friendlist', [JobListMessageController::class, "store"]);
    Route::post('message', [MessageController::class, "store"]);
    // Route::get('joblistmessage', [JobListMessageController::class, "show"]);
    Route::get('friendlist/getuserfriendlists/{userid}', [JobListMessageController::class, "getuserjoblist"]);
    Route::get('friendlist/textmessagesperfriendlist/{ftc_code}/{userid}', [JobListMessageController::class, "textmessagesperjoblist"]);
    // end of Private Message Route /////////////////////////////////

    // Private Review & ReplyReview Route Logic ///////////////
    Route::post('review', [ReviewController::class, "store"]);
    Route::post('replyreview', [ReplyReviewController::class, "store"]);
    Route::get('review/getallrev', [ReviewController::class, "getallrev"]);
    Route::get('review/getrevbyjob/{jobid}', [ReviewController::class, "getrevperjob"]);
    // end of Private Review and Reply Review Route ////////////

});

// end Middleware Filter for Private Access ///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


// Public Jobs Route /////////////////////////////
Route::get('jobs', [JobController::class, "index"]);
Route::get('jobs/{job_id}', [JobController::class, "show"]);
Route::get('jobs/getjobsbycategory/{categid}', [JobController::class, "getjobsbycateg"]);
Route::get('jobs/search/query', [JobController::class, "searchjobs"]);
Route::get('jobs/search/hero', [JobController::class, "search_hero"]);
Route::get('jobs/featured/query', [JobController::class, "featured"]);
Route::get('jobs/user/{user_id}', [JobController::class, "getJobsperUser"]);
// end of Public Jobs Route /////////////////////////

// Addon Route ///////////////////////////////
Route::get('addons', [AddonController::class, "index"]);
// end of Addon Route /////////////////////////

// Public Category Route /////////////////////////////
Route::get('categories', [CategoryController::class, "index"]);
Route::get('categories/{categid}/subcategories', [CategoryController::class, "getSubcategory"]);
Route::get('categories/popular', [CategoryController::class, "getpopular"]);
// end Public of Category Route /////////////////////////

// Public Subcategory Route /////////////////////////////
// Route::get('subcategories', [SubcategoryController::class, "index"]);
// end Public of Subcategory Route //////////////////////

// Profile Route /////////////////////////////////
Route::get('profiles/{user_id}', [Profile::class, "show"]);
// end of Profile Route ///////////////////////////

// Freelance Community Route ///////////////////////
Route::get('freelance-community', [FreelanceCommunityController::class, 'index']);
Route::post('freelance-community', [FreelanceCommunityController::class, 'store']);
Route::put('freelance-community/{id}', [FreelanceCommunityController::class, 'update']);
Route::delete('freelance-community/{id}', [FreelanceCommunityController::class, 'destroy']);
// end of Freelance Community Route ////////////////

// Public Download 
Route::get('offers/download/{offer_id}', [OfferController::class, "download"]);


// test api ///

Route::put('test/offers/close/{offer_id}', function ($id) {
    Offer::where('id', $id)->update(['offer_state' => 'Closed']);
    return response()->json([
        'message' => 'Offer Updated Successfully'
    ], 200);
});

/// end test api ///


// Assest API ///////////

// ttf ////////
Route::get('fonts/peshang/Peshang_Des_1_bold_.ttf', function () {

    $response = Http::get('https://server.pishewer.com/storage/fonts/Peshang_Des_1_bold_.ttf');

    if ($response->successful()) {
        $data = $response->body();
        return $data;
    } else {
        $statusCode = $response->status();
        $errorMessage = $response->body();

        return $statusCode . " " . $errorMessage;
    }
});

// wof //////////
Route::get('fonts/peshang/Peshang_Des_1_bold_.woff', function () {

    $response = Http::get('https://server.pishewer.com/storage/fonts/Peshang_Des_1_bold_.woff');

    if ($response->successful()) {
        $data = $response->body();
        return $data;
    } else {
        $statusCode = $response->status();
        $errorMessage = $response->body();

        return $statusCode . " " . $errorMessage;
    }
});

// eot
Route::get('fonts/peshang/Peshang_Des_1_bold_.eot', function () {

    $response = Http::get('https://server.pishewer.com/storage/fonts/Peshang_Des_1_bold_.eot');

    if ($response->successful()) {
        $data = $response->body();
        return $data;
    } else {
        $statusCode = $response->status();
        $errorMessage = $response->body();

        return $statusCode . " " . $errorMessage;
    }
});


// end of Assest API //////


// dummy data lab //////////////////////

// jobs trans table
Route::get('dummy/jobtrans/store', function () {
    try {
        $data_job = [];
        $data_jobtrans = [];

        for ($i = 0; $i < 1000; $i++) {
            $data_job[] = [
                'sold' => 0,
                'status' => 0,
                'user_id' => 40,
                'categ_id' => 12
            ];
        }

        for ($i = 0; $i < 1000; $i++) {
            $data_jobtrans[] = [
                'title' => "nec molestie sed justo pellentesque viverra pede ac diam" . $i,
                'description' => "sollicitudin mi sit amet lobortis sapien sapien non mi integer ac neque duis bibendum morbi non quam nec dui luctus rutrum nulla tellus in sagittis dui vel nisl duis ac nibh fusce lacus purus aliquet at feugiat non pretium quis lectus suspendisse potenti in eleifend quam a odio in hac habitasse platea dictumst maecenas ut massa quis augue luctus tincidunt nulla mollis molestie lorem quisque ut erat curabitur gravida nisi at nibh in hac habitasse platea dictumst aliquam augue quam sollicitudin vitae consectetuer eget rutrum at lorem integer tincidunt ante vel ipsum praesent blandit lacinia erat vestibulum sed magna at nunc commodo placerat praesent blandit nam nulla integer pede justo lacinia eget tincidunt eget tempus vel pede morbi porttitor lorem id ligula suspendisse ornare consequat lectus in est risus auctor sed tristique in tempus sit amet sem fusce consequat nulla nisl nunc nisl duis",
                'price' => "324",
                'completein' => "5",
                'locale' => "en",
                'job_id' => $i + 41
            ];
        }

        // Insert the data into the "job" table
        DB::table('jobs')->insert($data_job);

        // Insert the data into the "job_trans" table
        DB::table('job_trans')->insert($data_jobtrans);

        return response()->json([
            "mesg" => "data inserted successfully"
        ], 200);
    } catch (\Throwable $th) {
        throw $th;
    }
});
