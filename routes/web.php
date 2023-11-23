<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Auth\AuthController;

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

Route::get('config-clear', function () {
    \Artisan::call('config:cache');
    \Artisan::call('config:clear');
    return dd("Config Cleared");
});

Route::get('optimize', function () {
    \Artisan::call('optimize:clear');
    return dd("Optimized");
});

Route::get('/linkstorage', function () {
    symlink('/home/metroyal/dev.hyperscore/engine/storage/app/public',  '/home/metroyal/dev.hyperscore/storage');
    return dd('Storage link created');
});

Route::group(['middleware' => 'language'], function () {
    Route::get('/',                         [PageController::class, 'index'])->name('home');
    Route::get('/events',                   [PageController::class, 'viewEventsPage']);
    Route::get('/event/{id}',               [PageController::class, 'viewEventPageById']);
    Route::get('/login',                    [PageController::class, 'viewUserLoginPage'])->name('login');
    Route::get('/logout',                   [AuthController::class, 'doLogout'])->name('logout');
    Route::get('/register',                 [PageController::class, 'viewUserRegisterPage']);
    Route::get('/update-password',          [PageController::class, 'viewUpdatePasswordPage']);

    Route::group(['middleware' => 'auth'], function () {
        Route::get('/profile',                  [PageController::class, 'viewProfilePage']);
        Route::get('/profile/edit',             [PageController::class, 'viewEditProfilePage']);

        Route::prefix('/event')->group(function () {
            Route::get('/detail/{id}',          [PageController::class, 'viewMyEventDetailMemberPage']);
            Route::get('/judge/{id}',           [PageController::class, 'viewEventDetailJudgePage']);
            Route::get('/judge/scoring/{runningId}',   [PageController::class, 'viewJudgeScoringPage']);
        });
    });
});

Route::post('/doLogin',                 [AuthController::class, 'doLogin']);
Route::post('/forceUpdatePassword',     [AuthController::class, 'doForceUpdatePassword']);

Route::get('/set-language/id',          [Controller::class, 'setLangId']);
Route::get('/set-language/en',          [Controller::class, 'setLangEn']);