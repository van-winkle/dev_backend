<?php

use App\Http\Controllers\Phones\ContractController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Phones\ModelController;

use App\Http\Controllers\Phones\BrandController;


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




Route::resource('/model', ModelController::class);

//CRUD table CONTRACT
Route::resource('/contract', ContractController::class);

// Route::get('/brands', [BrandController::class, 'index']);
// Route::get('/brands/{id}', [BrandController::class, 'show']);


Route::resource('/brands', BrandController::class)->except('create');


