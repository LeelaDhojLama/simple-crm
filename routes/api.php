<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerApiResourceController;
use App\Http\Controllers\OfferApiController;
use App\Http\Controllers\OfferClaimApiController;
use App\Http\Controllers\PhpMailerController;
use App\Http\Controllers\SalesApiResourceController;
use App\Models\OfferClaims;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Customers
Route::get('/customers', [CustomerApiResourceController::class, 'index']);
Route::post('/customers', [CustomerApiResourceController::class, 'store']);
Route::get('/customers/{contact}', [CustomerApiResourceController::class, 'findByContactNumber']);
Route::get('/customer/{customerID}/offers/active', [OfferApiController::class, 'getActiveOfferForCustomer']);
Route::put('/customers/{id}', [CustomerApiResourceController::class, 'update']);

//Sales
Route::get('/sales/{customerID}', [SalesApiResourceController::class, 'showSalesByCustomerID']);
Route::get('/sales/{customerID}/all-report', [SalesApiResourceController::class, 'showAllSalesByCustomerID']);
Route::post('/sales', [SalesApiResourceController::class, 'store']);

//Offers
Route::get('/offers', [OfferApiController::class, 'index']);
Route::post('/offers', [OfferApiController::class, 'store']);
Route::put('/offers/{id}', [OfferApiController::class, 'update']);
Route::get('/offer-claim-status/{customerID}', [OfferApiController::class, 'isUserReadyForOfferClaim']);

//Offer Claims
Route::post('/offers/claim', [OfferClaimApiController::class, 'store']);

//Reports
Route::get('/sales', [SalesApiResourceController::class, 'customerReport']);
Route::get('/sales/reports/weekly-report', [SalesApiResourceController::class, 'weeklyReport']);
Route::get('/sales/reports/custom-report/{startDate}/{endDate}', [SalesApiResourceController::class, 'customDateRangeSalesReport']);
Route::get('/offer/reports/custom-offer-claims/{startDate}/{endDate}', [OfferApiController::class, 'offersClaimedReport']);
