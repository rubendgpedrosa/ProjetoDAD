<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Wallet;
use App\Movement;
use App\Http\Controllers\MovementsControllersAPI;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class WalletControllerAPI extends Controller
{
    public function index() {
        return Wallet::all();
    }

    public function destroy(Request $request){

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

    public function store(User $user){
        $wallet = new Wallet();
        $validatedData = $request->validate([
            'email' => 'required|email',
            'balance' => 'required|Integer|min:0.01|max:5000',
            'type' => 'required|in:c,bt,mb',
            'source' => 'in:cash,bank transfer'
        ]);
        $wallet ->fill($request->all());
        //$wallet.balance = 0;
        $wallet->save();
        return response()->json(new Wallet($wallet), 201);
    }

    public function show($id){
        //retrieve a specific wallet associated to a specific key, in this case, email is the key given.
        return Wallet::where('id', $id)->first();
    }
}
