<?php

use Illuminate\Http\Request;

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

// Easypaisa Payment API Routes
Route::post('/easypaisa/pay', 'PaymentController@initiatePayment')->name('api.easypaisa.pay');
// Easypaisa callback (API)
Route::match(['get','post'],'/easypaisa/callback','\Modules\Booking\Controllers\BookingController@easypaisaCallback')->name('api.easypaisa.callback');


Route::post('/paypal/pay', 'PaymentController@initiatePaypalPayment')->name('api.paypal.pay');
Route::get('/paypal/payment/success', 'PaymentController@paymentSuccess')->name('paypal.payment.success');
Route::get('/paypal/payment/cancel', 'PaymentController@paymentCancel')->name('paypal.payment.cancel');


// Jazzcash Payment API Routes
Route::post('/jazzcash/pay', 'PaymentController@initiateJazzcashPayment')->name('api.jazzcash.pay');
