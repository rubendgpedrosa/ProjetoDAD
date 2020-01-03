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
Route::middleware('auth:api')->get('categories/{id}', 'CategoryControllerAPI@show');
//Route::post('categories', 'CategoryControllerAPI@store');
//Route::put('categories', 'CategoryControllerAPI@update');
//Route::delete('categories', 'CategoryControllerAPI@destroy');

//Users
Route::middleware('auth:api', 'CheckAdmin')->get('users', 'UserControllerAPI@index');
Route::middleware('auth:api')->get('/user', function (Request $request) {return $request->user();});
Route::post('registration', 'UserControllerAPI@storeNormalUser');
Route::middleware('auth:api', 'CheckAdmin')->post('users', 'UserControllerAPI@store');
Route::middleware('auth:api', 'CheckID')->put('users/{id}', 'UserControllerAPI@update');
Route::middleware('auth:api', 'CheckAdmin')->put('users/toggleActivity', 'UserControllerAPI@toggleActivity');
Route::middleware('auth:api', 'CheckAdmin')->delete('users/{id}', 'UserControllerAPI@destroy');

//Movement
Route::middleware('auth:api', 'CheckAdmin')->get('movements', 'MovementsControllerAPI@index');
Route::middleware('auth:api', 'CheckUser', 'CheckID')->get('movements/{id}', 'MovementsControllerAPI@show');
Route::middleware('auth:api', 'CheckUser', 'CheckID')->post('movements', 'MovementsControllerAPI@registerExpense');
Route::middleware('auth:api', 'CheckUser', 'CheckID')->put('movements', 'MovementsControllerAPI@update');

//Wallet
Route::get('wallets', 'WalletControllerAPI@index'); //This should not be protected by auth.
Route::middleware('auth:api')->get('walletsEmail', 'WalletControllerAPI@walletsEmail');
//Route::post('wallets/create', 'WalletControllerAPI@store');
Route::middleware('auth:api', 'CheckUser', 'CheckID')->get('wallet/{id}', 'WalletControllerAPI@show');
Route::middleware('auth:api', 'CheckOperator')->put('wallets', 'WalletControllerAPI@registerIncome');

//Statistics
Route::middleware('auth:api', 'CheckAdmin')->get('statistics', 'StatisticsControllerAPI@index');
Route::middleware('auth:api', 'CheckUser', 'CheckID')->get('userStatistics/{id}', 'StatisticsControllerAPI@userStatistics');
