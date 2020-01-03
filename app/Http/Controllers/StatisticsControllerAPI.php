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
        $sumWallets = 0;
        $averagePerWallet = 0;
        $countWallets = (Wallet::all())->count();
        $wallets = Wallet::all();
        foreach($wallets as $wallet){
            $sumWallets = $sumWallets + $wallet->balance;
        }

        //sanitize the division between these two numbers to only have 2 decimal cases.
        $averagePerWallet = number_format((float)($sumWallets/$countWallets), 2, '.', '');

        $highestTransferValue = 0;
        $cTransfer = 0;
        $btTransfer = 0;
        $mbTransfer = 0;

        $countIncomeTransfer = 0;
        $countExpenseTransfer = 0;
        $movements = Movement::all();

        $countMovements = 0;

        foreach($movements as $movement){

            unset($movement->id);
            unset($movement->wallet_id);
            unset($movement->transfer_wallet_id);
            unset($movement->iban);
            unset($movement->mb_entity_code);
            unset($movement->mb_payment_reference);
            unset($movement->description);
            unset($movement->source_description);

            if($movement->value>$highestTransferValue){
                $highestTransferValue=$movement->value;
            }
                $countMovements = $countMovements+1;
            switch($movement->type){
            case 'e':
                $countExpenseTransfer = $countExpenseTransfer+1;
            break;
            case 'i':
                $countIncomeTransfer = $countIncomeTransfer+1;
            break;
            }

            switch($movement->type_payment){
                case 'c':
                    $cTransfer = $cTransfer+1;
                break;
                case 'bt':
                    $btTransfer = $btTransfer+1;
                break;
                case 'mb':
                    $mbTransfer = $mbTransfer+1;
                break;
            }
        }

        return response()->json([
            'sumWallets' => $sumWallets,
            'averagePerWallet' => $averagePerWallet,
            'highestTransferValue'=>$highestTransferValue,
            'countMovements'=>$countMovements,
            'cTransfer'=>$cTransfer,
            'btTransfer'=>$btTransfer,
            'mbTransfer'=>$mbTransfer,
            'countIncomeTransfer'=>$countIncomeTransfer,
            'countExpenseTransfer'=>$countExpenseTransfer,
            'movements'=>$movements
        ]);
    }


    public function userStatistics($id){
        $user = \App\User::where('id', $id)->first();
        $wallet = \App\Wallet::where('id', $id)->first();
        $movements = \App\Movement::where('wallet_id', $id)->get();
        $wallets = \App\Wallet::all();

        $highestHeldAmount = 0;
        $highestTransactionAmount = 0;
        $numberWalletsInteractedWith = 0;
        $walletsInteractedWith = array();
        $walletMostInteractedWithArray = array();
        $numberOfIncomesDeposits = 0;
        $cTransfer = 0;
        $btTransfer = 0;
        $mbTransfer= 0;
        $numberOfExpenses = 0;
        $numberOfIncomes = 0;
        $i = 0;
        foreach($movements as $movement){
            if($movement->end_balance > $highestHeldAmount){
                $highestHeldAmount = $movement->end_balance;
            }
            if($movement->value > $highestTransactionAmount){
                $highestTransactionAmount = $movement->value;
            }
            if($movement->transfer_wallet_id != null){
                if(!in_array($movement->transfer_wallet_id, $walletsInteractedWith)){
                    $numberWalletsInteractedWith++;
                    $walletsInteractedWith[$i] = $movement->transfer_wallet_id;
                    $walletMostInteractedWithArray[$movement->transfer_wallet_id] = 1;
                    $i++;
                }else{
                    $walletMostInteractedWithArray[$movement->transfer_wallet_id]++;
                }
            }else{
                switch($movement->type_payment){
                    case 'c':
                        $cTransfer = $cTransfer+1;
                    break;
                    case 'bt':
                        $btTransfer = $btTransfer+1;
                    break;
                    case 'mb':
                        $mbTransfer = $mbTransfer+1;
                    break;
                }
            }

            if($movement->type == 'i'){
                $numberOfIncomes++;
            }else{
                $numberOfExpenses++;
            }
        }

        $walletMostInteractedWith = 0;
        foreach($wallets as $walletSearch){
            if(array_key_exists($walletSearch->id , $walletMostInteractedWithArray)){
                if($walletMostInteractedWithArray[$walletSearch->id] > $walletMostInteractedWith){
                    $walletMostInteractedWith = $walletSearch->id;
                }
            }
        }

        $walletMostInteractedWith = \App\Wallet::where('id', $walletMostInteractedWith)->first();
        unset($walletMostInteractedWith->balance);
        unset($walletMostInteractedWith->created_at);
        unset($walletMostInteractedWith->updated_at);
        unset($walletMostInteractedWith->id);

        $numberOfTransactions = Movement::where('wallet_id', $id)->count();

        return response()->json([
            'highestHeldAmount' => $highestHeldAmount,
            'numberOfTransactions' => $numberOfTransactions,
            'highestTransactionAmount' => $highestTransactionAmount,
            'numberWalletsInteractedWith' => $numberWalletsInteractedWith,
            'numberOfIncomes' => $numberOfIncomes,
            'numberOfExpenses' => $numberOfExpenses,
            'mbTransfer' => $mbTransfer,
            'btTransfer' => $btTransfer,
            'cTransfer' => $cTransfer,
            'walletMostInteractedWith' => $walletMostInteractedWith,
        ]);
    }
}
