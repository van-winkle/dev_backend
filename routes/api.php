<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Phones\BrandController;
use App\Http\Controllers\Phones\ModelController;
use App\Http\Controllers\Phones\PhoneController;
use App\Http\Controllers\Phones\ContractController;
use App\Http\Controllers\Phones\PhonePlanController;
use App\Models\Phones\PhoneModel;

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
Route::prefix('models')
->controller(ModelController::class)
->group(function (){
    Route::get('/models-active/{id?}', [ModelController::class, 'phoneModelsActive']);
});
Route::resource('/models', ModelController::class);
/* End Models route */

/* Start Brands routes */
Route::prefix('brands')
->controller(BrandController::class)
->group(function () {
    Route::get('/brands-active/{id?}', [BrandController::class, 'BrandsActive']);
});
Route::resource('/brands', BrandController::class)->except('create, edit');
/* End Brands route */

/* Start Phones routes */
Route::prefix('phones')
->controller(PhoneController::class)
->group(function () {
    Route::get('/phones-active/{id?}', [PhoneController::class, 'activePhones']);
});
Route::resource('/phones', PhoneController::class);
/* End Phones routes */

/* Start Contracts routes */
Route::prefix('contracts')
->controller(ContractController::class)
->group(function () {
    Route::get('/contracts-active/{id?}', [ContractController::class, 'activeContracts']);
});
Route::resource('/contracts', ContractController::class);
/* End Contracts route */

/* Start Plans routes */
Route::prefix('plans')
->controller(PhonePlanController::class)
->group(function () {
    Route::get('/plans-active/{id?}', [PhonePlanController::class, 'activePlans']);
});
Route::resource('/', PhonePlanController::class);
/* End Plans route */
