<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Entry;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $dateFrom = now()->subDays(now()->day-1)->format('Y-m-d');
        $dateTo = now()->format('Y-m-d');

        $data = [];
        $data['Received Doc This Month'] = Entry::where(function($q)use($dateFrom, $dateTo){
            $q->where('user_id', auth()->id());
            $q->where('date','>=', $dateFrom);
            $q->where('date','<=', $dateTo);
        })->count();
        $data['Received Money Today'] = Payment::whereHas('entry', function($q)use($dateFrom, $dateTo){
            $q->where('user_id', auth()->id());
            $q->where('date','=', today()->format('Y-m-d'));
        })->where('payment_type', 'debit')
        ->sum('amount');
        $data['Total Earned Money This Month'] = Payment::whereHas('entry', function($q)use($dateFrom, $dateTo){
            $q->where('user_id', auth()->id());
            $q->where('date','>=', $dateFrom);
            $q->where('date','<=', $dateTo);
        })->where('payment_type', 'credit')
        ->sum('amount');
        $data['Total Received Money This Month'] = Payment::whereHas('entry', function($q)use($dateFrom, $dateTo){
            $q->where('user_id', auth()->id());
            $q->where('date','>=', $dateFrom);
            $q->where('date','<=', $dateTo);
        })->where('payment_type', 'debit')
        ->sum('amount');
        $data['Total Due Money This Month'] = ($data['Total Earned Money This Month'] - $data['Total Received Money This Month']);

      
        return view('dashboard.index', compact('data'));
    }
}
