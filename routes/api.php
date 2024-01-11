<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Phones\BrandController;
use App\Http\Controllers\Phones\ModelController;
use App\Http\Controllers\Phones\PhoneController;
use App\Http\Controllers\Phones\ContractController;
use App\Http\Controllers\Phones\PhoneIncidentController;
use App\Http\Controllers\Phones\PhonePlanController;
use App\Http\Controllers\Phones\PhoneIncidentAttachesController;
use App\Http\Controllers\Phones\TypePhoneController;

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

/* Start Brands routes */
Route::prefix('phone/brands')
->controller(BrandController::class)
->group(function () {
    Route::get('/brands-active/{id?}', 'brandsActive');
});
Route::resource('/phone/brands', BrandController::class)->except('create, edit');
/* End Brands route */

/* Start Models routes */
Route::prefix('phone/models')
->controller(ModelController::class)
->group(function (){
    Route::get('/models-active/{id?}', 'modelsActive');
});
Route::resource('phone/models', ModelController::class);
/* End Models route */

/* Start Phones routes */
Route::prefix('phone/phones')
->controller(PhoneController::class)
->group(function () {
    Route::get('/phones-active/{id?}', 'activePhones');
});
Route::resource('/phone/phones', PhoneController::class);
/* End Phones routes */

/* Start Contracts routes */
Route::prefix('phone/contracts')
->controller(ContractController::class)
->group(function () {
    Route::get('/contracts-active/{id?}', 'activeContracts');
});
Route::resource('/phone/contracts', ContractController::class);
/* End Contracts route */

/* Start Plans routes */
Route::prefix('phone/plans')
->controller(PhonePlanController::class)
->group(function () {
    Route::get('/plans-active/{id?}', 'activePlans');
});
Route::resource('/phone/plans', PhonePlanController::class);
/* End Plans route */

/* Start TypePhones routes */
Route::prefix('phone/typePhones')
->controller(TypePhoneController::class)
->group(function(){
    Route::get('/typePhones-active/{id?}', 'activetypePhones');
});

/* End TypePhones route */

/* Start incidents routes */

// Route::prefix('phone/incidents')
// ->controller(PhoneIncidentController::class)
// ->group(function () {
//     Route::get('/incidents-active/{id?}', 'activeIncidents');
// });

Route::resource('/phone/incidents', PhoneIncidentController::class);
/* End Incidents route */

/* Start Incidents Attaches routes*/
Route::resource('/phone/incidentAttaches', PhoneIncidentAttachesController::class);
/* End Incidents Attaches routes*/
