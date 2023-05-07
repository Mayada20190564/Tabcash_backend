<?php

namespace App\Http\Controllers;

use App\Transaction;
use App\Wallet;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TransactionController extends Controller
{
    
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        // validate inputs 
        $fields = $request->validate([
            "destination_id" => "required|numeric|max:4|min:1",
            "amount" => "required|numeric|max:5|min:2"
        ]);
        // get sender wallet
        $source_id = auth()->user()->id();
        $sender_wallet = Wallet::where('user_id', $source_id)->first();
        // get reciever wallet
        $reciver_wallet = Wallet::where('user_id' ,$fields['destination_id'])->first();
        if($sender_wallet['balance'] >= $fields['amount']){
            // subtract amount from sender
            $sender_wallet['balance'] = $sender_wallet['balance'] - $fields['amount'];
            // Add amount to reciever
            $reciver_wallet['balance'] = $reciver_wallet['balance'] + $fields['amount'];
            // ///////////// Create Transaction
            return Transaction::create([
                "source_id" => $source_id ,
                "destination_id" => $fields['destination_id'] ,
                "amount" => $fields['amount']
            ]);
        }else{
            return [
                "message" => "Transaction Process Faild"
            ];
        }
        
        
    }

  
    public function show(Transaction $transaction)
    {
        //
    }

  
    public function edit(Transaction $transaction)
    {
        //
    }

  
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    public function destroy(Transaction $transaction)
    {
        //
    }
}
