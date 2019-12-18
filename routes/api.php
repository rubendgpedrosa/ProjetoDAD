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
Route::get('users', 'UserControllerAPI@index');//->middleware('auth::api')
Route::get('users/{id}', 'UserControllerAPI@show');
Route::post('users', 'UserControllerAPI@store');
Route::put('users/{id}', 'UserControllerAPI@update');
Route::put('users/{id}', 'UserControllerAPI@uploadImage');
Route::delete('users/{id}', 'UserControllerAPI@destroy');

//Movement
Route::get('movements', 'MovementsControllerAPI@index');
Route::get('movements/{id}', 'MovementsControllerAPI@show');
Route::post('movements', 'MovementsControllerAPI@registerExpense');
Route::put('movements', 'MovementsControllerAPI@update');

//Wallet
Route::get('wallets', 'WalletControllerAPI@index');
Route::get('walletsEmail', 'WalletControllerAPI@walletsEmail');
Route::post('wallets/create', 'WalletControllerAPI@store');
Route::get('wallets/{id}', 'WalletControllerAPI@show');
Route::put('wallets', 'WalletControllerAPI@registerIncome');
