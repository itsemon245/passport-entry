<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Entry;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // $dateFrom = now()->subDays(now()->day-1)->format('Y-m-d');
        // $dateTo = now()->format('Y-m-d');
        $month = empty($request->query('month')) ? now('Asia/Dhaka')->month : $request->query('month');
        $month = (int) $month;
        $monthName = Carbon::createFromDate(now()->year, $month, 1)->format('F');
        $data = $this->getData($month);
        return view('dashboard.index', compact('data', 'monthName'));
    }

    public function getData(int $month)
    {
        $monthName = Carbon::createFromDate(now()->year, $month, 1)->format('F');
        $data      = [  ];
        if (auth()->user()->role !== 'client') {
            $data[ "Received Channel Doc on $monthName" ] = Entry::where(function ($q) use ($month) {
                $q->whereMonth('date', $month);
                $q->where('doc_type', 'channel');
            })->count();
            $data[ "Received General Doc on $monthName" ] = Entry::where(function ($q) use ($month) {
                $q->whereMonth('date', $month);
                $q->where('doc_type', 'general');
            })->count();

            $data[ 'Received Money Today' ] = Payment::where(function ($q) use ($month) {
                $q->where('created_at', '>=', today('Asia/Dhaka')->format('Y-m-d'));
                $q->where('created_at', '<', today('Asia/Dhaka')->addDay()->format('Y-m-d'));
                $q->where('payment_type', 'debit');
            })
                ->sum('amount');
            $data[ "Total Received Money on $monthName" ] = Payment::where(function ($q) use ($month) {
                $q->whereMonth('created_at', $month);
                $q->where('payment_type', 'debit');
            })
                ->sum('amount');
            $totalBalance = Payment::whereHas('entry', function ($q) use ($month) {
                $q->whereMonth('date', $month);
            })->where('payment_type', 'credit')
                ->sum('amount');
            $data[ "Total Due Money on $monthName" ] = ($totalBalance - $data[ "Total Received Money on $monthName" ]);

        } else {
            $data[ "Received Channel Doc on $monthName" ] = Entry::where(function ($q) use ($month) {
                $q->whereMonth('date', $month);
                $q->where('doc_type', 'channel');
                $q->where('user_id', auth()->id());
            })->count();
            $data[ "Received General Doc on $monthName" ] = Entry::where(function ($q) use ($month) {
                $q->whereMonth('date', $month);
                $q->where('doc_type', 'general');
                $q->where('user_id', auth()->id());
            })->count();
            $data[ "Total Payable Channel Money on $monthName" ] = Payment::whereHas('entry',function (Builder $q) use ($month) {
                $q->whereMonth('date', $month);
                $q->where('doc_type', 'channel');
                $q->where('user_id', auth()->id());
            })->where(function (Builder $q){
                $q->where('payment_type', 'credit');
            })->sum('amount');
            $data[ "Total Payable General Money on $monthName" ] = Payment::whereHas('entry',function (Builder $q) use ($month) {
                $q->whereMonth('date', $month);
                $q->where('doc_type', 'general');
                $q->where('user_id', auth()->id());
            })->where(function (Builder $q){
                $q->where('payment_type', 'credit');
            })->sum('amount');
            $data["Total Payable Money on $monthName"] = $data[ "Total Payable General Money on $monthName" ] + $data[ "Total Payable Channel Money on $monthName" ];
            $data[ "Total Paid Money on $monthName" ] = Payment::where(function (Builder $q) use($month) {
                $q->where('user_id', auth()->id());
                $q->where('payment_type', 'debit');
                $q->whereMonth('created_at', $month);
            })->sum('amount');
            $data[ "Total Due Money on $monthName" ] = $data["Total Payable Money on $monthName"] -  $data[ "Total Paid Money on $monthName" ];
        }
        return $data;
    }
}
