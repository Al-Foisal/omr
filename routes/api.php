<?php

use App\Http\Controllers\Api\GeneralController;
use App\Http\Controllers\Api\UserAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
    return response()->json([
        'status' => true,
        'data'   => $request->user(),
    ]);
});

Route::controller(UserAuthController::class)->prefix('/auth')->group(function () {
    Route::post('/register', 'register');
    Route::post('/verify-otp', 'verifyOtp')->middleware('api');
    Route::post('/login', 'login');
    Route::post('/store-forgot-password', 'storeForgotPassword');
    Route::post('/reset-password', 'resetPassword');
    Route::post('/resend-otp', 'resendOTP');
    Route::post('/logout', 'logout')->middleware('api');
});

Route::controller(GeneralController::class)->prefix('/general')->middleware('auth:sanctum')->group(function () {
    Route::post('/registered-or-suggest-courses', 'registeredOrSuggestCourses');
    Route::post('/store-course-registration', 'storeCourseRegistration');
});