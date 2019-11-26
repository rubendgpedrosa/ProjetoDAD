<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class VueController extends Controller
{
    public function index(){
        return view('vue.index');
    }
}
