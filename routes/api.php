<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\DiaryController;
use App\Http\Controllers\DiaryTypeController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
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

Route::get('/courses', [CourseController::class, 'index']);
Route::get('/diaries', [DiaryController::class, 'index']);
Route::get('/educations', [EducationController::class, 'index']);
Route::get('/roles', [RoleController::class, 'index']);
Route::get('/users', [UserController::class, 'index']);
Route::get('/diary-types', [DiaryTypeController::class, 'index']);

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function() {
    // users
    Route::get('user', [UserController::class, 'fetch']);
    Route::post('user', [UserController::class, 'update']);
    Route::post('logout', [UserController::class, 'logout']);
    // diaries
    Route::post('diary', [DiaryController::class, 'store']);
    Route::post('diary/{id}', [DiaryController::class, 'update']);
    Route::delete('diary/{id}', [DiaryController::class, 'destroy']);
    // courses
    Route::post('course', [CourseController::class, 'store']);
    Route::post('course/{id}', [CourseController::class, 'update']);
    Route::delete('course/{id}', [CourseController::class, 'destroy']);
    // detail course
    Route::post('detailcourse/{id}', [CourseController::class, 'storeDetailCourse']);
    Route::post('updatedetailcourse/{id}', [CourseController::class, 'updateDetailCourse']);
    Route::delete('deletedetailcourse/{id}', [CourseController::class, 'destroyDetailCourse']);
    // transactions
    Route::get('transaction', [TransactionController::class, 'index']);
    Route::post('transaction', [TransactionController::class, 'store']);
    Route::post('transaction/{id}', [TransactionController::class, 'update']);
    // diaries
    Route::get('diaries', [DiaryController::class, 'index']);
});
