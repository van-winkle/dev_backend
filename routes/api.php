<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Phones\BrandController;
use App\Http\Controllers\Phones\ModelController;
use App\Http\Controllers\Phones\PhoneController;
use App\Http\Controllers\Phones\ContractController;
use App\Http\Controllers\Phones\PhonePlanController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/* Start Models routes */
Route::group(['prefix' => 'models'], function () {
    Route::resource('/', ModelController::class);
});
Route::get('/models-active/{id?}', [ModelController::class, 'phoneModelsActive']);
/* End Models route */

/* Start Brands routes */
Route::group(['prefix' => 'brands'], function () {
    Route::resource('/', BrandController::class)->except('create, edit');
});
Route::get('/brands-active/{id?}', [BrandController::class, 'BrandsActive']);
/* End Brands route */

/* Start Phones routes */
Route::group(['prefix' => 'phones'], function () {
    Route::resource('/', PhoneController::class);
});
Route::get('/phones-active/{id?}', [PhoneController::class, 'activePhones']);
/* End Phones routes */

/* Start Contracts routes */
Route::group(['prefix' => 'contracts'], function () {
    Route::resource('/', ContractController::class);
});
Route::get('/contracts-active/{id?}', [ContractController::class, 'activeContracts']);
/* End Contracts route */

/* Start Plans routes */
Route::group(['prefix' => 'plans'], function () {
    Route::resource('/', PhonePlanController::class);
});
Route::get('/plans-active/{id?}', [PhonePlanController::class, 'activePlans']);
/* End Plans route */
