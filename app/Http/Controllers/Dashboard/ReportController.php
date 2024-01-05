<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use App\Models\Entry;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $clients = $this->getClients($request);
        // dd($clients);
        return view('dashboard.report.index', compact('clients'));
    }

    public function print(Request $request)
    {
        $clients = $this->getClients($request);
        $pdf = Pdf::loadView('dashboard.report.pdf', compact('clients'));
        return $pdf->stream();
    }

    protected function getClients(Request $request) {
        if ($request->has('date_from')) {
            $dateFrom = $request->date_from;
            $dateTo = $request->date_to;
        }else{
            $dateTo = now()->format('Y-m-d');
            $dateFrom = now()->subDays(now()->day-1)->format('Y-m-d');
        }
        $clients = User::whereHas('entries')
        ->with(['entries' => function($q)use($dateFrom, $dateTo){
            $q->where('date', '>=', $dateFrom);
            $q->where('date', '<=', $dateTo);
            $q->where('doc_type', '=', 'channel');
        }])
        ->withCount(['entries as channel_count' => function($q)use($dateFrom, $dateTo){
            $q->where('date', '>=', $dateFrom);
            $q->where('date', '<=', $dateTo);
            $q->where('doc_type', '=', 'channel');
        }])
        ->withCount(['entries as general_count' => function($q)use($dateFrom, $dateTo){
            $q->where('date', '>=', $dateFrom);
            $q->where('date', '<=', $dateTo);
            $q->where('doc_type', '=', 'general');
        }])
        ->get();

        return $clients;
    }
}
