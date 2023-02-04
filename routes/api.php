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

////////////////////////////////////////////////
