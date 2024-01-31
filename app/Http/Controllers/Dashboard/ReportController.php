<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Browsershot\Browsershot;

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
        $time    = str(now())->slug() . "-" . now()->timestamp;
        // Browsershot::html(view('dashboard.report.pdf', compact('clients'))->render())
        //     ->setNodeBinary('/home/emon/.nvm/versions/node/v20.10.0/bin/node')
        //     ->setNpmBinary('/home/emon/.nvm/versions/node/v20.10.0/bin/npm')->savePdf(storage_path('app/public/temp/report.pdf'));
        // return Storage::download('public/temp/report.pdf', "report-$time.pdf");
        // Browsershot::html(view('dashboard.report.pdf', compact('clients'))->render())->newHeadless()->save('example.pdf');

        // return Pdf::view('dashboard.report.pdf', compact('clients'))
        //     ->format('a4')
        //     ->save("report.pdf");
        // return $pdf->download();
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
            $dateTo   = now()->format('Y-m-d');
            $dateFrom = now()->subDays(now()->day - 1)->format('Y-m-d');
        }
        $clients = User::whereHas('entries', function ($q) use ($dateFrom, $dateTo) {
            $q->where('date', '>=', $dateFrom);
            $q->where('date', '<=', $dateTo);
        })->with([ 'entries' => function ($q) use ($dateFrom, $dateTo) {
                $q->where('date', '>=', $dateFrom);
                $q->where('date', '<=', $dateTo);
               # $q->where('doc_type', '=', 'channel');
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
