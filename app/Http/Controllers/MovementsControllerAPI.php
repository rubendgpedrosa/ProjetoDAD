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
        $request->validate([
            'id' => 'integer|required',
            'type' => 'required|in:0,1',
            'value' => 'required|between:0.01,5000',
            'category_id' => 'required|integer',
            'email' => 'email|required_if:type,==,1'
        ]);
        $walletToRetract = Wallet::where('id', $request->id)->first();
        $movement->wallet_id = $walletToRetract->id;
        $movement->type = $request->type;
        $movement->category_id = $request->category_id;
        $movement->description = $request->description;
        $movement->type = 'e';
        $movement->date = new \DateTime();
        $movement->start_balance = $walletToRetract->balance;
        $walletToRetract->balance = $walletToRetract->balance - $request->value;
        $walletToRetract->save();
        $movement->end_balance = $walletToRetract->balance;
        $movement->value = $request->value;

        if($request->type == 1){
            $movement_mirrored = new Movement;
            $walletToDeposit = Wallet::where('email', $request->email)->first();
            if($walletToDeposit == null){
                return abort(403);
            }
            $movement->transfer = 1;
            $movement->transfer_wallet_id = $walletToDeposit->id;
            $movement_mirrored->transfer = 1;
            //TODO bug when submitting multiple expenses
            $movement_mirrored->wallet_id = $walletToDeposit->id;
            //Income inserted in the wallet to receive the deposit
            $movement_mirrored->type = 'i';
            $movement_mirrored->category_id = $request->category_id;
            $movement_mirrored->description = $request->description;
            $movement_mirrored->source_description = $request->source_description;
            $movement_mirrored->date = new \DateTime();
            $movement_mirrored->transfer_wallet_id = $walletToRetract->id;
            $movement->source_description = $request->source_description;
            $movement_mirrored->source_description = $request->source_description;
            $movement_mirrored->start_balance = $walletToDeposit->balance;
            $movement_mirrored->end_balance = $walletToDeposit->balance + $request->value;
            $walletToDeposit->balance += $request->value;
            $movement_mirrored->value = $request->value;
            $walletToDeposit->save();
            $movement_mirrored->save();
            $movement->transfer_movement_id = $movement_mirrored->id;
            $movement->save();
            $movement_mirrored->transfer_movement_id = $movement->id;
            $movement_mirrored->save();
        }else{
            $movement->transfer = 0;
            $movement->type_payment = $request->type_payment;
            if($request->type_payment == 'bt'){
                $movement->iban = $request->iban;
            }else{
                $movement->mb_entity_code = $request->mb_entity_code;
                $movement->mb_payment_reference = $request->mb_payment_reference;
            }
        }
        $walletToRetract->save();
        $movement->save();

        return $movement;
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
