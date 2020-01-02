<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Wallet;
use App\Movement;
use App\User;
use App\Http\Controllers\MovementsControllersAPI;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class WalletControllerAPI extends Controller
{
    public function index() {
        return response()->json((Wallet::all())->count());
    }

    public function destroy(Request $request){

    }

    public function walletsEmail(){
        $wallets = Wallet::all();
        foreach($wallets as $wallet){
            unset($wallet->balance);
            unset($wallet->created_at);
            unset($wallet->updated_at);
        }
        return $wallets;
    }

    public function edit(){
        //update wallet balance

    }

    //Receive deposit
    public function registerIncome(Request $request){
        //validate sent data
        $validatedData = $request->validate([
            'email_income' => 'required|email',
            'value_income' => 'required|min:0.01|max:5000',
            'type_payment_income' => 'required|in:bt,mb',
            'source_description_income' => 'required|String',
            'IBAN_income' => 'required_if:type_payment_income,==,bt'
        ]);
        $movement = new Movement();
        $movement = app('App\Http\Controllers\MovementsControllerAPI')->store($request);

        $wallet = Wallet::where('email', $request->email_income)->first();
        $wallet->balance += $validatedData['value_income'];
        $wallet->save();
        return $movement;
    }

    public function store(Request $request){
        $wallet = new Wallet();
        $user = User::where('email', $request->email)->first();
        $wallet->email = $user['email'];
        $wallet->id = $user['id'];
        $wallet->save();
    }

    public function show($id){
        //retrieve a specific wallet associated to a specific key, in this case, email is the key given.
        return Wallet::where('id', $id)->first();
    }
}
