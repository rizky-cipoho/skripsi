<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Ujian\SoalController;
use App\Http\Controllers\AcceptController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Exam\ExamPageController;
use App\Http\Controllers\Exam\Question\QuestionController;


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

Route::get('/', function () {
    return Inertia::location('/login');
});
Route::get('guestLogin', [RegisteredUserController::class, 'guestLogin'])->name('guestLogin');

Route::group(['middleware'=>'auth', 'prefix'=>'/dashboard'], function(){
    Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/search', [DashboardController::class, 'search'])->name('search');
    Route::group(['prefix'=>'/my/exam'], function(){
        Route::get('/', [ExamPageController::class, 'myExam'])->name('myExam');
        Route::get('/add', [ExamPageController::class, 'add'])->name('examAdd');
        Route::get('/{id}', [ExamPageController::class, 'selected'])->name('examSelected');
        Route::get('/{id}/question', [ExamPageController::class, 'selected'])->name('examSelected');
        Route::get('/{id}/change/exam/name', [ExamPageController::class, 'changeExamName'])->name('changeExamName');
        Route::get('/{id}/change/exam/lesson', [ExamPageController::class, 'changeExamLesson'])->name('changeExamLesson');

        Route::group(['prefix'=>'/{id}/question'], function(){
            Route::get('/', [QuestionController::class, 'edit'])->name('questionEdit');
            Route::post('/update', [QuestionController::class, 'updateText'])->name('questionUpdate');
            Route::post('/add', [QuestionController::class, 'addQuestion'])->name('addQuestion');
            Route::post('/add/image/{questionId}', [QuestionController::class, 'addQuestionImage'])->name('addQuestionImage');
            Route::post('/remove', [QuestionController::class, 'removeQuestion'])->name('removeQuestion');
            Route::get('/get', [QuestionController::class, 'getQuestion'])->name('getQuestion');
        });
    });

        Route::get('/recommendation/{index}', function () {
            return Inertia::render('Dashboard');
        })->name('rIndex');
        Route::get('/user/{index}/{name}', function () {
            return Inertia::render('Dashboard');
        })->name('userIndex');
        Route::get('/rankcurrent', function () {
            return Inertia::render('rankcurrent');
        })->name('rankcurrent');
        Route::get('accept', [AcceptController::class, 'index']);
        Route::get('hujan', function(){
            return Inertia::render('list');
        });
        Route::post('post', [AcceptController::class, 'asd']);
    });

// Route::get('/dashboard', function () {
    //     return Inertia::render('Dashboard');
    // })->middleware(['auth', 'verified'])->name('dashboard');

    require __DIR__.'/auth.php';
