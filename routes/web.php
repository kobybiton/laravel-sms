<?php


use Illuminate\Support\Facades\Route;

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

// Restful api
Route::resources([
    'students' => StudentController::class,
    'teachers' => TeacherController::class,
    'periods' => PeriodController::class,
]);

Route::resource('students', StudentController::class)->only([
    'create', 'store'
]);

Route::post('student/login', 'Auth\LoginController@studentLogin');

Route::resource('teachers', TeacherController::class)->only([
    'create', 'store'
]);

Route::post('teacher/login', 'Auth\LoginController@teacherLogin');

// authenticated endpoints
Route::middleware('auth:student')->group(function() {
    Route::resource('students', StudentController::class)->only([
        'index', 'show', 'update', 'destroy'
    ]);
    // Student routes group
    Route::prefix('student')->group(function(){
        Route::prefix('/toggle-period')->group(function(){
            Route::put('/{id}',"StudentController@assignToPeriod");
            Route::delete('/{id}',"StudentController@removeFromPeriod");
        });

        Route::get('/all-from-period/{id}', 'PeriodController@students');
        Route::get('/all-of-teacher-via-period/{id}', 'TeacherController@students');
    });
});

Route::middleware('auth:teacher')->group(function() {
    Route::resource('teachers', TeacherController::class)->only([
        'index', 'show', 'update', 'destroy'
    ]);

    Route::prefix('teacher')->group(function(){
        Route::prefix('/toggle-period')->group(function(){
            Route::put('/{id}',"TeacherController@assignToPeriod");
            Route::delete('/{id}',"TeacherController@removeFromPeriod");
        });

        Route::get('/of-periods/{id}', 'TeacherController@periods');
    });
});

Route::get('get-csrf-token', function () {
    return csrf_token();
});


