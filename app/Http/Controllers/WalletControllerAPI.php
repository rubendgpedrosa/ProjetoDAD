<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Wallet;
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

    }

    public function store(User $user){
        $wallet = new Wallet();
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
