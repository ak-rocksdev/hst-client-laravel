<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Auth\AuthController;

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
    Route::prefix('/event')->group(function () {
        Route::get('/confirmation',             [ApiController::class, 'doConfirmCompetitionOnRegister']);
        Route::post('/register',                [ApiController::class, 'registerContestantByCompetitionId']);
        Route::get('/check-status-by-user',     [ApiController::class, 'getEventStatusByUserId']);
    });
    Route::prefix('/user')->group(function () {
        Route::put('/update',                       [ApiController::class, 'updateUserById']);
        Route::get('/origin/{user_id}',             [ApiController::class, 'getUserOriginByUserId']);
        Route::post('/photo-profile/update',        [ApiController::class, 'uploadPhotoProfileByUserId']);
        Route::put('/update-password',              [ApiController::class, 'updatePasswordByUserId']);
    });
});

Route::prefix('/event')->group(function () {
    Route::get('/registered-contestant',        [ApiController::class, 'getRegisteredContestantByCompetitionId']);
    Route::get('/result',                       [ApiController::class, 'getResultByCompetitionId']);
});

Route::prefix('/user')->group(function () {
    Route::post('/register',                    [AuthController::class, 'doRegister']);
    Route::post('/login',                       [AuthController::class, 'doLogin']);
});