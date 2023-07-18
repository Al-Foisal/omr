<?php

use App\Http\Controllers\Backend\AdminAuthController;
use App\Http\Controllers\Backend\AdminForgotPasswordController;
use App\Http\Controllers\Backend\AdminResetPasswordController;
use App\Http\Controllers\Backend\CompanyInfoController;
use App\Http\Controllers\Backend\CourseController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\ExamController;
use App\Http\Controllers\Backend\ExamQuestionController;
use App\Http\Controllers\Backend\ImportExportController;
use App\Http\Controllers\Backend\NotificationController;
use App\Http\Controllers\Backend\PageController;
use App\Http\Controllers\Backend\StudentPanelController;
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

Route::middleware('guest:admin')->group(function () {
    Route::get('/', [AdminAuthController::class, 'login'])->name('login');
});
Route::prefix('/admin')->name('admin.auth.')->middleware('guest:admin')->group(function () {
    Route::redirect('/login', '/')->name('login');
    Route::post('/store-login', [AdminAuthController::class, 'storeLogin'])->name('storeLogin');

    Route::get('/forgot-password', [AdminForgotPasswordController::class, 'forgotPassword'])->name('forgotPassword');
    Route::post('/store-forgot-password', [AdminForgotPasswordController::class, 'storeForgotPassword'])->name('storeForgotPassword');

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

        Route::get('/user-list', 'customerList')->name('customerList');
        Route::post('/update-status/{id}', 'updateStatus')->name('updateStatus');
        Route::get('/student-details/{id}', 'studentDetails')->name('studentDetails');
        Route::get('/student/{id}/course-details/{course_id}', 'studentCourseDetails')->name('studentCourseDetails');
        Route::delete('/student-delete/{id}', 'studentDelete')->name('studentDelete');
    });

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('auth.logout');

    Route::controller(ExamQuestionController::class)->prefix('/exam-question')->name('examQuestion.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create-or-edit/{id}', 'createOrEdit')->name('createOrEdit');
        Route::post('/store-or-update', 'storeOrUpdate')->name('storeOrUpdate'); //jandle by js
        Route::post('/make-for-review', 'makeForReview')->name('makeForReview'); //jandle by js
        Route::post('/update-status/{id}', 'updateStatus')->name('updateStatus');
        Route::post('/preview', 'preview')->name('preview');
        Route::post('/preview-answer', 'previewAnswer')->name('previewAnswer');
        Route::get('/go-to-exam-question', 'goToExamQuestion')->name('goToExamQuestion');
    });

    Route::controller(ExamController::class)->prefix('/exam')->name('exam.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create-or-edit/{id?}', 'createOrEdit')->name('createOrEdit');
        Route::match (['post', 'put'], '/store-or-update/{id?}', 'storeOrUpdate')->name('storeOrUpdate');
        Route::post('/update-status/{id}', 'updateStatus')->name('updateStatus');
        Route::post('/get-course-wise-subject', 'getCourseWiseSubject')->name('getCourseWiseSubject');
        Route::post('/get-course-wise-subject-exam', 'getCourseWiseSubjectExam')->name('getCourseWiseSubjectExam');
    });

    Route::controller(CourseController::class)->prefix('/course')->name('course.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/published-courses', 'publishedCourses')->name('publishedCourses');
        Route::get('/pending-courses', 'pendingCourses')->name('pendingCourses');
        Route::get('/create-or-edit/{id?}', 'createOrEdit')->name('createOrEdit');
        Route::match (['post', 'put'], '/store-or-update/{id?}', 'storeOrUpdate')->name('storeOrUpdate');
        Route::post('/update-status/{id}', 'updateStatus')->name('updateStatus');
        Route::get('/update-course-subject-status/{id}', 'updateCourseSubjectStatus')->name('updateCourseSubjectStatus');
        Route::get('/create-or-update-exam/{id}/{exam_id?}', 'createOrUpdateExam')->name('createOrUpdateExam');
        Route::post('/store-or-update-exam', 'storeOrUpdateExam')->name('storeOrUpdateExam');
        Route::get('/update-course-subject-topic-status/{id}', 'updateCourseSubjectTopicStatus')->name('updateCourseSubjectTopicStatus');
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
    Route::controller(NotificationController::class)->prefix('/notification')->name('notification.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
    });

    Route::controller(StudentPanelController::class)->prefix('/student-panel')->name('studentPanel.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/pending-course-registration', 'pendingCourseRegistration')->name('pendingCourseRegistration');
        Route::get('/approved-course-registration', 'approvedCourseRegistration')->name('approvedCourseRegistration');
        Route::post('/update-status/{id}', 'updateStatus')->name('updateStatus');
        Route::delete('/delete/{id}', 'delete')->name('delete');
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

    Route::controller(ImportExportController::class)->prefix('/ie')->name('ie.')->group(function () {
        Route::post('import', 'import')->name('import');
        Route::post('import-answer', 'importAnswer')->name('importAnswer');
        Route::post('export', 'export')->name('export');
    });
});