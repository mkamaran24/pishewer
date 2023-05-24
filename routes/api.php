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

// test MK Laptop

//My PC 

// My Laptop again

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

    // Private Jobs /////////////////////////////////////////////////////////////////////////
    Route::get('jobs', [JobController::class, "index"]);
    Route::get('jobs/{job_id}', [JobController::class, "show"]);
    Route::get('jobs/user/{user_id}', [JobController::class, "getJobsperUser"]);
    Route::post('jobs', [JobController::class, "store"]);
    Route::post('updatejob/{id}', [JobController::class, "updatejob"]);
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
    Route::get('categories/{categ_id}', [CategoryController::class, "show"]);
    Route::put('categories/{categ_id}', [CategoryController::class, "update"]);
    Route::delete('categories/{categ_id}', [CategoryController::class, "destroy"]);
    // end of Private Category Route ////////////

    // Private Subcategory Route //////////////////////////////////////////////////////////////
    Route::post('subcategories/{categ_id}', [SubcategoryController::class, "store"]);
    Route::get('subcategories/{subcateg_id}', [SubcategoryController::class, "show"]);
    Route::put('subcategories/{subcateg_id}', [SubcategoryController::class, "update"]);
    Route::delete('subcategories/{subcateg_id}', [SubcategoryController::class, "destroy"]);
    // end of Private Subcategory Route //////////////////////////////////////////////////////////

    // Private Profile Route /////////////////////////////////
    Route::get('profiles', [Profile::class, "index"]);
    Route::post('profiles', [Profile::class, "store"]);
    Route::delete('profiles', [Profile::class, "destroy"]);
    Route::post('updateprofile/{id}', [Profile::class, "updateprofile"]);
    // end of Private Profile Route ///////////////////////////

    // Private City Route /////////////////////////////////////
    Route::get('city', [City::class, "index"]);
    Route::post('city', [City::class, "store"]);
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
    Route::post('offers/upload/{offer_id}', [OfferController::class, "upload"]);
    Route::get('offers/download/{offer_id}', [OfferController::class, "download"]);
    //
    Route::get('offers/payment/getall', [OrderController::class, "index"]);
    Route::post('offers/payment', [OrderController::class, "store"]);
    Route::put('offers/payment/accept/{order_id}', [OrderController::class, "update"]);

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
// end of Public Jobs Route /////////////////////////

// Addon Route ///////////////////////////////
Route::get('addons', [AddonController::class, "index"]);
// end of Addon Route /////////////////////////

// Public Category Route /////////////////////////////
Route::get('categories', [CategoryController::class, "index"]);
Route::get('categories/{categid}/subcategories', [CategoryController::class, "getSubcategory"]);
// end Public of Category Route /////////////////////////

// Public Subcategory Route /////////////////////////////
Route::get('subcategories', [SubcategoryController::class, "index"]);
// end Public of Subcategory Route //////////////////////

// Profile Route /////////////////////////////////
Route::get('profiles/{user_id}', [Profile::class, "show"]);
// end of Profile Route ///////////////////////////






// Route::apiResources([
//     'users' => UserController::class,
//     'jobs' => JobController::class,
//     'addons' => AddonController::class,
//     'categories' => CategoryController::class,
//     'subcategories' => SubcategoryController::class,
//     'profiles' => Profile::class,
//     'city' => City::class,
//     'blogcategory' => BlogCategory::class,
//     'blogs' => BlogController::class,
//     'offers' => OfferController::class,
//     'orders' => OrderController::class
// ]);