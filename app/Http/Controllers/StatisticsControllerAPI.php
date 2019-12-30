<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Wallet;
use App\Movement;

class StatisticsControllerAPI extends Controller
{
    /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function index()
        {
            return response()->json(Wallet::all());
        }
}
