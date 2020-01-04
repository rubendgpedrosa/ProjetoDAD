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
        $countWallets = 0;
        $averagePerWallet = 0;
        $highestTransferValue = 0;
        $countMovements = 0;
        $countExpenseTransfer = 0;
        $countIncomeTransfer = 0;
        $cTransfer = 0;
        $btTransfer = 0;
        $mbTransfer = 0;

        $sumWallets = number_format((float)(Wallet::all()->sum('balance')), 2, '.', '');
        $countWallets = Wallet::all()->count();
        $averagePerWallet = number_format((float)(Wallet::all()->avg('balance')), 2, '.', '');
        $highestTransferValue = number_format((float)(Movement::all()->max('value')), 2, '.', '');
        $countMovements = Movement::all()->count();
        $countExpenseTransfer = Movement::where('type', 'e')->count();
        $countIncomeTransfer = Movement::where('type', 'i')->count();
        $cTransfer = Movement::where('type_payment', 'c')->count();
        $btTransfer = Movement::where('type_payment', 'bt')->count();
        $mbTransfer = Movement::where('type_payment', 'mb')->count();

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
        ]);
    }


    public function userStatistics($id){
        $highestHeldAmount = 0;
        $numberOfTransactions = 0;
        $highestTransactionAmount = 0;
        $numberWalletsInteractedWith = 0;
        $walletMostInteractedWith = 'None';
        $numberOfIncomes = 0;
        $numberOfExpenses = 0;
        $cTransfer = 0;
        $btTransfer = 0;
        $mbTransfer = 0;

        $highestHeldAmount = number_format((float)(\App\Wallet::where('id', $id)->max('balance')), 2, '.', '');
        $numberOfTransactions = Movement::where('wallet_id', $id)->count();
        $highestTransactionAmount = number_format((float)(\App\Movement::where('wallet_id', $id)->max('value')), 2, '.', '');
        $numberWalletsInteractedWith = \App\Movement::where('wallet_id', $id)->distinct('transfer_wallet_id')->count('transfer_wallet_id');
        $walletMostInteractedWith = (\App\Movement::select('transfer_wallet_id')->where('wallet_id', $id)->whereNotNull('transfer_wallet_id')
                                              ->groupBy('transfer_wallet_id')->orderByRaw('COUNT(*) DESC')->take(1)->first());
        if($walletMostInteractedWith){
            $walletMostInteractedWith = $walletMostInteractedWith->transfer_wallet_id;
        }
        $numberOfIncomes = \App\Movement::where('wallet_id', $id)->where('type', 'i')->count();
        $numberOfExpenses = \App\Movement::where('wallet_id', $id)->where('type', 'e')->count();
        $cTransfer = \App\Movement::where('wallet_id', $id)->where('type_payment', 'c')->count();
        $btTransfer = \App\Movement::where('wallet_id', $id)->where('type_payment', 'bt')->count();
        $mbTransfer = \App\Movement::where('wallet_id', $id)->where('type_payment', 'mb')->count();

        return response()->json([
            'highestHeldAmount' => $highestHeldAmount,
            'numberOfTransactions' => $numberOfTransactions,
            'highestTransactionAmount' => $highestTransactionAmount,
            'numberWalletsInteractedWith' => $numberWalletsInteractedWith,
            'walletMostInteractedWith' => \App\User::where('id', $walletMostInteractedWith)->value('email'),
            'numberOfIncomes' => $numberOfIncomes,
            'numberOfExpenses' => $numberOfExpenses,
            'cTransfer' => $cTransfer,
            'btTransfer' => $btTransfer,
            'mbTransfer' => $mbTransfer,
        ]);
    }
}
