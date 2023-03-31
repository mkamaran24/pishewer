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
use App\Http\Controllers\Api\CodeCheckController;
use App\Http\Controllers\Api\ForgetPasswordController;
use App\Http\Controllers\Api\isMailVerifiedController;
use App\Http\Controllers\Api\JobListMessageController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\ReplyReviewController;
use App\Http\Controllers\Api\ResetPasswordController;
use App\Http\Controllers\Api\ReviewController;
use App\Models\Blog;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


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


//// Basic CRUD Operation API Routes ///////

// Route::middleware('auth:sanctum')->group(function () {

// });

Route::apiResources([
    'users' => UserController::class,
    'jobs' => JobController::class,
    'addons' => AddonController::class,
    'categories' => CategoryController::class,
    'subcategories' => SubcategoryController::class,
    'profiles' => Profile::class,
    'city' => City::class
]);
Route::post('updatejob/{id}', [JobController::class, "updatejob"]);
Route::post('updateprofile/{id}', [Profile::class, "updateprofile"]);

Route::get('jobs/updatestatus/{jobid}',[JobController::class, "updatestatus"]);
Route::get('jobs/getjobstatus/{jobid}',[JobController::class, "getjobstatus"]);

////////////////////////////////////////////////


// Messages Route Logic ////////////////////////
Route::post('joblistmessage', [JobListMessageController::class, "store"]);
Route::post('message', [MessageController::class, "store"]);
// Route::get('joblistmessage', [JobListMessageController::class, "show"]);
Route::get('joblistmessage/getuserjoblist/{userid}', [JobListMessageController::class, "getuserjoblist"]);
Route::get('joblistmessage/textmessagesperjoblist/{joblistid}/{userid}', [JobListMessageController::class, "textmessagesperjoblist"]);

///////////////////////////////////////////////////


// Review & ReplyReview Route Logic ///////////////

Route::post('review', [ReviewController::class,"store"]);
Route::post('replyreview', [ReplyReviewController::class,"store"]);

Route::get('review/getallrev',[ReviewController::class,"getallrev"]);
Route::get('review/getrevbyjob/{jobid}',[ReviewController::class,"getrevperjob"]);

////////////////////////////////////////////////////


Route::get('blogs',function(){

    return Blog::with('comments.replies.replies')->get();

});