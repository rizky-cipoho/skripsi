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
use App\Http\Controllers\History\HistoryController;
use App\Http\Controllers\Rank\RankCurrentController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Cheat\CheatController;
use App\Http\Middleware\SessionThere;
use App\Http\Middleware\TokenIsMine;
use App\Http\Middleware\ExamRemove;

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
    Route::get('/history', [HistoryController::class, 'history'])->name('history')->middleware(SessionThere::class);
    Route::get('/rankCurrent', [RankCurrentController::class, 'rankCurrent'])->name('rankCurrent')->middleware(SessionThere::class);
    Route::get('/setting', [UserController::class, 'setting'])->name('setting')->middleware(SessionThere::class);
    Route::post('/setting/name', [UserController::class, 'settingName'])->name('settingName')->middleware(SessionThere::class);
    Route::post('/setting/description', [UserController::class, 'settingDescription'])->name('settingDescription')->middleware(SessionThere::class);
    Route::post('/setting/school', [UserController::class, 'settingSchool'])->name('settingSchool')->middleware(SessionThere::class);
    Route::post('/setting/image', [UserController::class, 'settingImage'])->name('settingImage')->middleware(SessionThere::class);
    Route::get('/search', [DashboardController::class, 'search'])->name('search');
    Route::group(['prefix'=>'/my/exam', 'middleware'=>['examRemove','sessionthere']], function(){
        Route::get('/', [ExamPageController::class, 'myExam'])->name('myExam');
        Route::get('/add', [ExamPageController::class, 'add'])->name('examAdd');
        Route::get('/{id}', [ExamPageController::class, 'selected'])->name('examSelected');
        // Route::get('/{id}/question', [ExamPageController::class, 'selected'])->name('examSelected');
        Route::post('/{id}/change/exam/name', [ExamPageController::class, 'changeExamDescription'])->name('changeExamDescription');
        Route::post('/{id}/change/image', [ExamPageController::class, 'changeExamImage'])->name('changeExamImage');
        Route::get('/{id}/change/exam/description', [ExamPageController::class, 'changeExamName'])->name('changeExamName');
        Route::get('/{id}/change/exam/lesson', [ExamPageController::class, 'changeExamLesson'])->name('changeExamLesson');
        Route::get('/{id}/history', [ExamPageController::class, 'myExamHistory'])->name('myExamHistory');
        Route::post('/{id}/key', [ExamPageController::class, 'examKey'])->name('examKey');
        Route::post('/{id}/time/duration', [ExamPageController::class, 'timeDuration'])->name('timeDuration');
        Route::post('/{id}/time/startExamCheckBox', [ExamPageController::class, 'startExamCheckBox'])->name('startExamCheckBox');
        Route::post('/{id}/time/timeStart', [ExamPageController::class, 'timeStart'])->name('timeStart');
        Route::post('/{id}/tier', [ExamPageController::class, 'tier'])->name('tier');
        Route::get('/{id}/remove', [ExamPageController::class, 'examRemove'])->name('examRemove');
        Route::post('/{id}/minimum', [ExamPageController::class, 'minimum'])->name('minimum');
        Route::post('/{id}/detected', [ExamPageController::class, 'detected'])->name('detected');

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
        Route::get('/{id}/generate/data/session', [ExamElseController::class, 'generateDataSession'])->name('generateDataSession')->middleware(SessionThere::class);
        Route::get('/{id}/generate/data/where/{token}', [ExamElseController::class, 'dataSession'])->middleware(TokenIsMine::class)->name('dataSession');
        Route::post('/{id}/generate/data/where/{token}/cheat', [CheatController::class, 'cheat'])->middleware(TokenIsMine::class)->name('cheat');
        Route::post('/{id}/generate/data/where/{token}/choice', [ExamElseController::class, 'getDataTokenChoice'])->middleware(TokenIsMine::class)->name('getDataTokenChoice');
        Route::get('/{id}/generate/data/where/{token}/over', [ExamElseController::class, 'tokenOver'])->middleware(TokenIsMine::class)->name('tokenOver');
        Route::get('/{id}/session/result/{token}', [ExamElseController::class, 'result'])->middleware(TokenIsMine::class)->name('result');
        Route::post('/{id}/generate/data/where/{token}/pin', [ExamElseController::class, 'tokenPin'])->middleware(TokenIsMine::class)->name('tokenPin');
    });

    Route::get('/recommendation/{index}', function () {
        return Inertia::render('Dashboard');
    })->name('rIndex');
    Route::get('/user/{index}/{name}', function () {
        return Inertia::render('Dashboard');
    })->name('userIndex');
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
