<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller {
    public function index() {
        $clients = User::whereNot('id', auth()->id())->get();
        return view( 'dashboard.client.index', compact( 'clients' ) );
    }


    public function edit(User $client) {
        return view( 'dashboard.client.partials.edit', [ 'client' => $client ] );
    }

    public function store(Request $request) {
        $path     = 'uploads/avatar';
        $filename = Str::slug( uniqid( 'pass-port-entry' ) . time() ) . "." . $request->file( 'avatar' )->extension();
        $avatar   = $request->file( 'avatar' )->storeAs( $path, $filename, 'public' );
        $client   = User::create( [
            ...$request->except( '_token' ),
            'avatar' => $avatar,
        ] );
        notify()->success( 'Client created successfully!' );
        return back();
    }
    public function update(Request $request, User $client) {
        if ( $request->has( 'avatar' ) ) {
            $path     = 'uploads/avatar';
            $filename = Str::slug( uniqid( 'pass-port-entry' ) . time() ) . "." . $request->file( 'avatar' )->getClientOriginalExtension();
            $avatar   = $request->file( 'avatar' )->storeAs( $path, $filename, 'public' );
            $array    = explode( 'storage', $client->avatar );
            $path     = array_pop( $array );

            if ( Storage::disk( 'public' )->exists( $path ) ) {
                Storage::delete( 'public/' . $path );
            }
        } else {
            $avatar = $client->avatar;
        }
        $client = $client->update( [
            'name'           => $request->name,
            'username'       => $request->username,
            'police_station' => $request->police_station,
            'password'       => $request->password ? $request->password : $client->password,
            'avatar'         => $avatar,
        ] );
        notify()->success( 'Client updated successfully!' );
        return back();
    }


    public function destroy(User $client) {
        $array = explode( 'storage', $client->avatar );
        $path  = array_pop( $array );

        if ( Storage::disk( 'public' )->exists( $path ) ) {
            Storage::delete( 'public/' . $path );
        }
        $client->delete();
        notify()->success( 'Client deleted successfully' );
        return back();
    }
}
