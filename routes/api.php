<?php

use App\Http\Controllers\Api\AnswerController;
use App\Http\Controllers\Api\GeneralController;
use App\Http\Controllers\Api\UserAuthController;
use App\Http\Controllers\Backend\DashboardController;
use App\Models\ExamQuestion;
use App\Models\ExamQuestionOption;
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
Route::post('/store-device-token', [DashboardController::class, 'store_device_token'])->middleware('auth:sanctum');


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
    Route::post('update-profile', 'updateProfile');
    Route::post('notification', 'notification');
    Route::post('/registered-or-suggest-courses', 'registeredOrSuggestCourses');
    Route::post('/search', 'search');
    Route::post('/store-course-registration', 'storeCourseRegistration');
});
Route::controller(AnswerController::class)->prefix('/answer')->middleware('auth:sanctum')->group(function () {
    Route::post('store', 'store');
    Route::post('/show', 'show');
});

Route::get('/q', function () {
    $faker = Faker\Factory::create();
    $exam  = ExamQuestion::where('exam_id', 11)->where('subject_id', 14)->get();

    foreach ($exam as $e) {
        $question                       = ExamQuestion::find($e->id);
        $question->subject_topic_id     = $faker->randomElement(['26', '27', '28', '29']);
        $question->question_name        = $faker->sentence(15);
        $question->question_explanation = $faker->text(400);
        $question->save();

        for ($i = 0; $i < 4; $i++) {
            // ExamQuestionOption::where('exam_question_id', $question->id)->delete();
            $answer = ExamQuestionOption::create([
                'exam_question_id' => $question->id,
                'option'           => $faker->sentence(7),
                'is_answer'        => $i == 3 ? 1 : 0,
            ]);
        }

    }

    return 'ok';
});

