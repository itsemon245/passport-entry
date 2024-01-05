<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\EntryController;
use App\Http\Controllers\Dashboard\ClientController;
use App\Http\Controllers\Dashboard\ReportController;
use App\Http\Controllers\Dashboard\PaymentController;
use App\Http\Controllers\Dashboard\DashboardController;


Route::prefix( 'dashboard' )
    ->middleware( [ 'auth', 'verified' ] )
    ->group( function () {

        Route::get( '/', [DashboardController::class, 'index'])->name( 'dashboard' );

        Route::resource( 'client', ClientController::class);
        Route::resource( 'entry', EntryController::class);
        Route::resource( 'payment', PaymentController::class);
        Route::resource( 'report', ReportController::class);
    } );