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
use App\Http\Controllers\Api\ReplyReviewController;
use App\Http\Controllers\Api\ResetPasswordController;
use App\Http\Controllers\Api\ReviewController;
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
    'city' => City::class,
    'blogcategory' => BlogCategory::class,
    'blogs' => BlogController::class,
    'offers' => OfferController::class
]);
Route::post('updatejob/{id}', [JobController::class, "updatejob"]);
Route::post('updateprofile/{id}', [Profile::class, "updateprofile"]);
Route::post('updateblog/{id}', [BlogController::class, "updateblog"]);
Route::post('blogs/comment', [BlogCommentController::class, "store"]);
Route::get('jobs/updatestatus/{jobid}', [JobController::class, "updatestatus"]);
Route::get('jobs/getjobstatus/{jobid}', [JobController::class, "getjobstatus"]);

////////////////////////////////////////////////


// Messages Route Logic ////////////////////////
Route::post('joblistmessage', [JobListMessageController::class, "store"]);
Route::post('message', [MessageController::class, "store"]);
// Route::get('joblistmessage', [JobListMessageController::class, "show"]);
Route::get('joblistmessage/getuserjoblist/{userid}', [JobListMessageController::class, "getuserjoblist"]);
Route::get('joblistmessage/textmessagesperjoblist/{joblistid}/{userid}', [JobListMessageController::class, "textmessagesperjoblist"]);

///////////////////////////////////////////////////


// Review & ReplyReview Route Logic ///////////////

Route::post('review', [ReviewController::class, "store"]);
Route::post('replyreview', [ReplyReviewController::class, "store"]);

Route::get('review/getallrev', [ReviewController::class, "getallrev"]);
Route::get('review/getrevbyjob/{jobid}', [ReviewController::class, "getrevperjob"]);

////////////////////////////////////////////////////


// Test Part /////////////////////////////

// Route::post('/test', function (Request $req) {



//     try {
//         //code...
//         $path = $req->file('zip')->store('testfile');


//         // Create a new ZipArchive instance
//         $zip = new ZipArchive;

//         // Open the zip file for reading
//         if ($zip->open($path) === TRUE) {
//             // Validate the contents of the zip file
//             $isValid = true;
//             for ($i = 0; $i < $zip->numFiles; $i++) {
//                 $file = $zip->statIndex($i);
//                 if (preg_match('/\.(php|pl|py|sh|rb)$/i', $file['name'])) {
//                     $isValid = false;
//                     break;
//                 }
//             }

//             // Close the zip file
//             $zip->close();

//             // Return the validation result
//             return response()->json(['isValid' => $isValid]);
//         } else {
//             // Error opening the zip file
//             return response()->json(['error' => 'Failed to open the zip file']);
//         }
//     } catch (\Throwable $th) {
//         //throw $th;
//         return response()->json([
//             'status' => false,
//             'message' => $th->getMessage(),
//         ], 500);
//     }

// });


// end of test part ///////////////////////