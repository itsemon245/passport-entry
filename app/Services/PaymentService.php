<?php 
namespace App\Services;
use App\Models\Entry;
use App\Models\Payment;

class PaymentService 
{
    public function debit(Entry $entry)
    { 
        $totalCredit = Payment::where(['user_id' => $entry->user_id, 'payment_type' => 'credit'])->sum('amount');
        $totalDebit = Payment::where(['user_id' => $entry->user_id, 'payment_type' => 'debit'])->sum('amount');
        $lastBalance = $totalCredit - $totalDebit;
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
        $totalCredit = Payment::where(['user_id' => $entry->user_id, 'payment_type' => 'credit'])->sum('amount');
        $totalDebit = Payment::where(['user_id' => $entry->user_id, 'payment_type' => 'debit'])->sum('amount');
        $lastBalance = $totalCredit - $totalDebit;
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