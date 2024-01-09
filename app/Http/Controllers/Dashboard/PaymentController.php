<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use App\Models\Entry;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Services\PaymentService;
use App\Http\Controllers\Controller;
use App\Models\PaymentHistory;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = [];
        if ($request->query('user_id') != null) {
            $data['total_balance'] = Payment::whereHas('entry', function ($q) use ($request) {
                    $q->where('date', '>=', $request->query('date_from'));
                    $q->where('date', '<=', $request->query('date_to'));
                })->where(function ($q) use ($request) {
                $q->where('payment_type', 'credit');
                $q->where('user_id',$request->query('user_id'));
            })->sum('amount');

            $data['total_paid'] = Payment::where(function ($q) use ($request) {
                $q->where('date', '>=', $request->query('date_from'));
                $q->where('date', '<=', $request->query('date_to'));
                $q->where('payment_type', 'debit');
                $q->where('user_id',$request->query('user_id'));
            })->sum('amount');
            $data['total_due'] = $data['total_balance'] - $data['total_paid'];

            $data['general_entry'] = Entry::where([
                'user_id'=> $request->query('user_id'),
                'doc_type' => 'general'
                ])->count();
            $data['channel_entry'] = Entry::where([
                'user_id'=> $request->query('user_id'),
                'doc_type' => 'channel'
                ])->count();

            $data['general_payment'] = Payment::whereHas('entry', function ($q) use ($request) {
                $q->where('doc_type', 'general');
            })->where([
                'user_id'=> $request->query('user_id'),
                'payment_type'=> 'credit',
                ])->sum('amount');

            $data['channel_payment'] = Payment::whereHas('entry', function ($q) use ($request) {
                    $q->where('doc_type', 'channel');
                })->where([
                'user_id'=> $request->query('user_id'),
                'payment_type'=> 'credit',
                ])->sum('amount');
        }
        $data = (object)[
            ...$data
        ];
        $payments = $this->getPaymentHistory($request);
        $clients = User::where('is_admin', 0)->get(['id', 'name', 'username']);
        return view('dashboard.payment.index', compact('clients', 'data', 'payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'=> 'required',
            'total_due'=> 'numeric|required',
            'amount'=> 'required|numeric|lte:total_due'
        ]);
        if($validator->fails()){
            notify()->warning('Amount can\'t be greater than Total Due');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $validator->validate();
        $payment = new PaymentService();
        $payment->debit($request);
        notify()->success('Payment Successful!');
        return back();
    }


    protected function getPaymentHistory(Request $request) {
        if ($request->query('user_id')) {
            $payments = PaymentHistory::where(function($q)use($request){
                if ($request->query('payment_from')) {
                    $q->where('date','>=' ,$request->query('payment_from'));
                    $q->where('date','<=' ,$request->query('payment_to')); 
                }else{
                    $q->where('date','>=' ,$request->query('date_from'));
                    $q->where('date','<=' ,$request->query('date_to'));
                }
                    $q->where('user_id', $request->query('user_id'));
            })->get();
        }else{
            $payments = null;
        }

        return $payments;
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
