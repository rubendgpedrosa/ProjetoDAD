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

Route::post('login', 'LoginControllerAPI@login')->name('login');
Route::middleware('auth:api')->post('logout','LoginControllerAPI@logout');

//Category
Route::middleware('auth:api')->get('categories', 'CategoryControllerAPI@index');
Route::get('categories/{id}', 'CategoryControllerAPI@show');
Route::post('categories', 'CategoryControllerAPI@store');
Route::put('categories', 'CategoryControllerAPI@update');
Route::delete('categories', 'CategoryControllerAPI@destroy');

//Users
Route::middleware('auth:api')->get('users', 'UserControllerAPI@index');
Route::middleware('auth:api')->get('/user', function (Request $request) {return $request->user();});
Route::post('users', 'UserControllerAPI@store');
Route::put('users/{id}', 'UserControllerAPI@update');
Route::put('users/toggleActivity', 'UserControllerAPI@toggleActivity');
Route::delete('users/{id}', 'UserControllerAPI@destroy');

//Movement
Route::middleware('auth:api')->get('movements', 'MovementsControllerAPI@index');
Route::middleware('auth:api')->get('movements/{id}', 'MovementsControllerAPI@show');
Route::post('movements', 'MovementsControllerAPI@registerExpense');
Route::put('movements', 'MovementsControllerAPI@update');

//Wallet
Route::get('wallets', 'WalletControllerAPI@index'); //This should not be protected by auth.
Route::middleware('auth:api')->get('walletsEmail', 'WalletControllerAPI@walletsEmail');
Route::post('wallets/create', 'WalletControllerAPI@store');
Route::middleware('auth:api')->get('wallet/{id}', 'WalletControllerAPI@show');
Route::put('wallets', 'WalletControllerAPI@registerIncome');
