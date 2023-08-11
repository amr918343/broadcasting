<?php
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Profile\ProfileController;
use App\Http\Controllers\Api\Contacts\ContactController;
use App\Http\Controllers\Api\Legal\FAQController;
use App\Http\Controllers\Api\Legal\PolicyController;
use App\Http\Controllers\Api\Legal\TermConditionController;

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

Route::group(['middleware' => 'changeLanguage'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('register', [AuthController::class, 'signup']);
        Route::post('verify-account', [AuthController::class, 'verifyAccount']);
        Route::post('login', [AuthController::class, 'signin'])->name('login');
        Route::post('send-reset-code', [AuthController::class, 'sendResetCode']);
        Route::post('confirm-reset-code', [AuthController::class, 'checkResetPassword']);
        Route::post('reset-password', [AuthController::class, 'resetPassword']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('logout', [AuthController::class, 'logout']);

        Route::group(['prefix' => 'profile'], function() {
            Route::get('/', [ProfileController::class, 'showUserProfile']);
            Route::put('/', [ProfileController::class, 'userProfileUpdate']);
            Route::delete('/', [ProfileController::class, 'UserProfileDelete']);
            Route::put('/password', [ProfileController::class, 'updatePassword']);
            Route::put('/avatar', [ProfileController::class, 'updateAvatar']);
        });
    });
    Route::get('home', [HomeController::class, 'index']);

    Route::group(['prefix' => 'pages'], function() {
        Route::group(['prefix' => 'contact'], function() {
            Route::post('', [ContactController::class, 'store']);
            Route::get('', [ContactController::class, 'index']);
        });
    });

    Route::group(['prefix' => 'legal'], function() {
        Route::get('faq', [FAQController::class, 'getFAQ']);
        Route::get('terms-conditions', [TermConditionController::class, 'getTermsConditions']);
        Route::get('policy', [PolicyController::class, 'getPolicies']);
    });
});
