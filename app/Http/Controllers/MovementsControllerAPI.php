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

    public function store(Request $request)
    {
        $movement = new Movement;
        $movement->wallet_id = $wallet->id;
        $movement->type = $request->type;
        $movement->transfer = $request->transfer;
        $movement->transfer_movement_id = $request->transfer_movement_id;
        $movement->type_payment = $request->type_payment;
        $movement->category_id = $request->category_id;
        $movement->iban = $request->iban;
        $movement->mb_entity_code = $request->mb_entity_code;
        $movement->mb_payment_reference = $request->mb_payment_reference;
        $movement->description = $request->description;
        $movement->source_description = $request->source_description;
        $movement->date = new \DateTime();
        $movement->start_balance = $wallet->balance;
        $movement->end_balance = $wallet->balance + $request->balance;
        $movement->value = $request->balance;
        return response()->json($movement->save());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function registerExpense(Request $request)
    {
        $movement = new Movement;
        $movement_mirrored = new Movement;
        $walletToDeposit = Wallet::where('email', $request->email)->first();
        $walletToRetract = Wallet::where('id', $request->id)->first();

        $movement->type = $request->type;
        $movement->category_id = $request->category_id;
        $movement->wallet_id = $walletToDeposit->id;
        $movement->description = $request->description;
        $movement_mirrored->type = $request->type;
        $movement_mirrored->category_id = $request->category_id;
        $movement_mirrored->wallet_id = $walletToRetract->id;
        $movement_mirrored->description = $request->description;

        $movement->iban = $request->iban;
        $movement->type_payment = $request->type_payment;
        $movement->mb_entity_code = $request->mb_entity_code;
        $movement->transfer = ($request->email == null? 0:1);
        $movement->mb_payment_reference = $request->mb_payment_reference;
        $movement->source_description = $request->source_description;
        $movement->date = new \DateTime();
        $movement->start_balance = $walletToDeposit->balance;
        $movement->end_balance = $walletToDeposit->balance + $request->value;
        $movement_mirrored->iban = $request->iban;
        $movement_mirrored->type_payment = $request->type_payment;
        $movement_mirrored->mb_entity_code = $request->mb_entity_code;
        $movement_mirrored->transfer = ($request->email == null? 0:1);
        $movement_mirrored->mb_payment_reference = $request->mb_payment_reference;
        $movement_mirrored->source_description = $request->source_description;
        $movement_mirrored->date = new \DateTime();
        $movement_mirrored->start_balance = $walletToRetract->balance;
        $movement_mirrored->end_balance = $walletToRetract->balance-$request->value;

        $walletToRetract->balance = $walletToRetract->balance - $request->value;
        $walletToDeposit->balance += $request->value;
        $walletToRetract->save();
        $walletToDeposit->save();

        $movement->value = $request->value;
        $movement->save();
        $movement_mirrored->value = $request->value;
        $movement_mirrored->save();
        return array($movement,$movement_mirrored);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Movement  $movement
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(MovementResource::collection(Movement::where('wallet_id',$id)->get()));
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
    public function update(Request $request)
    {
        $movement = Movement::where('id', $request->id)->first();
        $movement->description = $request['description'];
        $movement->category_id = $request['category_id'];
        $movement->save();
        return $movement;
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
