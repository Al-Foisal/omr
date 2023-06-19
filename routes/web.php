<?php

use App\Http\Controllers\Backend\AdminAuthController;
use App\Http\Controllers\Backend\AdminForgotPasswordController;
use App\Http\Controllers\Backend\AdminResetPasswordController;
use App\Http\Controllers\Backend\CompanyInfoController;
use App\Http\Controllers\Backend\CourseController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\PageController;
use App\Http\Controllers\Backend\SubjectController;
use App\Http\Controllers\Backend\TopicController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
 */

Route::get('/', [AdminAuthController::class, 'login']);
Route::prefix('/admin')->name('admin.auth.')->middleware('guest:admin')->group(function () {
    Route::redirect('/login', '/')->name('login');
    Route::post('/store-login', [AdminAuthController::class, 'storeLogin'])->name('storeLogin');

    Route::get('/forgot-password', [AdminForgotPasswordController::class, 'forgotPassword'])->name('forgotPassword');
    Route::post('/forgot-password', [AdminForgotPasswordController::class, 'storeForgotPassword'])->name('storeForgotPassword');

    Route::get('/reset-password/{token}', [AdminResetPasswordController::class, 'resetPassword'])->name('resetPassword');
    Route::post('/reset-password', [AdminResetPasswordController::class, 'storeForgotPassword'])->name('storeResetPassword');
});

Route::middleware('auth:admin')->prefix('/admin')->name('admin.')->group(function () {

    //admin management
    Route::controller(AdminAuthController::class)->name('auth.')->group(function () {
        Route::get('/admin-list', 'adminList')->name('adminList');
        Route::get('/create-admin', 'createAdmin')->name('createAdmin');
        Route::post('/store-admin', 'storeAdmin')->name('storeAdmin');
        Route::get('/edit-admin/{admin}', 'editAdmin')->name('editAdmin');
        Route::post('/update-admin/{admin}', 'updateAdmin')->name('updateAdmin');
        Route::post('/active-admin/{admin}', 'activeAdmin')->name('activeAdmin');
        Route::post('/inactive-admin/{admin}', 'inactiveAdmin')->name('inactiveAdmin');
        Route::delete('/delete-admin/{admin}', 'deleteAdmin')->name('deleteAdmin');

        Route::get('/customer-list', 'customerList')->name('customerList');
        Route::post('/update-custom-subscription', 'updateCustomSubscription')->name('updateCustomSubscription');
    });

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('auth.logout');

    Route::controller(CourseController::class)->prefix('/course')->name('course.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create-or-edit/{id?}', 'createOrEdit')->name('createOrEdit');
        Route::match (['post', 'put'], '/store-or-update/{id?}', 'storeOrUpdate')->name('storeOrUpdate');
        Route::post('/update-status/{id}', 'updateStatus')->name('updateStatus');
    });

    Route::controller(SubjectController::class)->prefix('/subject')->name('subject.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create-or-edit/{id?}', 'createOrEdit')->name('createOrEdit');
        Route::match (['post', 'put'], '/store-or-update/{id?}', 'storeOrUpdate')->name('storeOrUpdate');
        Route::post('/update-status/{id}', 'updateStatus')->name('updateStatus');
    });

    Route::controller(TopicController::class)->prefix('/topic')->name('topic.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create-or-edit/{id?}', 'createOrEdit')->name('createOrEdit');
        Route::match (['post', 'put'], '/store-or-update/{id?}', 'storeOrUpdate')->name('storeOrUpdate');
        Route::post('/update-status/{id}', 'updateStatus')->name('updateStatus');
    });

    Route::get('/company-info', [CompanyInfoController::class, 'showCompanyInfo'])->name('showCompanyInfo');
    Route::post('/company-info', [CompanyInfoController::class, 'storeCompanyInfo'])->name('storeCompanyInfo');

    Route::controller(PageController::class)->prefix('/page')->name('page.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{page}', 'edit')->name('edit');
        Route::put('/update/{page}', 'update')->name('update');
        Route::post('/active/{page}', 'active')->name('active');
        Route::post('/inactive/{page}', 'inactive')->name('inactive');
        Route::delete('/delete/{page}', 'delete')->name('delete');
    });
});