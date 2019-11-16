<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('page')) {
            return Wallet::collection(User::paginate(5));
        } else {
            return Wallet::collection(Wallet::all());
        }
    }
}
