<?php

use App\Http\Controllers\BackupController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
 */

Route::get('/', function () {
    return redirect(route('dashboard'));
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ ProfileController::class, 'edit' ])->name('profile.edit');
    Route::patch('/profile', [ ProfileController::class, 'update' ])->name('profile.update');
    Route::delete('/profile', [ ProfileController::class, 'destroy' ])->name('profile.destroy');
});

Route::get('install', function () {
    Artisan::call('migrate');
    Artisan::call('optimize:clear');
    notify()->success("App installed successfully,\nusername: admin\npassword: password");
    return redirect(route('dashboard'));
});

Route::get('backup', [ BackupController::class, 'index' ])->name('backup.index');
Route::get('backup/create', [ BackupController::class, 'create' ])->name('backup.create');
Route::get('backup/delete', [ BackupController::class, 'delete' ])->name('backup.delete');
Route::get('backup/download', [ BackupController::class, 'download' ])->name('backup.download');
Route::get('backup/download-latest', [ BackupController::class, 'downloadLatest' ])->name('backup.download.latest');
require __DIR__ . '/auth.php';
require __DIR__ . '/dashboard.php';
