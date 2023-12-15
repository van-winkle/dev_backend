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
Route::resource('/models', ModelController::class);
Route::get('/models_active/{id?}', [ModelController::class, 'phoneModelsActive']);
/* End Models route */

/* BRANDS ROUTES->K*/
Route::resource('/brands', BrandController::class)->except('create, edit');
Route::get('/brands-active/{id?}', [BrandController::class, 'BrandsActive']);
Route::resource('/brands', BrandController::class)->except('create');

/* Start Phone routes */
Route::resource('/phones', PhoneController::class);
Route::get('/phones-active/{id?}', [PhoneController::class, 'activePhones']);
/* End Phone routes */

//ROUTES table CONTRACT
Route::resource('/contract', ContractController::class);
Route::get('/phone-contract-active/{id?}',[ContractController::class, 'activeContracts']);

//ROUTES table PLANS
Route::resource('/plans', PhonePlanController::class);
Route::get('/phone-plan-active/{id?}',[PhonePlanController::class, 'activePlans']);
