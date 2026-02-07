<?php
use Modules\Booking\Controllers\BookingController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/intro','LandingpageController@index');
Route::get('/', 'HomeController@index');
Route::get('/mail', function (){
    \Illuminate\Support\Facades\Mail::raw('Test email from Laravel using Gmail', function ($message) {
        $message->to('moinabbas80@gmail.com')
            ->subject('Laravel Gmail SMTP Test');
    });
});
Route::get('/home', 'HomeController@index')->name('home');
Route::post('/install/check-db', 'HomeController@checkConnectDatabase');
Route::get('/update', 'HomeController@updateMigrate');
Route::get('/test_functions', 'HomeController@test');

//Login
Auth::routes();
//Custom User Login and Register
Route::post('register','\Modules\User\Controllers\UserController@userRegister')->name('auth.register');
Route::post('login','\Modules\User\Controllers\UserController@userLogin')->name('auth.login');
Route::any('logout','\Modules\User\Controllers\UserController@logout')->name('auth.logout');
// Social Login
Route::get('social-login/{provider}', 'Auth\LoginController@socialLogin');
Route::get('social-callback/{provider}', 'Auth\LoginController@socialCallBack');

// Logs
Route::get('admin/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->middleware(['auth', 'dashboard','system_log_view']);

Route::get('handle-payment', '\Modules\Booking\Controllers\PayPalPaymentController@handlePayment')->name('make.payment');
Route::get('cancel-payment', '\Modules\Booking\Controllers\PayPalPaymentController@paymentCancel')->name('cancel.payment');
Route::get('payment-success', '\Modules\Booking\Controllers\PayPalPaymentController@paymentSuccess')->name('success.payment');
Route::get('payment-notify', '\Modules\Booking\Controllers\PayPalPaymentController@webhook')->name('payment.notify');
Route::get('paypal-form', '\Modules\Booking\Controllers\PayPalPaymentController@index')->name('paypal.form');

// Stripe payemt
Route::post('/checkout', '\Modules\Booking\Controllers\BookingController@doCheckout')->name('booking.doCheckout');
Route::get('/booking/payment/confirm/{gateway}', [BookingController::class, 'confirmPayment'])->name('booking.confirmPayment');

// Easypaisa callback (accept both GET and POST to avoid MethodNotAllowed from gateway/browser redirect)
Route::match(['get','post'], '/easypaisa/callback', 'PaymentController@paymentCallback')->name('easypaisa.callback');

// EasyPaisa specific routes (map to existing methods)
Route::match(['get', 'post'], '/payment/easypaisa/callback', [BookingController::class, 'easypaisaCallback'])->name('booking.easypaisa.callback');
Route::match(['get', 'post'], '/booking/confirm/easypaisa', [BookingController::class, 'easypaisaCallback'])->name('booking.easypaisa.confirm');
Route::get('/payment/easypaisa/cancel', [BookingController::class, 'handleEasyPaisaCancel'])->name('booking.easypaisa.cancel');

// Media file fetch by id (JSON)
Route::get('media/get-file', [\Modules\Media\Controllers\MediaController::class, 'getFile'])->name('media.get_file');

// Easypaisa test route
Route::get('/easypaisa-test', function() {
    return view('easypaisa.test');
});

//Route::get('paypal/payment/success', 'PaymentController@paymentSuccess')->name('paypal.payment.success');
//Route::get('paypal/payment/cancel', 'PaymentController@paymentCancel')->name('paypal.payment.cancel');
