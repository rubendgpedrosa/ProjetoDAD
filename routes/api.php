<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', 'LoginControllerAPI@login')->name('login');
Route::middleware('auth:api')->post('logout', 'LoginControllerAPI@logout');

Route::middleware('auth:api')->get('teste', function () {return response()->json(['msg'=>'Só um teste'], 200);});

//Category
Route::get('categories', 'CategoryControllerAPI@index');
Route::get('categories/{id}', 'CategoryControllerAPI@show');
Route::post('categories', 'CategoryControllerAPI@store');
Route::put('categories', 'CategoryControllerAPI@update');
Route::delete('categories', 'CategoryControllerAPI@destroy');
//Users
Route::get('users', 'UserControllerAPI@index');
Route::get('users/{id}', 'UserControllerAPI@show');
Route::post('users', 'UserControllerAPI@store');
Route::put('users/{id}', 'UserControllerAPI@update');
Route::delete('users/{id}', 'UserControllerAPI@destroy');

//Movement
Route::get('movements', 'MovementsControllerAPI@index');
Route::get('movements/{id}', 'MovementsControllerAPI@show');
//Route::post('categories', 'CategoryControllerAPI@store');
//Route::put('categories', 'CategoryControllerAPI@update');
//Route::delete('categories', 'CategoryControllerAPI@destroy');

//Wallet
Route::post('wallet/create', 'WalletControllerAPI@store');
Route::get('wallet/{id}', 'WalletControllerAPI@show');
Route::put('wallet/registerIncome', 'WalletControllerAPI@registerIncome');

