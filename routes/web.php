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
use App\Http\Controllers\Exam\else\ExamElseController;
use App\Http\Controllers\Exam\Question\Choice\ChoiceController;
use App\Http\Middleware\SessionThere;

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

Route::group(['middleware'=>['auth'], 'prefix'=>'/dashboard'], function(){
    Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard')->middleware(SessionThere::class);
    Route::get('/search', [DashboardController::class, 'search'])->name('search');
    Route::group(['prefix'=>'/my/exam', 'middleware'=>'sessionthere'], function(){
        Route::get('/', [ExamPageController::class, 'myExam'])->name('myExam');
        Route::get('/add', [ExamPageController::class, 'add'])->name('examAdd');
        Route::get('/{id}', [ExamPageController::class, 'selected'])->name('examSelected');
        // Route::get('/{id}/question', [ExamPageController::class, 'selected'])->name('examSelected');
        Route::get('/{id}/change/exam/name', [ExamPageController::class, 'changeExamName'])->name('changeExamName');
        Route::get('/{id}/change/exam/lesson', [ExamPageController::class, 'changeExamLesson'])->name('changeExamLesson');
        Route::post('/{id}/key', [ExamPageController::class, 'examKey'])->name('examKey');
        Route::post('/{id}/time/duration', [ExamPageController::class, 'timeDuration'])->name('timeDuration');
        Route::post('/{id}/time/startExamCheckBox', [ExamPageController::class, 'startExamCheckBox'])->name('startExamCheckBox');
        Route::post('/{id}/time/timeStart', [ExamPageController::class, 'timeStart'])->name('timeStart');

        Route::group(['prefix'=>'/{id}/question'], function(){
            Route::get('/', [QuestionController::class, 'edit'])->name('questionEdit');
            Route::post('/update', [QuestionController::class, 'updateText'])->name('questionUpdate');
            Route::get('/questionDataUpdate', [QuestionController::class, 'returnGet'])->name('questionDataUpdate');
            Route::post('/add', [QuestionController::class, 'addQuestion'])->name('addQuestion');
            Route::post('/add/image/{questionId}', [QuestionController::class, 'addQuestionImage'])->name('addQuestionImage');
            Route::post('/remove', [QuestionController::class, 'removeQuestion'])->name('removeQuestion');
            Route::post('/choice/add', [ChoiceController::class, 'addChoice'])->name('addChoice');
            Route::post('/choice/update', [ChoiceController::class, 'updateChoice'])->name('updateChoice');
            Route::post('/choice/update/attachment', [ChoiceController::class, 'choiceAttachment'])->name('choiceAttachment');
            Route::post('/choice/remove', [ChoiceController::class, 'removeChoice'])->name('removeChoice');
            Route::post('/choice/key', [ChoiceController::class, 'keyChoice'])->name('keyChoice');
            Route::post('/choice/removeImage', [ChoiceController::class, 'removeImage'])->name('removeImage');
            Route::post('/choice/length', [ChoiceController::class, 'choiceLength'])->name('choiceLength');
        });
    });
    
    route::group(['prefix'=>'/exam/else'], function(){
        Route::get('/{id}', [ExamElseController::class, 'examInfo'])->name('examInfo')->middleware(SessionThere::class);;
        Route::get('/{id}/session', [ExamElseController::class, 'createSession'])->name('createSession')->middleware(SessionThere::class);;
        Route::get('/{id}/generate/data/session', [ExamElseController::class, 'generateDataSession'])->name('generateDataSession')->middleware(SessionThere::class);;
        Route::get('/{id}/generate/data/where/{token}', [ExamElseController::class, 'dataSession'])->name('dataSession');
        Route::post('/{id}/generate/data/where/{token}/choice', [ExamElseController::class, 'getDataTokenChoice'])->name('getDataTokenChoice');
        Route::get('/{id}/generate/data/where/{token}/over', [ExamElseController::class, 'tokenOver'])->name('tokenOver');
        Route::post('/{id}/generate/data/where/{token}/pin', [ExamElseController::class, 'tokenPin'])->name('tokenPin');
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
