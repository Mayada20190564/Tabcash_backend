<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Http\Controlers\authController;



// ///////////////   Auth Routes
// Register
Route::post('register' , "authController@register");
// Login
Route::post('login' , "authController@login");
Route::post('otp' , "authController@otp")->name('otp');
// Protected Routes
Route::group(['middleware' => ["auth:sanctum"]] , function(){
    Route::post('transaction' , "TransactionController@store")->name('transaction');
    Route::post('logout' , "authController@logout")->name('logout');
    
});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});