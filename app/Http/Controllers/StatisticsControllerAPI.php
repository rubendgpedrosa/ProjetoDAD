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

            $averagePerWallet = $sumWallets/$countWallets;

            $highestTransferValue = 0;
            $cTransfer = 0;
            $btTransfer = 0;
            $mbTransfer = 0;

            $countIncomes =0;
            $countExpenses =0;
            $movements = Movement::all();

            $countMovements = 0;

            foreach($movements as $movement){
                if($movement->value>$highestTransferValue){
                    $highestTransferValue=$movement->value;
                }
                    $countMovements = $countMovements+1;
                switch($movement->type){
                case 'e':
                    $countExpenses = $countExpenses+1;
                break;
                case 'i':
                    $countIncomes = $countIncomes+1;
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
                'countWallets'=>$countWallets,
                'highestTransferValue'=>$highestTransferValue,
                'countMovements'=>$countMovements,
                'cTransfer'=>$cTransfer,
                'btTransfer'=>$btTransfer,
                'mbTransfer'=>$mbTransfer,
            ]);
        }
}