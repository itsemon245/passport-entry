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
        $clients = User::whereNot('id', auth()->id())->paginate(10);
        return view('dashboard.client.index', compact( 'clients' ) );
    }


    public function edit(User $client) {
        return view( 'dashboard.client.partials.edit', [ 'client' => $client ] );
    }

    public function store(Request $request) {
        $seed     = Str::slug($request->name);
        $avatar   = "https://api.dicebear.com/7.x/initials/svg?seed=$seed&radius=50";
        $client   = User::create( [
            ...$request->except( '_token' ),
            'avatar' => $avatar,
        ] );
        notify()->success( ucwords($request->role).' created successfully!' );
        return back();
    }
    public function update(Request $request, User $client) {
        $seed = Str::slug($request->name);
        $avatar = "https://api.dicebear.com/7.x/initials/svg?seed=$seed&radius=50";
        $updated = $client->update( [
            'name'           => $request->name,
            'username'       => $request->username,
            'police_station' => $request->police_station,
            'password'       => $request->password ? $request->password : $client->password,
            'avatar'         => $avatar
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
