<?php

use App\Http\Controllers\Dashboard\ClientController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\EntryController;
use App\Http\Controllers\Dashboard\PaymentController;
use App\Http\Controllers\Dashboard\ReportController;
use Illuminate\Support\Facades\Route;

Route::prefix('dashboard')
    ->middleware([ 'auth', 'verified' ])
    ->group(function () {

        Route::get('/', [ DashboardController::class, 'index' ])->name('dashboard');

        Route::resource('client', ClientController::class);
        Route::resource('entry', EntryController::class);
        Route::resource('payment', PaymentController::class)->except('destroy');
        Route::resource('report', ReportController::class)->only('index');
        Route::get('report/print', [ ReportController::class, 'print' ])->name('report.print');
        Route::get('report/download/csv', [ ReportController::class, 'downloadCsv' ])->name('report.download.csv');
        Route::get('report/thanawise-print', [ ReportController::class, 'printThanawise' ])->name('report.print.thanawise');
        Route::get('report/iowise-print', [ ReportController::class, 'printIowise' ])->name('report.print.iowise');
        Route::get('report-client-wise', [ ReportController::class, 'clientWise' ])->name('report.client');
        Route::get('payment/{payment}/destroy', [ PaymentController::class, 'destroy' ])->name('payment.delete');
        Route::get('payment-history', [ PaymentController::class, 'history' ])->name('payment.history');
    });
