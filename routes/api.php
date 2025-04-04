<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Mail;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;
use Laravel\Fortify\Http\Controllers\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetController;

Route::post('/password/email', [PasswordResetController::class, 'sendResetLink']);
Route::post('/password/reset', [PasswordResetController::class, 'reset']);


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // user 
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/usersdetails',[AuthController::class,'usersdetails']);
    Route::post('/userupdate/{id}',[AuthController::class,'update']);
    Route::post('/userdelete/{id}',[AuthController::class,'destroy']);

    // policy
    Route::post('/policy', [PolicyController::class,'store']);
    Route::get('/index', [PolicyController::class,'index']);
    Route::post('/update/{id}', [PolicyController::class,'update']);
    Route::post('/destroy/{id}', [PolicyController::class,'destroy']);

});
