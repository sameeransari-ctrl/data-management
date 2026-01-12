<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\{
    //CmsController,
    //FaqController,
    ProfileController,
    DashboardController,
    NotificationController,
    SettingController,
    UserController,
    ProductController,
    RoleController,
    Auth\LoginController,
    Auth\ForgotPasswordController,
    BasicudiController,
    StaffController,
    ClientController,
    FsnController,
    ReportAnalyticsController,
    DataController,
    DesignationController,
    CompanyWebsiteController,
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
    ['middleware' => ['admin.check.login:web']],
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
    }
);




Route::group(
    ['middleware' => ['admin:web']],
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

        //Notification
        Route::get(
            'notification/read/',
            [NotificationController::class, 'markAllRead']
        )->name('notification.read');

        Route::controller(SettingController::class)
            ->group(
                function () {
                    Route::get('/runCommand/{slug}', 'runCommand')->name('setting.runCommand');
                    Route::get('/setting/general', 'generalList')->name('setting.general');
                }
            );

        Route::controller(UserController::class)
            ->group(
                function () {
                    Route::post('/user/changeStatus', 'changeStatus')
                        ->name('user.changeStatus')->middleware('permission');
                    Route::get('/user/{country_id}/cities', 'getCityByCountryId')
                    ->name('user.cities');
                    Route::get('/user/export-csv', 'exportCsv')
                    ->name('user.export-csv');
                }
            );


        // Route::controller(CmsController::class)
        //     ->group(
        //         function () {
        //             Route::get('/cms/{slug}', 'edit')->name('cms.edit');
        //             Route::post('/cms/{id}', 'update')->name('cms.update');
        //         }
        //     );

        Route::controller(RoleController::class)
            ->group(
                function () {
                    Route::post('/role/changeStatus', 'changeStatus')
                        ->name('role.changeStatus')->middleware('permission');
                }
            );

        Route::controller(DataController::class)
            ->group(
                function () {
                    Route::post('/data/changeStatus', 'changeStatus')
                        ->name('data.changeStatus')->middleware('permission');
                    Route::get('/data/export-csv', 'exportCsv')
                    ->name('data.export-csv');
                    Route::get('/import-data', 'importDataForm')->name('data.import-form');
                    Route::post('/import-data', 'importData')->name('data.import');
                }
            );

        Route::controller(StaffController::class)
            ->group(
                function () {
                    Route::post('/staff/changeStatus', 'changeStatus')
                        ->name('staff.changeStatus')->middleware('permission');
                    Route::get('/staff/export-csv', 'exportCsv')
                    ->name('staff.export-csv');
                }
            );

        
        // Route::controller(DataController::class)
        //     ->group(
        //         function () {
        //             Route::post('/data/changeStatus', 'changeStatus')
        //                 ->name('data.changeStatus')->middleware('permission');
        //             Route::get('/data/export-csv', 'exportCsv')
        //             ->name('data.export-csv');
        //         }
        //     );

        Route::controller(ClientController::class)
            ->group(
                function () {
                    Route::post('/client/changeStatus', 'changeStatus')->name('client.changeStatus');
                    Route::get('/client/{country_id}/cities', 'getCityByCountryId')
                    ->name('client.cities');
                    Route::get('/client/export-csv', 'exportCsv')
                    ->name('client.export-csv');
                    Route::get('/client/fsn-view', 'getFieldSafetyNotices')
                    ->name('client.fsn-view');
                }
            );

        Route::controller(FsnController::class)
            ->group(
                function () {
                    Route::get('/fsn', 'index')->name('fsn');
                    Route::get('/fsn/fsn-detail', 'fsnViewDetails')->name('fsn.fsn-detail');
                    Route::get('/fsn/export-csv', 'exportCsv')->name('fsn.export-csv');
                }
            );

        
        Route::controller(DesignationController::class)
            ->group(
                function () {
                    Route::get('/designation/list', 'list')->name('designation.list');
                }
            );

        Route::controller(CompanyWebsiteController::class)
            ->group(
                function () {
                    Route::get('/companywebsites/list', 'list')->name('companywebsites.list');
                }
            );

        //Product Route
        Route::controller(ProductController::class)
            ->group(
                function () {
                    Route::get('/get-questions', 'getProductTypeQuestions')->name('product.get-questions');
                    Route::get('/add-question/{type}', 'addQuestion')->name('product.add-question');
                    Route::post('/store-question', 'storeQuestion')->name('product.store-question');
                    Route::get('/edit-question/{id}', 'editQuestion')->name('product.edit-question');
                    Route::put('/update-question/{id}', 'updateQuestion')->name('product.update-question');
                    Route::delete('/destroy-question/{id}', 'destroyQuestion')->name('product.destroy-question');
                    Route::put('/update-product-status', 'updateProductStatus')->name('product.update-product-status');
                    Route::get('/import-product', 'importProductForm')->name('product.import-form');
                    Route::post('/import-product', 'importProduct')->name('product.import');
                    Route::get('/product/export-csv', 'exportCsv')->name('product.export-csv');
                    Route::get('/product/{client_id}/basic-udids', 'getClientBasicUdids')->name('product.basic-udids');
                }
            );

        Route::controller(BasicudiController::class)
            ->group(
                function () {
                    Route::get('/basicudi/export-csv', 'exportCsv')
                    ->name('basicudi.export-csv');
                    Route::get('/import-basic-udi', 'importBasicUdiForm')->name('basicudi.import-form');
                    Route::post('/import-basic-udi', 'importBasicUdi')->name('basicudi.import');
                }
            );

        //Resource Route
        Route::resources(
            [
                'notification' => NotificationController::class,
                //'faq' => FaqController::class,
                'profile' => ProfileController::class,
                'setting' => SettingController::class,
                'basicudi' => BasicudiController::class,
            ]
        );

        Route::group(
            ['middleware' => 'permission'],
            function () {
                //Product Route
                Route::controller(ProductController::class)
                    ->group(
                        function () {
                            Route::get('/get-questions', 'getProductTypeQuestions')->name('product.get-questions');
                            Route::get('/add-question/{type}', 'addQuestion')->name('product.add-question');
                            Route::post('/store-question', 'storeQuestion')->name('product.store-question');
                            Route::get('/edit-question/{id}', 'editQuestion')->name('product.edit-question');
                            Route::put('/update-question/{id}', 'updateQuestion')->name('product.update-question');
                            Route::delete('/destroy-question/{id}', 'destroyQuestion')
                                ->name('product.destroy-question');
                            Route::put('/update-product-status', 'updateProductStatus')
                                ->name('product.update-product-status');
                            Route::get('/product/export-csv', 'exportCsv')->name('product.export-csv');
                            Route::get('/product/review-detail/{id}', 'getRatingDetails')
                            ->name('product.rating-details');
                        }
                    );
                Route::resources(
                    [
                        'user' => UserController::class,
                        'role' => RoleController::class,
                        'staff' => StaffController::class,
                        'client' => ClientController::class,
                        'product' => ProductController::class,
                        'data' => DataController::class,
                    ]
                );
            }
        );

        Route::controller(ReportAnalyticsController::class)
            ->group(
                function () {
                    Route::get('/reports', 'index')->name('reports');
                }
            );
    }
);
