<?php 
namespace App\Services;
use App\Models\Entry;
use App\Models\Payment;

class PaymentService 
{
    public function debit(Entry $entry)
    { 
        $lastPayment = Payment::latest()->first();
        $lastBalance = $lastPayment ? $lastPayment->balance : 0;
        $amount = ($entry->doc_type == 'channel') ? (int) env('CHANNEL_PAY') : (int) env('GENERAL_PAY');
        return Payment::create([
            'user_id'=> $entry->user_id,
            'entry_id'=> $entry->id,
            'amount'=> $amount,
            'payment_type'=> 'debit',
            'balance'=> ($lastBalance - $amount)
        ]);
    }
    public function credit(Entry $entry)
    { 
        $lastPayment = Payment::latest()->first();
        $lastBalance = $lastPayment ? $lastPayment->balance : 0;
        $amount = ($entry->doc_type == 'channel') ? (int) env('CHANNEL_PAY') : (int) env('GENERAL_PAY');
        return Payment::create([
            'user_id'=> $entry->user_id,
            'entry_id'=> $entry->id,
            'amount'=> $amount,
            'payment_type'=> 'credit',
            'balance'=> ($lastBalance + $amount)
        ]);
    }
}



?>