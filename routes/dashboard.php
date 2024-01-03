<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\EntryController;
use App\Http\Controllers\Dashboard\ClientController;
use App\Http\Controllers\Dashboard\PaymentController;


Route::prefix( 'dashboard' )
    ->middleware( [ 'auth', 'verified' ] )
    ->group( function () {

        Route::get( '/', function () {
            return view( 'dashboard.index' );
        } )->name( 'dashboard' );

        Route::resource( 'client', ClientController::class);
        Route::resource( 'entry', EntryController::class);
        Route::resource( 'payment', PaymentController::class);
    } );