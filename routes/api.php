<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\WishlistController;
use App\Http\Middleware\adminmiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Services\FcmService;
use Laravel\Sanctum\Sanctum;

// use App\Http\Controllers\VerificationController;



// middleware for logout for Use Token -> middleware
Route::middleware('auth:sanctum')->group(function () {

    //  Email Verification Notice  3 Routes

    Route::get('/email/verify', [AuthController::class, 'VerifyNotice'])->name('verification.notice');

    // Email Verification Handler

    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'VerifyEmail'])->middleware(['signed'])->name('verification.verify');

    // Resending the Verification Email
    Route::post('/email/ResendEmail', [AuthController::class, 'ResendEmail'])->middleware(['throttle:6,1'])->name('verification.send');


    // Reset Password  3 Routes
    // Handling the Form Submission   { password reset link ->cin:e}
    Route::post('/forgot-password', [ResetPasswordController::class, 'forgetpassword'])->name('password.email');


    // Resetting the Password  {?token?}
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'reset_password'])->name('password.reset');

    // Handling updating the user's password in the database {Your password has been reset ->cin:raw:e,p,pc,tm}
    Route::post('/reset-password', [ResetPasswordController::class, 'update_password'])->name('password.update');
});

// ----------------------------------------------------------------------------------

Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [Authcontroller::class, 'register']);
});

// ---------------------------------------

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('/Wishlist', WishlistController::class)
        ->names([
            'index'   => 'user.wishlist.index',
            'store'   => 'user.wishlist.store',
            'destroy' => 'user.wishlist.destroy',
        ]);
    Route::apiResource('/order', OrderController::class);

    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products', [ProductController::class, 'show']);


    Route::post('/logout', [Authcontroller::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

// --------------------------------------------------------

Route::group(['prefix' => 'admin', 'middleware' => 'auth:sanctum', adminmiddleware::class], function () {
    Route::apiResource('/wishlist', WishlistController::class)
        ->names([

            'index'   => 'admin.wishlist.index',
            'show'    => 'admin.wishlist.show',
            'destroy' => 'admin.wishlist.destroy',
        ]);

    Route::get('orders', [OrderController::class, 'index']);
    Route::get('orders/{id}', [OrderController::class, 'destroy']);
    Route::get('orders/{id}', [OrderController::class, 'show']);

    Route::apiResource('/products', ProductController::class);
    Route::get('/', function (Request $request) {
        return $request->user();
    });
});
// -----------------------------------------------------------------


// Route Fcm Service 
Route::post('/send-notification', function (Request $request) {
    $title = $request->input('title');
    $body = $request->input('body');
    $target = $request->input('target');
    $image = $request->input('image');

    return FcmService::notify($title, $body, $target, $image);
});


// -------------------------------------------------------------------------------------
Route::get('/', function () {
    return view('welcome');
});

// Route::get('/admin', function () {
//     return view('admin');
// })->middleware('auth','is_admin:admin');

// Route::get('/admin', function () {
//     // صفحة الأدمن
// })->middleware('role:admin');


// ----------------------------------------------------------------------------------

    // route  old for verify email  

// Route::group(['middleware' => ['auth:sanctum']], function () {

//     Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify']);

// Route::post('email/resend', [VerificationController::class, 'resend']);
// });