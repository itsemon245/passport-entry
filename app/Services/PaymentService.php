<?php 
namespace App\Services;
use App\Models\Entry;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentService 
{
    public function debit(Request $request)
    {
        $totalCredit = Payment::where(['user_id' => $request->user_id, 'payment_type' => 'credit'])->sum('amount');
        $totalDebit = Payment::where(['user_id' => $request->user_id, 'payment_type' => 'debit'])->sum('amount');
        $lastBalance = $totalCredit - $totalDebit;
        $amount = (int) $request->amount;
        return Payment::create([
            'user_id'=> $request->user_id,
            'entry_id'=> null,
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

    // public function pay()
    // {
    //     return Payment::create([
    //         'user_id'=> $entry->user_id,
    //         'entry_id'=> $entry->id,
    //         'amount'=> $amount,
    //         'payment_type'=> 'debit',
    //         'balance'=> ($lastBalance - $amount)
    //     ]);
    // }
}



?>