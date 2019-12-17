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
        /*As a platform user I want to be able to register an expense (debit) movement of my
          virtual wallet. The movement requires the type of movement (payment to external entity
          or transfer); the value (from 0,01€ up to 5000,00€); the category of expense and a
          description.
          If the type of movement is a payment to an external entity, the registration must also
          include the type of payment (bank transfer or MB payment). When the payment uses a
          bank transfer the registration also requires the IBAN (2 capital letters followed by 23
          digits). For MB payments the registration also requires an MB entity code (5 digits) and
          the MB payment reference (9 digits).
          If the type of movement is a transfer, the registration must also include the e-mail of the
          destination wallet and a source description – application must guarantee that the
          destination e-mail is associated to a valid virtual wallet from another user.*/
        $movement = new Movement;
        $walletToDeposit = Wallet::where('email', $request->email)->first();
        $walletToRetract = Wallet::where('id', $request->id)->first();
        $movement->wallet_id = $walletToDeposit->id;
        $movement->category_id = $request->category_id;
        $movement->description = $request->description;
        $movement->iban = $request->iban;
        $movement->type_payment = $request->type_payment;
        $movement->mb_entity_code = $request->mb_entity_code;
        $movement->mb_payment_reference = $request->mb_payment_reference;
        $movement->source_description = $request->source_description;
        $movement->type = $request->type;
        $movement->transfer = ($request->email == null? 0:1);
        $movement->date = new \DateTime();
        $movement->start_balance = $walletToDeposit->balance;
        $movement->end_balance = $walletToDeposit->balance + $request->balance;
        $walletToRetract->balance = $walletToRetract->balance - $request->value;
        $walletToDeposit->balance += $request->value;
        $walletToRetract->save();
        $walletToDeposit->save();
        $movement->value = $request->value;
        $movement->save();
        return $walletToDeposit;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Movement  $movement
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(MovementResource::collection(Movement::where('wallet_id',$id)->paginate(10)));
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
