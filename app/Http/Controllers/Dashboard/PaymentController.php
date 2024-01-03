<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use App\Models\Entry;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

            $data['total_paid'] = Payment::whereHas('entry', function ($q) use ($request) {
                $q->where('date', '>=', $request->query('date_from'));
                $q->where('date', '<=', $request->query('date_to'));
            })->where(function ($q) use ($request) {
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
                'payment_type'=> 'debit',
                ])->sum('amount');

            $data['channel_payment'] = Payment::whereHas('entry', function ($q) use ($request) {
                    $q->where('doc_type', 'channel');
                })->where([
                'user_id'=> $request->query('user_id'),
                'payment_type'=> 'debit',
                ])->sum('amount');
        }
        $data = (object)[
            ...$data
        ];
        $clients = User::where('is_admin', 0)->get(['id', 'name', 'username']);
        return view('dashboard.payment.index', compact('clients', 'data'));
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
        //
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
