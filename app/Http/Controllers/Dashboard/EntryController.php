<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Entry;
use App\Models\User;
use App\Services\PaymentService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $entries = Entry::with('user')
            ->where(function (Builder $q) use ($request) {
                if (!empty($request->query('query'))) {
                    $q->orWhere('application_id', $request->query('query'));
                    $q->orWhere('police_station', $request->query('query'));
                    $q->orWhereHas('user', fn($q2) => $q2->where('name', $request->query('query')));
                }
            })
            ->where(function (Builder $q) use ($request) {
                if (!empty($request->query('doc_type'))) {
                    $q->orWhere('doc_type', $request->query('doc_type'));
                }
                if (!empty($request->query('date_filter'))) {
                    $q->where('date', $request->query('date_filter'));
                }
            })
            ->latest()->paginate();
        $clients = User::where('is_admin', 0)->get([ 'id', 'name', 'username' ]);
        return view('dashboard.entry.index', compact('entries', 'clients'))->with('request', session()->get('request'));
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
        $payment   = new PaymentService;
        $applicationIdUniqueValidation = $request->remarks !== 'second_time';
        $validator = Validator::make($request->all(), [
            'number_of_docs' => 'required_unless:is_channel,true|numeric|nullable',
            'application_id' => 'required_if_accepted:is_channel|max:14|nullable'.($applicationIdUniqueValidation ? '|unique:entries,application_id' : ''),
            'user_id'        => 'required|exists:users,id',
            'police_station' => 'required',
            'date'           => 'date:Y-m-d',
         ]);
        if ($validator->fails()) {
            foreach ($validator->errors() as $key => $value) {
                notify()->warning($value);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $validator->validate();
        if ($request->is_channel == 'true') {
            $entry = Entry::create([
                'user_id'        => $request->user_id,
                'date'           => $request->date,
                'time'           => $request->time,
                'application_id' => $request->application_id,
                'police_station' => $request->police_station,
                'doc_type'       => 'channel',
                'remarks'        => $request->remarks,
             ]);
            if ($request->remarks != 'negative') {
                $payment->credit($entry);
            }

        } elseif ($request->number_of_docs != null) {
            $number = (int) $request->number_of_docs;
            for ($i = 0; $i < $number; $i++) {
                $entry = Entry::create([
                    'user_id'        => $request->user_id,
                    'date'           => $request->date,
                    'time'           => $request->time,
                    'police_station' => $request->police_station,
                    'doc_type'       => 'general',
                 ]);
                $payment->credit($entry);
            }
        }

        notify()->success('Entry submitted successfully!');
        return redirect()->route('entry.index')->with('request', $request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Entry $entry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Entry $entry)
    {
        $clients = User::get([ 'id', 'name', 'username' ]);
        return view('dashboard.entry.partials.edit', compact('entry', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Entry $entry)
    {
        $applicationIdUniqueValidation = $request->remarks !== 'second_time';
        $validator = Validator::make($request->all(), [
            'number_of_docs' => 'numeric|nullable',
            'application_id' => 'required_if_accepted:is_channel|nullable'.($applicationIdUniqueValidation ? '|unique:entries,application_id,' . $entry->id : ''),
            'user_id'        => 'required|exists:users,id',
            'police_station' => 'required',
            'date'           => 'date:Y-m-d',
         ]);
        // $request->dd();
        if ($validator->fails()) {
            dd($validator->errors());
            foreach ($validator->errors() as $key => $value) {
                notify()->warning($value);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $validator->validate();
        $entry = tap($entry)->update([
            'user_id'        => $request->user_id,
            'date'           => $request->date,
            'time'           => $request->time,
            'application_id' => $request->application_id,
            'police_station' => $request->police_station,
            'remarks'        => $request->remarks,
        ]);
        if($request->remarks == 'negative') {
            $entry->payment?->delete();
            notify()->success('Entry marked as negative & Removed from credit');
        }else{
            if(!$entry->payment){
                $payment = new PaymentService;
                $payment->credit($entry);
                notify()->success('Entry redded to credit');
            }
            notify()->success('Entry updated successfully!');
        }
        return redirect()->route('entry.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Entry $entry)
    {
        if ($entry) {
            $entry->delete();
        }
        notify()->success('Entry deleted successfully!');
        return back();
    }
}
