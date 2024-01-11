<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use App\Models\Entry;
use Illuminate\Http\Request;
use App\Services\PaymentService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class EntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $entries = Entry::with('user')->latest()->get();
        $clients = User::where('is_admin', 0)->get(['id', 'name', 'username']);
        return view('dashboard.entry.index', compact('entries', 'clients'));
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
        $payment = new PaymentService;
        $validator = Validator::make($request->all(), [
            'number_of_docs'=> 'required_unless:is_channel,true|numeric|nullable',
            'application_id'=> 'required_if_accepted:is_channel|nullable|unique:entries,application_id',
            'user_id'=> 'required|exists:users,id',
            'police_station'=> 'required',
            'date' => 'date:Y-m-d',
        ]);
        if($validator->fails()){
            foreach ($validator->errors() as $key => $value) {
                notify()->warning($value);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $validator->validate();
        if ($request->is_channel == 'true') {
           $entry =  Entry::create([
                'user_id' => $request->user_id,
                'date'=> $request->date,
                'time'=> $request->time,
                'application_id'=> $request->application_id,
                'police_station'=> $request->police_station,
                'doc_type'=> 'channel',
            ]);
            $payment->credit($entry);
            
        }elseif($request->number_of_docs != null){
            $number = (int) $request->number_of_docs;
            for ($i=0; $i < $number; $i++) { 
                $entry = Entry::create([
                    'user_id' => $request->user_id,
                    'date'=> $request->date,
                    'time'=> $request->time,
                    'police_station'=> $request->police_station,
                    'doc_type'=> 'general',
                ]);
                $payment->credit($entry);
            }
        }

        notify()->success('Entry submitted successfully!');
        return redirect()->route('entry.index');
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
        $clients = User::get(['id', 'name', 'username']);
        return view('dashboard.entry.partials.edit', compact('entry', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Entry $entry)
    {
        $validator = Validator::make($request->all(), [
            'number_of_docs'=> 'numeric|nullable',
            'application_id'=> 'required_if_accepted:is_channel|nullable|unique:entries,application_id,'.$entry->id,
            'user_id'=> 'required|exists:users,id',
            'police_station'=> 'required',
            'date' => 'date:Y-m-d',
        ]);
        // $request->dd();
        if($validator->fails()){
            dd($validator->errors());
            foreach ($validator->errors() as $key => $value) {
                notify()->warning($value);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $validator->validate();
        $entry =  tap($entry)->update([
            'user_id' => $request->user_id,
            'date'=> $request->date,
            'time'=> $request->time,
            'application_id'=> $request->application_id,
            'police_station'=> $request->police_station,
            'doc_type'=> $request->is_channel == 'true' ? 'channel' : 'general',
        ]);
        notify()->success('Entry updated successfully!');
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
