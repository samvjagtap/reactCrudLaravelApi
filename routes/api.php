<?php

use App\Http\Controllers\API\ecom_customizationController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\imagesController;
use App\Models\ecom_customization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/create-user', [UserController::class, 'createUser']);
Route::put('/update-user/{id}', [UserController::class, 'updateUser']);
Route::delete('/delete-user/{id}', [UserController::class, 'deleteUser']);
    

// Route::post('/login', [UserController::class, 'login']);
Route::get('/unauthenticate', [UserController::class, 'unauthenticate'])->name('unauthenticate');

// Route::middleware('auth:api')->group(function () {
    Route::get('/get-user', [UserController::class, 'getUser']);
    Route::get('/get-user-detail/{id}', [UserController::class, 'getUserDetail']);
    Route::post('/logout', [UserController::class, 'logout']);
// });


Route::post('login', [AuthController::class,'login']);

// Route::middleware('auth:api')->group(function () {
    
// });

Route::group(['middleware' => 'api'], function() {
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('refresh', [AuthController::class,'refresh']);
    Route::post('me', [AuthController::class,'me']);
    Route::post('customization', [ecom_customizationController::class, 'createCustomization']);
    Route::get('getCustomization/{div_name}', [ecom_customizationController::class, 'getCustomization']);
    Route::post('set_image', [imagesController::class, 'insertImage']);
});
