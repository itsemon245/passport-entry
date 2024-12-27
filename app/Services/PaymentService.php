<?php
namespace App\Services;
use App\Models\Entry;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\PaymentHistory;

class PaymentService
{
    public function debit(Request $request)
    {
        $totalCredit = Payment::where(['user_id' => $request->user_id, 'payment_type' => 'credit'])->sum('amount');
        $totalDebit = Payment::where(['user_id' => $request->user_id, 'payment_type' => 'debit'])->sum('amount');
        $lastBalance = $totalCredit - $totalDebit;
        $amount = (int) $request->amount;
        $payment = Payment::create([
            'user_id'=> $request->user_id,
            'payment_method'=> $request->payment_method,
            'entry_id'=> null,
            'amount'=> $amount,
            'payment_type'=> 'debit',
            'date' => $request->date,
            // 'balance'=> ($lastBalance - $amount)
        ]);
        $this->history($payment);
        return $payment;
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
            // 'balance'=> ($lastBalance + $amount)
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

    public function history(Payment $payment){
        return $history = PaymentHistory::create([
            'user_id' => $payment->user_id,
            'payment_id' => $payment->id,
            'amount' => $payment->amount,
            'payment_method' => $payment->payment_method,
        ]);
    }
}



?>
