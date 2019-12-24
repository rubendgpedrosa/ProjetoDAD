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
            $wallet->balance = null;
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
            'email' => 'required|email',
            'balance' => 'required|Integer|min:0.01|max:5000',
            //'type' => 'required|in:c,bt,mb',
            //'source_description' => 'required|String',
            'source' => 'in:cash,bank transfer'
            //'IBAN' => 'required|'
        ]);
        $wallet = Wallet::find($validatedData['email']);
        $movement = new Movement();
        $movement = app('App\Http\Controllers\MovementsControllerAPI')->store($request, $wallet);
        $wallet->balance += $validatedData['balance'];
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
