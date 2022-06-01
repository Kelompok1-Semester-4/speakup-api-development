<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\DetailQuizController;
use App\Http\Controllers\DiaryController;
use App\Http\Controllers\DiaryTypeController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Constraint\Count;

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

Route::get('/courses', [CourseController::class, 'index']);
Route::get('/diaries', [DiaryController::class, 'index']);
Route::get('/educations', [EducationController::class, 'index']);
Route::get('/roles', [RoleController::class, 'index']);
Route::get('/users', [UserController::class, 'index']);
Route::get('/diary-types', [DiaryTypeController::class, 'index']);
Route::get('quizzes', [QuizController::class, 'index']);
Route::get('detail-quiz/{id}', [DetailQuizController::class, 'index']);
Route::get('/detail-question/{id}', [DetailQuizController::class, 'fetch']);
Route::get('detailsubcourse/{id}', [CourseController::class, 'detailSubCourse']);

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // users
    Route::get('detail-conselor/{id}', [UserController::class, 'detailConselor']);
    Route::post('update-verification/{id}', [UserController::class, 'updateVerification']);
    Route::get('user', [UserController::class, 'fetch']);
    Route::post('user/update', [UserController::class, 'update']);
    Route::post('logout', [UserController::class, 'logout']);
    Route::delete('user/{id}', [UserController::class, 'delete']);
    // diaries
    Route::post('diary', [DiaryController::class, 'store']);
    Route::post('diary/edit/{id}', [DiaryController::class, 'update']);
    Route::delete('diary/{id}', [DiaryController::class, 'destroy']);
    // courses
    Route::get('conselor-courses', [CourseController::class, 'conselorCourses']);
    Route::post('course', [CourseController::class, 'store']);
    Route::post('course/{id}', [CourseController::class, 'update']);
    Route::delete('course/{id}', [CourseController::class, 'destroy']);
    // detail course
    Route::post('detailcourse/{id}', [CourseController::class, 'storeDetailCourse']);
    Route::post('updatedetailcourse/{id}', [CourseController::class, 'updateDetailCourse']);
    Route::delete('deletedetailcourse/{id}', [CourseController::class, 'destroyDetailCourse']);

    // transactions
    Route::get('transaction', [TransactionController::class, 'index']);
    Route::post('transaction/{id}', [TransactionController::class, 'update']);
    Route::post('transaction', [TransactionController::class, 'store']);
    Route::get('conselor-transaction', [TransactionController::class, 'conselorTransaction']);
    Route::get('conselor-transaction/detail/{id}', [TransactionController::class, 'detailConselorTransaction']);
    Route::post('update-transaction/{id}', [TransactionController::class, 'updateTransaction']);

    // diaries
    Route::get('diaries-user', [DiaryController::class, 'show']);

    // quiz
    Route::post('quiz/store', [QuizController::class, 'store']);
    Route::post('quiz/update/{id}', [QuizController::class, 'update']);
    Route::delete('quiz/{id}', [QuizController::class, 'destroy']);
    // detail Quiz
    Route::post('detail-quiz/store', [DetailQuizController::class, 'store']);
    Route::post('detail-quiz/update/{id}', [DetailQuizController::class, 'update']);
    Route::delete('detail-quiz/{id}', [DetailQuizController::class, 'destroy']);
});
