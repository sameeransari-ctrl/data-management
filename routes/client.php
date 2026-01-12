<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Client\{
    ProfileController,
    DashboardController,
    BasicudiController,
    Auth\LoginController,
    Auth\ForgotPasswordController,
    Auth\RegisterController,
    FsnController,
    ProductController,
    UserController,
    NotificationController,
};

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

Route::group(
    ['middleware' => ['client.check.login:web']],
    function () {
        Route::controller(LoginController::class)
            ->group(
                function () {

                    Route::get('/', 'index')->name('login');
                    Route::post('/', 'login')->name('login.submit');

                    Route::get('otp-verify/{email}', 'showVerifyOtpForm')->name('login.otp.show');
                    Route::get('resend-otp/{email}', 'resendOtp')->name('login.otp.resend');
                    Route::post('otp-verify', 'verifyOtp')->name('otp.verify');
                }
            );

        Route::controller(ForgotPasswordController::class)
            ->group(
                function () {
                    Route::get('/forgot-password', 'showLinkRequestForm')->name('forgot-password');
                    Route::post('/forgot-password', 'sendResetLinkEmail')->name('forgotPassword');
                    Route::get('reset-password-success', 'showSuccessPage')->name('password.success');
                    Route::get('reset-password/{token}', 'showResetPasswordForm')->name('password.get');
                    Route::post('reset-password', 'submitResetPasswordForm')->name('password.post');
                    Route::get('reset-password-otp/{email}', 'showOtpResetPasswordForm')->name('otp.password.get');
                    Route::post('reset-password-otp', 'submitOtpResetPasswordForm')->name('reset.password.post');
                }
            );

        Route::controller(RegisterController::class)
            ->group(
                function () {
                    Route::get('/register', 'index')->name('register');
                    Route::post('/register', 'register')->name('register.submit');
                    Route::get('/register/{country_id}/cities', 'getCityByCountryId')
                        ->name('register.cities');
                }
            );
    }
);


Route::group(
    ['middleware' => ['client:web']],
    function () {
        Route::controller(DashboardController::class)
            ->group(
                function () {
                    Route::get('/dashboard', 'index')->name('dashboard');
                    Route::get('/dashboard/map-data', 'mapData')->name('dashboard.mapData');
                }
            );

        Route::get(
            '/logout',
            [LoginController::class, 'logout']
        )->name('logout');

        Route::get(
            'change-user-password',
            [
                ProfileController::class,
                'changeUserPassword'
            ]
        )->name('change-user-password');

        Route::post(
            'change-password',
            [
                ProfileController::class,
                'changePassword'
            ]
        )->name('change-password');

        Route::post(
            'update-profile',
            [
                ProfileController::class,
                'update'
            ]
        )->name('update-profile');

        Route::controller(BasicudiController::class)
            ->group(
                function () {
                    Route::get('/basicudi/export-csv', 'exportCsv')
                    ->name('basicudi.export-csv');
                    Route::get('/import-basic-udi', 'importBasicUdiForm')->name('basicudi.import-form');
                    Route::post('/import-basic-udi', 'importBasicUdi')->name('basicudi.import');
                }
            );

        Route::controller(UserController::class)
            ->group(
                function () {
                    Route::get('/user/export-csv', 'exportCsv')
                    ->name('user.export-csv');
                }
            );

        Route::get(
            'notification/read/',
            [NotificationController::class, 'markAllRead']
        )->name('notification.read');

        Route::controller(FsnController::class)
            ->group(
                function () {
                        Route::get('/get-single-product/{id}', 'getSingleProduct')->name('fsn.getSingleProduct');
                        Route::post('/preview-modal', 'previewModal')->name('fsn.previewModal');
                        Route::post('/edit-modal', 'editFsn')->name('fsn.editFsn');
                        Route::get('/fsn/export-csv', 'exportCsv')->name('fsn.export-csv');
                }
            );

        Route::controller(ProfileController::class)
            ->group(
                function () {
                    Route::get('/profile/{country_id}/cities', 'getCityByCountryId')
                    ->name('profile.cities');
                }
            );

        Route::controller(ProductController::class)
            ->group(
                function () {
                    Route::get('/product/review-detail/{id}', 'getRatingDetails')->name('product.rating-details');
                    Route::get('/add-question/{type}', 'addQuestion')->name('product.add-question');
                    Route::post('/store-question', 'storeQuestion')->name('product.store-question');
                    Route::get('/edit-question/{id}', 'editQuestion')->name('product.edit-question');
                    Route::put('/update-question/{id}', 'updateQuestion')->name('product.update-question');
                    Route::get('/get-questions', 'getProductTypeQuestions')->name('product.get-questions');
                    Route::put('/update-product-status', 'updateProductStatus')->name('product.update-product-status');
                    Route::delete('/destroy-question/{id}', 'destroyQuestion')->name('product.destroy-question');
                    Route::get('/product/export-csv', 'exportCsv')->name('product.export-csv');
                    Route::get('/import-product', 'importProductForm')->name('product.import-form');
                    Route::post('/import-product', 'importProduct')->name('product.import');
                }
            );


        //Resource Route
        Route::resources(
            [
                'profile' => ProfileController::class,
                'basicudi' => BasicudiController::class,
                'user' => UserController::class,
                'fsn' => FsnController::class,
                'product' => ProductController::class,
                'notification' => NotificationController::class,
            ]
        );
    }
);
