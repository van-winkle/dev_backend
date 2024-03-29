<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Phones\BrandController;
use App\Http\Controllers\Phones\ModelController;
use App\Http\Controllers\Phones\PhoneController;
use App\Http\Controllers\Phones\ContractController;
use App\Http\Controllers\Phones\PercentageRuleController;
use App\Http\Controllers\Phones\PhoneAssignmentController;
use App\Http\Controllers\Phones\PhoneContractAttachesController;
use App\Http\Controllers\Phones\PhonePlanController;
use App\Http\Controllers\Phones\TypePhoneController;
use App\Http\Controllers\Phones\PhoneIncidentController;
use App\Http\Controllers\Phones\PhoneIncidentAttachesController;
use App\Http\Controllers\Phones\PhoneIncidentResolutionController;
use App\Http\Controllers\Phones\PhoneIncidentResolutionAttachesController;

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
    ->group(function () {
        Route::get('/models-active/{id?}', 'modelsActive');
    });
Route::resource('phone/models', ModelController::class);
/* End Models route */

/* Start Phones routes */
Route::prefix('phone/phones')
    ->controller(PhoneController::class)
    ->group(function () {
        Route::get('/phones-active/{id?}', 'activePhones');
        Route::get('/phones-active-assign', 'activePhonesAssign');
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
    ->group(function () {
        Route::get('/typePhones-active/{id?}', 'activeTypePhones');
    });
/* End TypePhones route */

/* Start incidents routes */
Route::resource('/phone/incidents', PhoneIncidentController::class);
/* End Incidents route */

/* Start Incidents Attaches routes*/
Route::resource('/phone/incidentAttaches', PhoneIncidentAttachesController::class);
/* End Incidents Attaches routes*/

/* Start Percentage Rules routes*/
Route::resource('/phone/percentagesRules', PercentageRuleController::class);
/* End Percentage Rules routes*/

/* Start Contract Attaches routes*/
Route::prefix('phone/contractAttaches')->group(function () {
    Route::get('/viewFile/{id}', [PhoneContractAttachesController::class, 'viewFile']);
});
Route::resource('/phone/contractAttaches', PhoneContractAttachesController::class);
/* End Contract Attaches routes*/

/* Start Assignment routes */
Route::resource('/phone/assignments', PhoneAssignmentController::class);
Route::prefix('phone/assignments')
    ->controller(PhoneAssignmentController::class)
    ->group(function () {
    });
/* End Assignment route */

/* Start Incidents Resolutions routes*/
Route::resource('/phone/incidentResolutions', PhoneIncidentResolutionController::class);
/* End Incidents Resolutions routes*/

/* Start Incidents Resolutions Attaches routes*/
Route::resource('/phone/incidentResolutionsAttaches', PhoneIncidentResolutionAttachesController::class);
/* End Incidents Resolutions Attaches routes*/
