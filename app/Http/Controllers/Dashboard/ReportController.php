<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $clients = $this->getClients($request);
        // dd($clients);
        return view('dashboard.report.index', compact('clients'));
    }
    public function clientWise(Request $request)
    {
        if (auth()->user()->is_admin) {
            $user = User::find($request->user_id) ?? User::where('is_admin', 0)->first();
        } else {
            $user = User::find(auth()->id());
        }
        $dateFrom = $request->date_from ?? today('Asia/Dhaka')->subDays(today()->day - 1)->format('Y-m-d');
        $dateTo   = $request->date_to ?? today('Asia/Dhaka')->format('Y-m-d');
        $clients  = User::where('is_admin', 0)->get();
        $entries  = $user->entries()
            ->where(function (Builder $q) use ($dateFrom, $dateTo) {
                $q->where('date', '>=', $dateFrom);
                $q->where('date', '<=', $dateTo);
            })
            ->orderBy('date', 'desc')->get()->groupBy([
            'date',
            function ($item) {
                return $item[ 'doc_type' ];
            },
         ], $preserveKeys = false);
        return view('dashboard.report.clientwise', compact('clients', 'entries', 'user', 'dateFrom', 'dateTo'));
    }

    public function print(Request $request)
    {
        $clients = $this->getClients($request);
        return view('dashboard.report.pdf', compact('clients'));
    }

    public function downloadCsv(Request $request)
    {
        $clients = $this->getClients($request);
        $clients->toCsv();
    }

    protected function getClients(Request $request)
    {
        if ($request->has('date_from')) {
            $dateFrom = $request->date_from;
            $dateTo   = $request->date_to;
        } else {
            $dateTo   = now('Asia/Dhaka')->format('Y-m-d');
            $dateFrom = now('Asia/Dhaka')->subDays(now()->day - 1)->format('Y-m-d');
        }
        $clients = User::whereHas('entries', function ($q) use ($dateFrom, $dateTo) {
            $q->where('date', '>=', $dateFrom);
            $q->where('date', '<=', $dateTo);
        })->with([ 'entries' => function ($q) use ($dateFrom, $dateTo) {
            $q->where('date', '>=', $dateFrom);
            $q->where('date', '<=', $dateTo);
            $q->where('doc_type', '=', 'channel');
        } ])
            ->withCount([ 'entries as rowspan' => function ($q) use ($dateFrom, $dateTo) {
                $q->where('date', '>=', $dateFrom);
                $q->where('date', '<=', $dateTo);
                $q->where('doc_type', '=', 'channel');
            } ])
            ->withCount([ 'entries as channel_count' => function ($q) use ($dateFrom, $dateTo) {
                $q->where('date', '>=', $dateFrom);
                $q->where('date', '<=', $dateTo);
                $q->where('doc_type', '=', 'channel');
            } ])
            ->withCount([ 'entries as general_count' => function ($q) use ($dateFrom, $dateTo) {
                $q->where('date', '>=', $dateFrom);
                $q->where('date', '<=', $dateTo);
                $q->where('doc_type', '=', 'general');
            } ])
            ->get();

        return $clients;
    }
}
