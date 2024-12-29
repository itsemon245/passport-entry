<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Entry;
use App\Models\Payment;
use App\Models\User;
use App\Services\PaymentService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = [  ];
        if ($request->query('user_id') != null) {
            $data[ 'total_balance' ] = Payment::whereHas('entry', function ($q) use ($request) {
                $q->where('date', '>=', $request->query('date_from'));
                $q->where('date', '<=', $request->query('date_to'));
                $q->where('user_id', $request->query('user_id'));
            })->sum('amount');

            $data[ 'total_paid' ] = Payment::where(function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->query('date_from'));
                $q->whereDate('created_at', '<=', $request->query('date_to'));
                $q->where('payment_type', 'debit');
                $q->where('user_id', $request->query('user_id'));
            })->sum('amount');

            $data[ 'general_entry' ] = Entry::where(function ($q) use ($request) {
                $q->where('date', '>=', $request->query('date_from'));
                $q->where('date', '<=', $request->query('date_to'));
                $q->where('user_id', $request->query('user_id'));
                $q->where('doc_type', 'general');
            })->count();
            $data[ 'channel_entry' ] = Entry::where(function ($builder) use ($request) {
                $builder->where(function ($q) use($request) {
                    $q->where('date', '>=', $request->query('date_from'));
                    $q->where('date', '<=', $request->query('date_to'));
                    $q->where('user_id', $request->query('user_id'));
                    $q->where('doc_type', 'channel');
                });
                $builder->where(function ($q) {
                    $q->where('remarks','second_time');
                    $q->orWhereNull('remarks');
                });
            })->count();

            $data[ 'general_payment' ] = Payment::whereHas('entry', function ($q) use ($request) {
                $q->where('doc_type', 'general');
                $q->where('date', '>=', $request->query('date_from'));
                $q->where('date', '<=', $request->query('date_to'));
                $q->where('user_id', $request->query('user_id'));
            })->sum('amount');

            $data[ 'channel_payment' ] = Payment::whereHas('entry', function ($q) use ($request) {
                $q->where('doc_type', 'channel');
                $q->where('date', '>=', $request->query('date_from'));
                $q->where('date', '<=', $request->query('date_to'));
                $q->where('user_id', $request->query('user_id'));
            })->sum('amount');
            $data[ 'total_due' ] = $data[ 'channel_payment' ] + $data[ 'general_payment' ] - $data[ 'total_paid' ];

        }
        $data = (object) [
            ...$data,
         ];
        $payments = $this->getPaymentHistory($request);
        $clients  = User::where('is_admin', 0)->get([ 'id', 'name', 'username' ]);
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
            'user_id'   => 'required',
            'total_due' => 'numeric|required',
            'amount'    => 'required|numeric|lte:total_due',
            'date' => 'required|date',
         ]);
        if ($validator->fails()) {
            notify()->warning('Amount can\'t be greater than Total Due');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $validator->validate();
        $payment = new PaymentService();
        $payment->debit($request);
        notify()->success('Payment Successful!');
        return back();
    }

    protected function getPaymentHistory(Request $request)
    {
        if ($request->query('user_id')) {
            $payments = Payment::where(function (Builder $q) use ($request) {
                if ($request->query('payment_from')) {
                    $q->whereDate('created_at', '>=', $request->query('payment_from'));
                    $q->whereDate('created_at', '<=', $request->query('payment_to'));
                } else {
                    $q->whereDate('created_at', '>=', $request->query('date_from'));
                    $q->whereDate('created_at', '<=', $request->query('date_to'));
                }
                $q->where('user_id', $request->query('user_id'));
                $q->where('payment_type', 'debit');
            })
                ->latest()
                ->get();
        } else {
            $payments = null;
        }

        return $payments;
    }

    /**
     * Display the specified resource.
     */
    public function history(Request $request)
    {
        $paymentFrom = $request->has('payment_from') ? $request->payment_from : today()->subDays(today()->day - 1)->format('Y-m-d');
        $paymentTo   = $request->has('payment_to') ? $request->payment_to : today()->format('Y-m-d');
        $payments    = Payment::where(function (Builder $q) use ($paymentFrom, $paymentTo) {
            $q->whereDate('created_at', '>=', $paymentFrom);
            $q->whereDate('created_at', '<=', $paymentTo);
            $q->where('payment_type', 'debit');
        })->with('user')
            ->latest()->paginate();
        return view('dashboard.payment.partials.history', compact('payments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        $clients = User::where('is_admin', 0)->get([ 'id', 'name' ]);
        return view('dashboard.payment.partials.edit', compact('payment', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'amount'  => 'required|numeric',
         ]);
        if ($validator->fails()) {
            notify()->warning('Amount can\'t be greater than Total Due');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $validator->validate();
        $payment->update([
            'user_id'    => $request->user_id,
            'amount'     => $request->amount,
            'created_at' => $request->date,
         ]);
        notify()->success('Payment Updated Successfully!');
        return redirect(route('payment.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();
        notify()->success('Payment has been deleted!');
        return back();
    }
}
