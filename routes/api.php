<?php

use App\Http\Controllers\Api\v1\{
    AuthController,
    FieldSafetyNoticeController,
    UserController,
    ProductController,
    SettingController
};
use Illuminate\Support\Facades\Route;

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

Route::group(
    ['prefix' => 'v1'], function () {
        Route::controller(AuthController::class)->group(
            function () {
                Route::post('register', 'register');
                Route::post('verify-otp', 'verifyOtp');
                Route::post('login', 'login');
                Route::post('reset-password', 'resetPassword');
                Route::post('social-login/{provider}', 'socialLogin');
                Route::post('send-otp', 'sendOtp');
                Route::post('login-with-doccheck', 'loginWithDocCheck');
            }
        );
        Route::controller(SettingController::class)->group(
            function () {
                Route::get('countries', 'countries');
                Route::get('states', 'states');
                Route::get('cities', 'cities');
                Route::get('user-roles', 'userRoles');
            }
        );
        
        Route::middleware(['auth:sanctum', 'activeUser'])->group(
            function () {
                Route::get('logout', [AuthController::class, 'logout']);
                Route::post('change-password', [UserController::class, 'changePassword']);
                Route::resource('users', UserController::class);
                Route::resource('products', ProductController::class);
                Route::post('products/rating', [ProductController::class, 'storeProductRating']);
                Route::post('products/compare', [ProductController::class, 'compareProduct']);
                Route::get('product/{id}/questions/{type}', [ProductController::class, 'getQuestions']);
                Route::delete('products/scan/{id}', [ProductController::class, 'removeScannedProduct']);
                Route::post('products/scan', [ProductController::class, 'scanProduct']);
                Route::post('products/questions/answers', [ProductController::class, 'storeProductQuestionAnswers']);
                Route::resource('fieldsafetynotices', FieldSafetyNoticeController::class);
                Route::post('settings', [SettingController::class, 'updateUserSettings']);
                Route::post('check-email-phone-number', [UserController::class, 'checkEmailOrPhoneNumber']);
                Route::post('store-user-activity', [UserController::class, 'storeActivity']);
            }
        );
    }
);
