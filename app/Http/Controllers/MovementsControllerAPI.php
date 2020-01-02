<?php

namespace App\Http\Controllers;

use App\Movement;
use App\Wallet;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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
        $wallet = Wallet::where('email', $request->email_income)->first();
        $movement->wallet_id = $wallet->id;
        $movement->type_payment = $request->type_payment_income;
        $movement->iban = $request->IBAN_income;
        $movement->transfer = 0;
        $movement->source_description = $request->source_description_income;
        $movement->date = new \DateTime();
        $movement->start_balance = $wallet->balance;
        $movement->end_balance = $wallet->balance + $request->value_income;
        $movement->value = $request->value_income;
        $movement->save();
        return $movement;
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
            'source_email' => 'email|required',
            'type' => 'required|in:0,1',
            'value' => 'required|between:0.01,5000',
            'category_id' => 'required|integer',
            //Validation below isn't working properly for whatever reason so we validate if inside an if.
            //'email_to_transfer' => 'required_if:type,==,1'
        ]);
        $walletToRetract = Wallet::where('email', $request->source_email)->first();
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
            $request->validate([
                'email_to_transfer' => 'required|email',
            ]);
            $movement_mirrored = new Movement;
            $walletToDeposit = Wallet::where('email', $request->email_to_transfer)->first();
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
        $movements = Movement::where('wallet_id',$id)->get();
        foreach($movements as $movement){
            $user = \App\user::where('id', $movement->transfer_wallet_id)->first();
            if($user != null){
                $movement->photo = $user->photo;
            }
        }
        return $movements;
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
