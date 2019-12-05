<?php

namespace App\Http\Controllers;

use App\Movement;
use App\Wallet;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Movement as MovementResource;

class MovementsControllerAPI extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return MovementResource::collection(Movement::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Wallet $wallet)
    {
        //'wallet_id','type','transfer','transfer_movement_id','type_payment','category_id',
        //'iban','mb_entity_code','mb_payment_reference','descrition','source_description','date','start_balance','end_balance','value'
        $movement = new Movement;
        $movement->wallet_id = $wallet->id;
        $movement->type = $request->type;
        $movement->transfer = $request->transfer;
        $movement->date = new \DateTime();
        $movement->start_balance = $wallet->balance;
        $movement->end_balance = $wallet->balance + $request->balance;
        $movement->value = $request->balance;
        $movement->save();
        return $movement;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Movement  $movement
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return MovementResource::collection(Movement::where('wallet_id',$id)->paginate(10));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Movement  $movement
     * @return \Illuminate\Http\Response
     */
    public function edit(Movement $movement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Movement  $movement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Movement $movement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Movement  $movement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Movement $movement)
    {
        //
    }
}
