<?php


use App\Http\Controllers\V2\UserAuthController;
use Illuminate\Support\Facades\Route;



Route::get('users',[UserAuthController::class,'users']);




