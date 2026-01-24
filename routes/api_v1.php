<?php


use App\Http\Controllers\OrderAndProduct\ProductController;
use App\Http\Controllers\testSmsNotification;
use App\Http\Controllers\V1\UserAuthController;
use App\TestFactory_DB_Notifications\NotificationsController;
use Illuminate\Support\Facades\Route;

//basic of rate limiting -> use throolting


//Route::get('users',[UserAuthController::class,'users'])->middleware('throttle:5,1'); // allow 5 request only form same ib دي الطريقة البسيطة
// لو عاوز بقا اكستم حاجة خاصة
// create custom route service provider to control it   CustomRouteServiceProvider

Route::get('users',[UserAuthController::class,'users'])->middleware('throttle:custom_limit'); //use custom limt

Route::post('sendData',[ProductController::class,'sendData']);
Route::post('login',[UserAuthController::class,'login']);
Route::post('sendNotification',[testSmsNotification::class,'sendOtp']);
//Route::post('sendNotification',[NotificationsController::class,'sendNotification']);
// Social Auth
Route::post('login/google',[UserAuthController::class,'googleLogin']);
Route::post('login/facebook',[UserAuthController::class,'facebookLogin']);

