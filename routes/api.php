<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\TeamPageController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PermissionController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware(['auth'])->group(function() {
    // setShownNotificationAsRead
    Route::prefix('/notification')->group(function () {
        Route::get('/set-as-read',                  [ApiController::class, 'setShownNotificationAsRead']);
        Route::get('/count-unread',                 [ApiController::class, 'countUnreadNotification']);
        Route::get('/set-all-as-read',              [ApiController::class, 'setAllNotificationAsRead']); // NOTE: Belum
        Route::get('/get',                          [ApiController::class, 'getAllNotificationByUserId']); // NOTE: Belum
    });
    Route::prefix('/event')->group(function () {
        Route::get('/confirmation',                 [ApiController::class, 'doConfirmCompetitionOnRegister']);
        Route::post('/register',                    [ApiController::class, 'registerContestantByCompetitionId']);
        Route::get('/check-status-by-user',         [ApiController::class, 'getEventStatusByUserId']);
        Route::get('/get-games',                    [ApiController::class, 'getGamesByCompetitionIdAndType']);
        Route::get('/get-rounds',                   [ApiController::class, 'getRoundByCompetitionId']);
        Route::get('/get-participants',             [ApiController::class, 'getParticipantsByCompetitionId']);
    });
    Route::prefix('/user')->group(function () {
        Route::put('/update',                       [ApiController::class, 'updateUserById']);
        Route::get('/origin/{user_id}',             [ApiController::class, 'getUserOriginByUserId']);
        Route::post('/photo-profile/update',        [ApiController::class, 'uploadPhotoProfileByUserId']);
        Route::put('/update-password',              [ApiController::class, 'updatePasswordByUserId']);
    });
    Route::prefix('/team')->group(function () {
        Route::post('/apply',                        [TeamPageController::class, 'createTeamApplication']);
    });
    Route::get('/fill-participant-table',           [ApiController::class, 'fillParticipantTable']);
    Route::prefix('/score')->group(function () {
        Route::get('/get',                          [ApiController::class, 'getContestantScoreByJudgeIdOnCurrentGames']);
        Route::post('/set',                         [ApiController::class, 'setContestantScoreByJudgeIdOnCurrentGames']);
        Route::post('/verify',                      [ApiController::class, 'verifyScoreByContestantId']);
        Route::get('/calculate/{id_contestant}/{id_games}', [ApiController::class, 'calculateAverageScore']);
    });
    Route::prefix('/check-in')->group(function () {
        Route::get('/confirmation',                 [ApiController::class, 'getContestantByUserIdAndIDevent']);
        Route::post('/set',                         [ApiController::class, 'setAttendanceByCompetitionId']);
    });
    // Route::middleware(['role:member'])->group(function () { // Only used for first time
    //     Route::get('/assign-roles-all-users',           [PermissionController::class, 'assignRolesToAllUsers']);
    // });
});

Route::prefix('/event')->group(function () {
    Route::get('/registered-contestant',        [ApiController::class, 'getRegisteredContestantByCompetitionId']);
    Route::get('/result',                       [ApiController::class, 'getResultByCompetitionId']);
});

Route::prefix('/user')->group(function () {
    Route::post('/register',                    [AuthController::class, 'doRegister']);
    Route::post('/login',                       [AuthController::class, 'doLogin']);
});