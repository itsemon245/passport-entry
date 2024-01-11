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
        $data['Received Channel Doc This Month'] = Entry::where(function($q)use($dateFrom, $dateTo){
            $q->where('date','>=', $dateFrom);
            $q->where('date','<=', $dateTo);
            $q->where('doc_type','channel');
        })->count();
        $data['Received General Doc This Month'] = Entry::where(function($q)use($dateFrom, $dateTo){
            $q->where('date','>=', $dateFrom);
            $q->where('date','<=', $dateTo);
            $q->where('doc_type','general');
        })->count();
        
        $data['Received Money Today'] = Payment::where(function($q)use($dateFrom, $dateTo){
            $q->where('created_at', '>=', today('Asia/Dhaka')->format('Y-m-d'));
            $q->where('created_at', '<', today('Asia/Dhaka')->addDay()->format('Y-m-d'));
            $q->where('payment_type', 'debit');
        })
        ->sum('amount');
        $data['Total Received Money This Month'] = Payment::where(function($q)use($dateFrom, $dateTo){
            $q->where('created_at', '>=', $dateFrom);
            $q->where('created_at', '<=', $dateTo);
            $q->where('payment_type', 'debit');
        })
        ->sum('amount');
        $totalBalance = Payment::whereHas('entry', function($q)use($dateFrom, $dateTo){
            $q->where('date','>=', $dateFrom);
            $q->where('date','<=', $dateTo);
        })->where('payment_type', 'credit')
        ->sum('amount');
        $data['Total Due Money This Month'] = ($totalBalance - $data['Total Received Money This Month']);

      
        return view('dashboard.index', compact('data'));
    }
}
