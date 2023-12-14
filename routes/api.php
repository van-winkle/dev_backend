<?php


use App\Http\Controllers\Phones\PhoneController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Phones\BrandController;
use App\Models\Phones\PhoneBrand;

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

// Route::get('/brands', [BrandController::class, 'index']);
// Route::get('/brands/{id}', [BrandController::class, 'show']);


Route::resource('/brands', BrandController::class)->except('create');


/* Start Phone routes */
Route::resource('/phones', PhoneController::class);
Route::get('/phones-active/{id?}', [PhoneController::class, 'activePhones']);

Route::get('/phone-brands-active/{id?}', [BrandController::class, 'phoneBrandsActive']);
/* End Phone routes */
