<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Wallet as WalletResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    public function show(){
        return WalletResource::all();
    }
}